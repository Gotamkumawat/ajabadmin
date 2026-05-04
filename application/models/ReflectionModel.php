<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ReflectionModel extends CI_Model {

    public function insert_reflection($data) {
        return $this->db->insert('reflection', $data);
    }

    public function get_all_reflections() {
        $this->db->select('*');
        $this->db->from('reflection');
        $query = $this->db->get();
        return $query->result();
    }

    public function get_reflection_by_id($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('reflection');
        return $query->row();
    }

    public function update_reflection($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('reflection', $data);
    }

    public function delete_reflection($id) {
        $this->db->where('id', $id);
        return $this->db->delete('reflection');
    }
}