<?= $this->session->flashdata('pesan'); ?>
<div class="card shadow-sm border-bottom-primary">
    <div class="card-header bg-white py-3">
        <div class="row">
            <div class="col">
                <h4 class="h5 align-middle m-0 font-weight-bold text-primary">
                    Detail Penjualan - <?= isset($customer['nama_customer']) ? $customer['nama_customer'] : 'Nama Customer Tidak Tersedia'; ?> - <?= $tanggal ?>
                </h4>
            </div>

        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-striped w-100 dt-responsive " id="dataTable">
            <thead>
                <tr>
                    <th>No. </th>
                    <th>Nama Barang</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                if ($barang) :
                    foreach ($barang as $bk) :
                ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= $bk['nama_barang']; ?></td>
                            <td><?= "Rp " . number_format($bk['harga_barang'], 0, ',', '.') ?></td>
                            <td><?= $bk['jumlah_keluar']; ?></td>
                            <td><?= "Rp " . number_format($bk['harga_barang'] * $bk['jumlah_keluar'], 0, ',', '.') ?></td>
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