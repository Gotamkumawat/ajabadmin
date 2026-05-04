<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class KeywordsController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database(); // Database connect
        $this->load->helper('url'); 
        $this->load->model('SongModel'); // Model load
        $this->load->library('session');
        $this->load->library('upload'); // Add upload library
		if (!$this->session->userdata('logged_in')) {
			redirect('login');
		}
    }


    public function keywords(){
        $this->load->view('add-keywords'); // View load
    }

    // Add/Edit Keywords
    public function add_keywords($id = null) {
        $data = [];
        if ($id) {
            $keyword = $this->db->get_where('keywords', ['id' => $id])->row_array();
            if (!$keyword) show_404();
            $data['keyword'] = $keyword;
            $data['form_action'] = base_url('keywords/save');
        } else {
            $data['keyword'] = [];
            $data['form_action'] = base_url('keywords/save');
        }
        $this->load->view('add-keywords', $data);
    }

    // Edit Keywords
    public function edit($id) {
        $data['keyword'] = $this->db->get_where('keywords', ['id' => $id])->row_array();
        if (!$data['keyword']) show_404();
        $data['form_action'] = base_url('keywords/save');
        $this->load->view('add-keywords', $data);
    }

    // Delete Keyword
    public function delete($id) {
        if (empty($id) || !is_numeric($id)) {
            $this->session->set_flashdata('error', 'Invalid keyword ID!');
            redirect('keywords-lists');
            return;
        }

        $this->db->where('id', $id);
        $deleted = $this->db->delete('keywords');

        if ($deleted) {
            $this->session->set_flashdata('success', 'Keyword deleted successfully!');
        } else {
            $this->session->set_flashdata('error', 'Failed to delete the keyword.');
        }

        redirect('keywords-lists');
    }
                 
    public function save() {
        $id = $this->input->post('id');
        
        // Keywords table ke liye data prepare karo
        $data = [
            'word_original' => $this->input->post('word_original'),
            'word_transliteration' => $this->input->post('word_transliteration'),
            'is_keyword' => $this->input->post('is_keyword') ? 1 : 0,
            'word_translation' => $this->input->post('word_translation'),
            'related_songs' => is_array($this->input->post('related_songs')) ? implode(',', $this->input->post('related_songs')) : '',
            'related_poems' => is_array($this->input->post('related_poems')) ? implode(',', $this->input->post('related_poems')) : '',
            'related_reflections' => is_array($this->input->post('related_reflections')) ? implode(',', $this->input->post('related_reflections')) : '',
            'related_films' => is_array($this->input->post('related_films')) ? implode(',', $this->input->post('related_films')) : '',
            'related_film_episodes' => is_array($this->input->post('related_film_episodes')) ? implode(',', $this->input->post('related_film_episodes')) : '',
            'is_glossary' => $this->input->post('is_glossary') ? 1 : 0,
            'diacritic_text' => $this->input->post('diacritic_text'),
            'glossary_meaning' => $this->input->post('glossary_meaning'),
            'meta_title' => $this->input->post('meta_title'),
            'meta_keywords' => $this->input->post('meta_keywords'),
            'meta_description' => $this->input->post('meta_description'),
            'is_published' => $this->input->post('is_published'),
        ];

        if ($id) {
            // Update existing keyword
            $this->db->where('id', $id);
            $this->db->update('keywords', $data);
            $this->session->set_flashdata('success', 'Keyword updated successfully!');
        } else {
            // Insert new keyword
            $this->db->insert('keywords', $data);
            $this->session->set_flashdata('success', 'Keyword added successfully!');
        }

        redirect('keywords-lists');
    }

    // Fetch Keywords for DataTable
    public function fetch_keywords()
    {
        $keywords = $this->db->get('keywords')->result_array();
        $data = [];
        $i = 1;
        foreach ($keywords as $row) {
            $data[] = [
                'id' => $i++,
                'word_transliteration' => $row['word_transliteration'],
                'is_keyword' => $row['is_keyword'] == 1 ? 'Yes' : 'No',
                'word_translation' => $row['word_translation'],
                'is_published' => $row['is_published'] == 1 ? 'Yes' : 'No',
                'actions' => '<a href="'.base_url('edit-keyword/'.$row['id']).'" class="btn btn-sm btn-primary">Edit</a> 
                              <a href="'.base_url('delete-keyword/'.$row['id']).'" class="btn btn-sm btn-danger" onclick="return confirm(\'Are you sure?\')">Delete</a>'
            ];
        }

        echo json_encode(['data' => $data]);
    }

    // Keywords List View
    public function keywordslists() {
        $this->load->view('keywords-lists');
    }
}

