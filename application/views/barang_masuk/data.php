<?= $this->session->flashdata('pesan'); ?>
<div class="card shadow-sm border-bottom-primary">
    <div class="card-header bg-white py-3">
        <div class="row">
            <div class="col">
                <h4 class="h5 align-middle m-0 font-weight-bold text-primary">
                    Data Barang Masuk
                </h4>
            </div>
            <div class="col-auto">
                <a href="<?= base_url('barangmasuk/add') ?>" class="btn btn-sm btn-primary btn-icon-split">
                    <span class="icon">
                        <i class="fa fa-plus"></i>
                    </span>
                    <span class="text">
                        Tambah Barang Masuk
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
                    <th>Tanggal Masuk</th>
                    <th>Nama Petugas</th>
                    <th>Nama Supplier</th>
                    <th>Nama Barang</th>
                    <th>Harga Masuk</th>
                    <th>Jumlah Masuk</th>
                    <th>Total Pembelian</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                $total = 0;
                if ($barangmasuk) :
                    foreach ($barangmasuk as $bm) :
                        $total += $bm['jumlah_masuk'];

                        $totalPembelian = 0;
                        $totalPembelian = ($bm['jumlah_masuk'] * $bm['harga_masuk']);
                ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= $bm['id_barang_masuk']; ?></td>
                            <td><?= $bm['tanggal_masuk']; ?></td>
                            <td><?= $bm['nama_petugas']; ?></td>
                            <td><?= $bm['nama_supplier']; ?></td>
                            <td><?= $bm['nama_barang']; ?></td>
                            <td><?= "Rp " . number_format($bm['harga_masuk'], 0, ',', '.'); ?></td>
                            <td><?= $bm['jumlah_masuk'] . ' ' . $bm['nama_satuan']; ?></td>
                            <td><?= "Rp " . number_format($totalPembelian, 0, ',', '.'); ?></td>
                            <td>
                                <a href="<?= base_url('barangmasuk/edit/') . $bm['id_barang_masuk'] ?>" class="btn btn-warning btn-circle btn-sm"><i class="fa fa-pen"></i></a>
                                <?php if (is_admin()) : ?>
                                    <a onclick="return confirm('Yakin ingin hapus?')" href="<?= base_url('barangmasuk/delete/') . $bm['id_barang_masuk'] ?>" class="btn btn-danger btn-circle btn-sm"><i class="fa fa-trash"></i></a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="8" class="text-center">
                            Data Kosong
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>