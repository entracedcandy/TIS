<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Visiting_Petelur_Controller extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->helper(['form', 'url']);
        $this->load->library('form_validation');
        $this->load->model(['M_Dash' => 'dash', 'M_Questions', 'M_Visiting' => 'visiting', 'M_master_harga']);
    }

    public function index() {
        $user = $this->_get_user_info();

        if ($this->input->method() === 'post') {
            $this->_handle_form_submission($user);
            return;
        }

        $this->_display_form($user);
    }

    public function get_questions_by_type() {
        $tipe_ternak = $this->input->post('tipe_ternak');
        $user = $this->_get_user_info();
        
        $questions = $this->M_Questions->get_questions_by_livestock_type($tipe_ternak, $user);
        
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($questions));
    }

    private function _get_user_info() {
        $token = $this->session->userdata('token');
        return $this->dash->getUserInfo($token)->row_array();
    }

    private function _get_current_sub_area($master_sub_area_id) {
        return $this->db->select('nama_sub_area')
                       ->from('master_sub_area')
                       ->where('master_sub_area_id', $master_sub_area_id)
                       ->get()
                       ->row_array();
    }

    private function _handle_form_submission($user) {
        try {
            $form_data = $this->session->userdata('visiting_form_data') ?: [];
            $tipe_ternak = $this->input->post('tipe_ternak');
            
            if (!$tipe_ternak) {
                throw new Exception('Tipe ternak harus dipilih');
            }

            $tujuan_kunjungan = $form_data['tujuan_kunjungan'] ?? '';
            $jenis_kasus = '-'; 

            if ($tujuan_kunjungan === 'Kasus') {
                if (!empty($form_data['jenis_kasus'])) {
                    $jenis = $form_data['jenis_kasus'];
                    
                    if ($jenis === 'Lambat puncak') {
                        $jenis_kasus = $jenis;
                    } else {
                        $kasus_field_map = [
                            'Bacterial' => 'bacterial',
                            'Viral' => 'virus',
                            'Parasit' => 'parasit',
                            'Jamur' => 'jamur',
                            'Lain-lain' => 'lain_lain'
                        ];

                        $field_name = $kasus_field_map[$jenis] ?? '';
                        
                        $nama_kasus = !empty($field_name) ? ($form_data[$field_name] ?? '') : '';

                        $jenis_kasus = !empty($nama_kasus) ? "$jenis: $nama_kasus" : $jenis;
                    }
                }
            }

            $page = ($tipe_ternak === 'Layer') ? 'layer' : 'visiting_petelur';
            
            $questions = $this->M_Questions->get_questions_by_page($page);
            
            $latitude = $this->input->post('latitude');
            $longitude = $this->input->post('longitude');
            $location_address = $this->input->post('location_address');

            $vip_farm = $this->input->post('vip_farm');

            $data = [
                'id_user' => $user['id_user'],
                'tujuan_kunjungan' => $tujuan_kunjungan,
                'jenis_kasus' => $jenis_kasus,
                'latitude' => $latitude,
                'longitude' => $longitude,
                'location_address' => $location_address,
                'vip_farm' => (trim($vip_farm) === 'Ya') ? 'Ya' : 'Tidak'
            ];
            
            foreach ($questions as $q) {
                $input_name = 'q' . $q['questions_id']; 
                $field_name = $q['field_name'];
                
                if ($field_name === 'tipe_ternak') {
                    continue;
                }
                
                $value = $this->input->post($input_name);
                
                $data[$field_name] = $this->M_Questions->process_field_value($field_name, $value);
            }

            $data['waktu_kunjungan'] = date('Y-m-d H:i:s');
            
            $this->visiting->insert_visiting_petelur($data, $tipe_ternak);


        try {
            $this->M_master_harga->recalculate_all_prices();

            $this->session->set_flashdata('success', 'Data visiting berhasil disimpan DAN semua harga berhasil dihitung ulang!');

        } catch (Exception $recalc_error) {

            log_message('error', 'Gagal hitung ulang harga setelah submit visiting: ' . $recalc_error->getMessage());
            $this->session->set_flashdata('warning', 'Data visiting berhasil disimpan, TAPI gagal melakukan hitung ulang harga otomatis. Error: ' . $recalc_error->getMessage());
        }
            
        } catch (Exception $e) {
            $this->session->set_flashdata('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
        
        redirect('Dashboard_new/index');
    }

    private function _display_form($user) {
        $nama_lokasi = ''; 

        if (isset($user['group_user']) && $user['group_user'] === 'koordinator') {
            $area = $this->db->get_where('master_area', ['master_area_id' => $user['master_area_id']])->row_array();

            // var_dump($area); die();

            if ($area) {
                $nama_lokasi = $area['nama_area'];
            }
        } else {
            $sub_area = $this->db->get_where('master_sub_area', ['master_sub_area_id' => $user['master_sub_area_id']])->row_array();
            if ($sub_area) {
                $nama_lokasi = $sub_area['nama_sub_area'];
            }
        }
        
        $data = [
            'title' => 'CP APPS',
            'nama_lokasi_header' => $nama_lokasi, 
            'questions' => $this->M_Questions->get_form_questions('visiting_petelur', $user)
        ];

        $this->load->view('templates/dash_h', $data);
        $this->load->view('form_visiting_petelur_view', $data);
        $this->load->view('templates/dash_f', $data);
    }

    public function ajax_refresh_farm_options() {
        $token = $this->session->userdata('token');
        $user = $this->dash->getUserInfo($token)->row_array();
        
        $tipe_ternak = $this->input->post('tipe_ternak');
        if (empty($tipe_ternak)) {
            $this->output->set_status_header(400, 'Tipe ternak required');
            echo json_encode(['error' => 'Tipe ternak tidak valid.']);
            return;
        }

        $selected_nama_farm = $this->input->post('selected_nama_farm');

        try {
            $page = ($tipe_ternak === 'Layer') ? 'layer' : 'visiting_petelur';
            $field_name = ($tipe_ternak === 'Layer') ? 'layer_nama_farm' : 'nama_farm';

            $all_questions = $this->M_Questions->get_questions_by_page($page);
            $nama_farm_question = null;
            foreach ($all_questions as $q) {
                if ($q['field_name'] === $field_name) {
                    $nama_farm_question = $q;
                    break;
                }
            }

            if (!$nama_farm_question) {
                $all_questions = $this->M_Questions->get_questions_by_page('visiting_petelur');
                foreach ($all_questions as $q) {
                    if ($q['field_name'] === 'nama_farm') {
                        $nama_farm_question = $q;
                        break;
                    }
                }
            }

            if (!$nama_farm_question) {
                throw new Exception('Konfigurasi pertanyaan "nama_farm" tidak ditemukan.');
            }

            $options = $this->M_Questions->get_question_options(
                $nama_farm_question, 
                $user, 
                $tipe_ternak 
            );
            
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($options));

        } catch (Exception $e) {
            $this->output->set_status_header(500, 'Server Error');
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

}
?>