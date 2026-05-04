<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ContributeModel extends CI_Model {

    public function get_all_contributes() {
        $query = $this->db->get('contribute'); // Table name
        return $query->result();
    }

    // Optional: Insert, Update, Delete methods
         public function get_contribute($id) {
                return $this->db->get_where('contribute', ['id' => $id])->row();
            }

            public function insert_contribute($data) {
                return $this->db->insert('contribute', $data);
            }

            public function update_contribute($id, $data) {
                $this->db->where('id', $id);
                return $this->db->update('contribute', $data);
            }

            public function delete_contribute($id) {
                $this->db->where('id', $id);
                return $this->db->delete('contribute');
            }

}
