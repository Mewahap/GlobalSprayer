<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Penjualan extends CI_Controller
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
        $data['title'] = "Data Penjualan";
        $data['penjualan'] = $this->admin->getPenjualan();

        // Inisialisasi array untuk menyimpan total penjualan per customer
        $total_penjualan_per_customer = [];

        // Memeriksa apakah variabel $penjualan memiliki nilai
        if ($data['penjualan']) :
            // Melakukan iterasi terhadap setiap penjualan dalam $penjualan
            foreach ($data['penjualan'] as $bk) :
                // Mengambil data barang keluar tanpa batasan tanggal
                $barangkeluar = $this->admin->getBarangKeluar(null, null, null, null, $bk['nama_customer']);

                // Melakukan iterasi terhadap setiap barang keluar untuk menghitung total penjualan
                foreach ($barangkeluar as $bek) {
                    // Inisialisasi total penjualan untuk customer jika belum ada
                    if (!isset($total_penjualan_per_customer[$bk['nama_customer']])) {
                        $total_penjualan_per_customer[$bk['nama_customer']] = 0;
                    }
                    // Menambahkan jumlah keluar dikalikan harga barang ke total penjualan per customer
                    $total_penjualan_per_customer[$bk['nama_customer']] += $bek['jumlah_keluar'] * $bek['harga_barang'];
                }
            endforeach;
        endif;

        // Menyiapkan data untuk ditampilkan di view
        $data['total_penjualan_per_customer'] = $total_penjualan_per_customer;

        $this->template->load('templates/dashboard', 'penjualan/data', $data);
    }

    public function detail($kode_customer, $tanggal)
    {
        $data['title'] = "Detail Penjualan";
        $data['tanggal'] = $tanggal;
        $data['customer'] = $this->admin->get('customer', ['kode_customer' => $kode_customer]);
        $data['barang'] = $this->admin->getBarangFromPenjualan($kode_customer, $tanggal);

        $this->load->view('penjualan/detail', $data);
    }

    public function print_detail_customer($kode_customer, $tanggal)
    {
        $data['title'] = "Detail Penjualan";
        $data['tanggal'] = $tanggal;
        $data['customer'] = $this->admin->get('customer', ['kode_customer' => $kode_customer]);
        $data['barang'] = $this->admin->getBarangFromPenjualan($kode_customer, $tanggal);

        // Load PDF library
        $this->load->library('pdf');

        // Generate PDF
        $html = $this->load->view('penjualan/detail_pdf', $data, true);
        $this->pdf->createPDF($html, 'detail_penjualan_' . $kode_customer . '_' . $tanggal, false);
    }
}
