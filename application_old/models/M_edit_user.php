<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_edit_user extends CI_Model
{
    // Mengambil semua data user dengan nama area & sub-area-nya
    public function get_all_users()
    {
        $this->db->select('
        u.*, 
        a.nama_area, 
        sa.nama_sub_area'
    );
        $this->db->from('z_master_user u');
        $this->db->join('master_area a', 'a.master_area_id = u.master_area_id', 'left');
        $this->db->join('master_sub_area sa', 'sa.master_sub_area_id = u.master_sub_area_id', 'left');
        return $this->db->get()->result_array();
    }

    // Mengambil satu data user berdasarkan ID
    public function get_user_by_id($id_user)
    {
        return $this->db->get_where('z_master_user', ['id_user' => $id_user])->row_array();
    }

    // Mengambil semua data area untuk dropdown
    public function get_all_areas()
    {
        $this->db->order_by('nama_area', 'ASC');
        return $this->db->get('master_area')->result_array();
    }

    // Mengambil semua data sub-area untuk dropdown
    public function get_all_sub_areas()
    {
        return $this->db->get('master_sub_area')->result_array();
    }

    // Memperbarui data user di database
    public function update_user($id_user, $data)
    {
        $this->db->where('id_user', $id_user);
        return $this->db->update('z_master_user', $data);
    }

    public function close_current_area_history($id_user, $end_date)
    {
        $this->db->where('id_user', $id_user);
        $this->db->where('end_date', '9999-12-31'); // Cari record yang masih aktif
        return $this->db->update('history_user_area', ['end_date' => $end_date]);
    }
    
    public function add_new_area_history($data)
    {
        return $this->db->insert('history_user_area', $data);
    }

    public function get_area_history_by_user($id_user)
    {
        $this->db->where('id_user', $id_user);
        $this->db->order_by('start_date', 'DESC');
        return $this->db->get('history_user_area')->result_array();
    }


    /**
     * Mengambil semua user beserta status kontributor harga mereka saat ini.
     * Telah dimodifikasi untuk menangani relasi one-to-many 
     * dengan `GROUP BY` agar user tidak duplikat.
     */
    public function get_all_users_with_selection_status()
    {
        $this->db->select('u.id_user, u.username');
        
        $this->db->select('MAX(h.history_user_terpilih_id) AS histori_id');
        $this->db->select('COUNT(h.history_user_terpilih_id) AS jumlah_kontribusi_aktif');
        
        // --- INI BARIS TAMBAHAN UNTUK FIX ERROR ---
        // Kita tambahkan MAX(h.start_date) untuk mengambil tanggal aktivasi terbaru
        // dari user tersebut. Ini akan memperbaiki error di controller.
        $this->db->select('MAX(h.start_date) AS start_date');
        // --- AKHIR BARIS TAMBAHAN ---

        $this->db->from('z_master_user u');
        $this->db->join('history_user_terpilih h', 'u.id_user = h.id_user AND h.end_date IS NULL', 'left');
        
        $this->db->group_by('u.id_user, u.username'); 
        
        $this->db->order_by('u.username', 'ASC');
        return $this->db->get()->result_array();
    }


    // FUNGSI BARU (Untuk form admin)
    /**
     * Mengambil semua jenis harga yang sedang aktif diikuti oleh user.
     * @param int $id_user
     * @return array Contoh: ['harga_jual_telur_layer', 'harga_jagung']
     */
    public function get_user_active_contributions($id_user)
    {
        $this->db->select('jenis_harga_key');
        $this->db->from('history_user_terpilih');
        $this->db->where('id_user', $id_user);
        $this->db->where('end_date IS NULL');
        $query = $this->db->get();
        
        // Mengubah hasil query menjadi array sederhana
        $active_keys = [];
        foreach ($query->result_array() as $row) {
            $active_keys[] = $row['jenis_harga_key'];
        }
        return $active_keys;
    }

    // FUNGSI BARU (Dipakai oleh Controller Update)
    /**
     * Mengupdate daftar kontribusi user berdasarkan array checkbox yang baru.
     * @param int $id_user
     * @param array $new_selected_keys Contoh: ['harga_jual_telur_layer', 'harga_telur_puyuh']
     * @return bool
     */
    public function update_user_contributions($id_user, $new_selected_keys)
    {
        // 1. Dapatkan semua kontribusi yang SEDANG AKTIF (end_date IS NULL)
        $current_active_keys = $this->get_user_active_contributions($id_user);
        
        // 2. Tentukan apa yang harus dinonaktifkan
        // (Ada di $current_active_keys TAPI TIDAK ADA di $new_selected_keys)
        $keys_to_deactivate = array_diff($current_active_keys, $new_selected_keys);
        
        // 3. Tentukan apa yang harus diaktifkan
        // (Ada di $new_selected_keys TAPI TIDAK ADA di $current_active_keys)
        $keys_to_activate = array_diff($new_selected_keys, $current_active_keys);

        $this->db->trans_start();

        // Nonaktifkan yang perlu
        if (!empty($keys_to_deactivate)) {
            $this->db->where('id_user', $id_user);
            $this->db->where_in('jenis_harga_key', $keys_to_deactivate);
            $this->db->where('end_date IS NULL');
            $this->db->update('history_user_terpilih', ['end_date' => date('Y-m-d H:i:s')]);
        }
        
        // Aktifkan yang baru
        if (!empty($keys_to_activate)) {
            $data_to_insert = [];
            $now = date('Y-m-d H:i:s');
            foreach ($keys_to_activate as $key) {
                $data_to_insert[] = [
                    'id_user' => $id_user,
                    'jenis_harga_key' => $key,
                    'start_date' => $now,
                    'end_date' => NULL
                ];
            }
            $this->db->insert_batch('history_user_terpilih', $data_to_insert);
        }

        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    /**
     * [BARU] Menambahkan user baru ke database
     *
     * @param array $data Data user dari form
     * @return int|bool ID user baru jika sukses, false jika gagal.
     */
    public function create_user($data)
    {
        // PENTING: Hash password sebelum disimpan
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        // Tambahkan data standar
        $data['date_created'] = date('Y-m-d H:i:s');
        // $data['updated_at'] = date('Y-m-d H:i:s');
        
        // Hapus field 'start_date' jika tidak sengaja terkirim ke fungsi ini
        unset($data['start_date']);

        // Insert ke tabel z_master_user
        if ($this->db->insert('z_master_user', $data)) {
            // Kembalikan ID dari user yang baru dibuat
            return $this->db->insert_id();
        } else {
            return false;
        }
    }

}