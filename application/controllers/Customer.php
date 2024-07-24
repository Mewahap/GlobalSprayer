<?php
error_reporting(0);
?>
<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Customer extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        cek_login();
        if (!is_admin()) {
            redirect('dashboard');
        }

        $this->load->model('Admin_model', 'admin');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $data['title'] = "Data Customer";
        $data['customers'] = $this->admin->get('customer');
        if (is_admin())
            $data['disable_print'] = true;
        $this->template->load('templates/dashboard', 'customer/data', $data);
    }

    private function _validasi($mode)
    {
        $this->form_validation->set_rules('nama', 'Nama', 'required|trim');
        $this->form_validation->set_rules('telepon', 'Nomor Telepon', 'required|trim');

        if ($mode == 'add') {
            $this->form_validation->set_rules('kode_customer', 'Kode Customer', 'required|trim|is_unique[customer.kode_customer]');
        } else {
            // $this->form_validation->set_rules('kode_customer', 'Kode Customer', 'required|trim');
        }
    }

    public function add()
    {
        $this->_validasi('add');

        if ($this->form_validation->run() == false) {
            $data['title'] = "Tambah Customer";
            $this->template->load('templates/dashboard', 'customer/add', $data);
        } else {
            $input = $this->input->post(null, true);
            $input_data = [
                'kode_customer' => $input['kode_customer'],
                'nama'          => $input['nama'],
                'telepon'       => $input['telepon'],
            ];

            if ($this->admin->insert('customer', $input_data)) {
                set_pesan('data berhasil disimpan.');
                redirect('customer');
            } else {
                set_pesan('data gagal disimpan', false);
                redirect('customer/add');
            }
        }
    }

    public function edit($getId)
    {
        $id = encode_php_tags($getId);
        $this->_validasi('edit');

        if ($this->form_validation->run() == false) {
            $data['title'] = "Edit Customer";
            $data['customer'] = $this->admin->get('customer', ['id_customer' => $id]);
            $this->template->load('templates/dashboard', 'customer/edit', $data);
        } else {
            $input = $this->input->post(null, true);
            $input_data = [
                // 'kode_customer' => $input['kode_customer'],
                'nama'          => $input['nama'],
                'telepon'       => $input['telepon'],
            ];

            if ($this->admin->update('customer', 'id_customer', $id, $input_data)) {
                set_pesan('data berhasil diubah.');
                redirect('customer');
            } else {
                set_pesan('data gagal diubah.', false);
                redirect('customer/edit/' . $id);
            }
        }
    }

    public function delete($getId)
    {
        $id = encode_php_tags($getId);
        if ($this->admin->delete('customer', 'id_customer', $id)) {
            set_pesan('data berhasil dihapus.');
        } else {
            set_pesan('data gagal dihapus.', false);
        }
        redirect('customer');
    }
}
