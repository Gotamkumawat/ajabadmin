<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Echoes extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Echoes_model'); // Load the model
    }

    // Show Echo list page
    public function index() {
        $this->load->view('echoes-list'); // Your view
    }

    // Fetch data for DataTable
    public function fetch_echoes() {
        $echoes = $this->Echoes_model->get_all_echoes();
        $data = [];
        $sl_no = 1;

        foreach($echoes as $e) {
            $data[] = [
                'sl_no' => $sl_no++,
                'category' => $e->category,
                'title' => $e->title,
                'is_publish' => $e->is_publish ? 'Yes' : 'No',
                'action' => '<a href="'.base_url('echoes/edit/'.$e->id).'" class="btn btn-sm btn-primary">Edit</a> 
                             <a href="'.base_url('echoes/delete/'.$e->id).'" class="btn btn-sm btn-danger" onclick="return confirm(\'Are you sure?\')">Delete</a>'
            ];
        }

        echo json_encode(['data' => $data]);
    }

    // 🔹 Edit Form
    public function edit($id = null) {
        if (!$id) show_404();

        $data['echo'] = $this->Echoes_model->get_echo_by_id($id);
        $data['action_url'] = base_url('echoes/update/'.$id);
        $this->load->view('echoes-form', $data);
    }

    // 🔹 Update Function
    public function update($id) {
        $formData = [
            'category' => $this->input->post('category'),
            'title' => $this->input->post('title'),
            'is_publish' => $this->input->post('is_publish') ? 1 : 0,
        ];

        $this->Echoes_model->update_echo($id, $formData);
        $this->session->set_flashdata('success', 'Echo updated successfully!');
        redirect('echoes');
    }
}
