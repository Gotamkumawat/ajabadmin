<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// GlossaryModel: handles CRUD for glossary table
class GlossaryModel extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // Fetch all glossaries
    public function get_all_glossaries() {
        return $this->db->get('glossary')->result_array();
    }

    // Fetch single glossary by id
    public function get_glossary_by_id($id) {
        return $this->db->get_where('glossary', ['id' => $id])->row_array();
    }

    // Insert a glossary
    public function insert_glossary(array $data) {
        return $this->db->insert('glossary', $data);
    }

    // Update a glossary
    public function update_glossary($id, array $data) {
        return $this->db->where('id', $id)->update('glossary', $data);
    }

    // Delete a glossary
    public function delete_glossary($id) {
        return $this->db->where('id', $id)->delete('glossary');
    }

    // Search glossary terms
    public function search_glossaries($term) {
        $this->db->like('glossary_term', $term);
        $this->db->or_like('glossary_meaning', $term);
        return $this->db->get('glossary')->result_array();
    }

    // Published glossaries only
    public function get_published_glossaries() {
        return $this->db->get_where('glossary', ['is_published' => 1])->result_array();
    }
}