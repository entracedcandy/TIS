<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_target extends CI_Model
{
    
    public function get_all_target()
    {
        $sql = "SELECT 
                    mt.id_target, 
                    u.username, 
                    mt.target,
                    mt.vip_target  /* <-- TAMBAHKAN INI */
                FROM 
                    master_target AS mt
                JOIN 
                    z_master_user AS u ON mt.id_user = u.id_user
                WHERE 
                    mt.is_active = 1";

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function get_target_by_id($id_target) {
        $this->db->select('t.*, u.username'); 
        $this->db->from('master_target t'); 
        $this->db->join('z_master_user u', 'u.id_user = t.id_user', 'left'); 
        $this->db->where('t.id_target', $id_target); 
        return $this->db->get()->row_array();
    }

    public function update_target($id_target, $data)
    {
        $this->db->where('id_target', $id_target);
        return $this->db->update('master_target', $data);
    }

    public function close_current_target_history($id_user, $end_date)
    {
        $this->db->where('id_user', $id_user);
        $this->db->where('end_date', '9999-12-31'); 
        return $this->db->update('history_target', ['end_date' => $end_date]);
    }

    public function add_new_target_history($data)
    {
        return $this->db->insert('history_target', $data);
    }

    public function get_users_without_target()
    {
        $this->db->select('u.id_user, u.username, u.caption');
        $this->db->from('z_master_user u');
        $this->db->join('master_target t', 'u.id_user = t.id_user AND t.is_active = 1', 'left');
        $this->db->where('t.id_user IS NULL'); 
        return $this->db->get()->result_array();
    }

    public function create_target($data)
    {
        return $this->db->insert('master_target', $data);
    }

    public function create_target_history($data)
    {
        return $this->db->insert('history_target', $data);
    }
}
