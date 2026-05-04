<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Echoes_model extends CI_Model {

    public function get_all_echoes() {
        $query = $this->db->get('echo'); // Table name
        return $query->result();
    }

         public function get_echo_by_id($id) {
                return $this->db->get_where('echo', ['id' => $id])->row();
            }

            public function update_echo($id, $data) {
                $this->db->where('id', $id);
                return $this->db->update('echo', $data);
            }
    // Optional: add insert, update, delete methods later
}
