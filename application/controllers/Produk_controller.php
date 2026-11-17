<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Produk_controller extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Produk_model', 'produk_model');
        $this->load->model('produk_kategori_model', 'produk_kategori_model');
        $this->load->model('Produk_gambar_model', 'produk_gambar_model');
    }

    public function detail($id = 0)
    {
        $id = (int) $id;
        if ($id < 1) {
            redirect('');
            return;
        }

        $produk = $this->produk_model->get_by_id($id);
        if (!$produk) {
            show_404();
            return;
        }

        $data = array(
            'title' => 'Detail Produk - ' . $produk['nama'],
            'pelanggan_login' => $this->session->userdata('pelanggan_login'),
            'produk' => $produk,
            'list_gambar' => $this->produk_gambar_model->ambil_semua($id),
            'kategori' => $this->produk_kategori_model->get_by_id($produk['kategori_id'])
        );

        $this->load->view('ecom/template/header', $data);
        $this->load->view('ecom/produk_detail', $data);
        $this->load->view('ecom/template/footer');
    }

    public function tambah_keranjang()
    {
        if (strtoupper($this->input->method()) !== 'POST') {
            show_404();
            return;
        }

        $id = (int) $this->input->post('id_produk');
        $jumlah = (int) $this->input->post('jumlah');
        $produk = $this->produk_model->get_by_id($id);

        if (!$produk) {
            show_404();
            return;
        }
        if ($jumlah < 1 || $jumlah > (int) $produk['stok']) {
            $this->session->set_flashdata('message', 'Jumlah pembelian tidak valid atau melebihi stok tersedia.');
            redirect('produk/' . $id);
            return;
        }

        $cart = $this->session->userdata('keranjang');
        if (!is_array($cart)) {
            $cart = array();
        }
        $sudah_ada = isset($cart[$id]) ? (int) $cart[$id] : 0;
        if (($sudah_ada + $jumlah) > (int) $produk['stok']) {
            $this->session->set_flashdata('message', 'Jumlah di keranjang melebihi stok tersedia.');
            redirect('produk/' . $id);
            return;
        }

        $cart[$id] = $sudah_ada + $jumlah;
        $this->session->set_userdata('keranjang', $cart);
        $this->session->set_flashdata('message', 'Produk berhasil ditambahkan ke keranjang.');
        redirect('keranjang');
    }
}
