<?php
error_reporting(0);
?>
<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Barangkeluar extends CI_Controller
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
        $data['title'] = "Data Barang Keluar";
        $data['barangkeluar'] = $this->admin->getBarangkeluar();
        $this->template->load('templates/dashboard', 'barang_keluar/data', $data);
    }

    private function _validasi()
    {
        $this->form_validation->set_rules('tanggal_keluar', 'Tanggal Keluar', 'required|trim');
        $this->form_validation->set_rules('customer', 'Customer', 'required');
        $this->form_validation->set_rules('barang_id[]', 'Barang', 'required');

        $input = $this->input->post('barang_id', true);
        foreach ($input as $key => $value) {
            $stok = $this->admin->get('barang', ['id_barang' => $value])['stok'];
            $stok_valid = $stok + 1;

            $this->form_validation->set_rules(
                'jumlah_keluar[]',
                'Jumlah Keluar',
                "required|trim|numeric|greater_than[0]|less_than[{$stok_valid}]",
                [
                    'less_than' => "Jumlah Keluar tidak boleh lebih dari {$stok}"
                ]
            );
        }
    }

    public function add()
    {
        $this->_validasi();
        if ($this->form_validation->run() == false) {
            $data['title'] = "Data Barang Keluar";
            $data['barang'] = $this->admin->get('barang', null, ['stok >' => 0]);

            // Mendapatkan dan men-generate kode transaksi barang keluar
            $kode = 'T-BK-' . date('ymd');
            $kode_terakhir = $this->admin->getMax('barang_keluar', 'id_barang_keluar', $kode);
            $kode_tambah = substr($kode_terakhir, -5, 5);
            $kode_tambah++;
            $number = str_pad($kode_tambah, 5, '0', STR_PAD_LEFT);
            $data['id_barang_keluar'] = $kode . $number;
            $data['lokasi'] = "lokasi";
            $data['customer'] = $this->admin->get('customer');
            $this->template->load('templates/dashboard', 'barang_keluar/add', $data);
        } else {
            $input = $this->input->post(null, true);
            $barang_id = $input['barang_id'];
            $jumlah_keluar = $input['jumlah_keluar'];

            $insert = false;
            foreach ($barang_id as $key => $value) {
                $kode = 'T-BK-' . date('ymd');
                $kode_terakhir = $this->admin->getMax('barang_keluar', 'id_barang_keluar', $kode);
                $kode_tambah = substr($kode_terakhir, -5, 5);
                $kode_tambah++;
                $number = str_pad($kode_tambah, 5, '0', STR_PAD_LEFT);
                $input['id_barang_keluar'] = $kode . $number;
                $insert = $this->admin->insert('barang_keluar', [
                    'id_barang_keluar' => $input['id_barang_keluar'],
                    'user_id' => $input['user_id'],
                    'barang_id' => $value,
                    'jumlah_keluar' => $jumlah_keluar[$key],
                    'tanggal_keluar' => $input['tanggal_keluar'],
                    'lokasi' => $input['lokasi'],
                    'customer' => $input['customer']
                ]);
            }

            if ($insert) {
                set_pesan('data berhasil disimpan.');
                redirect('barangkeluar');
            } else {
                set_pesan('Opps ada kesalahan!');
                redirect('barangkeluar/add');
            }
        }
    }

    public function edit($getId)
    {
        $id = encode_php_tags($getId);
        $this->_validasi();
        if ($this->form_validation->run() == false) {
            $data['title'] = "Data Barang Keluar";
            $data['barangkeluar'] = $this->admin->get('barang_keluar', ['id_barang_keluar' => $id]);
            $data['barang'] = $this->admin->get('barang', null, ['stok >' => 0]);
            $data['customer'] = $this->admin->get('customer');
            $data['id_barang_keluar'] = $id;
            // stok
            $barang_id = $data['barangkeluar']['barang_id'];
            $stok = $this->admin->get('barang', ['id_barang' => $barang_id])['stok'];
            $data['stok'] = $stok;
            $this->template->load('templates/dashboard', 'barang_keluar/edit', $data);
        } else {
            $input = $this->input->post(null, true);
            $update = $this->admin->update('barang_keluar', 'id_barang_keluar', $id, $input);
            if ($update) {
                set_pesan('data berhasil disimpan');
                redirect('barangkeluar');
            } else {
                set_pesan('data gagal disimpan', false);
                redirect('barangkeluar/add');
            }
        }
    }

    public function delete($getId)
    {
        $id = encode_php_tags($getId);
        if ($this->admin->delete('barang_keluar', 'id_barang_keluar', $id)) {
            set_pesan('data berhasil dihapus.');
        } else {
            set_pesan('data gagal dihapus.', false);
        }
        redirect('barangkeluar');
    }
}
