<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class OccupationController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper('url');
        $this->load->library('session');
        if (!$this->session->userdata('logged_in')) {
            redirect('login');
        }
    }

    public function add($id = null) {
        $data = [];
        if ($id !== null) {
            $row = $this->db
                ->where('id', (int)$id)
                ->where('category_type', 'person')
                ->get('category')
                ->row_array();
            if (empty($row)) {
                $this->session->set_flashdata('error', 'Occupation not found');
                redirect('occupation-list');
                return;
            }
            $data['occupation'] = $row;
        } else {
            $data['occupation'] = [];
        }

        $this->load->view('add-occupation', $data);
    }

    public function save() {
        if (!$this->db->table_exists('category')) {
            $this->session->set_flashdata('error', 'Category table not found');
            redirect('occupation-list');
            return;
        }

        $id = (int)$this->input->post('id');
        $name = trim((string)$this->input->post('name'));

        if ($name === '') {
            $this->session->set_flashdata('error', 'Occupation name is required');
            if ($id > 0) {
                redirect('occupation/edit/' . $id);
            } else {
                redirect('add-occupation');
            }
            return;
        }

        $this->db->from('category');
        $this->db->where('category_type', 'person');
        $this->db->where('LOWER(TRIM(name)) =', strtolower($name));
        if ($id > 0) {
            $this->db->where('id !=', $id);
        }
        $duplicate = $this->db->get()->row_array();
        if (!empty($duplicate)) {
            $this->session->set_flashdata('error', 'Occupation name already exists');
            if ($id > 0) {
                redirect('occupation/edit/' . $id);
            } else {
                redirect('add-occupation');
            }
            return;
        }

        $payload = [
            'name' => $name,
            'category_type' => 'person'
        ];

        if ($id > 0) {
            $updated = $this->db
                ->where('id', $id)
                ->where('category_type', 'person')
                ->update('category', $payload);

            $this->session->set_flashdata($updated ? 'success' : 'error', $updated ? 'Occupation updated successfully' : 'Failed to update occupation');
        } else {
            $inserted = $this->db->insert('category', $payload);
            $this->session->set_flashdata($inserted ? 'success' : 'error', $inserted ? 'Occupation added successfully' : 'Failed to add occupation');
        }

        redirect('occupation-list');
    }

    public function fetch_occupations() {
        $this->output->set_content_type('application/json');

        if (!$this->db->table_exists('category')) {
            echo json_encode(['data' => []]);
            return;
        }

        $rows = $this->db
            ->select('id, name')
            ->from('category')
            ->where('category_type', 'person')
            ->where('name IS NOT NULL', null, false)
            ->where("TRIM(name) !=", '')
            ->order_by('name', 'ASC')
            ->get()
            ->result_array();

        $data = [];
        $sl = 1;
        foreach ($rows as $row) {
            $id = isset($row['id']) ? (int)$row['id'] : 0;
            $name = isset($row['name']) ? trim((string)$row['name']) : '';
            $data[] = [
                'sl_no' => $sl++,
                'id' => $id,
                'name' => $name
            ];
        }

        echo json_encode(['data' => $data]);
    }

    public function delete($id) {
        $this->output->set_content_type('application/json');

        if (!$this->db->table_exists('category')) {
            echo json_encode(['status' => 'error', 'message' => 'Category table not found']);
            return;
        }

        $id = (int)$id;
        if ($id <= 0) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid occupation id']);
            return;
        }

        $inUse = false;
        if ($this->db->table_exists('person_category')) {
            $count = $this->db
                ->where('category_id', $id)
                ->count_all_results('person_category');
            $inUse = ($count > 0);
        }

        if ($inUse) {
            echo json_encode(['status' => 'error', 'message' => 'This occupation is mapped with people and cannot be deleted']);
            return;
        }

        $deleted = $this->db
            ->where('id', $id)
            ->where('category_type', 'person')
            ->delete('category');

        if ($deleted) {
            echo json_encode(['status' => 'success', 'message' => 'Occupation deleted successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to delete occupation']);
        }
    }
}

