<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Resource_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function insert_resource($data) {
        $data['created_at'] = date('Y-m-d H:i:s');
        return $this->db->insert('resource', $data);
    }

    public function get_all_resources() {
        $query = $this->db->get('resource');
        return $query->result();
    }

    public function get_resource_by_id($id) {
        $query = $this->db->get_where('resource', ['id' => $id]);
        return $query->row();
    }

    public function update_resource($id, $data) {
        $data['updated_at'] = date('Y-m-d H:i:s');
        $this->db->where('id', $id);
        return $this->db->update('resource', $data);
    }

    public function delete_resource($id) {
        $this->db->where('id', $id);
        return $this->db->delete('resource');
    }
}