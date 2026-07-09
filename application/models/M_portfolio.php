<?php

defined('BASEPATH') or exit('No direct script access allowed');

class M_portfolio extends CI_Model
{
  public function __construct(){
    $this->load->database();
    $this->premix = $this->load->database('premix', TRUE);
  }

  public function daftar()
  {
    $this->premix->select('*');
    $this->premix->from('tb_portfolio');
    $this->premix->join('tb_user', 'tb_user.id_user = tb_portfolio.id_user', 'left');
    // $this->premix->join('tb_client', 'tb_client.id_client = tb_portfolio.id_client', 'left');
    $this->premix->join('tb_layanan', 'tb_layanan.judul_layanan = tb_portfolio.nama_layanan', 'left');
    $this->premix->order_by('id_portfolio', 'desc');
    return $this->premix->get()->result();
  }

  public function detail($id_portfolio)
  {
    $this->premix->select('*');
    $this->premix->from('tb_portfolio');
    $this->premix->join('tb_user', 'tb_user.id_user = tb_portfolio.id_user', 'left');
    // $this->premix->join('tb_client', 'tb_client.id_client = tb_portfolio.id_client', 'left');
    $this->premix->join('tb_layanan', 'tb_layanan.judul_layanan = tb_portfolio.nama_layanan', 'left');
    $this->premix->where('id_portfolio', $id_portfolio);
    return $this->premix->get()->row();
  }

  public function tambah()
  {
    $user = $this->session->userdata('id_user');
    $layanan = $this->input->post('layanan');
    $judul = $this->input->post('judul', true);
    $slug = url_title($judul, 'dash', true);
    $isi = $this->input->post('isi', true);
    $status = $this->input->post('status', true);
    $noreg_kementan = $this->input->post('noreg_kementan', true);
    $website = $this->input->post('website', true);
    $date = $this->input->post('date', true);
    $testimoni = $this->input->post('testimoni', true);
    $komposisi = $this->input->post('komposisi', true);

    // CEK GAMBAR JIKA ADA GAMBAR AKAN DIUPLOAD 
    // id   // nama gambar
    $uploadImage = $_FILES['image']['name'];
    // var_dump($uploadImage);
    // die;
    if ($uploadImage) {
      // CEK FILE
      $config['allowed_types'] = 'gif|jpg|png';
      $config['max_size'] = '5048';
      $config['upload_path'] = './assets/img/portfolio/';
      $this->upload->initialize($config);
      // UPLOAD FILE  
      if ($this->upload->do_upload('image')) {
        $gambar = $this->upload->data('file_name');
      } else {
        echo $this->upload->display_errors();
      }
    }
    $data = [
      'id_user'           => $user,
      'nama_layanan'      => $layanan,
      'noreg_kementan'    => $noreg_kementan,
      'judul_portfolio'   => htmlspecialchars($judul),
      'slug_portfolio'    => htmlspecialchars($slug),
      'isi_portfolio'     => $isi,
      'gambar_portfolio'  => $gambar,
      'website_portfolio' => $website,
      'status_portfolio'  => $status,
      'date_project'      => $date,
      'testimoni'         => $testimoni,
      'komposisi'         => $komposisi
    ];

    $this->premix->insert('tb_portfolio', $data);
    $this->session->set_flashdata('success', 'Berhasil Membuat portfolio');
    redirect('portfolio');
  }

  public function edit($data)
  {
    $this->premix->where('id_portfolio', $data['id_portfolio']);
    $this->premix->update('tb_portfolio', $data);
  }

  public function hapus($data)
  {
    $this->premix->where('id_portfolio', $data['id_portfolio']);
    $this->premix->delete('tb_portfolio', $data); // FLASH DATA
    $this->session->set_flashdata('success', 'Berhasil menghapus data');
    redirect('portfolio');
  }


  // FRONT END
  // Read data
  public function read($slug_portfolio)
  {

    $this->premix->select('*');
    $this->premix->from('tb_portfolio');
    $this->premix->join('tb_user', 'tb_user.id_user = tb_portfolio.id_user', 'left');
    // $this->premix->join('tb_client', 'tb_client.id_client = tb_portfolio.id_client', 'left');
    $this->premix->join('tb_layanan', 'tb_layanan.judul_layanan = tb_portfolio.nama_layanan', 'left');
    $this->premix->where('slug_portfolio', $slug_portfolio);
    return $this->premix->get()->row();
  }

  public function recent_portfolio()
  {
    $this->premix->select('*');
    $this->premix->from('tb_portfolio');
    $this->premix->join('tb_user', 'tb_user.id_user = tb_portfolio.id_user', 'left');
    $this->premix->join('tb_layanan', 'tb_layanan.judul_layanan = tb_portfolio.nama_layanan', 'left');
    $this->premix->order_by('id_portfolio', 'desc');
    $this->premix->limit(6);
    return $this->premix->get()->result();
  }

  public function lastst_portfolio()
  {
    $this->premix->select('*');
    $this->premix->from('tb_portfolio');
    $this->premix->join('tb_user', 'tb_user.id_user = tb_portfolio.id_user', 'left');
    $this->premix->join('tb_layanan', 'tb_layanan.judul_layanan = tb_portfolio.nama_layanan', 'left');
    $this->premix->order_by('id_portfolio', 'desc');
    $this->premix->limit(10);
    return $this->premix->get()->result();
  }
}
