<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ReflectionController extends CI_Controller {
    private $reflectionFieldsCache = null;

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->model('ReflectionModel');
        $this->load->library('session');
		if (!$this->session->userdata('logged_in')) {
			redirect('login');
		}
    }

    public function add_reflection() {
        $this->load->view('add-reflection');
    }

    public function save() {
        // Handle interview audio upload (text "Audio Link" field replaced by Audio Upload).
        // Keeps the existing file when no new one is chosen.
        $interview_audio = trim((string) $this->input->post('interview_audio_existing'));
        if (!empty($_FILES['interview_audio_upload']['name'])) {
            $audioCfg = [
                'upload_path'   => FCPATH . 'uploads/audio/',
                'allowed_types' => 'mp3|wav|ogg|m4a|aac|flac',
                'max_size'      => 51200, // 50 MB
                'file_name'     => time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $_FILES['interview_audio_upload']['name']),
            ];
            if (!is_dir($audioCfg['upload_path'])) { @mkdir($audioCfg['upload_path'], 0755, true); }
            $this->load->library('upload');
            $this->upload->initialize($audioCfg);
            if ($this->upload->do_upload('interview_audio_upload')) {
                $u = $this->upload->data();
                $interview_audio = 'uploads/audio/' . $u['file_name'];
            } else {
                $this->session->set_flashdata('error', $this->upload->display_errors());
                redirect('add-reflection');
                return;
            }
        }

        // Handle thumbnail upload
        $thumbnail_url = '';
        if (!empty($_FILES['thumbnail_url']['name'])) {
            $config['upload_path'] = FCPATH . 'uploads/thumbnails/';
            if (!is_dir($config['upload_path'])) { @mkdir($config['upload_path'], 0755, true); }
            $config['allowed_types'] = 'jpg|jpeg|png|gif|webp|avif';
            $config['max_size'] = 2048;
            $config['file_name'] = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $_FILES['thumbnail_url']['name']);
            $this->load->library('upload');
            $this->upload->initialize($config);
            if ($this->upload->do_upload('thumbnail_url')) {
                $uploadData = $this->upload->data();
                $thumbnail_url = 'uploads/thumbnails/' . $uploadData['file_name'];
            } else {
                $this->session->set_flashdata('error', $this->upload->display_errors());
                redirect('add-reflection');
                return;
            }
        } else {
            $thumbnail_url = $this->input->post('thumbnail_url_existing');
            if ($thumbnail_url === null || $thumbnail_url === '') {
                $thumbnail_url = $this->input->post('thumbnail_url');
            }
        }

        $data = array(
            'title' => $this->input->post('title'),
            'verb' => $this->input->post('verb'),
            'speaker_id' => is_array($this->input->post('speaker_id')) ? implode(',', $this->input->post('speaker_id')) : ($this->input->post('speaker_id') ?? ''),
            'format'              => $this->input->post('format'),
            'second_title'        => $this->input->post('second_title'),
            'interview_video'     => $this->input->post('interview_video'),
            'interview_audio'     => $interview_audio,
            'interview_text'      => $this->input->post('interview_text'),
            'text_interview'      => $this->input->post('interview_text'),
            'interview_about'     => $this->input->post('interview_about'),
            'interview_place'     => $this->input->post('interview_place'),
            'interview_year'      => $this->input->post('interview_year'),
            'essay_content'       => $this->input->post('essay_content'),
            'visual_story_desc'   => $this->input->post('visual_story_desc'),
            'audio_story_title'   => $this->input->post('audio_story_title'),
            'audio_story_editor'  => $this->input->post('audio_story_editor'),
            'editor_type'         => $this->input->post('editor_type'),
            'text_editor_content' => $this->input->post('text_editor_content'),
            'moduler_editor_content' => $this->input->post('moduler_editor_content'),
            'thumbnail_url' => $thumbnail_url,
            // Thumbnail Excerpt UI maps to reflection_excerpt column (canonical); also store in thumbnail_excerpt for legacy reads.
            'thumbnail_excerpt' => $this->input->post('thumbnail_excerpt'),
            'reflection_excerpt' => $this->input->post('thumbnail_excerpt') !== null && trim((string)$this->input->post('thumbnail_excerpt')) !== ''
                ? $this->input->post('thumbnail_excerpt')
                : $this->input->post('reflection_excerpt'),
            'reflection_type' => is_array($this->input->post('reflection_type')) ? implode(',', $this->input->post('reflection_type')) : ($this->input->post('reflection_type') ?? ''),
            'youtube_video_id' => $this->input->post('interview_video') !== null && $this->input->post('interview_video') !== '' ? $this->input->post('interview_video') : $this->input->post('youtube_video_id'),
            'duration' => $this->input->post('duration'),
            'related_keywords' => is_array($this->input->post('related_keywords')) ? implode(',', $this->input->post('related_keywords')) : ($this->input->post('related_keywords') ?? ''),
            'attributed' => is_array($this->input->post('attributed')) ? implode(',', $this->input->post('attributed')) : ($this->input->post('attributed') ?? ''),
            'poet' => is_array($this->input->post('poet')) ? implode(',', $this->input->post('poet')) : ($this->input->post('poet') ?? ''),
            'related_poems' => is_array($this->input->post('related_poems')) ? implode(',', $this->input->post('related_poems')) : ($this->input->post('related_poems') ?? ''),
            'related_reflections' => is_array($this->input->post('related_reflections')) ? implode(',', $this->input->post('related_reflections')) : ($this->input->post('related_reflections') ?? ''),
            'original_text' => $this->input->post('original_text'),
            'related_words' => is_array($this->input->post('related_words')) ? implode(',', $this->input->post('related_words')) : ($this->input->post('related_words') ?? ''),
            'related_episodes' => is_array($this->input->post('related_episodes')) ? implode(',', $this->input->post('related_episodes')) : ($this->input->post('related_episodes') ?? ''),
            'related_films' => is_array($this->input->post('related_films')) ? implode(',', $this->input->post('related_films')) : ($this->input->post('related_films') ?? ''),
            'related_couplets' => is_array($this->input->post('related_couplets')) ? implode(',', $this->input->post('related_couplets')) : ($this->input->post('related_couplets') ?? ''),
            'related_songs' => is_array($this->input->post('related_songs')) ? implode(',', $this->input->post('related_songs')) : ($this->input->post('related_songs') ?? ''),
            'related_people' => is_array($this->input->post('related_people')) ? implode(',', $this->input->post('related_people')) : ($this->input->post('related_people') ?? ''),
            'related_stories' => is_array($this->input->post('related_stories')) ? implode(',', $this->input->post('related_stories')) : ($this->input->post('related_stories') ?? ''),
            'is_classroom_reflection' => $this->input->post('is_classroom_reflection'),
            'show_on_landing_page' => $this->input->post('show_on_landing_page'),
            'publish' => $this->input->post('publish'),
            'meta_title' => $this->input->post('meta_title'),
            'meta_keywords' => $this->input->post('meta_keywords'),
            'meta_description' => $this->input->post('meta_description'),
            'date_of_upload' => date('Y-m-d H:i:s')
        );

        $insert = $this->ReflectionModel->insert_reflection($this->filter_reflection_columns($data));

        if ($insert) {
            $newId = (int) $this->db->insert_id();
            $this->sync_word_reflection_junction($newId, $this->input->post('related_keywords'));
            $this->sync_reflection_related_all($newId);
            $this->session->set_flashdata('success', 'Reflection saved successfully!');
        } else {
            $this->session->set_flashdata('error', 'Failed to save reflection: ' . $this->db->_error_message());
        }

        redirect('add-reflection');
    }

    /**
     * Mirror keyword selections into word_reflection junction (word_id, reflection_id).
     */
    private function sync_word_reflection_junction($reflectionId, $keywords) {
        if (!$this->db->table_exists('word_reflection')) return;
        $reflectionId = (int) $reflectionId;
        if ($reflectionId <= 0) return;
        $ids = [];
        if (is_array($keywords)) {
            foreach ($keywords as $k) { $k = (int) $k; if ($k > 0) { $ids[$k] = true; } }
        } elseif ($keywords !== null && trim((string)$keywords) !== '') {
            foreach (explode(',', (string)$keywords) as $k) { $k = (int) trim($k); if ($k > 0) { $ids[$k] = true; } }
        }
        $this->db->where('reflection_id', $reflectionId)->delete('word_reflection');
        foreach (array_keys($ids) as $wid) {
            $this->db->insert('word_reflection', [
                'word_id'       => $wid,
                'reflection_id' => $reflectionId,
            ]);
        }
    }

    /**
     * Generic helper: replace junction rows for a reflection with given IDs.
     */
    private function sync_reflection_junction($table, $fkCol, $reflectionId, $ids) {
        if (!$this->db->table_exists($table)) return;
        $reflectionId = (int) $reflectionId;
        if ($reflectionId <= 0) return;
        $clean = [];
        if (is_array($ids)) {
            foreach ($ids as $v) { $v = (int) $v; if ($v > 0) { $clean[$v] = true; } }
        } elseif ($ids !== null && trim((string)$ids) !== '') {
            foreach (explode(',', (string)$ids) as $v) { $v = (int) trim($v); if ($v > 0) { $clean[$v] = true; } }
        }
        $this->db->where('reflection_id', $reflectionId)->delete($table);
        foreach (array_keys($clean) as $vid) {
            $this->db->insert($table, ['reflection_id' => $reflectionId, $fkCol => $vid]);
        }
    }

    /**
     * Sync ALL related-content junction tables from posted form fields.
     */
    private function sync_reflection_related_all($reflectionId) {
        $this->sync_reflection_junction('reflection_song',        'song_id',         $reflectionId, $this->input->post('related_songs'));
        $this->sync_reflection_junction('reflection_couplet',     'couplet_id',      $reflectionId, $this->input->post('related_poems'));
        $this->sync_reflection_junction('reflection_person',      'person_id',       $reflectionId, $this->input->post('related_people'));
        $this->sync_reflection_junction('reflection_filmepisode', 'film_episode_id', $reflectionId, $this->input->post('related_episodes'));
    }

    public function edit($id) {
        $data['reflection'] = $this->ReflectionModel->get_reflection_by_id($id);
        if (!$data['reflection']) {
            $this->session->set_flashdata('error', 'Reflection not found!');
            redirect('reflections-list');
        }
        $this->normalize_reflection_for_form($data['reflection']);
        $this->load->view('add-reflection', $data);
    }

    public function update($id) {
        $this->load->library('upload');

        // Handle interview audio upload (file replaces previous text "Audio Link")
        $interview_audio = trim((string) $this->input->post('interview_audio_existing'));
        if (!empty($_FILES['interview_audio_upload']['name'])) {
            $audioCfg = [
                'upload_path'   => FCPATH . 'uploads/audio/',
                'allowed_types' => 'mp3|wav|ogg|m4a|aac|flac',
                'max_size'      => 51200,
                'file_name'     => time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $_FILES['interview_audio_upload']['name']),
            ];
            if (!is_dir($audioCfg['upload_path'])) { @mkdir($audioCfg['upload_path'], 0755, true); }
            $this->upload->initialize($audioCfg);
            if ($this->upload->do_upload('interview_audio_upload')) {
                $u = $this->upload->data();
                $interview_audio = 'uploads/audio/' . $u['file_name'];
            } else {
                $this->session->set_flashdata('error', $this->upload->display_errors());
                redirect('reflection/edit/' . $id);
                return;
            }
        }
        if ($interview_audio === '') {
            $existing = $this->ReflectionModel->get_reflection_by_id($id);
            if ($existing && !empty($existing->interview_audio)) {
                $interview_audio = $existing->interview_audio;
            }
        }

        $thumbnail_url = trim((string) $this->input->post('thumbnail_url_existing'));
        if (!empty($_FILES['thumbnail_url']['name'])) {
            $config['upload_path'] = FCPATH . 'uploads/thumbnails/';
            if (!is_dir($config['upload_path'])) { @mkdir($config['upload_path'], 0755, true); }
            $config['allowed_types'] = 'jpg|jpeg|png|gif|webp|avif';
            $config['max_size'] = 2048;
            $config['file_name'] = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $_FILES['thumbnail_url']['name']);
            $this->upload->initialize($config);
            if ($this->upload->do_upload('thumbnail_url')) {
                $uploadData = $this->upload->data();
                $thumbnail_url = 'uploads/thumbnails/' . $uploadData['file_name'];
            } else {
                $this->session->set_flashdata('error', $this->upload->display_errors());
                redirect('reflection/edit/' . $id);
                return;
            }
        }
        if ($thumbnail_url === '') {
            $existing = $this->ReflectionModel->get_reflection_by_id($id);
            if ($existing && !empty($existing->thumbnail_url)) {
                $thumbnail_url = $existing->thumbnail_url;
            }
        }

        $data = array(
            'title' => $this->input->post('title'),
            'verb' => $this->input->post('verb'),
            'speaker_id' => is_array($this->input->post('speaker_id')) ? implode(',', $this->input->post('speaker_id')) : ($this->input->post('speaker_id') ?? ''),
            'format' => $this->input->post('format'),
            'second_title' => $this->input->post('second_title'),
            'interview_video' => $this->input->post('interview_video'),
            'interview_audio' => $interview_audio,
            'interview_text' => $this->input->post('interview_text'),
            'text_interview' => $this->input->post('interview_text'),
            'interview_about' => $this->input->post('interview_about'),
            'interview_place' => $this->input->post('interview_place'),
            'interview_year' => $this->input->post('interview_year'),
            'thumbnail_url' => $thumbnail_url,
            'thumbnail_excerpt' => $this->input->post('thumbnail_excerpt'),           
            'reflection_excerpt' => $this->input->post('reflection_excerpt'),
            'reflection_type' => is_array($this->input->post('reflection_type')) ? implode(',', $this->input->post('reflection_type')) : ($this->input->post('reflection_type') ?? ''),
            'youtube_video_id' => $this->input->post('interview_video') !== null && $this->input->post('interview_video') !== '' ? $this->input->post('interview_video') : $this->input->post('youtube_video_id'),
            'duration' => $this->input->post('duration'),
            'editor_type'         => $this->input->post('editor_type'),
            'text_editor_content' => $this->input->post('text_editor_content'),
            'moduler_editor_content' => $this->input->post('moduler_editor_content'),
            'related_keywords' => is_array($this->input->post('related_keywords')) ? implode(',', $this->input->post('related_keywords')) : ($this->input->post('related_keywords') ?? ''),
            'attributed' => is_array($this->input->post('attributed')) ? implode(',', $this->input->post('attributed')) : ($this->input->post('attributed') ?? ''),
            'poet' => is_array($this->input->post('poet')) ? implode(',', $this->input->post('poet')) : ($this->input->post('poet') ?? ''),
            'related_poems' => is_array($this->input->post('related_poems')) ? implode(',', $this->input->post('related_poems')) : ($this->input->post('related_poems') ?? ''),
            'related_reflections' => is_array($this->input->post('related_reflections')) ? implode(',', $this->input->post('related_reflections')) : ($this->input->post('related_reflections') ?? ''),
            'original_text' => $this->input->post('original_text'),
            'related_words' => is_array($this->input->post('related_words')) ? implode(',', $this->input->post('related_words')) : ($this->input->post('related_words') ?? ''),
            'related_episodes' => is_array($this->input->post('related_episodes')) ? implode(',', $this->input->post('related_episodes')) : ($this->input->post('related_episodes') ?? ''),
            'related_films' => is_array($this->input->post('related_films')) ? implode(',', $this->input->post('related_films')) : ($this->input->post('related_films') ?? ''),
            'related_couplets' => is_array($this->input->post('related_couplets')) ? implode(',', $this->input->post('related_couplets')) : ($this->input->post('related_couplets') ?? ''),
            'related_songs' => is_array($this->input->post('related_songs')) ? implode(',', $this->input->post('related_songs')) : ($this->input->post('related_songs') ?? ''),
            'related_people' => is_array($this->input->post('related_people')) ? implode(',', $this->input->post('related_people')) : ($this->input->post('related_people') ?? ''),
            'related_stories' => is_array($this->input->post('related_stories')) ? implode(',', $this->input->post('related_stories')) : ($this->input->post('related_stories') ?? ''),
            'is_classroom_reflection' => $this->input->post('is_classroom_reflection'),
            'show_on_landing_page' => $this->input->post('show_on_landing_page'),
            'publish' => $this->input->post('publish'),
            'meta_title' => $this->input->post('meta_title'),
            'meta_keywords' => $this->input->post('meta_keywords'),
            'meta_description' => $this->input->post('meta_description'),
            'date_of_upload' => date('Y-m-d H:i:s')
        );

        $update = $this->ReflectionModel->update_reflection($id, $this->filter_reflection_columns($data));
        // Always sync junctions (independent of update success — same fields might mean no row change but new selections)
        $this->sync_word_reflection_junction((int) $id, $this->input->post('related_keywords'));
        $this->sync_reflection_related_all((int) $id);

        if ($update) {
            $this->session->set_flashdata('success', 'Reflection updated successfully!');
        } else {
            $this->session->set_flashdata('error', 'Failed to update reflection: ' . $this->db->_error_message());
        }

        redirect('reflections-list');
    }

    public function delete($id) {
        $delete = $this->ReflectionModel->delete_reflection($id);
        if ($delete) {
            echo json_encode(['status' => 'success', 'message' => 'Reflection deleted successfully!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to delete reflection: ' . $this->db->_error_message()]);
        }
    }

    public function fetch_reflections() {
        $reflections = $this->ReflectionModel->get_all_reflections();
        $data = [];
        $sl_no = 1;

        foreach ($reflections as $ref) {
            // Get speaker names
            $speaker_names = '';
            if (!empty($ref->speaker_id)) {
                $ids = explode(',', $ref->speaker_id);
                $names = [];
                foreach ($ids as $id) {
                    $id = trim($id);
                    if ($id) {
                        $person = $this->db->get_where('person', ['id' => $id])->row();
                        if ($person) {
                            $full_name = trim(
                                $person->first_name .
                                (isset($person->middle_name) && $person->middle_name ? ' ' . $person->middle_name : '') .
                                (isset($person->last_name) && $person->last_name ? ' ' . $person->last_name : '')
                            );
                            $names[] = $full_name !== '' ? $full_name : 'N/A';
                        } else {
                            $names[] = 'N/A';
                        }
                    }
                }
                $speaker_names = implode(', ', $names);
            }

            $data[] = [
                'id' => $ref->id,
                'sl_no' => $sl_no++,
                'date_of_upload' => $ref->date_of_upload ?? date('Y-m-d H:i:s'),
                'title' => $ref->title,
                'speaker_id' => $speaker_names,
                'format' => $ref->format,
                'publish' => $ref->publish
            ];
        }

        echo json_encode(['data' => $data]);
    }

    private function reflection_table_fields() {
        if ($this->reflectionFieldsCache !== null) {
            return $this->reflectionFieldsCache;
        }
        if (!$this->db->table_exists('reflection')) {
            $this->reflectionFieldsCache = [];
            return $this->reflectionFieldsCache;
        }
        $this->reflectionFieldsCache = $this->db->list_fields('reflection');
        return $this->reflectionFieldsCache;
    }

    private function filter_reflection_columns(array $data) {
        $fields = $this->reflection_table_fields();
        if (empty($fields)) {
            return $data;
        }
        $allowed = array_fill_keys($fields, true);
        return array_filter(
            $data,
            function ($k) use ($allowed) {
                return isset($allowed[$k]);
            },
            ARRAY_FILTER_USE_KEY
        );
    }

    private function normalize_reflection_for_form($reflection) {
        if (!$reflection || !is_object($reflection)) {
            return;
        }
        if ((!isset($reflection->interview_video) || trim((string) $reflection->interview_video) === '')) {
            foreach (['youtube_video_id', 'youtube_link', 'youtube_url'] as $k) {
                if (isset($reflection->$k) && trim((string) $reflection->$k) !== '') {
                    $reflection->interview_video = $reflection->$k;
                    break;
                }
            }
        }
        if ((!isset($reflection->interview_text) || trim((string) $reflection->interview_text) === '')) {
            foreach (['text_interview'] as $k) {
                if (isset($reflection->$k) && trim((string) $reflection->$k) !== '') {
                    $reflection->interview_text = $reflection->$k;
                    break;
                }
            }
        }
        if ((!isset($reflection->second_title) || trim((string) $reflection->second_title) === '')) {
            foreach (['secondTitle', 'title2', 'sub_title', 'subtitle'] as $k) {
                if (isset($reflection->$k) && trim((string) $reflection->$k) !== '') {
                    $reflection->second_title = $reflection->$k;
                    break;
                }
            }
        }
    }
}