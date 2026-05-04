<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class StoryController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database(); // Database connect
        $this->load->helper('url'); 
        $this->load->library('session');
        $this->load->model('StoryModel'); // Model load
        $this->load->library('session');
		if (!$this->session->userdata('logged_in')) {
			redirect('login');
		}
    }

    public function add_story() {
        $this->load->view('add-story'); // View load
    }

    public function save() {
        // Get all POST data
        $main_title = $this->input->post('main_title');
        $second_title = $this->input->post('second_title');
        
        // Handle multi-select arrays by imploding them
        $author = $this->input->post('author');
        $author = is_array($author) ? implode(',', $author) : ($author ?? ''); // Implode if array, else empty
        
        $category = $this->input->post('category');
        $category = is_array($category) ? implode(',', $category) : ($category ?? '');
        
        $related_songs = $this->input->post('related_songs');
        $related_songs = is_array($related_songs) ? implode(',', $related_songs) : ($related_songs ?? '');
        
        $related_couplets = $this->input->post('related_couplets');
        $related_couplets = is_array($related_couplets) ? implode(',', $related_couplets) : ($related_couplets ?? '');
        
        $related_words = $this->input->post('related_words');
        $related_words = is_array($related_words) ? implode(',', $related_words) : ($related_words ?? '');
        
        $related_reflections = $this->input->post('related_reflections');
        $related_reflections = is_array($related_reflections) ? implode(',', $related_reflections) : ($related_reflections ?? '');
        
        $related_people = $this->input->post('related_people');
        $related_people = is_array($related_people) ? implode(',', $related_people) : ($related_people ?? '');
        
        $related_films = $this->input->post('related_films');
        $related_films = is_array($related_films) ? implode(',', $related_films) : ($related_films ?? '');
        
        $related_filmEpisode = $this->input->post('related_filmEpisode');
        $related_filmEpisode = is_array($related_filmEpisode) ? implode(',', $related_filmEpisode) : ($related_filmEpisode ?? '');
        
        // Single fields (no implode needed)
        $verb = $this->input->post('verb');
        $description = $this->input->post('description');
        $note = $this->input->post('note');
        $thumbnail_url = $this->input->post('thumbnail_url');
        $is_echo = $this->input->post('is_echo');
        $is_class_room_idea = $this->input->post('is_class_room_idea');
        $is_class_room_experiment = $this->input->post('is_class_room_experiment');
        $is_story = $this->input->post('is_story');
        $show_on_landing_page = $this->input->post('show_on_landing_page');
        $is_published = $this->input->post('is_published');
        $meta_title = $this->input->post('meta_title');
        $meta_keywords = $this->input->post('meta_keywords');
        $meta_description = $this->input->post('meta_description');
        
        // Prepare data array
        $data = array(
            'main_title' => $main_title,
            'second_title' => $second_title,
            'author' => $author,
            'verb' => $verb,
            'description' => $description,
            'note' => $note,
            'category' => $category,
            'thumbnail_url' => $thumbnail_url,
            'is_echo' => $is_echo,
            'is_class_room_idea' => $is_class_room_idea,
            'is_class_room_experiment' => $is_class_room_experiment,
            'related_songs' => $related_songs,
            'related_couplets' => $related_couplets,
            'related_words' => $related_words,
            'related_reflections' => $related_reflections,
            'related_people' => $related_people,
            'related_films' => $related_films,
            'related_filmEpisode' => $related_filmEpisode,
            'is_story' => $is_story,
            'show_on_landing_page' => $show_on_landing_page,
            'is_published' => $is_published,
            'meta_title' => $meta_title,
            'meta_keywords' => $meta_keywords,
            'meta_description' => $meta_description
        );

        // Insert via model
        $insert = $this->StoryModel->insert_story($data);

        if ($insert) {
            $this->session->set_flashdata('success', 'Story saved successfully!');
        } else {
            $this->session->set_flashdata('error', 'Error saving story: ' . print_r($this->db->error(), true)); // Add error logging
        }

        redirect('add-story');
    }

    // Fetch data for DataTable
    public function fetch_story() {
        $story = $this->StoryModel->get_all_story();
        $data = [];
        $sl_no = 1;

        foreach($story as $c) {
            $data[] = [
                'sl_no' => $sl_no++,
                'main_title' => $c->main_title,
                'verb' => $c->verb,
                'is_publish' => $c->is_published ? 'Yes' : 'No', // Note: Field is is_published, but you wrote is_publish—fix if needed
                'action' => '<a href="'.base_url('story/edit/'.$c->id).'" class="btn btn-sm btn-primary">Edit</a>
                             <a href="'.base_url('story/delete/'.$c->id).'" class="btn btn-sm btn-danger" onclick="return confirm(\'Are you sure?\')">Delete</a>'
            ];
        }

        echo json_encode(['data' => $data]);
    }

    public function edit($id) {
        $story = $this->StoryModel->get_story_by_id($id);
        $data['story'] = $story;
        $this->load->view('add-story', $data); // Reuse same form for edit
    }

    public function update($id) {
        // Same as save: Handle multi-select arrays by imploding them
        $main_title = $this->input->post('main_title');
        $second_title = $this->input->post('second_title');
        
        $author = $this->input->post('author');
        $author = is_array($author) ? implode(',', $author) : ($author ?? '');
        
        $category = $this->input->post('category');
        $category = is_array($category) ? implode(',', $category) : ($category ?? '');
        
        $related_songs = $this->input->post('related_songs');
        $related_songs = is_array($related_songs) ? implode(',', $related_songs) : ($related_songs ?? '');
        
        $related_couplets = $this->input->post('related_couplets');
        $related_couplets = is_array($related_couplets) ? implode(',', $related_couplets) : ($related_couplets ?? '');
        
        $related_words = $this->input->post('related_words');
        $related_words = is_array($related_words) ? implode(',', $related_words) : ($related_words ?? '');
        
        $related_reflections = $this->input->post('related_reflections');
        $related_reflections = is_array($related_reflections) ? implode(',', $related_reflections) : ($related_reflections ?? '');
        
        $related_people = $this->input->post('related_people');
        $related_people = is_array($related_people) ? implode(',', $related_people) : ($related_people ?? '');
        
        $related_films = $this->input->post('related_films');
        $related_films = is_array($related_films) ? implode(',', $related_films) : ($related_films ?? '');
        
        $related_filmEpisode = $this->input->post('related_filmEpisode');
        $related_filmEpisode = is_array($related_filmEpisode) ? implode(',', $related_filmEpisode) : ($related_filmEpisode ?? '');
        
        // Single fields
        $verb = $this->input->post('verb');
        $description = $this->input->post('description');
        $note = $this->input->post('note');
        $thumbnail_url = $this->input->post('thumbnail_url');
        $is_echo = $this->input->post('is_echo');
        $is_class_room_idea = $this->input->post('is_class_room_idea');
        $is_class_room_experiment = $this->input->post('is_class_room_experiment');
        $is_story = $this->input->post('is_story');
        $show_on_landing_page = $this->input->post('show_on_landing_page');
        $is_published = $this->input->post('is_published');
        $meta_title = $this->input->post('meta_title');
        $meta_keywords = $this->input->post('meta_keywords');
        $meta_description = $this->input->post('meta_description');

        // Prepare data array
        $data = array(
            'main_title' => $main_title,
            'second_title' => $second_title,
            'author' => $author,
            'verb' => $verb,
            'description' => $description,
            'note' => $note,
            'category' => $category,
            'thumbnail_url' => $thumbnail_url,
            'is_echo' => $is_echo,
            'is_class_room_idea' => $is_class_room_idea,
            'is_class_room_experiment' => $is_class_room_experiment,
            'related_songs' => $related_songs,
            'related_couplets' => $related_couplets,
            'related_words' => $related_words,
            'related_reflections' => $related_reflections,
            'related_people' => $related_people,
            'related_films' => $related_films,
            'related_filmEpisode' => $related_filmEpisode,
            'is_story' => $is_story,
            'show_on_landing_page' => $show_on_landing_page,
            'is_published' => $is_published,
            'meta_title' => $meta_title,
            'meta_keywords' => $meta_keywords,
            'meta_description' => $meta_description
        );

        $this->db->where('id', $id);
        $update = $this->db->update('story', $data);

        if ($update) {
            $this->session->set_flashdata('success', 'Story updated successfully!');
        } else {
            $this->session->set_flashdata('error', 'Something went wrong while updating: ' . print_r($this->db->error(), true)); // Add error logging
        }

        redirect('stories-list');
    }

    // Add delete if needed (you have route but no function)
    public function delete($id) {
        $this->db->where('id', $id);
        $delete = $this->db->delete('story');
        if ($delete) {
            $this->session->set_flashdata('success', 'Story deleted successfully!');
        } else {
            $this->session->set_flashdata('error', 'Error deleting story.');
        }
        redirect('stories-list');
    }
}