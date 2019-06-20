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

  public function index_put()
  {
    $id = $this->put('id');

    if ($id == null) {
      $this->response([
        'status' => FALSE,
        'message' => 'Proses edit gagal'
      ], REST_Controller::HTTP_BAD_REQUEST);
    }

    if ($this->put('no_identitas') == "" || $this->put('nama') == "" || $this->put('tmpt_lahir') == "" || $this->put('tgl_lahir') == "" || $this->put('gender') == "" || $this->put('alamat') == "" || $this->put('no_hp') == "" || $this->put('pendidikan') == "" || $this->put('status_perkawinan') == "" || $this->put('username') == "" || $this->put('password') == "" ) {
      $this->response([
        'status' => FALSE,
        'message' => 'Proses edit gagal, lengkapi data anda.'
      ], REST_Controller::HTTP_BAD_REQUEST);
    }
    else{
      $pasien = array(
        'no_identitas' => $this->put('no_identitas'),
        'nama' => $this->put('nama'),
        'tmpt_lahir' => $this->put('tmpt_lahir'),
        'tgl_lahir' => $this->put('tgl_lahir'),
        'gender' => $this->put('gender'),
        'alamat' => $this->put('alamat'),
        'no_hp' => $this->put('no_hp'),
        'pendidikan' => $this->put('pendidikan'),
        'status_perkawinan' => $this->put('status_perkawinan'),
        'username' => $this->put('username'),
        'password' => $this->hash_password($this->put('password')),
      );
  
      if ($this->MainModel->update('pasien', $pasien, ['id_pasien' => $id]) > 0) {
        $this->response([
          'status' => TRUE,
          'message' => 'Proses edit berhasil'
        ], REST_Controller::HTTP_OK);
      }
      else{
        $this->response([
          'status' => FALSE,
          'message' => 'Proses edit gagal'
        ], REST_Controller::HTTP_BAD_REQUEST);
      }
    }

  }
}
