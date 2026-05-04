<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class StoryModel extends CI_Model {

    public function insert_story($data) {
        return $this->db->insert('story', $data);
    }

    public function get_story_by_id($id) {
        return $this->db->get_where('story', ['id' => $id])->row();
    }

    public function get_all_story() {
        $query = $this->db->get('story');
        return $query->result();
    }
}