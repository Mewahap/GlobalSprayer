<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        cek_login();

        $this->load->model('Admin_model', 'admin');
    }

    public function index()
    {
        $data['title'] = "Dashboard";
        $data['barang'] = $this->admin->count('barang');
        $data['barang_masuk'] = $this->admin->sum('barang_masuk', 'jumlah_masuk');
        $data['barang_keluar'] = $this->admin->sum('barang_keluar', 'jumlah_keluar');
        $data['supplier'] = $this->admin->count('supplier');
        $data['customer'] = $this->admin->count('customer');
        $data['user'] = $this->admin->count('user');
        $data['stok'] = $this->admin->sum('barang', 'stok');
        $data['barang_min'] = $this->admin->min('barang', 'stok', 10);
        $data['transaksi'] = [
            'barang_masuk' => $this->admin->getBarangMasuk(5),
            'barang_keluar' => $this->admin->getBarangKeluar(5)
        ];
        $terlaris = $this->admin->getBarangTerlaris(5);
        $data['barang_terlaris'] = array_column($terlaris, 'nama_barang');
        $data['jumlah_terlaris'] = array_column($terlaris, 'jumlah_keluar');

        // Line Chart
        $bln = ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'];
        $data['cbm'] = [];
        $data['cbk'] = [];

        foreach ($bln as $b) {
            $data['cbm'][] = $this->admin->chartBarangMasuk($b);
            $data['cbk'][] = $this->admin->chartBarangKeluar($b);
        }

        $this->template->load('templates/dashboard', 'dashboard', $data);
    }
}
