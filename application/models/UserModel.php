<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UserModel extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_user_by_username($username) {
        return $this->db->where('username', $username)->get('users')->row_array();
    }
}
