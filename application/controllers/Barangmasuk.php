<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Barangmasuk extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        cek_login();

        $this->load->model('Admin_model', 'admin');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $data['title'] = "Data Barang Masuk";
        $data['barangmasuk'] = $this->admin->getBarangMasuk();
        $this->template->load('templates/dashboard', 'barang_masuk/data', $data);
    }

    private function _validasi()
    {
        $this->form_validation->set_rules('tanggal_masuk', 'Tanggal Masuk', 'required|trim');
        $this->form_validation->set_rules('petugas_id', 'Petugas', 'required');
        $this->form_validation->set_rules('barang_id', 'Barang', 'required');
        $this->form_validation->set_rules('jumlah_masuk', 'Jumlah Masuk', 'required|trim|numeric|greater_than[0]');
    }

    public function add()
    {
        $this->_validasi();
        if ($this->form_validation->run() == false) {
            $data['title'] = "Data Barang Masuk";
            $this->db->where('role', 'petugas');
            $data['petugas'] = $this->admin->get('user');
            $data['barang'] = $this->admin->get('barang');
            $data['supplier'] = $this->admin->get('supplier');

            // Mendapatkan dan men-generate kode transaksi barang masuk
            $kode = 'T-BM-' . date('ymd');
            $kode_terakhir = $this->admin->getMax('barang_masuk', 'id_barang_masuk', $kode);
            if ($kode_terakhir !== null) {
                $kode_tambah = substr($kode_terakhir, -5, 5);
                $kode_tambah++;
            } else {
                $kode_tambah = 1;
            }
            $number = str_pad($kode_tambah, 5, '0', STR_PAD_LEFT);
            $data['id_barang_masuk'] = $kode . $number;

            $this->template->load('templates/dashboard', 'barang_masuk/add', $data);
        } else {
            $input = $this->input->post(null, true);
            $insert = $this->admin->insert('barang_masuk', $input);

            if ($insert) {
                set_pesan('data berhasil disimpan.');
                redirect('barangmasuk');
            } else {
                set_pesan('Opps ada kesalahan!');
                redirect('barangmasuk/add');
            }
        }
    }

    public function edit($getId)
    {
        $id = encode_php_tags($getId);
        $this->_validasi();
        if ($this->form_validation->run() == false) {
            $data['title'] = "Data Barang Masuk";
            $data['barangmasuk'] = $this->admin->get('barang_masuk', ['id_barang_masuk' => $id]);
            $this->db->where('role', 'petugas');
            $data['petugas'] = $this->admin->get('user');
            $data['barang'] = $this->admin->get('barang');
            $data['supplier'] = $this->admin->get('supplier');
            $data['id_barang_masuk'] = $id;
            $data['stok'] = $this->admin->get('barang', ['id_barang' => $data['barangmasuk']['barang_id']])['stok'];

            $this->template->load('templates/dashboard', 'barang_masuk/edit', $data);
        } else {
            $input = $this->input->post(null, true);
            $update = $this->admin->update('barang_masuk', 'id_barang_masuk', $id, $input);

            if ($update) {
                set_pesan('data berhasil diubah.');
                redirect('barangmasuk');
            } else {
                set_pesan('Opps ada kesalahan!');
                redirect('barangmasuk/edit/' . $id);
            }
        }
    }

    public function delete($getId)
    {
        $id = encode_php_tags($getId);
        if ($this->admin->delete('barang_masuk', 'id_barang_masuk', $id)) {
            set_pesan('data berhasil dihapus.');
        } else {
            set_pesan('data gagal dihapus.', false);
        }
        redirect('barangmasuk');
    }
}
