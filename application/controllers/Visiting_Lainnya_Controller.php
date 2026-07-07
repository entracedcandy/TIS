<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Visiting_Lainnya_Controller extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper(['form', 'url']);
        $this->load->library('form_validation');
        $this->load->model(['M_Dash' => 'dash', 'M_Visiting' => 'visiting', 'M_Questions']);
    }

    public function index() {
        $token = $this->session->userdata('token');
        $user = $this->dash->getUserInfo($token)->row_array();

        if ($this->input->method() === 'post') {
            $this->_handle_form_submission($user);
            return;
        }

        $this->_display_form($user);
    }

    private function _handle_form_submission($user) {
        try {
            $form_data_session = $this->session->userdata('visiting_form_data') ?: [];
            $tujuan_kunjungan = $form_data_session['tujuan_kunjungan'] ?? '-';
            
            $jenis_kasus = '-';
            if ($tujuan_kunjungan === 'Kasus' && isset($form_data_session['jenis_kasus'])) {
                $jenis_kasus = $form_data_session['jenis_kasus'];
            }
            
            $processed_data = $this->M_Questions->process_form_data(
                'visiting_lainnya', 
                $this->input->post(), 
                $user
            );

            $final_data = array_merge($processed_data, [
                'tujuan_kunjungan' => $tujuan_kunjungan,
                'jenis_kasus'      => $jenis_kasus,
                'latitude'         => $this->input->post('latitude'),
                'longitude'        => $this->input->post('longitude'),
                'location_address' => $this->input->post('location_address'),
                'waktu_kunjungan'  => date('Y-m-d H:i:s')
            ]);
            
            unset($final_data['master_sub_area_id']);

            $this->visiting->insert_visiting($final_data, 'Lainnya');
            
            $this->session->set_flashdata('success', 'Data visiting berhasil disimpan!');

        } catch (Exception $e) {
            $this->session->set_flashdata('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
        
        $this->session->unset_userdata(['visiting_form_data', 'visiting_type', 'livestock_type']);
        
        redirect('Dashboard_new/index');
    }

    private function _display_form($user) {
        $nama_lokasi = '';

        if (isset($user['group_user']) && $user['group_user'] === 'koordinator') {
            $area = $this->db->get_where('master_area', ['master_area_id' => $user['master_area_id']])->row_array();
            if ($area) {
                $nama_lokasi = $area['nama_area'];
            }
        } else {
            $sub_area = $this->db->get_where('master_sub_area', ['master_sub_area_id' => $user['master_sub_area_id']])->row_array();
            if ($sub_area) {
                $nama_lokasi = $sub_area['nama_sub_area'];
            }
        }

        $all_questions = $this->M_Questions->get_form_questions(
            'visiting_lainnya',
            $user
        );

        foreach ($all_questions as $key => $question) {

            if (isset($question['field_name']) && $question['field_name'] == 'nama_farm') {
                $sub_area_id = $user['master_sub_area_id'] ?? null;
                $this->db->select('nama_farm');
                $this->db->from('master_farm');

                if ($sub_area_id) {
                    if (isset($user['group_user']) && !in_array($user['group_user'], ['koordinator', 'administrator'])) {
                        $this->db->where('master_sub_area_id', $sub_area_id);
                    }
                    elseif (isset($user['group_user']) && $user['group_user'] === 'koordinator') {
                    }
                }
                $this->db->where('tipe_ternak', 'Lainnya');
                $this->db->order_by('nama_farm', 'ASC');

                $filtered_farms = $this->db->get()->result_array();
                $new_options = [];
                if (!empty($filtered_farms)) {
                    foreach ($filtered_farms as $farm) {
                        $new_options[] = [
                            'option_value' => $farm['nama_farm'],
                            'option_text' => $farm['nama_farm']
                        ];
                    }
                }
                $all_questions[$key]['options'] = $new_options;
            }

            elseif (isset($question['field_name']) && $question['field_name'] == 'pakan_lainnya') {
                $this->db->select('nama_pakan');
                $this->db->from('master_pakan'); 
                $this->db->where('tipe_ternak', 'Lainnya'); 
                $this->db->order_by('nama_pakan', 'ASC'); 

                $filtered_pakan = $this->db->get()->result_array();
                $new_options_pakan = [];
                if (!empty($filtered_pakan)) {
                    foreach ($filtered_pakan as $pakan) {
                        $new_options_pakan[] = [
                            'option_value' => $pakan['nama_pakan'], 
                            'option_text' => $pakan['nama_pakan']  
                        ];
                    }
                }
                $all_questions[$key]['options'] = $new_options_pakan;
            }
        }
        
        $data = [
            'title' => 'CP APPS',
            'questions' => $all_questions, 
            'nama_lokasi_header' => $nama_lokasi,
            'visiting_type' => $this->session->userdata('visiting_type'),
            'livestock_type' => $this->session->userdata('livestock_type')
        ];

        $this->load->view('templates/dash_h', $data);
        $this->load->view('form_visiting_lainnya_view', $data);
        $this->load->view('templates/dash_f', $data);
    }
    
    public function get_options_by_livestock_type() {
        $questions_id = $this->input->post('questions_id');
        $livestock_type = $this->input->post('livestock_type');
        $token = $this->session->userdata('token');
        $user = $this->dash->getUserInfo($token)->row_array();
        
        $options = $this->M_Questions->get_options_by_livestock_type(
            $questions_id, 
            $user['master_sub_area_id'], 
            $livestock_type
        );
        
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($options));
    }
    
}