<?php

defined('BASEPATH') or exit('No direct script access allowed');

class M_berita extends CI_Model
{
  public function __construct(){
    $this->load->database();
    $this->premix = $this->load->database('premix', TRUE);
  }

  public function daftar()
  {
    // MENGGAMBIL SEMUA DATA DARI TB_BERITA
    $this->premix->select('*');
    $this->premix->from('tb_berita');
    // MENGGABUNGKAN DATA DARI TABLE TB_USER & TB_BERITA
    $this->premix->join('tb_user', 'tb_user.id_user = tb_berita.id_user', 'left');
    // MENGGABUNGKAN DATA DARI TABLE TB_KATEGORI & TB_BERITA
    $this->premix->join('tb_kategori_berita', 'tb_kategori_berita.id_kategori = tb_berita.id_kategori', 'left');
    $this->premix->order_by('id_berita', 'DESC');
    return $this->premix->get()->result();
  }

  public function detail($id_berita)
  {
    // MENGGAMBIL DATA BERDASARKAN ID_BERITA
    $this->premix->select('*');
    $this->premix->from('tb_berita');
    // MENGGABUNGKAN DATA DARI TABLE TB_USER & TB_BERITA
    $this->premix->join('tb_user', 'tb_user.id_user = tb_berita.id_user', 'left');
    // MENGGABUNGKAN DATA DARI TABLE TB_KATEGORI & TB_BERITA
    $this->premix->join('tb_kategori_berita', 'tb_kategori_berita.id_kategori = tb_berita.id_kategori', 'left');
    $this->premix->where('id_berita', $id_berita);
    return $this->premix->get()->row();
  }

  public function tambah()
  {
    // MENGGAMBIL DATA DARI INPUTAN
    $user = $this->session->userdata('id_user');
    $kategori = $this->input->post('kategori');
    $judul = $this->input->post('judul', true);
    $slug = url_title($judul, 'dash', true);
    $isi = $this->input->post('isi', true);
    $status = $this->input->post('status', true);
    $jenis = $this->input->post('jenis_berita', true);
    $keywords = $this->input->post('keywords', true);
    // CEK GAMBAR JIKA ADA GAMBAR AKAN DIUPLOAD 
    // id   // nama gambar
    $uploadImage = $_FILES['image']['name'];
    // var_dump($uploadImage);
    // die;
    if ($uploadImage) {
      // CEK FILE
      $config['allowed_types'] = 'gif|jpg|png';
      $config['max_size'] = '5048';
      $config['upload_path'] = './assets/img/berita/';
      $this->upload->initialize($config);
      // UPLOAD FILE  
      if ($this->upload->do_upload('image')) {
        $gambarBertia = $this->upload->data('file_name');
      } else {
        echo $this->upload->display_errors();
      }
    }
    $data = [
      'id_user'       => $user,
      'id_kategori'   => $kategori,
      'slug_berita'   => htmlspecialchars($slug),
      'judul_berita'  => htmlspecialchars($judul),
      'isi_berita'    => $isi,
      'gambar_berita' => $gambarBertia,
      'status_berita' => $status,
      'jenis_berita'  => $jenis,
      'keywords'      => htmlspecialchars($keywords),
    ];

    $this->premix->insert('tb_berita', $data);
    // FLASH DATA
    $this->session->set_flashdata('success', 'Berhasil Membuat Berita');
    redirect('berita');
  }

  public function edit($data)
  {
    // QUERY UPDATE
    $this->premix->where('id_berita', $data['id_berita']);
    $this->premix->update('tb_berita', $data);
  }

  public function hapus($data)
  {
    // QUERY HAPUS
    $this->premix->where('id_berita', $data['id_berita']);
    $this->premix->delete('tb_berita', $data); // FLASH DATA
    $this->session->set_flashdata('success', 'Berhasil menghapus data');
    redirect('berita');
  }


  // FRONT END
  // Read data
  public function read($slug_berita)
  {
    // MENGAMBIL DATA BERDASARKAN SLUG_BERITA
    $this->premix->select('*');
    $this->premix->from('tb_berita');
    // MENGGABUNGKAN DATA DARI TABLE TB_USER & TB_BERITA
    $this->premix->join('tb_user', 'tb_user.id_user = tb_berita.id_user', 'left');
    // MENGGABUNGKAN DATA DARI TABLE TB_KATEGORI & TB_BERITA
    $this->premix->join('tb_kategori_berita', 'tb_kategori_berita.id_kategori = tb_berita.id_kategori', 'left');
    $this->premix->where('slug_berita', $slug_berita);
    return $this->premix->get()->row();
  }

  public function recent_berita()
  {
    // MENGGAMBIL SEMUA DATA DARI TABLE TB_BERITA
    $this->premix->select('*');
    $this->premix->from('tb_berita');
    // MENGGABUNGKAN DATA DARI TABLE TB_USER & TB_BERITA
    $this->premix->join('tb_user', 'tb_user.id_user = tb_berita.id_user', 'left');
    // MENGGABUNGKAN DATA DARI TABLE TB_KATEGORI & TB_BERITA
    $this->premix->join('tb_kategori_berita', 'tb_kategori_berita.id_kategori = tb_berita.id_kategori', 'left');
    $this->premix->order_by('id_berita', 'DESC');
    // DIBATASI 6 DATA 
    $this->premix->limit(6);
    return $this->premix->get()->result();
  }

  public function lastst_berita()
  {
    $this->premix->select('*');
    $this->premix->from('tb_berita');
    $this->premix->join('tb_user', 'tb_user.id_user = tb_berita.id_user', 'left');
    $this->premix->join('tb_kategori_berita', 'tb_kategori_berita.id_kategori = tb_berita.id_kategori', 'left');
    $this->premix->order_by('id_berita', 'DESC');
    $this->premix->limit(10);
    return $this->premix->get()->result();
  }
}
