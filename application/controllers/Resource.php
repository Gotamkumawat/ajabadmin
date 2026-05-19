<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Resource extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Resource_model');
        $this->load->library('session');
		if (!$this->session->userdata('logged_in')) {
			redirect('login');
		}
    }

    public function add() {
        redirect('add_new');
    }

    public function edit($id) {
        $data['resource'] = $this->Resource_model->get_resource_by_id($id);
        if (!$data['resource']) {
            $this->session->set_flashdata('error', 'Resource not found.');
            redirect('resource/list');
        }
        $this->load->view('add-resource', $data);
    }

    public function save() {
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $resource_category = $this->input->post('resource_category');
            if (is_array($resource_category)) {
                $resource_category = implode(',', $resource_category);
            }

            $data = [
                'resource_category' => $resource_category,
                'main_title' => $this->input->post('main_title'),
                'second_title' => $this->input->post('second_title'),
                'info' => $this->input->post('info'),
                'resource_author_name' => $this->input->post('resource_author_name'),
                'thumbnail_url' => $this->input->post('thumbnail_url'),
                'description' => $this->input->post('description'),
                'is_published' => $this->input->post('is_published') === 'true' ? 1 : 0
            ];

            $id = $this->input->post('id');
            if ($id) {
                $update = $this->Resource_model->update_resource($id, $data);
                if ($update) {
                    $this->session->set_flashdata('success', 'Resource updated successfully.');
                } else {
                    $this->session->set_flashdata('error', 'Failed to update resource.');
                }
            } else {
                $insert = $this->Resource_model->insert_resource($data);
                if ($insert) {
                    $this->session->set_flashdata('success', 'Resource added successfully.');
                } else {
                    $this->session->set_flashdata('error', 'Something went wrong. Please try again.');
                }
            }
            redirect('add-resource');
        }
    }

    public function delete($id) {
        $delete = $this->Resource_model->delete_resource($id);
        if ($delete) {
            $this->session->set_flashdata('success', 'Resource deleted successfully.');
        } else {
            $this->session->set_flashdata('error', 'Failed to delete resource.');
        }
        redirect('resource/list');
    }

    public function fetch_resources() {
        $resources = $this->Resource_model->get_all_resources();
        $data = [];
        $sl_no = 1;

        foreach ($resources as $r) {
            $data[] = [
                'sl_no' => $sl_no++,
                'main_title' => $r->main_title,
                'resource_category' => $r->resource_category,
                'is_published' => $r->is_published ? 'Yes' : 'No',
                'action' => '<a href="'.base_url('resource/edit/'.$r->id).'" class="btn btn-sm btn-primary">Edit</a> 
                            <a href="'.base_url('resource/delete/'.$r->id).'" class="btn btn-sm btn-danger" onclick="return confirm(\'Are you sure you want to delete this resource?\')">Delete</a>'
            ];
        }

        echo json_encode(['data' => $data]);
    }

    public function list() {
        $this->load->view('resources-list');
    }
}