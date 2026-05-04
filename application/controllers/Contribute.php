<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Contribute extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('ContributeModel');
    }

    // List page
    public function index() {
        $this->load->view('contributions-list'); // Your view
    }

    // Fetch data for DataTable
    public function fetch_contributes() {
        $contributes = $this->ContributeModel->get_all_contributes();
        $data = [];
        $sl_no = 1;

        foreach($contributes as $c) {
            $data[] = [
                'sl_no' => $sl_no++,
                'category' => $c->category,
                'title' => $c->title,
                'is_publish' => $c->is_publish ? 'Yes' : 'No',
                'action' => '<a href="'.base_url('contribute/edit/'.$c->id).'" class="btn btn-sm btn-primary">Edit</a> 
                             <a href="'.base_url('contribute/delete/'.$c->id).'" class="btn btn-sm btn-danger" onclick="return confirm(\'Are you sure?\')">Delete</a>'
            ];
        }

        echo json_encode(['data' => $data]);
    }
            // Add Contribution Form
            public function add() {
                $data['action_url'] = base_url('contribute/save');
                $this->load->view('add-contribute', $data);
            }

            // Save Contribution
            public function save() {
                $post = $this->input->post();
                $data = [
                    'category' => $post['category'],
                    'title' => $post['title'],
                    'content' => $post['content'],
                    'is_publish' => isset($post['is_publish']) ? 1 : 0,
                ];
                $this->ContributeModel->insert_contribute($data);
                redirect('contributions-list');
            }

            // Edit Contribution Form
            public function edit($id) {
                $contribute = $this->ContributeModel->get_contribute($id);
                $data['contribute'] = $contribute;
                $data['action_url'] = base_url('contribute/update/'.$id);
                $this->load->view('add-contribute', $data);
            }

            // Update Contribution
            public function update($id) {
                $post = $this->input->post();
                $data = [
                    'category' => $post['category'],
                    'title' => $post['title'],
                    'content' => $post['content'],
                    'is_publish' => isset($post['is_publish']) ? 1 : 0,
                ];
                $this->ContributeModel->update_contribute($id, $data);
                redirect('contributions-list');
            }

            // Delete Contribution
            public function delete($id) {
                $this->ContributeModel->delete_contribute($id);
                redirect('contributions-list');
            }

    // Optional: Edit / Delete methods
}
