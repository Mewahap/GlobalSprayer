<?= $this->session->flashdata('pesan'); ?>
<div class="card shadow-sm border-bottom-primary">
    <div class="card-header bg-white py-3">
        <div class="row">
            <div class="col">
                <h4 class="h5 align-middle m-0 font-weight-bold text-primary">
                    Data Barang Keluar
                </h4>
            </div>
            <div class="col-auto">
                <a href="<?= base_url('barangkeluar/add') ?>" class="btn btn-sm btn-primary btn-icon-split">
                    <span class="icon">
                        <i class="fa fa-plus"></i>
                    </span>
                    <span class="text">
                        Tambah Barang Keluar
                    </span>
                </a>
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-striped w-100 dt-responsive " id="dataTable">
            <thead>
                <tr>
                    <th>No. </th>
                    <th>No Transaksi</th>
                    <th>Customer</th>
                    <th>Tanggal Keluar</th>
                    <th>Nama Barang</th>
                    <th>Harga Barang</th>
                    <th>Jumlah Keluar</th>
                    <th>Total Penjualan</th>
                    <?php if (is_admin()) : ?><th>Aksi</th><?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                if ($barangkeluar) :
                    foreach ($barangkeluar as $bk) :
                ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= $bk['id_barang_keluar']; ?></td>
                            <td><?= $bk['nama_customer']; ?></td>
                            <td><?= $bk['tanggal_keluar']; ?></td>
                            <td><?= $bk['nama_barang']; ?></td>
                            <td><?= "Rp " . number_format($bk['harga_barang'], 0, ',', '.'); ?></td>
                            <td><?= $bk['jumlah_keluar'] . ' ' . $bk['nama_satuan']; ?></td>
                            <td><?= "Rp " . number_format($bk['harga_barang'] * $bk['jumlah_keluar'], 0, ',', '.') ?></td>
                            <?php if (is_admin()) : ?>
                                <td>
                                    <a href="<?= base_url('barangkeluar/edit/') . $bk['id_barang_keluar'] ?>" class="btn btn-warning btn-circle btn-sm"><i class="fa fa-pen"></i></a>
                                    <a onclick="return confirm('Yakin ingin hapus?')" href="<?= base_url('barangkeluar/delete/') . $bk['id_barang_keluar'] ?>" class="btn btn-danger btn-circle btn-sm"><i class="fa fa-trash"></i></a>
                                </td> <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="7" class="text-center">
                            Data Kosong
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>