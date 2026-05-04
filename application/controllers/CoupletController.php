<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CoupletController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database(); // Database connect
        $this->load->helper('url'); 
        $this->load->model('CoupletModel'); // Model load
        $this->load->library('session');
        $this->load->library('upload'); // Add upload library
		if (!$this->session->userdata('logged_in')) {
			redirect('login');
		}
    }

    public function add_couplet() {
        $this->load->view('add-couplet'); // View load
    }

    public function save() {
        $original_title = $this->input->post('original_title');
        $couplet_transliteration = $this->input->post('couplet_transliteration');
        $couplet_translation = $this->input->post('couplet_translation');
        $poet = $this->input->post('poet'); // FIX: get 'poet' not 'poet_id'
        $attributed_poet = $this->input->post('attributed_poet');
        $translator = $this->input->post('translator');
        // Handle audio file upload
        $audio_file = '';
        if (!empty($_FILES['audio_file']['name'])) {
            $config['upload_path'] = FCPATH . 'uploads/audio/'; // Folder path
            $config['allowed_types'] = 'mp3|wav|ogg|m4a';
            $config['max_size'] = 10240; // 10MB
            $config['file_name'] = time() . '_' . $_FILES['audio_file']['name'];
            $this->upload->initialize($config);
            if ($this->upload->do_upload('audio_file')) {
                $uploadData = $this->upload->data();
                $audio_file = 'uploads/audio/' . $uploadData['file_name'];
            } else {
                $this->session->set_flashdata('error', $this->upload->display_errors());
                redirect('add-couplet');
                return;
            }
        } else {
            $audio_file = $this->input->post('audio_file'); // Fallback if no upload
        }
        $related_songs = $this->input->post('related_songs');
        $related_reflections = $this->input->post('related_reflections');
        $related_poems = $this->input->post('related_poems');
        $related_people = $this->input->post('related_people');
        $related_films = $this->input->post('related_films');
        $related_film_episodes = $this->input->post('related_film_episodes');
        $keywords = $this->input->post('keywords');
        $original_text = $this->input->post('original_text');
        $english_transliteration_text = $this->input->post('english_transliteration_text');
        $english_translation_text = $this->input->post('english_translation_text');
        $extra_translation_text = $this->input->post('extra_translation_text');
        $note_text = $this->input->post('note_text');
        $glossary = $this->input->post('glossary');
        $meta_title = $this->input->post('meta_title');
        $meta_keywords = $this->input->post('meta_keywords');
        $meta_description = $this->input->post('meta_description');
        $is_published = $this->input->post('is_published');

        $thumbnail = '';
        if (!empty($_FILES['thumbnailUrl']['name'])) {
            $config['upload_path'] = FCPATH . 'uploads/thumbnails/'; // Folder path
            $config['allowed_types'] = 'jpg|jpeg|png|gif|avif';
            $config['max_size'] = 2048; // 2MB
            $config['file_name'] = time() . '_' . $_FILES['thumbnailUrl']['name'];
            $this->upload->initialize($config);
            if ($this->upload->do_upload('thumbnailUrl')) {
                $uploadData = $this->upload->data();
                $thumbnail = 'uploads/thumbnails/' . $uploadData['file_name'];
            } else {
                // Handle error, maybe set flashdata
                $this->session->set_flashdata('error', $this->upload->display_errors());
                redirect('add-couplet');
                return;
            }
        } else {
            $thumbnail = $this->input->post('thumbnailUrl_existing');
            if ($thumbnail === null || $thumbnail === '') {
                $thumbnail = '';
            }
        }

        $data = array(
            'original_title' => $original_title,
            'couplet_transliteration' => $couplet_transliteration,
            'couplet_translation' => $couplet_translation,
            'poet_id' => serialize($poet),
            'attributed_poet' => serialize($attributed_poet),
            'translator' => serialize($translator),
            // 'audio_file' => $audio_file, // No longer saving in couplet table
            'related_songs' => serialize($related_songs),
            'related_reflections' => serialize($related_reflections),
            'related_poems' => serialize($related_poems),
            'related_people' => serialize($related_people),
            'related_films' => serialize($related_films),
            'related_film_episodes' => serialize($related_film_episodes),
            'keywords' => $keywords,
            'original_text' => $original_text,
            'english_transliteration_text' => $english_transliteration_text,
            'english_translation_text' => $english_translation_text,
            'note_text' => $note_text,
            'glossary' => $glossary,
            'thumbnail_image_upload' => $thumbnail,
            'meta_title' => $meta_title,
            'meta_keywords' => $meta_keywords,
            'meta_description' => $meta_description,
            'is_published' => $is_published
        );

        $data = $this->CoupletModel->swap_transliteration_translation_columns($data);

        // Model se insert function call
        $insert = $this->CoupletModel->insert_couplet($data);
        if ($insert) {
            $couplet_id = (int) $this->db->insert_id();
            $this->CoupletModel->sync_couplet_translations($couplet_id, $english_translation_text, $extra_translation_text);
            $this->sync_couplet_poet_table($couplet_id, $poet, $attributed_poet);
            if ($audio_file) {
                $audio_url = base_url($audio_file);
                $this->db->insert('couplet_audio', [
                    'couplet_id' => $couplet_id,
                    'soundcloud_track_id' => $audio_url
                ]);
            }
        }
        redirect('add-couplet');
    }

    /**
     * Mirror poet / attributed poet selections into couplet_poet (junction table).
     */
    private function sync_couplet_poet_table($coupletId, $poet, $attributed_poet) {
        if (!$this->db->table_exists('couplet_poet')) {
            return;
        }
        $coupletId = (int) $coupletId;
        if ($coupletId <= 0) {
            return;
        }
        $ids = [];
        $add = function ($v) use (&$ids) {
            if ($v === null || $v === '') {
                return;
            }
            if (is_array($v)) {
                foreach ($v as $p) {
                    $p = (int) $p;
                    if ($p > 0) {
                        $ids[$p] = true;
                    }
                }
                return;
            }
            $p = (int) $v;
            if ($p > 0) {
                $ids[$p] = true;
            }
        };
        $add($poet);
        $add($attributed_poet);

        $this->db->where('couplet_id', $coupletId)->delete('couplet_poet');
        foreach (array_keys($ids) as $pid) {
            $this->db->insert('couplet_poet', [
                'couplet_id' => $coupletId,
                'poet_id' => $pid,
            ]);
        }
    }
    

    public function fetch_couplets() {
        $this->load->model('CoupletModel');
        $couplets = $this->CoupletModel->get_all_couplets();
        $data = array();
        $sl_no = 1;

        $collect_person_ids = function ($raw) {
            $out = [];
            if ($raw === null || $raw === '') {
                return $out;
            }
            $u = @unserialize($raw);
            if ($u !== false && is_array($u)) {
                foreach ($u as $id) {
                    $id = (int) $id;
                    if ($id > 0) {
                        $out[$id] = true;
                    }
                }
                return array_keys($out);
            }
            $s = trim((string) $raw);
            if ($s === '') {
                return [];
            }
            if (preg_match('/^\d+$/', $s)) {
                return [(int) $s];
            }
            foreach (explode(',', $s) as $p) {
                $id = (int) trim($p);
                if ($id > 0) {
                    $out[$id] = true;
                }
            }
            return array_keys($out);
        };

        $person_full_name = function (array $person) {
            $fullName = trim(
                ($person['first_name'] ?? '') . ' ' .
                ($person['middle_name'] ?? '') . ' ' .
                ($person['last_name'] ?? '')
            );
            return $fullName !== '' ? $fullName : 'Unnamed';
        };

        foreach ($couplets as $row) {
            $idSet = [];
            foreach ($collect_person_ids($row['poet_id'] ?? '') as $pid) {
                $idSet[(int) $pid] = true;
            }
            foreach ($collect_person_ids($row['attributed_poet'] ?? '') as $pid) {
                $idSet[(int) $pid] = true;
            }
            if ($this->db->table_exists('couplet_poet')) {
                $jrows = $this->db->select('poet_id')
                    ->from('couplet_poet')
                    ->where('couplet_id', (int) $row['id'])
                    ->get()->result_array();
                foreach ($jrows as $jr) {
                    $pid = isset($jr['poet_id']) ? (int) $jr['poet_id'] : 0;
                    if ($pid > 0) {
                        $idSet[$pid] = true;
                    }
                }
            }
            $names = [];
            foreach (array_keys($idSet) as $personId) {
                $person = $this->db->get_where('person', ['id' => $personId])->row_array();
                if ($person) {
                    $names[] = $person_full_name($person);
                }
            }
            $poetNames = implode(', ', $names);
            $data[] = array(
                'sl_no' => $sl_no++,
                'created_at' => !empty($row['created_at']) ? date('d M Y', strtotime($row['created_at'])) : '—',
                'original_title' => $row['original_title'],
                'couplet_transliteration' => $row['couplet_transliteration'],
                'poet_id' => $poetNames,
                'is_published' => $row['is_published'] ? 'Yes' : 'No',
                'action' => '<a href="'.base_url('edit-couplet/'.$row['id']).'" class="btn btn-info btn-sm">Edit</a>
                            <a href="'.base_url('delete-couplet/'.$row['id']).'" class="btn btn-danger btn-sm">Delete</a>'
            );
        }

        // Always set JSON header and output only JSON
        header('Content-Type: application/json');
        echo json_encode(['data' => $data]);
    }


         // Fetch data for DataTable
    public function fetch_couplet() {
        $story = $this->CoupletModel->get_all_couplet();
        $data = [];
        $sl_no = 1;

        foreach($couplet as $c) {
            $data[] = [
                'sl_no' => $sl_no++,
                'original_title' => $c->original_title,
                'related_songs' => $c->related_songs,
                'is_publish' => $c->is_published ? 'Yes' : 'No',
                'action' => '<a href="'.base_url('cartoon/edit/'.$c->id).'" class="btn btn-sm btn-primary">Edit</a>
                             <a href="'.base_url('cartoon/delete/'.$c->id).'" class="btn btn-sm btn-danger" onclick="return confirm(\'Are you sure?\')">Delete</a>'
            ];
        }

        echo json_encode(['data' => $data]);
    }
    
    // 🟢 Ye function sirf edit ke liye call hoga
        public function edit($id) {
            $data['couplet'] = $this->CoupletModel->get_couplet_by_id($id);
            // Fetch audio file URL from couplet_audio
            $audio = $this->db->get_where('couplet_audio', ['couplet_id' => $id])->row_array();
            $data['audio_file_url'] = isset($audio['soundcloud_track_id']) ? $audio['soundcloud_track_id'] : '';
            $data['form_action'] = base_url('couplet/update/'.$id);
            $data['page_title'] = 'Edit Poem Details';
            // add-couplet view hi load karna hai (same form)
            $this->load->view('add-couplet', $data);
        }

        // 🟢 Update existing record
        public function update($id) {
            $poet_post = $this->input->post('poet');
            $attributed_poet_post = $this->input->post('attributed_poet');
            $data = $this->input->post();
            unset($data['thumbnailUrl_existing']);
            // serialize arrays
            $array_fields = ['poet', 'attributed_poet', 'translator', 'soundcloud_urls', 'related_songs', 'related_reflections', 'related_poems', 'related_people', 'related_films', 'related_film_episodes'];
            foreach($array_fields as $field) {
                if(isset($data[$field]) && is_array($data[$field])) {
                    $data[$field] = serialize($data[$field]);
                }
            }
            // rename keys to match db columns
            $data['poet_id'] = $data['poet'];
            unset($data['poet']);
            $data['soundCloud_track_url'] = $data['soundcloud_urls'];
            unset($data['soundcloud_urls']);
            // Thumbnail: same pattern as song (keep existing path unless new file uploaded)
            if (!empty($_FILES['thumbnailUrl']['name'])) {
                $this->load->library('upload');
                $config['upload_path'] = FCPATH . 'uploads/thumbnails/';
                $config['allowed_types'] = 'jpg|jpeg|png|gif|avif';
                $config['max_size'] = 2048;
                $config['file_name'] = time() . '_' . $_FILES['thumbnailUrl']['name'];
                $this->upload->initialize($config);
                if ($this->upload->do_upload('thumbnailUrl')) {
                    $upload_data = $this->upload->data();
                    $data['thumbnail_image_upload'] = 'uploads/thumbnails/' . $upload_data['file_name'];
                } else {
                    $this->session->set_flashdata('error', $this->upload->display_errors());
                    redirect('couplets-list');
                    return;
                }
            } else {
                $existingThumb = $this->input->post('thumbnailUrl_existing');
                if ($existingThumb !== null && $existingThumb !== '') {
                    $data['thumbnail_image_upload'] = $existingThumb;
                }
            }
            $data = $this->CoupletModel->swap_transliteration_translation_columns($data);
            $this->CoupletModel->update_couplet($id, $data);
            $translationPayload = $this->input->post('english_translation_text');
            $extraTranslationPayload = $this->input->post('extra_translation_text');
            $this->CoupletModel->sync_couplet_translations((int)$id, $translationPayload, $extraTranslationPayload);
            $this->sync_couplet_poet_table((int) $id, $poet_post, $attributed_poet_post);
            redirect('couplets-list');
        }

        // 🟢 Delete
        public function delete($id) {
            $id = (int) $id;
            if ($this->db->table_exists('couplet_poet')) {
                $this->db->where('couplet_id', $id)->delete('couplet_poet');
            }
            $this->db->delete('couplet', ['id' => $id]);
            redirect('couplets-list');
        }
    }
