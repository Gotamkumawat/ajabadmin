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
        // Handle thumbnail upload
        $thumbnail_url = '';
        if (!empty($_FILES['thumbnail_url']['name'])) {
            $config['upload_path'] = FCPATH . 'uploads/thumbnails/';
            $config['allowed_types'] = 'jpg|jpeg|png|gif';
            $config['max_size'] = 2048;
            $config['file_name'] = time() . '_' . $_FILES['thumbnail_url']['name'];
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
            'interview_audio'     => $this->input->post('interview_audio'),
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
            'thumbnail_excerpt' => $this->input->post('thumbnail_excerpt'),
            'reflection_excerpt' => $this->input->post('reflection_excerpt'),
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
            $this->session->set_flashdata('success', 'Reflection saved successfully!');
        } else {
            $this->session->set_flashdata('error', 'Failed to save reflection: ' . $this->db->_error_message());
        }

        redirect('add-reflection');
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
        $thumbnail_url = trim((string) $this->input->post('thumbnail_url_existing'));
        if (!empty($_FILES['thumbnail_url']['name'])) {
            $config['upload_path'] = FCPATH . 'uploads/thumbnails/';
            $config['allowed_types'] = 'jpg|jpeg|png|gif|webp';
            $config['max_size'] = 2048;
            $config['file_name'] = time() . '_' . $_FILES['thumbnail_url']['name'];
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
            'interview_audio' => $this->input->post('interview_audio'),
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