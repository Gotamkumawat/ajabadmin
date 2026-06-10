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
        $editId = (int) $this->input->post('id');
        if ($editId > 0) {
            // edit submission — delegate to update flow
            $this->update($editId);
            return;
        }
        $original_title = $this->input->post('original_title');
        $couplet_transliteration = $this->input->post('couplet_transliteration');
        $couplet_translation = $this->input->post('couplet_translation');
        $poet = $this->input->post('poet'); // FIX: get 'poet' not 'poet_id'
        $attributed_poet = $this->input->post('attributed_poet');
        $translator = $this->input->post('translator');
        // Handle audio file upload (auto-create dir; non-blocking on error)
        $audio_file = '';
        if (!empty($_FILES['audio_file']['name'])) {
            $audioDir = FCPATH . 'uploads/audio/';
            if (!is_dir($audioDir)) { @mkdir($audioDir, 0755, true); }
            $this->load->library('upload');
            $audioConfig = [
                'upload_path'   => $audioDir,
                'allowed_types' => 'mp3|wav|ogg|m4a',
                'max_size'      => 10240,
                'file_name'     => time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $_FILES['audio_file']['name']),
            ];
            $this->upload->initialize($audioConfig);
            if ($this->upload->do_upload('audio_file')) {
                $uploadData = $this->upload->data();
                $audio_file = 'uploads/audio/' . $uploadData['file_name'];
            }
            // If upload fails, just skip audio — don't block save
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
        if (empty($keywords)) {
            $kw = $this->input->post('relatedkeywords');
            if (is_array($kw)) {
                $keywords = implode(',', array_filter($kw, function ($x) { return trim($x) !== ''; }));
            } elseif ($kw !== null) {
                $keywords = (string) $kw;
            }
        }
        $original_text = $this->input->post('original_text');
        $english_transliteration_text = $this->input->post('english_transliteration_text');
        $english_translation_text = $this->input->post('english_translation_text');
        $extra_translation_text = $this->input->post('extra_translation_text');
        $note_text = $this->input->post('note_text');
        // Poem Glossary now matches Song Glossary UI: a multi-select of word IDs.
        // DB column `couplet.glossary` is kept as free TEXT for backward compatibility,
        // so we resolve the selected IDs to their `word.word_transliteration` and store
        // them comma-separated. Any unmatched legacy free-text is preserved via
        // `glossary_extra_text` (hidden field rendered in add-couplet.php).
        $glossary_raw = $this->input->post('glossary');
        $glossary_extra = trim((string) $this->input->post('glossary_extra_text'));
        $glossary_parts = [];
        if (is_array($glossary_raw)) {
            $ids = array_values(array_filter(array_map(function ($v) { return (int) $v; }, $glossary_raw), function ($v) { return $v > 0; }));
            if (!empty($ids) && $this->db->table_exists('word')) {
                $rows = $this->db->select('id, word_transliteration')->from('word')->where_in('id', $ids)->get()->result();
                // Preserve user's selection order
                $byId = [];
                foreach ($rows as $r) { $byId[(int) $r->id] = trim((string) $r->word_transliteration); }
                foreach ($ids as $id) {
                    if (!empty($byId[$id])) { $glossary_parts[] = $byId[$id]; }
                }
            }
        } elseif ($glossary_raw !== null && trim((string) $glossary_raw) !== '') {
            // Backward compat: if some caller still posts a single string, keep it as-is.
            $glossary_parts[] = trim((string) $glossary_raw);
        }
        if ($glossary_extra !== '') { $glossary_parts[] = $glossary_extra; }
        $glossary = implode(', ', array_values(array_unique(array_filter(array_map('trim', $glossary_parts), 'strlen'))));
        $meta_title = $this->input->post('meta_title');
        $meta_keywords = $this->input->post('meta_keywords');
        $meta_description = $this->input->post('meta_description');
        $is_published = $this->input->post('is_published');
        $thumbnail_excerpt = $this->input->post('thumbnail_excerpt');

        $thumbnail = '';
        if (!empty($_FILES['thumbnailUrl']['name'])) {
            $thumbDir = FCPATH . 'images/';
            if (!is_dir($thumbDir)) { @mkdir($thumbDir, 0755, true); }
            $this->load->library('upload');
            $thumbConfig = [
                'upload_path'   => $thumbDir,
                'allowed_types' => 'jpg|jpeg|png|gif|avif',
                'max_size'      => 2048,
                'file_name'     => time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $_FILES['thumbnailUrl']['name']),
            ];
            $this->upload->initialize($thumbConfig);
            if ($this->upload->do_upload('thumbnailUrl')) {
                $uploadData = $this->upload->data();
                $thumbnail = 'images/' . $uploadData['file_name'];
            } else {
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

        // poet_id column is INT — store first/single poet; preserve all in junction table.
        $poetIdInt = 0;
        if (is_array($poet)) {
            foreach ($poet as $p) { $p = (int) $p; if ($p > 0) { $poetIdInt = $p; break; } }
        } else {
            $poetIdInt = (int) $poet;
        }

        // Helper: serialize only non-empty arrays; otherwise store empty string (avoids "N;" stored garbage)
        $serializeOrEmpty = function ($v) {
            if (is_array($v)) {
                $clean = array_values(array_filter(array_map('strval', $v), function ($x) { return trim($x) !== ''; }));
                return !empty($clean) ? serialize($clean) : '';
            }
            $s = trim((string) $v);
            return $s !== '' ? $s : '';
        };

        $data = array(
            'original_title' => $original_title,
            'couplet_transliteration' => $couplet_transliteration,
            'couplet_translation' => $couplet_translation,
            'poet_id' => $poetIdInt,
            'attributed_poet' => $serializeOrEmpty($attributed_poet),
            'translator' => $serializeOrEmpty($translator),
            // 'audio_file' => $audio_file, // No longer saving in couplet table
            'related_songs' => $serializeOrEmpty($related_songs),
            'related_reflections' => $serializeOrEmpty($related_reflections),
            'related_poems' => $serializeOrEmpty($related_poems),
            'related_people' => $serializeOrEmpty($related_people),
            'related_films' => $serializeOrEmpty($related_films),
            'related_film_episodes' => $serializeOrEmpty($related_film_episodes),
            'keywords' => $keywords,
            'original_text' => $original_text,
            'english_transliteration_text' => $english_transliteration_text,
            'english_translation_text' => $english_translation_text,
            'note_text' => $note_text,
            'glossary' => $glossary,
            'thumbnail_url' => $thumbnail,
            'thumbnail_image_upload' => $thumbnail,
            'thumbnail_excerpt' => $thumbnail_excerpt,
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
            $this->sync_couplet_related_all($couplet_id);
            if ($audio_file) {
                $this->sync_couplet_audio($couplet_id, $audio_file);
            }
            $this->session->set_flashdata('success', 'Couplet saved successfully!');
            redirect('couplets-list');
            return;
        } else {
            $dbErr = $this->db->error();
            $msg = !empty($dbErr['message']) ? $dbErr['message'] : 'Failed to save couplet';
            $this->session->set_flashdata('error', $msg);
        }
        redirect('add-couplet');
    }

    /**
     * Generic helper: replace junction rows for a couplet with given IDs.
     * @param string $table   junction table name
     * @param string $fkCol   foreign-key column on junction (e.g. 'word_id')
     * @param int    $coupletId
     * @param mixed  $ids     array, CSV string, or null
     */
    private function sync_couplet_junction($table, $fkCol, $coupletId, $ids) {
        if (!$this->db->table_exists($table)) return;
        $coupletId = (int) $coupletId;
        if ($coupletId <= 0) return;
        $clean = [];
        if (is_array($ids)) {
            foreach ($ids as $v) { $v = (int) $v; if ($v > 0) { $clean[$v] = true; } }
        } elseif ($ids !== null && trim((string)$ids) !== '') {
            foreach (explode(',', (string)$ids) as $v) { $v = (int) trim($v); if ($v > 0) { $clean[$v] = true; } }
        }
        $this->db->where('couplet_id', $coupletId)->delete($table);
        foreach (array_keys($clean) as $vid) {
            $this->db->insert($table, ['couplet_id' => $coupletId, $fkCol => $vid]);
        }
    }

    /**
     * Sync ALL related-content junction tables from posted form fields.
     */
    private function sync_couplet_related_all($coupletId) {
        $this->sync_couplet_junction('couplet_word',           'word_id',            $coupletId, $this->input->post('relatedkeywords'));
        $this->sync_couplet_junction('couplet_song',           'song_id',            $coupletId, $this->input->post('related_songs'));
        $this->sync_couplet_junction('couplet_reflection',     'reflection_id',      $coupletId, $this->input->post('related_reflections'));
        $this->sync_couplet_junction('couplet_relatedcouplet', 'related_couplet_id', $coupletId, $this->input->post('related_poems'));
        $this->sync_couplet_junction('couplet_film',           'film_id',            $coupletId, $this->input->post('related_films'));
        $this->sync_couplet_junction('couplet_filmepisode',    'film_episode_id',    $coupletId, $this->input->post('related_film_episodes'));
        $this->sync_couplet_junction('couplet_people',         'person_id',          $coupletId, $this->input->post('related_people'));
    }

    /**
     * Replace couplet_audio row(s) with single audio URL.
     */
    private function sync_couplet_audio($coupletId, $audioPathOrUrl) {
        if (!$this->db->table_exists('couplet_audio')) return;
        $coupletId = (int) $coupletId;
        if ($coupletId <= 0) return;
        $this->db->where('couplet_id', $coupletId)->delete('couplet_audio');
        $audio = trim((string) $audioPathOrUrl);
        if ($audio === '') return;
        // Make sure we store full URL when path is relative
        if (!preg_match('#^https?://#i', $audio)) {
            $audio = base_url($audio);
        }
        $this->db->insert('couplet_audio', [
            'couplet_id'         => $coupletId,
            'soundcloud_track_id' => $audio,
        ]);
    }

    /**
     * Mirror keyword selections into couplet_word (junction table).
     * Accepts $keywords as array of word IDs, CSV string, or null.
     */
    private function sync_couplet_word_table($coupletId, $keywords) {
        if (!$this->db->table_exists('couplet_word')) {
            return;
        }
        $coupletId = (int) $coupletId;
        if ($coupletId <= 0) {
            return;
        }
        $ids = [];
        if (is_array($keywords)) {
            foreach ($keywords as $k) {
                $k = (int) $k;
                if ($k > 0) { $ids[$k] = true; }
            }
        } elseif ($keywords !== null && trim((string)$keywords) !== '') {
            foreach (explode(',', (string)$keywords) as $k) {
                $k = (int) trim($k);
                if ($k > 0) { $ids[$k] = true; }
            }
        }
        // Wipe existing junction rows for this couplet
        $this->db->where('couplet_id', $coupletId)->delete('couplet_word');
        foreach (array_keys($ids) as $wid) {
            $this->db->insert('couplet_word', [
                'couplet_id' => $coupletId,
                'word_id'    => $wid,
            ]);
        }
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

        // Batch-load all persons once to avoid N+1 queries
        $personById = [];
        if ($this->db->table_exists('person')) {
            $allPersons = $this->db->select('id, first_name, middle_name, last_name')->get('person')->result_array();
            foreach ($allPersons as $p) {
                $personById[(int) $p['id']] = $p;
            }
        }

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
                if (isset($personById[$personId])) {
                    $names[] = $person_full_name($personById[$personId]);
                }
            }
            $poetNames = !empty($names) ? implode(', ', $names) : '—';
            $data[] = array(
                'sl_no' => $sl_no++,
                'created_at' => !empty($row['created_at']) ? date('d M Y', strtotime($row['created_at'])) : '—',
                'original_title' => $row['original_title'] ?? '',
                'couplet_transliteration' => $row['couplet_transliteration'] ?? '',
                'couplet_translation' => $row['couplet_translation'] ?? '',
                'poet_id' => $poetNames,
                'is_published' => $row['is_published'] ? 'Yes' : 'No',
                'action' => '<button type="button" class="btn btn-sm btn-info admin-preview-btn" data-id="'.$row['id'].'">Preview</button>
                            <a href="'.base_url('edit-couplet/'.$row['id']).'" class="btn btn-primary btn-sm">Edit</a>
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
            $id = (int) $id;
            if ($id <= 0) { redirect('couplets-list'); return; }
            @file_put_contents(FCPATH . 'couplet_update_debug.log',
                "[".date('Y-m-d H:i:s')."] couplet update id=$id\n"
                ."POST keys: ".implode(',', array_keys($_POST))."\n"
                ."POST[thumbnailUrl_existing]=".(isset($_POST['thumbnailUrl_existing'])?var_export($_POST['thumbnailUrl_existing'],true):'(unset)')."\n"
                ."FILES[thumbnailUrl][name]=".(isset($_FILES['thumbnailUrl']['name'])?var_export($_FILES['thumbnailUrl']['name'],true):'(unset)')."\n"
                ."---\n", FILE_APPEND);
            $poet_post = $this->input->post('poet');
            $attributed_poet_post = $this->input->post('attributed_poet');
            $data = $this->input->post();
            unset($data['thumbnailUrl_existing']);
            unset($data['id']);
            unset($data['extra_translation_text']);
            unset($data['audio_file']);
            // serialize arrays for text columns — empty arrays become empty string (not "N;")
            $array_fields = ['attributed_poet', 'translator', 'soundcloud_urls', 'related_songs', 'related_reflections', 'related_poems', 'related_people', 'related_films', 'related_film_episodes'];
            foreach($array_fields as $field) {
                if(isset($data[$field])) {
                    if (is_array($data[$field])) {
                        $clean = array_values(array_filter(array_map('strval', $data[$field]), function ($x) { return trim($x) !== ''; }));
                        $data[$field] = !empty($clean) ? serialize($clean) : '';
                    }
                }
            }
            // poet_id column is INT — store first/single poet; preserve all in junction table.
            $poetIdInt = 0;
            if (is_array($poet_post)) {
                foreach ($poet_post as $p) { $p = (int) $p; if ($p > 0) { $poetIdInt = $p; break; } }
            } else {
                $poetIdInt = (int) $poet_post;
            }
            $data['poet_id'] = $poetIdInt;
            unset($data['poet']);
            // relatedkeywords[] (multi-select) → keywords CSV column
            if (isset($data['relatedkeywords'])) {
                $kw = $data['relatedkeywords'];
                if (is_array($kw)) {
                    $data['keywords'] = implode(',', array_filter($kw, function ($x) { return trim($x) !== ''; }));
                } else {
                    $data['keywords'] = (string) $kw;
                }
                unset($data['relatedkeywords']);
            }
            if (isset($data['soundcloud_urls'])) {
                $data['soundCloud_track_url'] = $data['soundcloud_urls'];
                unset($data['soundcloud_urls']);
            }
            // Thumbnail: upload to /images, save in thumbnail_url + thumbnail_image_upload
            if (!empty($_FILES['thumbnailUrl']['name'])) {
                $thumbDir = FCPATH . 'images/';
                if (!is_dir($thumbDir)) { @mkdir($thumbDir, 0755, true); }
                $this->load->library('upload');
                $thumbConfig = [
                    'upload_path'   => $thumbDir,
                    'allowed_types' => 'jpg|jpeg|png|gif|avif',
                    'max_size'      => 2048,
                    'file_name'     => time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $_FILES['thumbnailUrl']['name']),
                ];
                $this->upload->initialize($thumbConfig);
                if ($this->upload->do_upload('thumbnailUrl')) {
                    $upload_data = $this->upload->data();
                    $newPath = 'images/' . $upload_data['file_name'];
                    $data['thumbnail_url'] = $newPath;
                    $data['thumbnail_image_upload'] = $newPath;
                } else {
                    $this->session->set_flashdata('error', $this->upload->display_errors());
                    redirect('couplets-list');
                    return;
                }
            } else {
                $existingThumb = $this->input->post('thumbnailUrl_existing');
                if ($existingThumb !== null && $existingThumb !== '') {
                    $data['thumbnail_url'] = $existingThumb;
                    $data['thumbnail_image_upload'] = $existingThumb;
                }
            }
            $data = $this->CoupletModel->swap_transliteration_translation_columns($data);
            // Filter to actual couplet table columns only
            $coupletCols = $this->db->list_fields('couplet');
            $data = array_intersect_key($data, array_flip($coupletCols));
            $this->CoupletModel->update_couplet($id, $data);
            $translationPayload = $this->input->post('english_translation_text');
            $extraTranslationPayload = $this->input->post('extra_translation_text');
            $this->CoupletModel->sync_couplet_translations((int)$id, $translationPayload, $extraTranslationPayload);
            $this->sync_couplet_poet_table((int) $id, $poet_post, $attributed_poet_post);
            $this->sync_couplet_related_all((int) $id);
            // Audio: handle uploaded file first, then post-existing
            if (!empty($_FILES['audio_file']['name'])) {
                $audioDir = FCPATH . 'uploads/audio/';
                if (!is_dir($audioDir)) { @mkdir($audioDir, 0755, true); }
                $this->load->library('upload');
                $audioConfig = [
                    'upload_path'   => $audioDir,
                    'allowed_types' => 'mp3|wav|ogg|m4a',
                    'max_size'      => 10240,
                    'file_name'     => time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $_FILES['audio_file']['name']),
                ];
                $this->upload->initialize($audioConfig);
                if ($this->upload->do_upload('audio_file')) {
                    $up = $this->upload->data();
                    $this->sync_couplet_audio((int) $id, 'uploads/audio/' . $up['file_name']);
                }
            }
            $this->session->set_flashdata('success', 'Couplet updated successfully!');
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
