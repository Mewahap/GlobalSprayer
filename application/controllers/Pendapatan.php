<?php
defined('BASEPATH') or exit('No direct script access allowed');

class pendapatan extends CI_Controller
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
        $data['pendapatan'] = $this->admin->getLaba();
        $this->template->load('templates/dashboard', 'pendapatan/data', $data);
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
