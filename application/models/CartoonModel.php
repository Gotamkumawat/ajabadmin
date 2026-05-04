<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CartoonModel extends CI_Model {

    public function insert_cartoon($data) {
        return $this->db->insert('cartoon', $data);
    }

      // Update existing cartoon
            public function update_cartoon($id, $data) {
                $this->db->where('id', $id);
                return $this->db->update('cartoon', $data);
            }

            // Get cartoon by id
            public function get_cartoon($id) {
                $query = $this->db->get_where('cartoon', ['id' => $id]);
                return $query->row_array();
            }

            // Get all cartoons
            public function get_all_cartoons() {
                $query = $this->db->get('cartoon');
                return $query->result();
            }
               public function delete_cartoon($id) {
                return $this->db->where('id', $id)->delete('cartoon');
            }
}
