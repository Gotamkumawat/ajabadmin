<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PersonModel extends CI_Model {

    public function insert_person($data) {
        return $this->db->insert('person', $data);
    }

    public function fetch_all() {
        $this->db->select('*');
        $this->db->from('person');
        $this->db->order_by('id', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }

    public function get_person_by_id($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('person');
        return $query->row();
    }

    public function update_person($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('person', $data);
    }

    public function delete_person($id) {
        $this->db->where('id', $id);
        return $this->db->delete('person');
    }
}