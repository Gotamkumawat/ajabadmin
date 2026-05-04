<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class RadioModel extends CI_Model {

    public function insert_radio($data) {
        return $this->db->insert('radio', $data);
    }

    public function get_all_radio() {
        $query = $this->db->get('radio');
        return $query->result();
    }

    public function get_radio_by_id($id) {
        return $this->db->where('id', $id)->get('radio')->row();
    }

    public function update_radio($id, $data) {
        return $this->db->where('id', $id)->update('radio', $data);
    }

    public function delete_radio($id) {
        return $this->db->where('id', $id)->delete('radio');
    }

}
