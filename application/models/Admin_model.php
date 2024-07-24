<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin_model extends CI_Model
{
    public function get($table, $data = null, $where = null)
    {
        if ($data != null) {
            return $this->db->get_where($table, $data)->row_array();
        } else {
            return $this->db->get_where($table, $where)->result_array();
        }
    }

    public function update($table, $pk, $id, $data)
    {
        $this->db->where($pk, $id);
        return $this->db->update($table, $data);
    }

    public function insert($table, $data, $batch = false)
    {
        return $batch ? $this->db->insert_batch($table, $data) : $this->db->insert($table, $data);
    }

    public function delete($table, $pk, $id)
    {
        return $this->db->delete($table, [$pk => $id]);
    }

    public function getUsers($id)
    {
        /**
         * ID disini adalah untuk data yang tidak ingin ditampilkan. 
         * Maksud saya disini adalah 
         * tidak ingin menampilkan data user yang digunakan, 
         * pada managemen data user
         */
        $this->db->where('id_user !=', $id);
        return $this->db->get('user')->result_array();
    }

    public function getBarang()
    {
        $this->db->join('jenis j', 'b.jenis_id = j.id_jenis');
        $this->db->join('satuan s', 'b.satuan_id = s.id_satuan');
        $this->db->order_by('id_barang');
        return $this->db->get('barang b')->result_array();
    }

    public function getBarangMasuk($limit = null, $id_barang = null, $range = null)
    {
        $this->db->select('*, u.nama as nama_petugas');
        $this->db->join('user u', 'bm.petugas_id = u.id_user');
        $this->db->join('supplier sp', 'bm.supplier_id = sp.id_supplier');
        $this->db->join('barang b', 'bm.barang_id = b.id_barang');
        $this->db->join('satuan s', 'b.satuan_id = s.id_satuan');
        if ($limit != null) {
            $this->db->limit($limit);
        }

        if ($id_barang != null) {
            $this->db->where('id_barang', $id_barang);
        }

        if ($range != null) {
            $this->db->where('tanggal_masuk' . ' >=', $range['mulai']);
            $this->db->where('tanggal_masuk' . ' <=', $range['akhir']);
        }

        $this->db->order_by('id_barang_masuk', 'DESC');
        return $this->db->get('barang_masuk bm')->result_array();
    }

    public function getBarangKeluar($limit = null, $id_barang = null, $range = null, $unit = null, $customer = null)
    {
        $this->db->select('*, c.nama as nama_customer');
        $this->db->join('user u', 'bk.user_id = u.id_user');
        $this->db->join('customer c', 'bk.customer = c.id_customer');
        $this->db->join('barang b', 'bk.barang_id = b.id_barang');
        $this->db->join('satuan s', 'b.satuan_id = s.id_satuan');
        if ($limit != null) {
            $this->db->limit($limit);
        }
        if ($id_barang != null) {
            $this->db->where('id_barang', $id_barang);
        }
        if ($range != null) {
            $this->db->where('tanggal_keluar' . ' >=', $range['mulai']);
            $this->db->where('tanggal_keluar' . ' <=', $range['akhir']);
        }
        if ($unit != null) {
            $this->db->where('unit', $unit);
        }
        if ($customer != null) {
            $this->db->where('c.nama', $customer);
        }
        $this->db->order_by('id_barang_keluar', 'DESC');
        return $this->db->get('barang_keluar bk')->result_array();
    }

    public function getBarangTerlaris($limit = null, $id_barang = null, $range = null, $unit = null)
    {
        $this->db->select('*, b.nama_barang as nama_barang, sum(jumlah_keluar) as jumlah_keluar');
        $this->db->join('user u', 'bk.user_id = u.id_user');
        $this->db->join('customer c', 'bk.customer= c.id_customer');
        $this->db->join('barang b', 'bk.barang_id = b.id_barang');
        $this->db->join('satuan s', 'b.satuan_id = s.id_satuan');
        if ($limit != null) {
            $this->db->limit($limit);
        }
        if ($id_barang != null) {
            $this->db->where('id_barang', $id_barang);
        }
        if ($range != null) {
            $this->db->where('tanggal_keluar' . ' >=', $range['mulai']);
            $this->db->where('tanggal_keluar' . ' <=', $range['akhir']);
        }
        if ($unit != null) {
            $this->db->where('unit', $unit);
        }
        $this->db->group_by('bk.barang_id');
        $this->db->order_by('jumlah_keluar', 'DESC');
        return $this->db->get('barang_keluar bk')->result_array();
    }

    public function getPenjualan($limit = null, $kode_customer = null, $tanggal_keluar = null, $range = null)
    {
        // get every customer on barang keluar and group by customer
        $this->db->select('*, c.nama as nama_customer, bk.tanggal_keluar as tanggal_penjualan');
        $this->db->join('user u', 'bk.user_id = u.id_user');
        $this->db->join('customer c', 'bk.customer= c.id_customer');
        $this->db->join('barang b', 'bk.barang_id = b.id_barang');
        $this->db->join('satuan s', 'b.satuan_id = s.id_satuan');
        if ($limit != null) {
            $this->db->limit($limit);
        }
        if ($kode_customer != null) {
            $this->db->where('c.kode_customer', $kode_customer);
        }
        if ($tanggal_keluar != null) {
            $this->db->where('tanggal_keluar', $tanggal_keluar);
        }
        if ($range != null) {
            $this->db->where('tanggal_keluar' . ' >=', $range['mulai']);
            $this->db->where('tanggal_keluar' . ' <=', $range['akhir']);
        }
        $this->db->group_by('bk.customer');
        $this->db->group_by('bk.tanggal_keluar');
        $this->db->order_by('id_barang_keluar', 'DESC');
        return $this->db->get('barang_keluar bk')->result_array();
    }

    public function getBarangFromPenjualan($kode_customer, $tanggal_keluar)
    {
        $this->db->select('b.nama_barang, SUM(bk.jumlah_keluar) as total_jumlah, b.harga_barang, s.nama_satuan');
        $this->db->from('barang_keluar bk');
        $this->db->join('barang b', 'bk.barang_id = b.id_barang');
        $this->db->join('satuan s', 'b.satuan_id = s.id_satuan');
        $this->db->where('bk.customer', $kode_customer);
        $this->db->where('DATE(bk.tanggal_keluar)', $tanggal_keluar);

        // Group by nama_barang to sum the quantities for the same barang
        $this->db->group_by('b.nama_barang');

        return $this->db->get()->result_array();
    }

    public function getLaba($kode_barang = null)
    {
        // Subquery untuk total pembelian
        $subquery_pembelian = $this->db->select('bm.barang_id, SUM(bm.jumlah_masuk * b.harga_masuk) AS total_pembelian')
            ->from('barang_masuk bm')
            ->join('barang b', 'b.id_barang = bm.barang_id')
            ->group_by('bm.barang_id')
            ->get_compiled_select();

        // Subquery untuk jumlah barang masuk
        $subquery_barang_masuk = $this->db->select('barang_id, SUM(jumlah_masuk) as jumlahbarangmasuk')
            ->from('barang_masuk')
            ->group_by('barang_id')
            ->get_compiled_select();

        // Subquery untuk jumlah barang keluar
        $subquery_barang_keluar = $this->db->select('barang_id, SUM(jumlah_keluar) as jumlahbarangkeluar')
            ->from('barang_keluar')
            ->group_by('barang_id')
            ->get_compiled_select();

        // Query utama
        $this->db->select('
            MONTH(bk.tanggal_keluar) AS bulan, 
            YEAR(bk.tanggal_keluar) AS tahun, 
            bk.barang_id, 
            b.nama_barang, 
            COALESCE(tp.total_pembelian, 0) AS total_pembelian, 
            SUM(bk.jumlah_keluar * b.harga_barang) AS total_penjualan, 
            SUM((b.harga_barang - b.harga_masuk) * bk.jumlah_keluar) AS laba_pendapatan,
            COALESCE(bm.jumlahbarangmasuk, 0) AS jumlahbarangmasuk,
            COALESCE(bkq.jumlahbarangkeluar, 0) AS jumlahbarangkeluar
        ');

        // Menghubungkan tabel yang diperlukan
        $this->db->from('barang_keluar bk');
        $this->db->join('barang b', 'bk.barang_id = b.id_barang', 'left');
        $this->db->join("($subquery_pembelian) tp", 'bk.barang_id = tp.barang_id', 'left');
        $this->db->join("($subquery_barang_masuk) bm", 'bk.barang_id = bm.barang_id', 'left');
        $this->db->join("($subquery_barang_keluar) bkq", 'bk.barang_id = bkq.barang_id', 'left'); // Mengganti alias

        // Kelompokkan berdasarkan bidang yang ditentukan
        $this->db->group_by(array('bk.barang_id', 'b.nama_barang', 'MONTH(bk.tanggal_keluar)', 'YEAR(bk.tanggal_keluar)'));

        // Urutkan berdasarkan tahun, bulan, dan nama_barang
        $this->db->order_by('YEAR(bk.tanggal_keluar)', 'ASC');
        $this->db->order_by('MONTH(bk.tanggal_keluar)', 'ASC');
        $this->db->order_by('b.nama_barang', 'ASC');

        // Jika kode_barang tertentu diberikan, tambahkan klausul where
        if ($kode_barang !== null) {
            $this->db->where('bk.barang_id', $kode_barang);
        }

        // Eksekusi query dan kembalikan hasilnya
        return $this->db->get()->result_array();
    }

    public function getPetugas()
    {
        $this->db->where('role', 'petugas');
        $this->db->where('is_active', 1);
        return $this->db->get('user')->result_array();
    }

    public function getMax($table, $field, $kode = null)
    {
        $this->db->select_max($field);
        if ($kode != null) {
            $this->db->like($field, $kode, 'after');
        }
        return $this->db->get($table)->row_array()[$field];
    }

    public function count($table)
    {
        return $this->db->count_all($table);
    }

    public function sum($table, $field)
    {
        $this->db->select_sum($field);
        return $this->db->get($table)->row_array()[$field];
    }

    public function min($table, $field, $min)
    {
        $field = $field . ' <=';
        $this->db->where($field, $min);
        return $this->db->get($table)->result_array();
    }

    public function chartBarangMasuk($bulan)
    {
        $like = 'T-BM-' . date('y') . $bulan;
        $this->db->like('id_barang_masuk', $like, 'after');
        return count($this->db->get('barang_masuk')->result_array());
    }

    public function chartBarangKeluar($bulan)
    {
        $like = 'T-BK-' . date('y') . $bulan;
        $this->db->like('id_barang_keluar', $like, 'after');
        return count($this->db->get('barang_keluar')->result_array());
    }

    public function laporan($table, $mulai, $akhir)
    {
        $tgl = $table == 'barang_masuk' ? 'tanggal_masuk' : 'tanggal_keluar';
        $this->db->where($tgl . ' >=', $mulai);
        $this->db->where($tgl . ' <=', $akhir);
        return $this->db->get($table)->result_array();
    }

    public function cekStok($id)
    {
        $this->db->join('satuan s', 'b.satuan_id=s.id_satuan');
        return $this->db->get_where('barang b', ['id_barang' => $id])->row_array();
    }
}
