<?php error_reporting(0); ?>
<?= $this->session->flashdata('pesan'); ?>
<div class="card shadow-sm border-bottom-primary">
    <div class="card-header bg-white py-3">
        <div class="row">
            <div class="col">
                <h4 class="h5 align-middle m-0 font-weight-bold text-primary">
                    Informasi Stok Data Barang
                </h4>
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-striped w-100 dt-responsive" id="dataTable1">
            <thead>
                <tr>
                    <th>No. </th>
                    <th>ID Barang</th>
                    <th>Nama Barang</th>
                    <th>Stok</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                $stok_kosong = array_filter($barang, function ($b) {
                    return $b['stok'] == 0;
                });

                if ($stok_kosong) :
                    foreach ($stok_kosong as $b) :
                ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= $b['id_barang']; ?></td>
                            <td><?= $b['nama_barang']; ?></td>
                            <td><?= $b['stok']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="4" class="text-center">
                            Tidak ada barang dengan stok kosong
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="card shadow-sm border-bottom-primary" style="margin-top: 30px;">
    <div class="card-header bg-white py-3">
        <div class="row">
            <div class="col">
                <h4 class="h5 align-middle m-0 font-weight-bold text-primary">
                    Data Barang
                </h4>
            </div>
            <div class="col-auto">
                <?php if (is_admin()) : ?>
                    <a href="<?= base_url('barang/add') ?>" class="btn btn-sm btn-primary btn-icon-split">
                        <span class="icon">
                            <i class="fa fa-plus"></i>
                        </span>
                        <span class="text">
                            Tambah Barang
                        </span>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-striped w-100 dt-responsive" id="dataTable2">
            <thead>
                <tr>
                    <th>No. </th>
                    <th>ID Barang</th>
                    <th>Gambar</th>
                    <th>Nama Barang</th>
                    <th>Stok</th>
                    <th>Satuan</th>
                    <th>Kategori</th>
                    <th>Harga Masuk</th>
                    <th>Harga Jual</th>
                    <?php if (is_admin()) : ?>
                        <th>Aksi</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                if ($barang) :
                    foreach ($barang as $b) :
                        $total = $b['harga_barang'] * $b['stok'];
                ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= $b['id_barang']; ?></td>
                            <td><?php if ($b['image'] != null) { ?>
                                    <img src="assets/upload/<?= $b['image']; ?>" width="80px" />
                                <?php } ?>
                            </td>
                            <td><?= $b['nama_barang']; ?></td>
                            <td><?= $b['stok']; ?></td>
                            <td><?= $b['nama_satuan']; ?></td>
                            <td><?= $b['nama_jenis']; ?></td>
                            <td><?= "Rp " . number_format($b['harga_masuk'], 0, ',', '.'); ?></td>
                            <td><?= "Rp " . number_format($b['harga_barang'], 0, ',', '.'); ?></td>
                            <?php if (is_admin()) : ?>
                                <td>
                                    <a href="<?= base_url('barang/edit/') . $b['id_barang'] ?>" class="btn btn-warning btn-circle btn-sm"><i class="fa fa-edit"></i></a>
                                    <a onclick="return confirm('Yakin ingin hapus?')" href="<?= base_url('barang/delete/') . $b['id_barang'] ?>" class="btn btn-danger btn-circle btn-sm"><i class="fa fa-trash"></i></a>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="10" class="text-center">
                            Data Kosong
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>