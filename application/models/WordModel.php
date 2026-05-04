<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class WordModel extends CI_Model {

    public function insert_word($data) {
        return $this->db->insert('word', $data);
    }

    public function fetch_words() {
        $this->db->select('*');
        $this->db->from('word');
        $query = $this->db->get();
        return $query->result();
    }

    public function get_word_by_id($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('word');
        return $query->row();
    }

    public function update_word($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('word', $data);
    }

    public function delete_word($id) {
        $this->db->where('id', $id);
        return $this->db->delete('word');
    }
}