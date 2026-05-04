<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login_model extends CI_Model {

    public function check_login($username, $password)
    {
        $query = $this->db->get_where('users', ['username' => $username]);
        $user = $query->row();

        if ($user) {
            // Agar password hash nahi hai to simple match kar lo
            if ($user->password === $password) {
                return $user;
            }

            // Agar hash use kar rahe ho to password_verify() lagao
            // if (password_verify($password, $user->password)) {
            //     return $user;
            // }
        }
        return false;
    }
}
