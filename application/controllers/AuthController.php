<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AuthController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('UserModel');
        // $this->load->library('session');
        $this->load->library(['session', 'encryption']); // ← add this
        $this->load->helper(['url', 'form']);
    }

    // Login Page
    public function login() {
        if ($this->session->userdata('logged_in')) {
            redirect('welcome');
        }
        $this->load->view('signin');
    }

    // Authentication
   public function authenticate() {
    $username = $this->input->post('username');
    $password = $this->input->post('password');

    $user = $this->UserModel->get_user_by_username($username);

    if ($user) {
        // 🔹 Direct password match (no encryption)
        if ($password === 'p@ssw0rd123' && $user['username'] === 'ajabshahar-admin') {
            if ($user['role'] === 'admin') {
                $session_data = [
                    'user_id'   => $user['id'],
                    'username'  => $user['username'],
                    'role'      => $user['role'],
                    'logged_in' => TRUE
                ];
                $this->session->set_userdata($session_data);
                redirect('welcome');
            } else {
                $this->session->set_flashdata('error', 'Access denied! Admins only.');
                redirect('login');
            }
        } else {
            $this->session->set_flashdata('error', 'Invalid username or password!');
            redirect('login');
        }
    } else {
        $this->session->set_flashdata('error', 'User not found!');
        redirect('login');
    }
}

    // Logout
    public function logout() {
        $this->session->sess_destroy();
        redirect('login');
    }
}
