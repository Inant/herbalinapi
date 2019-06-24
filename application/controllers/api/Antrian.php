<?php
use Restserver\Libraries\REST_Controller;

defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Antrian extends REST_Controller
{
    public function index_get()
    {
        date_default_timezone_set('Asia/Jakarta');
        $date = date('Y-m-d');

        $tanggal = $this->get('tanggal');
        if ($tanggal === null) {
            //jika tidak ada parameter tanggal, maka yg ditampilkan antrian hari ini
            $antrian = $this->MainModel->getData('a.*, p.nama', 'antrian a', ['pasien p', 'a.id_pasien = p.id_pasien'], "waktu BETWEEN '$date 00:00:00' AND '$date 23:59:59' AND a.status='Mengantri'", ['nomor', 'ASC']);
        } else {
          //jika ada parameter tanggal, maka yg ditampilkan antrian hari itu
            $antrian = $this->MainModel->getData('a.*, p.nama', 'antrian a', ['pasien p', 'a.id_pasien = p.id_pasien'], "waktu BETWEEN '$tanggal 00:00:00' AND '$tanggal 23:59:59' AND a.status='Mengantri'", ['nomor', 'ASC']);
        }
        // var_dump($pasien);
        if ($antrian) {
            $this->response([
        'status' => true,
        'data' => $antrian
      ], REST_Controller::HTTP_OK);
        } else {
            $this->response([
        'status' => false,
        'message' => 'Belum ada antrian'
      ], REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function index_post()
    {
        if ($this->post('id_pasien') == "" || $this->post('keluhan') == "" || $this->post('waktu') == "") {
            $this->response([
        'status' => false,
        'message' => 'Proses pesan antrian gagal, lengkapi data.'
      ], REST_Controller::HTTP_BAD_REQUEST);
        } 
        else {
            // date_default_timezone_set('Asia/Jakarta');
            // $date = date('Y-m-d');
            $date = explode(' ', $this->post('waktu'));
            $date = $date[0];
            $nomor = $this->MainModel->getData("MAX(nomor) as nomor", "antrian", "", "waktu BETWEEN '$date 00:00:00' AND '$date 23:59:59' AND status = 'Mengantri'", "");

            $antrian = array(
              //id_user tidak usah dikasi di form, ngambil dari session
              'id_pasien' => $this->post('id_pasien'),
              'keluhan' => $this->post('keluhan'),
              //waktu di dapat dari gabungan tanggal dan waktu, tanggal nya pasien milih sendiri, waktu nya ngambil waktu sekarang, dipisah berdasarkan spasi
              //format waktu nya tahun-bulan-tanggal jam:menit:detik, contoh = 2019-06-25 09:05:15
              'waktu' => $this->post('waktu'),
              'nomor' => $nomor[0]['nomor'] + 1,
              //status ga usah dikasi di form nya
              'status' => 'Mengantri',
            );

            if ($this->MainModel->insert('antrian', $antrian) > 0) {
                $this->response([
                'status' => true,
                'message' => 'Proses pesan antrian berhasil'
                ], REST_Controller::HTTP_CREATED);
            } 
            else {
                $this->response([
                  'status' => false,
                  'message' => 'Proses pesan antrian gagal'
                ], REST_Controller::HTTP_BAD_REQUEST);
            }
        }
    }

    public function index_put()
    {
        $id = $this->put('id');

        if ($id == null) {
            $this->response([
        'status' => false,
        'message' => 'Proses edit gagal'
      ], REST_Controller::HTTP_BAD_REQUEST);
        }

        if ($this->put('no_identitas') == "" || $this->put('nama') == "" || $this->put('tmpt_lahir') == "" || $this->put('tgl_lahir') == "" || $this->put('gender') == "" || $this->put('alamat') == "" || $this->put('no_hp') == "" || $this->put('pendidikan') == "" || $this->put('status_perkawinan') == "" || $this->put('username') == "" || $this->put('password') == "") {
            $this->response([
        'status' => false,
        'message' => 'Proses edit gagal, lengkapi data anda.'
      ], REST_Controller::HTTP_BAD_REQUEST);
        } else {
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
          'status' => true,
          'message' => 'Proses edit berhasil'
        ], REST_Controller::HTTP_OK);
            } else {
                $this->response([
          'status' => false,
          'message' => 'Proses edit gagal'
        ], REST_Controller::HTTP_BAD_REQUEST);
            }
        }
    }
}
