<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Laporan extends CI_Controller
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
        $this->form_validation->set_rules('transaksi', 'Transaksi', 'required|in_list[barang_masuk,barang_keluar]');
        $this->form_validation->set_rules('tanggal', 'Periode Tanggal', 'required');

        if ($this->form_validation->run() == false) {
            $data['title'] = "Laporan Transaksi";
            $this->template->load('templates/dashboard', 'laporan/form', $data);
        } else {
            $input = $this->input->post(null, true);
            $table = $input['transaksi'];
            $tanggal = $input['tanggal'];
            $pecah = explode(' - ', $tanggal);
            $mulai = date('Y-m-d', strtotime($pecah[0]));
            $akhir = date('Y-m-d', strtotime(end($pecah)));

            $query = '';
            if ($table == 'barang_masuk') {
                $query = $this->admin->getBarangMasuk(null, null, ['mulai' => $mulai, 'akhir' => $akhir]);
            } else {
                $query = $this->admin->getBarangKeluar(null, null, ['mulai' => $mulai, 'akhir' => $akhir]);
            }

            $this->_cetak($query, $table, $tanggal);
        }
    }

    private function _cetak($data, $table_, $tanggal)
    {
        $this->load->library('CustomPDF');
        $table = $table_ == 'barang_masuk' ? 'Barang Masuk' : 'Barang Keluar';

        $pdf = new FPDF();
        $pdf->AddPage('L', 'Letter');
        $pdf->SetFont('Times', 'B', 16);
        $pdf->Image('./assets/img/inventory.jpeg', 10, 8, 17, 15);
        $pdf->Image('./assets/img/2.png', 255, 8, 15, 14);
        $pdf->Cell(260, 7, 'Laporan ' . $table, 0, 1, 'C');
        $pdf->SetFont('Times', '', 10);
        $pdf->Cell(260, 4, 'Tanggal : ' . $tanggal, 0, 1, 'C');
        $pdf->Line(10, 25, 270, 25);
        $pdf->Ln(10);

        $pdf->SetFont('Arial', 'B', 10);

        if ($table_ == 'barang_masuk') :
            $pdf->Cell(10, 7, 'No.', 1, 0, 'C');
            $pdf->Cell(35, 7, 'Tgl Masuk', 1, 0, 'C');
            $pdf->Cell(40, 7, 'ID Transaksi', 1, 0, 'C');
            $pdf->Cell(55, 7, 'Nama Barang', 1, 0, 'C');
            $pdf->Cell(30, 7, 'Jumlah Masuk', 1, 0, 'C');
            $pdf->Cell(42, 7, 'Nama Supplier', 1, 0, 'C');
            $pdf->Cell(40, 7, 'Total Pembelian', 1, 0, 'C');
            $pdf->Ln();

            $no = 1;
            $total = 0;
            foreach ($data as $d) {
                $pdf->SetFont('Arial', '', 10);
                $pdf->Cell(10, 7, $no++ . '.', 1, 0, 'C');
                $pdf->Cell(35, 7, $d['tanggal_masuk'], 1, 0, 'C');
                $pdf->Cell(40, 7, $d['id_barang_masuk'], 1, 0, 'C');
                $pdf->Cell(55, 7, $d['nama_barang'], 1, 0, 'L');
                $pdf->Cell(30, 7, $d['jumlah_masuk'] . ' ' . $d['nama_satuan'], 1, 0, 'C');
                $pdf->Cell(42, 7, $d['nama_supplier'], 1, 0, 'C');
                $pdf->Cell(40, 7, 'Rp ' . number_format($d['harga_masuk'] * $d['jumlah_masuk'], 0, ',', '.'), 1, 0, 'C');
                $pdf->Ln();
                $total += $d['harga_masuk'] * $d['jumlah_masuk']; // Tambahkan total pembelian ke total keseluruhan
            }
            $pdf->Cell(10 + 35 + 40 + 55 + 30 + 42, 7, 'Total', 1, 0, 'C');
            $pdf->Cell(40, 7, 'Rp ' . number_format($total, 0, ',', '.'), 1, 0, 'C');
            $pdf->Ln();

            $pdf->Ln(60);
            $pdf->Cell(75);
            $pdf->Cell(270, 7, 'Slawi, ' . date('d-m-y'), 0, 1, 'C');
            $pdf->Cell(75);
            $pdf->Cell(270, 7, 'Pemilik Toko,', 0, 1, 'C');
            $pdf->Ln(20);
            $pdf->Cell(75);
            $pdf->SetFont('Times', 'B', 15);
            $pdf->Cell(270, 7, 'Sustoro', 0, 1, 'C');
            $pdf->SetFont('Times', '', 12);
            $pdf->Cell(75);
        else :
            $pdf->Cell(10, 7, 'No.', 1, 0, 'C');
            $pdf->Cell(30, 7, 'Tanggal Keluar', 1, 0, 'C');
            $pdf->Cell(40, 7, 'ID Transaksi', 1, 0, 'C');
            $pdf->Cell(50, 7, 'Nama Barang', 1, 0, 'C');
            $pdf->Cell(35, 7, 'Jumlah Keluar', 1, 0, 'C');
            $pdf->Cell(47, 7, 'Customer', 1, 0, 'C');
            $pdf->Cell(35, 7, 'Total Penjualan', 1, 0, 'C');
            $pdf->Ln();

            $no = 1;
            $total = 0;
            foreach ($data as $d) {
                $pdf->SetFont('Arial', '', 10);
                $pdf->Cell(10, 7, $no++ . '.', 1, 0, 'C'); // (lebar, tinggi)
                $pdf->Cell(30, 7, $d['tanggal_keluar'], 1, 0, 'C');
                $pdf->Cell(40, 7, $d['id_barang_keluar'], 1, 0, 'C');
                $pdf->Cell(50, 7, $d['nama_barang'], 1, 0, 'L');
                $pdf->Cell(35, 7, $d['jumlah_keluar'] . ' ' . $d['nama_satuan'], 1, 0, 'C');
                $pdf->Cell(47, 7, $d['nama'], 1, 0, 'C');
                $pdf->Cell(35, 7, 'Rp ' . number_format($d['harga_barang'] * $d['jumlah_keluar'], 0, ',', '.'), 1, 0, 'C');
                $pdf->Ln();
                $total += $d['harga_barang'] * $d['jumlah_keluar'];
            }
            $pdf->Cell(10 + 30 + 40 + 50 + 35 + 47, 7, 'Total', 1, 0, 'C');
            $pdf->Cell(35, 7, 'Rp ' . number_format($total, 0, ',', '.'), 1, 0, 'C');
            $pdf->Ln();

            $pdf->Ln(60);
            $pdf->Cell(75);
            $pdf->Cell(270, 7, 'Slawi, ' . date('d-m-y'), 0, 1, 'C'); 
            $pdf->Cell(75);
            $pdf->Cell(270, 7, 'Pemilik Toko,', 0, 1, 'C');
            $pdf->Ln(20);
            $pdf->Cell(75);
            $pdf->SetFont('Times', 'B', 15);
            $pdf->Cell(270, 7, 'Sustoro', 0, 1, 'C');
            $pdf->SetFont('Times', '', 12);
            $pdf->Cell(75);



        endif;
        ob_end_clean();
        $file_name = $table . ' ' . $tanggal;
        $pdf->Output('I', $file_name);
    }
}





