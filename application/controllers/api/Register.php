<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Register extends REST_Controller 
{
  public function index_get()
  {
    $id = $this->get('id');
    if ($id === null) {
      $pasien = $this->MainModel->getData('*', 'pasien', '', '', '');
    }
    else{
      $pasien = $this->MainModel->getData('*', 'pasien', '', ['id_pasien' => $id], '');
    }
    // var_dump($pasien);
    if ($pasien) {
      $this->response([
        'status' => TRUE,
        'data' => $pasien
      ], REST_Controller::HTTP_OK);
    }
    else{
      $this->response([
        'status' => FALSE,
        'message' => 'Data tidak ditemukan'
      ], REST_Controller::HTTP_NOT_FOUND);
    }
  }
  private function hash_password($password){
    return password_hash($password, PASSWORD_BCRYPT);
  }
  public function index_post()
  {
    if ($this->post('no_identitas') == "" || $this->post('nama') == "" || $this->post('tmpt_lahir') == "" || $this->post('tgl_lahir') == "" || $this->post('gender') == "" || $this->post('alamat') == "" || $this->post('no_hp') == "" || $this->post('pendidikan') == "" || $this->post('status_perkawinan') == "" || $this->post('username') == "" || $this->post('password') == "" ) {
      $this->response([
        'status' => FALSE,
        'message' => 'Proses register gagal, lengkapi data anda.'
      ], REST_Controller::HTTP_BAD_REQUEST);
    }
    else{
      $pasien = array(
        'no_identitas' => $this->post('no_identitas'),
        'nama' => $this->post('nama'),
        'tmpt_lahir' => $this->post('tmpt_lahir'),
        'tgl_lahir' => $this->post('tgl_lahir'),
        'gender' => $this->post('gender'),
        'alamat' => $this->post('alamat'),
        'no_hp' => $this->post('no_hp'),
        'pendidikan' => $this->post('pendidikan'),
        'status_perkawinan' => $this->post('status_perkawinan'),
        'status' => 'Aktif',
        'username' => $this->post('username'),
        'password' => $this->hash_password($this->post('password')),
      );
  
      if ($this->MainModel->insert('pasien', $pasien) > 0) {
        $this->response([
          'status' => TRUE,
          'message' => 'Proses register berhasil'
        ], REST_Controller::HTTP_CREATED);
      }
      else{
        $this->response([
          'status' => FALSE,
          'message' => 'Proses register gagal'
        ], REST_Controller::HTTP_BAD_REQUEST);
      }
    }
  }
}
