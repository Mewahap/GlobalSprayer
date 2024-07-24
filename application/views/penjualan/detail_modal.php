<h2>Detail Penjualan untuk <?= $customer['nama_customer']; ?></h2>

<table>
    <thead>
        <tr>
            <th>Tanggal Order</th>
            <th>Nama Barang</th>
            <th>Jumlah</th>
            <th>Total Harga</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($barang as $b) : ?>
            <tr>
                <td><?= $b['tanggal_keluar']; ?></td>
                <td><?= $b['nama_barang']; ?></td>
                <td><?= $b['jumlah_keluar']; ?></td>
                <td><?= "Rp " . number_format($b['jumlah_keluar'] * $b['harga_barang'], 0, ',', '.'); ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>