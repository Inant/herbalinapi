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
  }
}
