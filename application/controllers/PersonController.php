<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PersonController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->model('PersonModel');
        $this->load->library('session');
		if (!$this->session->userdata('logged_in')) {
			redirect('login');
		}
    }

    public function add_person() {
        $this->load->view('add-person');
    }

    public function save() {
        // File upload handling
        $thumbnail_image_upload = '';
        if (!empty($_FILES['thumbnail_image_upload']['name'])) {
            $config['upload_path'] = FCPATH . 'uploads/';
            if (!is_dir($config['upload_path'])) { @mkdir($config['upload_path'], 0755, true); }
            // Allow more image types and case-insensitive extensions
            $config['allowed_types'] = 'gif|jpg|png|jpeg|webp|avif';
            $config['max_size'] = 2048;
            $config['file_name'] = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $_FILES['thumbnail_image_upload']['name']);

            $this->load->library('upload', $config);

            if (!is_dir(FCPATH . 'Uploads/')) {
                mkdir(FCPATH . 'Uploads/', 0755, true); // फोल्डर नहीं है तो बनाएं
            }

            if ($this->upload->do_upload('thumbnail_image_upload')) {
                $thumbnail_image_upload = $this->upload->data('file_name');
            } else {
                log_message('error', $this->upload->display_errors());
                $this->session->set_flashdata('error', 'Failed to upload thumbnail: ' . $this->upload->display_errors());
                redirect('add-person');
            }
        }

        $data = array(
            'first_name' => $this->input->post('first_name'),
            'middle_name' => $this->input->post('middle_name'),
            'last_name' => $this->input->post('last_name'),
            'first_name_in_hindi' => $this->input->post('first_name_in_hindi'),
            'middle_name_in_hindi' => $this->input->post('middle_name_in_hindi'),
            'last_name_in_hindi' => $this->input->post('last_name_in_hindi'),
            // The form posts the chosen Occupation under the name `occupation`
            // (single select). We mirror that value into BOTH columns:
            //   - person.primary_occupation  → canonical source for List + Edit views
            //   - person.occupation          → legacy CSV column (kept in sync)
            'primary_occupation' => is_array($this->input->post('occupation')) ? implode(',', $this->input->post('occupation')) : ($this->input->post('occupation') ?? ''),
            'occupation'         => is_array($this->input->post('occupation')) ? implode(',', $this->input->post('occupation')) : ($this->input->post('occupation') ?? ''),
            'profile' => $this->input->post('profile'),
            'thumbnail_image_upload' => $thumbnail_image_upload,
            // 'thumbnail_excerpt' field removed from the UI; intentionally NOT written here
            // so existing DB values are preserved on save/update.
            'thumbnail_url' => $this->input->post('thumbnail_url'),
            'about' => $this->input->post('about'),
            'keywords' => is_array($this->input->post('keywords')) ? implode(',', $this->input->post('keywords')) : ($this->input->post('keywords') ?? ''),
            'songs' => is_array($this->input->post('songs')) ? implode(',', $this->input->post('songs')) : ($this->input->post('songs') ?? ''),
            'reflections' => is_array($this->input->post('reflections')) ? implode(',', $this->input->post('reflections')) : ($this->input->post('reflections') ?? ''),
            'poems' => is_array($this->input->post('poems')) ? implode(',', $this->input->post('poems')) : ($this->input->post('poems') ?? ''),
            'people' => is_array($this->input->post('people')) ? implode(',', $this->input->post('people')) : ($this->input->post('people') ?? ''),
            'films' => is_array($this->input->post('films')) ? implode(',', $this->input->post('films')) : ($this->input->post('films') ?? ''),
            'film_episode' => is_array($this->input->post('film_episode')) ? implode(',', $this->input->post('film_episode')) : ($this->input->post('film_episode') ?? ''),
            'display' => in_array(strtolower((string)$this->input->post('display')), ['1', 'true', 'yes', 'on'], true) ? 1 : 0,
            'publish' => in_array(strtolower((string)$this->input->post('publish')), ['1', 'true', 'yes', 'on'], true) ? 1 : 0,
            'meta_title' => $this->input->post('meta_title'),
            'meta_keywords' => $this->input->post('meta_keywords'),
            'meta_description' => $this->input->post('meta_description'),
            'date_of_upload' => date('Y-m-d H:i:s')
        );

        if ($this->db->field_exists('profile_tags', 'person')) {
            $tagPost = $this->input->post('profile_tags');
            $data['profile_tags'] = is_array($tagPost)
                ? implode(',', array_filter(array_map('intval', $tagPost)))
                : '';
        }

        $insert = $this->PersonModel->insert_person($data);

        if ($insert) {
            $newPersonId = (int) $this->db->insert_id();
            if ($newPersonId > 0) {
                $this->_sync_person_categories_from_post($newPersonId);
            }
            $this->session->set_flashdata('success', 'Person saved successfully!');
        } else {
            $this->session->set_flashdata('error', 'Failed to save person: ' . $this->db->_error_message());
        }

        redirect('add-person');
    }

    public function edit($id) {
        $data['person'] = $this->PersonModel->get_person_by_id($id);
        if (!$data['person']) {
            $this->session->set_flashdata('error', 'Person not found!');
            redirect('people-list');
        }
        $this->normalize_person_for_form($data['person']);
        $this->load->view('add-person', $data);
    }

    public function update($id) {
        // File upload handling
        $thumbnail_image_upload = trim((string)$this->input->post('existing_thumbnail_image_upload')); // Keep existing file if no new file uploaded
        $thumbnail_url = trim((string)$this->input->post('existing_thumbnail_url'));
        if (!empty($_FILES['thumbnail_image_upload']['name'])) {
            $config['upload_path'] = FCPATH . 'uploads/';
            if (!is_dir($config['upload_path'])) { @mkdir($config['upload_path'], 0755, true); }
            $config['allowed_types'] = 'gif|jpg|png|jpeg|webp|avif';
            $config['max_size'] = 2048; // 2MB
            $config['file_name'] = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $_FILES['thumbnail_image_upload']['name']);

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('thumbnail_image_upload')) {
                $thumbnail_image_upload = $this->upload->data('file_name');
                $thumbnail_url = $thumbnail_image_upload;
                // Delete old file if exists
                $existing_person = $this->PersonModel->get_person_by_id($id);
                $existingPath = FCPATH . 'uploads/' . $existing_person->thumbnail_image_upload;
                if ($existing_person->thumbnail_image_upload && file_exists($existingPath)) {
                    @unlink($existingPath);
                }
            } else {
                $this->session->set_flashdata('error', 'Failed to upload thumbnail: ' . $this->upload->display_errors());
                redirect('person/edit/' . $id);
            }
        }

        $data = array(
            'first_name' => $this->input->post('first_name'),
            'middle_name' => $this->input->post('middle_name'),
            'last_name' => $this->input->post('last_name'),
            'first_name_in_hindi' => $this->input->post('first_name_in_hindi'),
            'middle_name_in_hindi' => $this->input->post('middle_name_in_hindi'),
            'last_name_in_hindi' => $this->input->post('last_name_in_hindi'),
            // The form posts the chosen Occupation under the name `occupation`
            // (single select). We mirror that value into BOTH columns:
            //   - person.primary_occupation  → canonical source for List + Edit views
            //   - person.occupation          → legacy CSV column (kept in sync)
            'primary_occupation' => is_array($this->input->post('occupation')) ? implode(',', $this->input->post('occupation')) : ($this->input->post('occupation') ?? ''),
            'occupation'         => is_array($this->input->post('occupation')) ? implode(',', $this->input->post('occupation')) : ($this->input->post('occupation') ?? ''),
            'profile' => $this->input->post('profile'),
            'thumbnail_image_upload' => $thumbnail_image_upload,
            // 'thumbnail_excerpt' field removed from the UI; preserve existing DB value.
            'thumbnail_url' => $thumbnail_url,
            'about' => $this->input->post('about'),
            'keywords' => is_array($this->input->post('keywords')) ? implode(',', $this->input->post('keywords')) : ($this->input->post('keywords') ?? ''),
            'songs' => is_array($this->input->post('songs')) ? implode(',', $this->input->post('songs')) : ($this->input->post('songs') ?? ''),
            'reflections' => is_array($this->input->post('reflections')) ? implode(',', $this->input->post('reflections')) : ($this->input->post('reflections') ?? ''),
            'poems' => is_array($this->input->post('poems')) ? implode(',', $this->input->post('poems')) : ($this->input->post('poems') ?? ''),
            'people' => is_array($this->input->post('people')) ? implode(',', $this->input->post('people')) : ($this->input->post('people') ?? ''),
            'films' => is_array($this->input->post('films')) ? implode(',', $this->input->post('films')) : ($this->input->post('films') ?? ''),
            'film_episode' => is_array($this->input->post('film_episode')) ? implode(',', $this->input->post('film_episode')) : ($this->input->post('film_episode') ?? ''),
            'display' => in_array(strtolower((string)$this->input->post('display')), ['1', 'true', 'yes', 'on'], true) ? 1 : 0,
            'publish' => in_array(strtolower((string)$this->input->post('publish')), ['1', 'true', 'yes', 'on'], true) ? 1 : 0,
            'meta_title' => $this->input->post('meta_title'),
            'meta_keywords' => $this->input->post('meta_keywords'),
            'meta_description' => $this->input->post('meta_description'),
            'date_of_upload' => date('Y-m-d H:i:s')
        );

        if ($this->db->field_exists('profile_tags', 'person')) {
            $tagPost = $this->input->post('profile_tags');
            $data['profile_tags'] = is_array($tagPost)
                ? implode(',', array_filter(array_map('intval', $tagPost)))
                : '';
        }

        $update = $this->PersonModel->update_person($id, $data);

        if ($update) {
            $this->_sync_person_categories_from_post((int) $id);
            $this->session->set_flashdata('success', 'Person updated successfully!');
        } else {
            $this->session->set_flashdata('error', 'Failed to update person: ' . $this->db->_error_message());
        }

        redirect('people-list');
    }

    public function delete($id) {
        // Delete associated file
        $person = $this->PersonModel->get_person_by_id($id);
        if ($person->thumbnail_image_upload && file_exists('./Uploads/' . $person->thumbnail_image_upload)) {
            unlink('./Uploads/' . $person->thumbnail_image_upload);
        }

        $delete = $this->PersonModel->delete_person($id);
        if ($delete) {
            echo json_encode(['status' => 'success', 'message' => 'Person deleted successfully!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to delete person: ' . $this->db->_error_message()]);
        }
    }

    public function fetch_person() {
        $people = $this->PersonModel->fetch_all();
        $data = [];
        $sl_no = 1;

        // Build category map { id => name }
        $occupation_map = [];
        if ($this->db->table_exists('category')) {
            $rows = $this->db
                ->select('id, name')
                ->from('category')
                ->where('category_type', 'person')
                ->where('name IS NOT NULL', null, false)
                ->where("TRIM(name) !=", '')
                ->get()
                ->result_array();
            foreach ($rows as $row) {
                $id = isset($row['id']) ? (string)$row['id'] : null;
                $name = isset($row['name']) ? trim((string)$row['name']) : '';
                if ($id !== null && $name !== '') {
                    $occupation_map[$id] = $name;
                }
            }
        }

        // Build person → categories[] map
        $person_category_map = [];
        if ($this->db->table_exists('person_category')) {
            $personCategoryRows = $this->db
                ->select('person_id, category_id')
                ->from('person_category')
                ->get()
                ->result_array();
            foreach ($personCategoryRows as $row) {
                $pid = isset($row['person_id']) ? (string)$row['person_id'] : '';
                $cid = isset($row['category_id']) ? (string)$row['category_id'] : '';
                if ($pid === '' || $cid === '') {
                    continue;
                }
                if (!isset($person_category_map[$pid])) {
                    $person_category_map[$pid] = [];
                }
                $person_category_map[$pid][] = $cid;
            }
        }

        // 6 high-level occupation buckets — map detailed category name → bucket
        $bucket_for_name = function ($name) {
            $n = strtolower(trim((string) $name));
            $n = ltrim($n, '_');                      // strip leading underscores from group names
            if ($n === '') return null;
            // Order matters: more specific first
            if (strpos($n, 'poet') !== false)        return 'Poet';
            if (strpos($n, 'singer') !== false ||
                strpos($n, 'qawwal') !== false ||
                strpos($n, 'musician') !== false)    return 'Singer';
            if (strpos($n, 'writer') !== false ||
                strpos($n, 'scholar') !== false ||
                strpos($n, 'researcher') !== false ||
                strpos($n, 'translator') !== false ||
                strpos($n, 'journalist') !== false ||
                strpos($n, 'historian') !== false)   return 'Writer';
            if (strpos($n, 'artist') !== false ||
                strpos($n, 'painter') !== false ||
                strpos($n, 'photographer') !== false ||
                strpos($n, 'filmmaker') !== false ||
                strpos($n, 'designer') !== false ||
                strpos($n, 'animator') !== false ||
                strpos($n, 'illustrator') !== false ||
                strpos($n, 'novelist') !== false ||
                strpos($n, 'dancer') !== false ||
                strpos($n, 'theatre') !== false ||
                strpos($n, 'actor') !== false ||
                strpos($n, 'performing') !== false)  return 'Artist';
            if (strpos($n, 'legendary') !== false)   return 'Legendary Figure';
            return 'Other';
        };

        // Build person → profile_tags[] map (categories whose name starts with `_`)
        // Also build person → bucketed-occupation map (one of the 6)
        $display_map = ['1' => 'Yes', '0' => 'No', 'yes' => 'Yes', 'no' => 'No', 'Yes' => 'Yes', 'No' => 'No'];

        foreach ($people as $p) {
            $personId = isset($p->id) ? (string)$p->id : '';

            // Collect category names (from junction; fallback to legacy CSV).
            // Used ONLY for Profile Tags now; Occupation comes from person.primary_occupation below.
            $catIds = ($personId !== '' && isset($person_category_map[$personId])) ? $person_category_map[$personId] : [];
            if (empty($catIds) && !empty($p->occupation)) {
                $catIds = array_filter(array_map('trim', explode(',', $p->occupation)));
            }

            // Profile Tags = junction categories whose name doesn't start with `_`.
            // (Legacy `_`-prefixed entries are skipped here; the Occupation column uses
            //  a different source — see below.)
            $profileTags = [];
            foreach ($catIds as $cid) {
                $catName = isset($occupation_map[$cid]) ? $occupation_map[$cid] : '';
                if ($catName === '') continue;
                if (strpos($catName, '_') !== 0) {
                    $profileTags[] = $catName;
                }
            }

            // ============================================================
            // Occupation column source: person.primary_occupation (single
            // category.id stored on the person row). This replaces the older
            // junction-based logic so the column shows the person's chosen
            // primary occupation rather than every underscore-prefixed
            // category they happen to be linked to.
            // primary_occupation MAY be a single id, or (defensively) a
            // comma-separated list — handle both.
            // ============================================================
            $occupationNames = [];
            $rawPrimary = isset($p->primary_occupation) ? trim((string) $p->primary_occupation) : '';
            if ($rawPrimary !== '') {
                $primaryIds = array_filter(array_map('trim', explode(',', $rawPrimary)));
                foreach ($primaryIds as $pid) {
                    if (isset($occupation_map[$pid])) {
                        $occupationNames[] = ltrim($occupation_map[$pid], '_');
                    } elseif ($pid !== '') {
                        // Not a numeric id — assume the value itself is a name.
                        $occupationNames[] = ltrim($pid, '_');
                    }
                }
            }
            $occupation_value = !empty($occupationNames) ? implode(', ', array_values(array_unique($occupationNames))) : '—';

            // Profile Tags fallback: legacy person.profile_tags CSV
            if (empty($profileTags) && !empty($p->profile_tags)) {
                $rawTagIds = array_filter(array_map('trim', explode(',', (string) $p->profile_tags)));
                foreach ($rawTagIds as $tid) {
                    if (isset($occupation_map[$tid])) {
                        $profileTags[] = $occupation_map[$tid];
                    } elseif ($tid !== '') {
                        $profileTags[] = $tid;
                    }
                }
            }
            $profile_tags_value = !empty($profileTags) ? implode(', ', array_values(array_unique($profileTags))) : '—';

            // Full name including middle name
            $fullName = trim(implode(' ', array_filter([
                $p->first_name ?? '',
                $p->middle_name ?? '',
                $p->last_name ?? '',
            ], function ($v) { return trim((string) $v) !== ''; })));
            if ($fullName === '') { $fullName = 'Unnamed'; }

            $display_val = isset($display_map[$p->display]) ? $display_map[$p->display] : $p->display;

            $data[] = [
                'sl_no' => $sl_no++,
                'date_of_upload' => !empty($p->date_of_upload) ? date('d-m-Y H:i', strtotime($p->date_of_upload)) : '-',
                'person_name' => $fullName,
                'occupation' => $occupation_value,
                'profile_tags' => $profile_tags_value,
                'display' => $display_val,
                'publish' => ($p->publish == 1 || strtolower($p->publish) == 'yes') ? 'Yes' : 'No',
                'id' => $p->id
            ];
        }

        echo json_encode(['data' => $data]);
    }

    public function ajax_create_occupation() {
        $this->output->set_content_type('application/json');

        $occupation_name = trim((string)$this->input->post('occupation_name', true));
        if ($occupation_name === '') {
            echo json_encode(['status' => 'error', 'message' => 'Occupation name is required']);
            return;
        }

        if (!$this->db->table_exists('category')) {
            echo json_encode(['status' => 'error', 'message' => 'category table not found']);
            return;
        }

        $this->db->where('category_type', 'person');
        $this->db->where('name', $occupation_name);
        $existing = $this->db->get('category')->row_array();
        if (!empty($existing)) {
            $existing_id = isset($existing['id']) ? (string)$existing['id'] : '';
            echo json_encode([
                'status' => 'success',
                'message' => 'Occupation already exists',
                'occupation_id' => $existing_id,
                'occupation_name' => (string)$existing['name']
            ]);
            return;
        }

        $insert_data = [
            'name' => $occupation_name,
            'category_type' => 'person'
        ];

        $inserted = $this->db->insert('category', $insert_data);
        if (!$inserted) {
            $db_error = $this->db->error();
            $message = !empty($db_error['message']) ? $db_error['message'] : 'Failed to add occupation';
            echo json_encode(['status' => 'error', 'message' => $message]);
            return;
        }

        $new_id = (string)$this->db->insert_id();
        if ($new_id !== '') {
            $created = $this->db->get_where('category', ['id' => $new_id])->row_array();
            if (!empty($created)) {
                $new_id = isset($created['id']) ? (string)$created['id'] : $new_id;
                $occupation_name = isset($created['name']) ? (string)$created['name'] : $occupation_name;
            }
        }

        echo json_encode([
            'status' => 'success',
            'message' => 'Occupation added successfully',
            'occupation_id' => $new_id,
            'occupation_name' => $occupation_name
        ]);
    }

    public function ajax_update_occupation() {
        $this->output->set_content_type('application/json');
        $id = (int)$this->input->post('id');
        $name = trim((string)$this->input->post('occupation_name', true));
        if ($id <= 0 || $name === '') {
            echo json_encode(['status' => 'error', 'message' => 'id and occupation_name are required']); return;
        }
        if (!$this->db->table_exists('category')) { echo json_encode(['status' => 'error', 'message' => 'category table not found']); return; }
        $this->db->where('id', $id)->where('category_type', 'person')->update('category', ['name' => $name]);
        echo json_encode(['status' => 'success', 'occupation_id' => (string)$id, 'occupation_name' => $name]);
    }

    private function _sync_person_categories_from_post($personId) {
        $personId = (int) $personId;
        if ($personId <= 0 || !$this->db->table_exists('person_category')) {
            return;
        }
        $ids = [];
        $occ = $this->input->post('occupation');
        if (is_array($occ)) {
            foreach ($occ as $v) {
                $v = (int) $v;
                if ($v > 0) {
                    $ids[] = $v;
                }
            }
        }
        $tags = $this->input->post('profile_tags');
        if (is_array($tags)) {
            foreach ($tags as $v) {
                $v = (int) $v;
                if ($v > 0) {
                    $ids[] = $v;
                }
            }
        }
        $ids = array_values(array_unique($ids));
        $this->db->where('person_id', $personId)->delete('person_category');
        foreach ($ids as $cid) {
            $this->db->insert('person_category', [
                'person_id'   => $personId,
                'category_id' => $cid,
            ]);
        }
    }

    /**
     * AJAX endpoint for creating persons (used by reflection form)
     * Accepts JSON body: { "name": "...", "hyperlink": "..." }
     */
    public function ajax_create() {
        $this->output->set_content_type('application/json');
        
        // Get JSON input
        $raw = json_decode((string) file_get_contents('php://input'), true);
        if (!is_array($raw)) {
            echo json_encode(['success' => false, 'message' => 'Invalid JSON data']);
            return;
        }
        
        $name = trim((string) ($raw['name'] ?? ''));
        $hyperlink = trim((string) ($raw['hyperlink'] ?? ''));
        
        if ($name === '') {
            echo json_encode(['success' => false, 'message' => 'Name is required']);
            return;
        }
        
        // Split name into parts
        $parts = preg_split('/\s+/', $name);
        $first = isset($parts[0]) ? $parts[0] : '';
        $last = '';
        $middle = '';
        
        if (count($parts) > 1) {
            $last = array_pop($parts);
            if (count($parts) > 1) {
                $middle = implode(' ', $parts);
            }
        }
        
        // Check for duplicate person
        $this->db->where('first_name', $first);
        if ($middle !== '') {
            $this->db->where('middle_name', $middle);
        }
        if ($last !== '') {
            $this->db->where('last_name', $last);
        }
        $existing = $this->db->get('person')->row_array();
        
        if (!empty($existing)) {
            echo json_encode([
                'success' => true, 
                'message' => 'Person already exists',
                'id' => (string) $existing['id'],
                'name' => $name
            ]);
            return;
        }
        
        // Insert new person
        $data = [
            'first_name' => $first,
            'middle_name' => $middle,
            'last_name' => $last,
            'thumbnail_url' => $hyperlink,
            'date_of_upload' => date('Y-m-d H:i:s')
        ];
        
        $inserted = $this->db->insert('person', $data);
        if (!$inserted) {
            echo json_encode(['success' => false, 'message' => 'Failed to create person']);
            return;
        }
        
        $newId = (string) $this->db->insert_id();
        echo json_encode([
            'success' => true,
            'message' => 'Person created successfully',
            'id' => $newId,
            'name' => $name
        ]);
    }

    private function normalize_person_for_form($person) {
        if (!$person || !is_object($person)) {
            return;
        }

        if ((!isset($person->profile) || trim((string)$person->profile) === '')) {
            foreach (['profile_tag', 'tags'] as $k) {
                if (isset($person->$k) && trim((string)$person->$k) !== '') {
                    $person->profile = $person->$k;
                    break;
                }
            }
        }

        if ((!isset($person->thumbnail_image_upload) || trim((string)$person->thumbnail_image_upload) === '')) {
            foreach (['thumbnail_url', 'thumbnail_image'] as $k) {
                if (isset($person->$k) && trim((string)$person->$k) !== '') {
                    $person->thumbnail_image_upload = $person->$k;
                    break;
                }
            }
        }

        if ((!isset($person->thumbnail_excerpt) || trim((string)$person->thumbnail_excerpt) === '')) {
            foreach (['excerpt', 'thumbnail_description'] as $k) {
                if (isset($person->$k) && trim((string)$person->$k) !== '') {
                    $person->thumbnail_excerpt = $person->$k;
                    break;
                }
            }
        }
    }
}