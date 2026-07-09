<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_Visiting extends CI_Model {
    
    private $table_mapping = [
        'visiting_kantor' => 'visiting_kantor',
        'visiting_agen' => 'visiting_agen',
        'visiting_peternak' => 'visiting_peternak',
        'visiting_kemitraan' => 'visiting_kemitraan',
        'visiting_subagen' => 'visiting_subagen',
        'visiting_koordinasi' => 'visiting_koordinasi',
        // Pedaging types
        'Grower' => 'visiting_p_grower',
        'Bebek Pedaging' => 'visiting_p_bebek_pedaging',
        // Petelur types
        'Layer' => 'visiting_p_layer',
        'Bebek Petelur' => 'visiting_p_bebek_petelur',
        'Puyuh' => 'visiting_p_puyuh',
        'Arap' => 'visiting_p_arap',
        'Lainnya' => 'visiting_p_lainnya'
    ];

    public function __construct() {
        parent::__construct();
    }

    public function insert_visiting($data, $type) {
        // For pedaging types, check tipe_ternak instead of livestock_type
        if (isset($data['tipe_ternak']) && array_key_exists($data['tipe_ternak'], $this->table_mapping)) {
            $table = $this->table_mapping[$data['tipe_ternak']];
        } 
        // For other visiting types
        else if (array_key_exists($type, $this->table_mapping)) {
            $table = $this->table_mapping[$type];
        }
        else {
            throw new Exception('Invalid visiting or livestock type: ' . $type);
        }
        
        return $this->db->insert($table, $data);
    }

    public function insert_visiting_petelur($data, $tipe_ternak) {
        // Use the same logic as insert_visiting but specifically for petelur
        return $this->insert_visiting($data, $tipe_ternak);
    }

    public function get_questions_by_page($page = 'visiting') {
        $this->db->where('page', $page);
        $questions = $this->db->get('questions')->result_array();

        foreach ($questions as &$q) {
            if ($q['type'] === 'radio' || $q['type'] === 'select') {
                if (isset($q['combine_options']) && !empty($q['combine_options'])) {
                    $combine_ids = explode(',', $q['combine_options']);
                    $this->db->where_in('questions_id', $combine_ids);
                    $q['options'] = $this->db->get('options')->result_array();
                } else {
                    $this->db->where('questions_id', $q['questions_id']);
                    $q['options'] = $this->db->get('options')->result_array();
                }
            }
        }

        return $questions;
    }

    public function get_all_pages() {
        $this->db->distinct();
        $this->db->select('page');
        return $this->db->get('questions')->result_array();
    }

    public function get_questions_by_field_name($field_name) {
        $this->db->where('field_name', $field_name);
        return $this->db->get('questions')->result_array();
    }

    public function get_table_by_livestock_type($livestock_type) {
        return isset($this->table_mapping[$livestock_type]) ? $this->table_mapping[$livestock_type] : null;
    }

    public function get_all_livestock_types() {
        return array_keys($this->table_mapping);
    }

    // Method to validate livestock type
    public function is_valid_livestock_type($type) {
        return array_key_exists($type, $this->table_mapping);
    }

    // Method to get data from specific livestock table
    public function get_visiting_data($livestock_type, $conditions = []) {
        $table = $this->get_table_by_livestock_type($livestock_type);
        
        if (!$table) {
            return false;
        }
        
        if (!empty($conditions)) {
            $this->db->where($conditions);
        }
        
        return $this->db->get($table)->result_array();
    }

    // Method to update visiting data
    public function update_visiting_data($livestock_type, $data, $conditions) {
        $table = $this->get_table_by_livestock_type($livestock_type);
        
        if (!$table) {
            return false;
        }
        
        $this->db->where($conditions);
        return $this->db->update($table, $data);
    }

    // Method to delete visiting data
    public function delete_visiting_data($livestock_type, $conditions) {
        $table = $this->get_table_by_livestock_type($livestock_type);
        
        if (!$table) {
            return false;
        }
        
        $this->db->where($conditions);
        return $this->db->delete($table);
    }
}
?>