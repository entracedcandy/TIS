<?php

defined('BASEPATH') or exit('No direct script access allowed');

class M_dashboard extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->premix = $this->load->database('premix', TRUE);
  }

  // Total user
  public function user()
  {
    // MENGHITUNG JUMLAH DATA DARI TABLE TB_USER 
    $this->premix->select('COUNT(*) AS total');
    $this->premix->from('tb_user');
    $query = $this->premix->get();
    return $query->row();
  }

  // Total berita
  public function berita()
  {
    $this->premix->select('COUNT(*) AS total');
    $this->premix->from('tb_berita');
    $query = $this->premix->get();
    return $query->row();
  }

  // Total berita
  public function layanan()
  {
    $this->premix->select('COUNT(*) AS total');
    $this->premix->from('tb_layanan');
    $query = $this->premix->get();
    return $query->row();
  }

  // Total client
  public function client()
  {
    $this->premix->select('COUNT(*) AS total');
    $this->premix->from('tb_client');
    $query = $this->premix->get();
    return $query->row();
  }

  // Total staff
  public function staff()
  {
    $this->premix->select('COUNT(*) AS total');
    $this->premix->from('tb_staff');
    $query = $this->premix->get();
    return $query->row();
  }

  // Total portfolio
  public function portfolio()
  {
    $this->premix->select('COUNT(*) AS total');
    $this->premix->from('tb_portfolio');
    $query = $this->premix->get();
    return $query->row();
  }
}
