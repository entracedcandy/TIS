<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Visiting_Pedaging_Controller extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->helper(['form', 'url']);
        $this->load->library('form_validation');
        $this->load->model(['M_Dash' => 'dash', 'M_Visiting' => 'visiting', 'M_Questions', 'M_master_harga']);
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
            $form_data = $this->session->userdata('visiting_form_data') ?: [];
            $tipe_ternak = $this->input->post('tipe_ternak') ?? $form_data['tipe_ternak'] ?? null;

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

            $questions = $this->M_Questions->get_questions_by_page('visiting_pedaging');
            
            $vip_farm = $this->input->post('vip_farm');
            
            $data = [
                'id_user' => $user['id_user'], 
                'tujuan_kunjungan' => $tujuan_kunjungan,
                'jenis_kasus' => $jenis_kasus,
                'latitude' => $this->input->post('latitude'),
                'longitude' => $this->input->post('longitude'),
                'location_address' => $this->input->post('location_address'),
                'vip_farm' => (trim($vip_farm) === 'Ya') ? 'Ya' : 'Tidak'
            ];
            
            foreach ($questions as $q) {
                $input_name = 'q' . $q['questions_id']; 
                $field_name = $q['field_name'];
                
                if (in_array($field_name, ['jenis_ternak', 'tipe_ternak'])) {
                    continue;
                }
                
                if ($tipe_ternak === 'Grower' && $field_name === 'pedaging_harga_panen') {
                    continue;
                }
                
                $value = $this->input->post($input_name);
                
                // <-- DIUBAH DI SINI
                if (in_array($field_name, [
                    // 'efektif_terisi_pedaging', // <-- DIHAPUS
                    'efektif_terisi_cp_pedaging',    // <-- BARU
                    'efektif_terisi_non_cp_pedaging',// <-- BARU
                    'deplesi_pedaging',
                    'intake_pedaging',
                    'fcr_pedaging',
                    'pencapaian_berat_pedaging',
                    'keseragaman_pedaging',
                    'pedaging_harga_panen'
                ])) {
                // <-- AKHIR PERUBAHAN
                    $value = str_replace(',', '', $value);
                    if (strpos($value, '.') !== false) {
                        $value = (float) $value;
                    }
                }
                
                $data[$field_name] = $value;
            }

            $data['waktu_kunjungan'] = date('Y-m-d H:i:s');
            
            // 1. Simpan data visiting (ini sudah ada di kode Anda)
            $this->visiting->insert_visiting($data, $tipe_ternak);
            
            // --- PERUBAHAN DIMULAI DI SINI ---
            
            // 2. Panggil fungsi hitung ulang harga dari model
            try {
                // Panggil fungsi model yang sudah kita pusatkan
                $this->M_master_harga->recalculate_all_prices();
                
                // 3. Ubah pesan sukses agar user tahu kedua proses berhasil
                $this->session->set_flashdata('success', 'Data visiting berhasil disimpan DAN semua harga berhasil dihitung ulang!');
            
            } catch (Exception $recalc_error) {
                // 4. Jika simpan data BERHASIL, tapi hitung ulang GAGAL
                log_message('error', 'Gagal hitung ulang harga setelah submit visiting pedaging: ' . $recalc_error->getMessage());
                $this->session->set_flashdata('warning', 'Data visiting berhasil disimpan, TAPI gagal melakukan hitung ulang harga otomatis. Error: ' . $recalc_error->getMessage());
            }
            // --- PERUBAHAN SELESAI ---

        } catch (Exception $e) {
            // Ini adalah catch jika simpan data visiting GAGAL
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

        $data = [
            'title' => 'CP APPS',
            'questions' => $this->M_Questions->get_form_questions(
                'visiting_pedaging', 
                $user 
            ),
            'nama_lokasi_header' => $nama_lokasi, 
            'visiting_type' => $this->session->userdata('visiting_type'),
            'livestock_type' => $this->session->userdata('livestock_type')
        ];
        

        // echo '<pre>';
        // var_dump($data['questions']);
        // echo '</pre>';
        // die();

        $this->load->view('templates/dash_h', $data);
        $this->load->view('form_visiting_pedaging_view', $data);
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

    public function ajax_refresh_farm_options() {
        // 1. Dapatkan user
        $token = $this->session->userdata('token');
        $user = $this->dash->getUserInfo($token)->row_array();
        
        // 2. Dapatkan tipe_ternak dari POST
        $tipe_ternak = $this->input->post('tipe_ternak');
        if (empty($tipe_ternak)) {
            $this->output->set_status_header(400, 'Tipe ternak required');
            echo json_encode(['error' => 'Tipe ternak tidak valid.']);
            return;
        }

        // BARU: Ambil nama farm yang saat ini dipilih (untuk dipertahankan)
        $selected_nama_farm = $this->input->post('selected_nama_farm');

        try {
            // 3. Temukan konfigurasi pertanyaan 'nama_farm'
            $all_questions = $this->M_Questions->get_questions_by_page('visiting_pedaging');
            $nama_farm_question = null;
            foreach ($all_questions as $q) {
                if ($q['field_name'] === 'nama_farm') {
                    $nama_farm_question = $q;
                    break;
                }
            }

            if (!$nama_farm_question) {
                throw new Exception('Konfigurasi pertanyaan "nama_farm" tidak ditemukan.');
            }

            // 4. Panggil M_Questions untuk mendapatkan opsi farm yang sudah difilter
            $options = $this->M_Questions->get_question_options(
                $nama_farm_question, 
                $user, 
                $tipe_ternak // Filter berdasarkan tipe ternak yang dipilih
            );
            
            // 5. Kembalikan data sebagai JSON
            $response = [
                'options' => $options,
                'selected_farm' => $selected_nama_farm // Kembalikan nilai farm yang sebelumnya dipilih
            ];

            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($response));

        } catch (Exception $e) {
            $this->output->set_status_header(500, 'Server Error');
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
    
}
?>