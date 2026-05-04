<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class NewsModel extends CI_Model {

    public function insert_news($data) {
        return $this->db->insert('news', $data);
    }

    public function get_all_news() {
        $query = $this->db->get('news');
        return $query->result();
    }

    public function get_news_by_id($id) {
        $query = $this->db->where('id', $id)->get('news');
        return $query->row_array();
    }

    public function update_news($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('news', $data);
    }

    public function delete_news($id) {
        $this->db->where('id', $id);
        return $this->db->delete('news');
    }
}