// `$pdf->Ln(60);`
//    - Baris ini berfungsi untuk menambahkan baris kosong vertikal (line break) setinggi 60 satuan pada dokumen PDF.

// `$pdf->Cell(75);`
//    - Baris ini menambahkan cell kosong dengan lebar 75 satuan. Ini berfungsi sebagai spasi horizontal untuk memindahkan posisi berikutnya ke kanan sebesar 75 satuan.

// `$pdf->Cell(270, 7, 'Slawi, ' . date('d-m-y'), 0, 1, 'C');`
//    - Baris ini membuat cell dengan lebar 270 satuan dan tinggi 7 satuan. Teks di dalam cell adalah 'Slawi, ' diikuti dengan tanggal saat ini dalam format 'dd-mm-yy'.
//    - `0` adalah border (0 berarti tanpa border).
//    - `1` berarti pindah ke baris baru setelah cell ini.
//    - `'C'` adalah alignment (tengah).

// `$pdf->Cell(75);`
//    - Menambahkan cell kosong lagi dengan lebar 75 satuan untuk spasi horizontal.

// `$pdf->Cell(270, 7, 'Pemilik Toko,', 0, 1, 'C');`
//    - Membuat cell dengan teks 'Pemilik Toko,' dengan spesifikasi yang sama seperti sebelumnya.

// `$pdf->Ln(20);`
//    - Menambahkan baris kosong vertikal setinggi 20 satuan.

// `$pdf->Cell(75);`
//    - Menambahkan cell kosong lagi dengan lebar 75 satuan untuk spasi horizontal.

// `$pdf->SetFont('Times', 'B', 15);`
//    - Mengatur font ke 'Times', dengan gaya Bold (B), dan ukuran font 15.

// `$pdf->Cell(270, 7, 'Sustoro', 0, 1, 'C');`
//    - Membuat cell dengan teks 'Sustoro' dengan spesifikasi yang sama seperti sebelumnya.

// `$pdf->SetFont('Times', '', 12);`
//     - Mengatur font kembali ke 'Times', dengan gaya normal (tanpa Bold), dan ukuran font 12.

// `$pdf->Cell(75);`
//     - Menambahkan cell kosong lagi dengan lebar 75 satuan untuk spasi horizontal.

// Secara keseluruhan, kode ini berfungsi untuk menambahkan beberapa teks ke dalam dokumen PDF dengan format dan posisi tertentu. Teks yang ditambahkan adalah tanggal saat ini, teks 'Pemilik Toko,', dan nama 'Sustoro', dengan beberapa pengaturan spasi dan format teks.
