<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CartoonController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper('url');
        $this->load->library('session'); // ✅ popup के लिए जरूरी
        $this->load->model('CartoonModel');
    }

    // Add / Edit page
    public function add_upload($id = null) {
        $data = [];
        if($id) {
            // Edit mode
            $cartoon = $this->CartoonModel->get_cartoon($id);
            if(!$cartoon) {
                $this->session->set_flashdata('error', 'Cartoon not found!');
                redirect('cartoons-list');
            }
            $data['cartoon'] = $cartoon;
            $data['form_action'] = base_url('cartoon/update');
            $data['button_text'] = 'Update Cartoon';
        } else {
            // Add mode
            $data['cartoon'] = null;
            $data['form_action'] = base_url('cartoon/save');
            $data['button_text'] = 'Add Cartoon';
        }
        $this->load->view('add-upload', $data);
    }

    // Save new cartoon
    public function save() {
        $data = [
            'title' => $this->input->post('title'),
            'thumbnail_url' => $this->input->post('thumbnail_url'),
            'is_published' => $this->input->post('is_published')
        ];

        $insert = $this->CartoonModel->insert_cartoon($data);

        if ($insert) {
            $this->session->set_flashdata('success', 'Cartoon added successfully!');
        } else {
            $this->session->set_flashdata('error', 'Failed to add cartoon.');
        }

        redirect('add-upload'); // Add page wapas
    }

    // Update existing cartoon
    public function update() {
        $id = $this->input->post('id');
        $data = [
            'title' => $this->input->post('title'),
            'thumbnail_url' => $this->input->post('thumbnail_url'),
            'is_published' => $this->input->post('is_published')
        ];

        $update = $this->CartoonModel->update_cartoon($id, $data);

        if ($update) {
            $this->session->set_flashdata('success', 'Cartoon updated successfully!');
        } else {
            $this->session->set_flashdata('error', 'Failed to update cartoon.');
        }

        redirect('cartoons-list'); // List page wapas
    }

    // Delete cartoon
    // ❌ Delete cartoon
    public function delete($id) {
        $delete = $this->CartoonModel->delete_cartoon($id);

        if ($delete) {
            $this->session->set_flashdata('success', 'Cartoon deleted successfully!');
        } else {
            $this->session->set_flashdata('error', 'Failed to delete cartoon.');
        }

        redirect('cartoons-list');
    }

    // Fetch data for DataTable
    public function fetch_cartoons() {
        $cartoons = $this->CartoonModel->get_all_cartoons();
        $data = [];
        $sl_no = 1;

        foreach($cartoons as $c) {
            $data[] = [
                'sl_no' => $sl_no++,
                'title' => $c->title,
                'thumbnail_url' => $c->thumbnail_url ? '<img src="'.$c->thumbnail_url.'" width="50">' : '',
                'is_publish' => $c->is_published ? 'Yes' : 'No',
                'action' => '
                    <a href="'.base_url('cartoon/edit/'.$c->id).'" class="btn btn-sm btn-primary">Edit</a>
                    <a href="'.base_url('cartoon/delete/'.$c->id).'" class="btn btn-sm btn-danger">Delete</a>'
            ];
        }

        echo json_encode(['data' => $data]);
    }
}
