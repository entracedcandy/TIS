<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Check_In_Controller extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->model(['M_Dash' => 'dash', 'M_Check_In']);
    }

    /**
     * Menampilkan halaman utama Check-In
     */
    public function index() {
        $data['title'] = 'CP-APPS'; // Judul halaman
        
        $this->load->view('templates/dash_h', $data);
        $this->load->view('check_in_view', $data);
        $this->load->view('templates/dash_f', $data);
    }

    /**
     * Menerima dan memproses data check-in dari form
     */
    public function submit_check_in() {
        // Pastikan request adalah POST
        if ($this->input->method() !== 'post') {
            redirect('Check_In_Controller');
            return;
        }

        try {
            // Ambil info user yang sedang login
            $user = $this->_get_user_info();
            if (!$user) {
                throw new Exception('Sesi tidak valid. Silakan login kembali.');
            }

            // Siapkan data untuk disimpan
            $data = [
                'id_user'          => $user['id_user'],
                'latitude'         => $this->input->post('latitude'),
                'longitude'        => $this->input->post('longitude'),
                'location_address' => $this->input->post('location_address'),
                'check_in_time'    => date('Y-m-d H:i:s') // Timestamp dibuat di sisi server
            ];
            
            // Panggil model untuk menyimpan data
            $success = $this->M_Check_In->insert_check_in($data);

            if ($success) {
                $this->session->set_flashdata('success', 'Check-in berhasil direkam pada ' . $data['check_in_time']);
            } else {
                throw new Exception('Gagal menyimpan data ke database.');
            }

        } catch (Exception $e) {
            $this->session->set_flashdata('error', 'Gagal melakukan check-in: ' . $e->getMessage());
        }

        // Redirect kembali ke halaman check-in
        redirect('Check_In_Controller');
    }

    /**
     * Helper untuk mendapatkan info user dari session
     */
    private function _get_user_info() {
        $token = $this->session->userdata('token');
        if (!$token) return null;
        return $this->dash->getUserInfo($token)->row_array();
    }
}