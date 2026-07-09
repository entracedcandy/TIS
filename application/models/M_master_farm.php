<?php
class M_master_farm extends CI_Model {
    
    public function insert_master_farm($data) {
        return $this->db->insert('master_farm', $data);
    }
    
    public function get_questions_by_page($page = 'master_farm') {
        $this->db->where('page', $page);
        return $this->db->get('questions')->result_array();
    }
    
    public function get_farm_by_id($id) {
        return $this->db->get_where('master_farm', ['master_farm_id' => $id])->row_array();
    }
    
    public function update_farm_data($id, $data) {
        $this->db->where('master_farm_id', $id);
        return $this->db->update('master_farm', $data);
    }
    
    public function close_current_capacity_history($master_farm_id, $end_date) {
        $this->db->where('master_farm_id', $master_farm_id);
        $this->db->where('end_date', '9999-12-31'); 
        $this->db->update('history_farm_capacity', ['end_date' => $end_date]);
    }
    
    public function add_new_capacity_history($data) {
        return $this->db->insert('history_farm_capacity', $data);
    }
    
    public function create_initial_capacity_history($master_farm_id, $kapasitas, $nama_farm) {
        $data = [
            'master_farm_id' => $master_farm_id,
            'nama_farm'      => $nama_farm,
            'kapasitas'      => $kapasitas,
            'start_date'     => date('Y-m-d'), 
            'end_date'       => '9999-12-31'   
        ];
        return $this->db->insert('history_farm_capacity', $data);
    }

    public function get_all_peternak() {
        $this->db->order_by('nama_peternak', 'ASC');
        return $this->db->get('master_peternak')->result_array();
    }

    public function get_options_by_field_name($field_name, $page) {
        $this->db->where('field_name', $field_name);
        $this->db->where('page', $page); 
        $question = $this->db->get('questions')->row();
        
        if (!$question) {
            return [];
        }
        
        $this->db->select('option_text');
        $this->db->from('options');
        $this->db->where('questions_id', $question->questions_id);
        $this->db->order_by('option_text', 'ASC');
        
        return $this->db->get()->result_array();
    }
}