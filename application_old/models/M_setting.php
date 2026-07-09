<?php

defined('BASEPATH') or exit('No direct script access allowed');

class M_setting extends CI_Model
{
  public function __construct(){
    $this->load->database();
    $this->premix = $this->load->database('premix', TRUE);
  }

  public function daftar()
  {
    // MENGGABIL SEMUA DATA DARI TABLE TB_SETTING
    $this->premix->select('*');
    $this->premix->from('tb_setting');
    $this->premix->order_by('id_setting', 'desc');
    return $this->premix->get()->row();
  }

  public function update($data)
  {
    $this->premix->where('id_setting', $data['id_setting']);
    $this->premix->update('tb_setting', $data);
  }
}
