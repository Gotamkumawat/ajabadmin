<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class WordController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->model('WordModel');
        $this->load->library('session');
		if (!$this->session->userdata('logged_in')) {
			redirect('login');
		}
    }

    public function add_word() {
        $this->load->view('add-word');
    }

    public function save() {
        $data = array(
            'word_original' => $this->input->post('word_original'),
            'word_transliteration' => $this->input->post('word_transliteration'),
            'diacritic' => $this->input->post('diacritic'),
            'word_translation' => $this->input->post('word_translation'),
            'related_songs' => is_array($this->input->post('related_songs')) ? implode(',', $this->input->post('related_songs')) : ($this->input->post('related_songs') ?? ''),
            'related_reflections' => is_array($this->input->post('related_reflections')) ? implode(',', $this->input->post('related_reflections')) : ($this->input->post('related_reflections') ?? ''),
            'related_couplets' => is_array($this->input->post('related_couplets')) ? implode(',', $this->input->post('related_couplets')) : ($this->input->post('related_couplets') ?? ''),
            'related_episodes' => is_array($this->input->post('related_episodes')) ? implode(',', $this->input->post('related_episodes')) : ($this->input->post('related_episodes') ?? ''),
            'related_people' => is_array($this->input->post('related_people')) ? implode(',', $this->input->post('related_people')) : ($this->input->post('related_people') ?? ''),
            'related_stories' => is_array($this->input->post('related_stories')) ? implode(',', $this->input->post('related_stories')) : ($this->input->post('related_stories') ?? ''),
            'related_keywords' => is_array($this->input->post('related_keywords')) ? implode(',', $this->input->post('related_keywords')) : ($this->input->post('related_keywords') ?? ''),
            'related_poems' => is_array($this->input->post('related_poems')) ? implode(',', $this->input->post('related_poems')) : ($this->input->post('related_poems') ?? ''),
            'related_films' => is_array($this->input->post('related_films')) ? implode(',', $this->input->post('related_films')) : ($this->input->post('related_films') ?? ''),
            'Related_film_episode' => is_array($this->input->post('Related_film_episode')) ? implode(',', $this->input->post('Related_film_episode')) : ($this->input->post('Related_film_episode') ?? ''),
            'glossary_meaning' => $this->input->post('glossary_meaning'),
            'is_glossary_word' => $this->input->post('is_glossary_word'),
            'entry_box' => $this->input->post('entry_box'),
            'is_root_word' => $this->input->post('is_root_word'),
            'is_this_keyword' => $this->input->post('is_this_keyword'),            
            'english_intro_excerpt' => $this->input->post('english_intro_excerpt'),
            'hindi_intro_excerpt' => $this->input->post('hindi_intro_excerpt'),
            'english_transliteration' => $this->input->post('english_transliteration'),
            'english_translation' => $this->input->post('english_translation'),
            'writer' => is_array($this->input->post('writer')) ? implode(',', $this->input->post('writer')) : ($this->input->post('writer') ?? ''),
            'display_ajab_shahar_team' => $this->input->post('display_ajab_shahar_team'),
            'thumbnail_url' => $this->input->post('thumbnail_url'),
            'show_on_landing_page' => $this->input->post('show_on_landing_page'),
            'default_reflection_id' => is_array($this->input->post('default_reflection_id')) ? implode(',', $this->input->post('default_reflection_id')) : ($this->input->post('default_reflection_id') ?? ''),
            'synonyms' => is_array($this->input->post('synonyms')) ? implode(',', $this->input->post('synonyms')) : ($this->input->post('synonyms') ?? ''),
            'related_words' => is_array($this->input->post('related_words')) ? implode(',', $this->input->post('related_words')) : ($this->input->post('related_words') ?? ''),
            'publish' => $this->input->post('publish'),
            'meta_title' => $this->input->post('meta_title'),
            'meta_keywords' => $this->input->post('meta_keywords'),
            'meta_description' => $this->input->post('meta_description'),
            'date_of_upload' => date('Y-m-d H:i:s')
        );

        $insert = $this->WordModel->insert_word($data);

        if ($insert) {
            $this->session->set_flashdata('success', 'Word saved successfully!');
        } else {
            $this->session->set_flashdata('error', 'Failed to save word: ' . $this->db->_error_message());
        }

        redirect('add-word');
    }

    public function edit($id) {
        $data['word'] = $this->WordModel->get_word_by_id($id);
        if (!$data['word']) {
            $this->session->set_flashdata('error', 'Word not found!');
            redirect('words-list');
        }
        $this->load->view('add-word', $data);
    }

    public function update($id) {
        $data = array(
            'word_original' => $this->input->post('word_original'),
            'word_transliteration' => $this->input->post('word_transliteration'),
            'diacritic' => $this->input->post('diacritic'),
            'word_translation' => $this->input->post('word_translation'),
            'related_songs' => is_array($this->input->post('related_songs')) ? implode(',', $this->input->post('related_songs')) : ($this->input->post('related_songs') ?? ''),
            'related_reflections' => is_array($this->input->post('related_reflections')) ? implode(',', $this->input->post('related_reflections')) : ($this->input->post('related_reflections') ?? ''),
            'related_couplets' => is_array($this->input->post('related_couplets')) ? implode(',', $this->input->post('related_couplets')) : ($this->input->post('related_couplets') ?? ''),
            'related_episodes' => is_array($this->input->post('related_episodes')) ? implode(',', $this->input->post('related_episodes')) : ($this->input->post('related_episodes') ?? ''),
            'related_people' => is_array($this->input->post('related_people')) ? implode(',', $this->input->post('related_people')) : ($this->input->post('related_people') ?? ''),
            'related_stories' => is_array($this->input->post('related_stories')) ? implode(',', $this->input->post('related_stories')) : ($this->input->post('related_stories') ?? ''),
            'related_keywords' => is_array($this->input->post('related_keywords')) ? implode(',', $this->input->post('related_keywords')) : ($this->input->post('related_keywords') ?? ''),
            'related_poems' => is_array($this->input->post('related_poems')) ? implode(',', $this->input->post('related_poems')) : ($this->input->post('related_poems') ?? ''),
            'related_films' => is_array($this->input->post('related_films')) ? implode(',', $this->input->post('related_films')) : ($this->input->post('related_films') ?? ''),
            'Related_film_episode' => is_array($this->input->post('Related_film_episode')) ? implode(',', $this->input->post('Related_film_episode')) : ($this->input->post('Related_film_episode') ?? ''),
            'glossary_meaning' => $this->input->post('glossary_meaning'),
            'is_glossary_word' => $this->input->post('is_glossary_word'),
            'entry_box' => $this->input->post('entry_box'),           
            'is_root_word' => $this->input->post('is_root_word'),
            'is_this_keyword' => $this->input->post('is_this_keyword'),      
            'english_intro_excerpt' => $this->input->post('english_intro_excerpt'),
            'hindi_intro_excerpt' => $this->input->post('hindi_intro_excerpt'),
            'english_transliteration' => $this->input->post('english_transliteration'),
            'english_translation' => $this->input->post('english_translation'),
            'writer' => is_array($this->input->post('writer')) ? implode(',', $this->input->post('writer')) : ($this->input->post('writer') ?? ''),
            'display_ajab_shahar_team' => $this->input->post('display_ajab_shahar_team'),
            'thumbnail_url' => $this->input->post('thumbnail_url'),
            'show_on_landing_page' => $this->input->post('show_on_landing_page'),
            'default_reflection_id' => is_array($this->input->post('default_reflection_id')) ? implode(',', $this->input->post('default_reflection_id')) : ($this->input->post('default_reflection_id') ?? ''),
            'synonyms' => is_array($this->input->post('synonyms')) ? implode(',', $this->input->post('synonyms')) : ($this->input->post('synonyms') ?? ''),
            'related_words' => is_array($this->input->post('related_words')) ? implode(',', $this->input->post('related_words')) : ($this->input->post('related_words') ?? ''),
            'publish' => $this->input->post('publish'),
            'meta_title' => $this->input->post('meta_title'),
            'meta_keywords' => $this->input->post('meta_keywords'),
            'meta_description' => $this->input->post('meta_description'),
            'date_of_upload' => date('Y-m-d H:i:s')
        );

        $update = $this->WordModel->update_word($id, $data);

        if ($update) {
            $this->session->set_flashdata('success', 'Word updated successfully!');
        } else {
            $this->session->set_flashdata('error', 'Failed to update word: ' . $this->db->_error_message());
        }

        redirect('words-list');
    }

    public function delete($id) {
        $delete = $this->WordModel->delete_word($id);
        if ($delete) {
            echo json_encode(['status' => 'success', 'message' => 'Word deleted successfully!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to delete word: ' . $this->db->_error_message()]);
        }
    }

    public function fetch_words() {
        $words = $this->WordModel->fetch_words();
        $data = [];
        $sl_no = 1;

        foreach ($words as $word) {
            $data[] = [
                'id' => $word->id,
                'sl_no' => $sl_no++,
                'date_of_upload' => $word->date_of_upload ?? date('Y-m-d H:i:s'),
                'word_original' => $word->word_original,
                'meta_keywords' => $word->meta_keywords,
                'is_root_word' => $word->is_root_word,
                'publish' => $word->publish
            ];
        }

        echo json_encode(['data' => $data]);
    }
}