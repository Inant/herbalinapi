<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Login extends REST_Controller 
{
  public function __construct() {
    parent::__construct();
    $this->load->model('LoginModel');
  }

  public function index_post()
  {
    $username = $this->post('username');
    $password = $this->post('password');

    // $where = array(
    //   'username' => $username, 
    //   'password' => $password,
    //   'status' => 'Aktif'
    // );
    $cek = $this->LoginModel->login($username, $password);

    if ($cek != NULL) {

      $this->response([
        'status' => TRUE,
        'data' => [
          'id_pasien' => $cek['id_pasien'],
          'nama' => $cek['nama'],
          'username' => $cek['username'],
          'haslogin' => 'true'
        ]
      ], REST_Controller::HTTP_OK);
    }
    else{
      $this->response([
        'status' => FALSE,
        'message' => 'Username atau password salah.'
      ], REST_Controller::HTTP_BAD_REQUEST);
    }
  }
}
