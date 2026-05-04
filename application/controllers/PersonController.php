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
            $config['upload_path'] = FCPATH . 'Uploads/';
            // Allow more image types and case-insensitive extensions
            $config['allowed_types'] = 'gif|jpg|png|jpeg|webp|GIF|JPG|PNG|JPEG|WEBP';
            $config['max_size'] = 2048;
            $config['file_name'] = time() . '_' . $_FILES['thumbnail_image_upload']['name'];

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
            'primary_occupation' => is_array($this->input->post('primary_occupation')) ? implode(',', $this->input->post('primary_occupation')) : ($this->input->post('primary_occupation') ?? ''),
            'occupation' => is_array($this->input->post('occupation')) ? implode(',', $this->input->post('occupation')) : ($this->input->post('occupation') ?? ''),
            'profile' => $this->input->post('profile'),
            'thumbnail_image_upload' => $thumbnail_image_upload,
            'thumbnail_excerpt' => $this->input->post('thumbnail_excerpt'),
            'thumbnail_url' => $this->input->post('thumbnail_url'),
            'about' => $this->input->post('about'),
            'keywords' => is_array($this->input->post('keywords')) ? implode(',', $this->input->post('keywords')) : ($this->input->post('keywords') ?? ''),
            'songs' => is_array($this->input->post('songs')) ? implode(',', $this->input->post('songs')) : ($this->input->post('songs') ?? ''),
            'reflections' => is_array($this->input->post('reflections')) ? implode(',', $this->input->post('reflections')) : ($this->input->post('reflections') ?? ''),
            'poems' => is_array($this->input->post('poems')) ? implode(',', $this->input->post('poems')) : ($this->input->post('poems') ?? ''),
            'people' => is_array($this->input->post('people')) ? implode(',', $this->input->post('people')) : ($this->input->post('people') ?? ''),
            'films' => is_array($this->input->post('films')) ? implode(',', $this->input->post('films')) : ($this->input->post('films') ?? ''),
            'film_episode' => is_array($this->input->post('film_episode')) ? implode(',', $this->input->post('film_episode')) : ($this->input->post('film_episode') ?? ''),
            'display' => $this->input->post('display'),
            'publish' => $this->input->post('publish'),
            'meta_title' => $this->input->post('meta_title'),
            'meta_keywords' => $this->input->post('meta_keywords'),
            'meta_description' => $this->input->post('meta_description'),
            'date_of_upload' => date('Y-m-d H:i:s')
        );

        $insert = $this->PersonModel->insert_person($data);

        if ($insert) {
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
            $config['upload_path'] = './Uploads/';
            $config['allowed_types'] = 'gif|jpg|png|jpeg';
            $config['max_size'] = 2048; // 2MB
            $config['file_name'] = time() . '_' . $_FILES['thumbnail_image_upload']['name'];

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('thumbnail_image_upload')) {
                $thumbnail_image_upload = $this->upload->data('file_name');
                $thumbnail_url = $thumbnail_image_upload;
                // Delete old file if exists
                $existing_person = $this->PersonModel->get_person_by_id($id);
                if ($existing_person->thumbnail_image_upload && file_exists('./Uploads/' . $existing_person->thumbnail_image_upload)) {
                    unlink('./Uploads/' . $existing_person->thumbnail_image_upload);
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
            'primary_occupation' => is_array($this->input->post('primary_occupation')) ? implode(',', $this->input->post('primary_occupation')) : ($this->input->post('primary_occupation') ?? ''),
            'occupation' => is_array($this->input->post('occupation')) ? implode(',', $this->input->post('occupation')) : ($this->input->post('occupation') ?? ''),
            'profile' => $this->input->post('profile'),
            'thumbnail_image_upload' => $thumbnail_image_upload,
            'thumbnail_excerpt' => $this->input->post('thumbnail_excerpt'),
            'thumbnail_url' => $thumbnail_url,
            'about' => $this->input->post('about'),
            'keywords' => is_array($this->input->post('keywords')) ? implode(',', $this->input->post('keywords')) : ($this->input->post('keywords') ?? ''),
            'songs' => is_array($this->input->post('songs')) ? implode(',', $this->input->post('songs')) : ($this->input->post('songs') ?? ''),
            'reflections' => is_array($this->input->post('reflections')) ? implode(',', $this->input->post('reflections')) : ($this->input->post('reflections') ?? ''),
            'poems' => is_array($this->input->post('poems')) ? implode(',', $this->input->post('poems')) : ($this->input->post('poems') ?? ''),
            'people' => is_array($this->input->post('people')) ? implode(',', $this->input->post('people')) : ($this->input->post('people') ?? ''),
            'films' => is_array($this->input->post('films')) ? implode(',', $this->input->post('films')) : ($this->input->post('films') ?? ''),
            'film_episode' => is_array($this->input->post('film_episode')) ? implode(',', $this->input->post('film_episode')) : ($this->input->post('film_episode') ?? ''),
            'display' => $this->input->post('display'),
            'publish' => $this->input->post('publish'),
            'meta_title' => $this->input->post('meta_title'),
            'meta_keywords' => $this->input->post('meta_keywords'),
            'meta_description' => $this->input->post('meta_description'),
            'date_of_upload' => date('Y-m-d H:i:s')
        );

        $update = $this->PersonModel->update_person($id, $data);

        if ($update) {
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

        // Prepare display map (if needed, here just Yes/No)
        $display_map = [
            '1' => 'Yes',
            '0' => 'No',
            'yes' => 'Yes',
            'no' => 'No',
            'Yes' => 'Yes',
            'No' => 'No',
        ];

        foreach ($people as $p) {
            // Occupation: convert IDs to names
            $occupation_names = '';
            $names = [];
            $personId = isset($p->id) ? (string)$p->id : '';
            $mappedOccupationIds = ($personId !== '' && isset($person_category_map[$personId])) ? $person_category_map[$personId] : [];
            if (!empty($mappedOccupationIds)) {
                foreach ($mappedOccupationIds as $oid) {
                    if (isset($occupation_map[$oid])) {
                        $names[] = $occupation_map[$oid];
                    }
                }
            } elseif (!empty($p->occupation)) {
                // Fallback for legacy rows that still use person.occupation CSV.
                $ids = array_filter(explode(',', $p->occupation));
                foreach ($ids as $oid) {
                    $oid = trim($oid);
                    if (isset($occupation_map[$oid])) {
                        $names[] = $occupation_map[$oid];
                    } else if ($oid !== '') {
                        $names[] = $oid;
                    }
                }
            }
            if (!empty($names)) {
                $names = array_values(array_unique($names));
                $occupation_names = implode(', ', $names);
            }

            // Display: convert to Yes/No
            $display_val = isset($display_map[$p->display]) ? $display_map[$p->display] : $p->display;

            $data[] = [
                'sl_no' => $sl_no++,
                'date_of_upload' => !empty($p->date_of_upload) ? date('d-m-Y H:i', strtotime($p->date_of_upload)) : '-',
                'person_name' => trim(($p->first_name ?? '') . ' ' . ($p->last_name ?? '')),
                'occupation' => $occupation_names,
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

    private function normalize_person_for_form($person) {
        if (!$person || !is_object($person)) {
            return;
        }

        if ((!isset($person->profile) || trim((string)$person->profile) === '')) {
            foreach (['profile_tags', 'profile_tag', 'tags'] as $k) {
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