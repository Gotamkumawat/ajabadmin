<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AddAboutModel extends CI_Model {

    public function insert_about($data) {
        return $this->db->insert('about_header', $data);
    }

    public function get_all_about() {
        $this->db->select('*');
        $this->db->from('about_header');
        $this->db->order_by('id', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }

    public function get_about_by_id($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('about_header');
        return $query->row();
    }

    public function update_about($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('about_header', $data);
    }

    public function delete_about($id) {
        $this->db->where('id', $id);
        return $this->db->delete('about_header');
    }

    public function insert_sub_header($data) {
        return $this->db->insert('about_subheader', $data);
    }

    public function get_all_sub_header() {
        $this->db->select('*');
        $this->db->from('about_subheader');
        $this->db->order_by('sort_order_no', 'ASC');
        $query = $this->db->get();
        return $query->result();
    }

    public function get_sub_header_by_id($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('about_subheader');
        return $query->row();
    }

    public function update_sub_header($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('about_subheader', $data);
    }

    public function delete_sub_header($id) {
        $this->db->where('id', $id);
        return $this->db->delete('about_subheader');
    }

    public function insert_about_images($data) {
        return $this->db->insert('about_images', $data);
    }

    public function get_all_about_images() {
        $this->db->select('*');
        $this->db->from('about_images');
        $this->db->order_by('sort_order_no', 'ASC');
        $query = $this->db->get();
        return $query->result();
    }

    public function get_about_image_by_id($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('about_images');
        return $query->row();
    }

    public function update_about_image($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('about_images', $data);
    }

    public function delete_about_image($id) {
        $this->db->where('id', $id);
        return $this->db->delete('about_images');
    }
}