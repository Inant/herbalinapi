<?php
defined('BASEPATH') or exit('No direct script access allowed');

class LoginModel extends CI_Model
{
    public function login($username, $password)
    {
        // fetch by username first
        $this->db->where('username', $username);
        $query = $this->db->get('pasien');
        $result = $query->row_array(); // get the row first

        if (!empty($result) && password_verify($password, $result['password'])) {
            // if this username exists, and the input password is verified using password_verify
            return $result;
        } else {
            return false;
        }
    }
}
