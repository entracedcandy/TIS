<?php

defined('BASEPATH') or exit('No direct script access allowed');

class M_kategori extends CI_Model
{
  public function __construct(){
    $this->load->database();
    $this->premix = $this->load->database('premix', TRUE);
  }

  public function daftarKategoriBerita()
  {
    // MENGGAMBIL SEMUA DATA DARI TB_KATEGORI_BERITA
    $this->premix->select('*');
    $this->premix->from('tb_kategori_berita');
    $this->premix->order_by('id_kategori', 'DESC');
    return $this->premix->get()->result();
  }

  public function tambahKategoriBerita()
  {
    // MENGGAMBIL DATA DARI INPUTAN
    $name = $this->input->post('name', true);
    $slug = url_title($name, 'dash', true);
    $data = [
      'slug-kategori' => $slug,
      'nama_kategori' => htmlspecialchars($name),
    ];
    // QUERY INSERT DATA
    $this->premix->insert('tb_kategori_berita', $data);
    $this->session->set_flashdata('success', 'Berhasil Membuat Kategori ' . $name);
    redirect('kategori/kategoriberita');
  }
  public function editKategoriBerita($kategori)
  { // QUERY UPDATE

    $this->premix->set('slug-kategori', $kategori['slug_kategori']);
    $this->premix->set('nama_kategori', $kategori['nama_kategori']);
    $this->premix->where('id_kategori', $kategori['id_kategori']);
    $this->premix->update('tb_kategori_berita', $kategori);

    // FLASH DATA
    $this->session->set_flashdata('success', 'Berhasil mengedit data');
    redirect('kategori/kategoriberita');
  }
  public function hapusKategoriBerita($kategori)
  {
    $this->premix->where('id_kategori', $kategori['id_kategori']);
    $this->premix->delete('tb_kategori_berita', $kategori);
    // FLASH DATA
    $this->session->set_flashdata('success', 'Berhasil menghapus data');
    redirect('kategori/kategoriberita');
  }


  // KATEGORI STAFF
  public function daftarKategoriStaff()
  {
    $this->premix->select('*');
    $this->premix->from('tb_kategori_staff');
    $this->premix->order_by('id_kategori', 'DESC');
    return $this->premix->get()->result();
  }

  public function tambahKategoriStaff()
  {
    $name = $this->input->post('name', true);
    $slug = url_title($name, 'dash', true);
    $data = [
      'slug_kategori' => $slug,
      'nama_kategori' => htmlspecialchars($name),
    ];
    // QUERY INSERT DATA
    $this->premix->insert('tb_kategori_staff', $data);
    $this->session->set_flashdata('success', 'Berhasil Membuat Kategori ' . $name);
    redirect('kategori/kategoriStaff');
  }
  public function editKategoriStaff($kategori)
  { // QUERY UPDATE

    $this->premix->set('slug_kategori', $kategori['slug_kategori']);
    $this->premix->set('nama_kategori', $kategori['nama_kategori']);
    $this->premix->where('id_kategori', $kategori['id_kategori']);
    $this->premix->update('tb_kategori_staff', $kategori);

    // FLASH DATA
    $this->session->set_flashdata('success', 'Berhasil mengedit data');
    redirect('kategori/kategoriStaff');
  }
  public function hapusKategoriStaff($kategori)
  {
    $this->premix->where('id_kategori', $kategori['id_kategori']);
    $this->premix->delete('tb_kategori_staff', $kategori); // FLASH DATA
    $this->session->set_flashdata('success', 'Berhasil menghapus data');
    redirect('kategori/kategoriStaff');
  }


  // KATEGORI CLIENT
  public function daftarKategoriClient()
  {
    $this->premix->select('*');
    $this->premix->from('tb_kategori_client');
    $this->premix->order_by('id_kategori', 'DESC');
    return $this->premix->get()->result();
  }

  public function tambahKategoriClient()
  {
    $name = $this->input->post('name', true);
    $slug = url_title($name, 'dash', true);
    $data = [
      'slug_kategori' => $slug,
      'nama_kategori' => htmlspecialchars($name),
    ];
    // QUERY INSERT DATA
    $this->premix->insert('tb_kategori_client', $data);
    $this->session->set_flashdata('success', 'Berhasil Membuat Kategori ' . $name);
    redirect('kategori/kategoriclient');
  }
  public function editKategoriClient($kategori)
  { // QUERY UPDATE

    $this->premix->set('slug_kategori', $kategori['slug_kategori']);
    $this->premix->set('nama_kategori', $kategori['nama_kategori']);
    $this->premix->where('id_kategori', $kategori['id_kategori']);
    $this->premix->update('tb_kategori_client', $kategori);

    // FLASH DATA
    $this->session->set_flashdata('success', 'Berhasil mengedit data');
    redirect('kategori/kategoriclient');
  }
  public function hapusKategoriClient($kategori)
  {
    $this->premix->where('id_kategori', $kategori['id_kategori']);
    $this->premix->delete('tb_kategori_client', $kategori); // FLASH DATA
    $this->session->set_flashdata('success', 'Berhasil menghapus data');
    redirect('kategori/kategoriclient');
  }
}
