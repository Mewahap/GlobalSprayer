<?= $this->session->flashdata('pesan'); ?>
<div class="card shadow-sm border-bottom-primary">
    <div class="card-header bg-white py-3">
        <div class="row">
            <div class="col">
                <h4 class="h5 align-middle m-0 font-weight-bold text-primary">
                    Data Penjualan
                </h4>
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-striped w-100 dt-responsive " id="dataTable">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Periode</th>
                    <th>Nama Barang</th>
                    <th>Qty</th>
                    <th>Harga Jual</th>
                    <th>Total Penjualan</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                if ($penjualan) :
                    foreach ($penjualan as $bk) :

                ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= $bk['tanggal_penjualan']; ?></td>
                            <td><?= $bk['nama_barang']; ?></td>
                            <td><?= $bk['jumlah_keluar'] . ' ' . $bk['nama_satuan']; ?></td>
                            <td><?= "Rp " . number_format(($bk['harga_barang']), 0, ',', '.'); ?></td>
                            <td><?= "Rp " . number_format(($bk['jumlah_keluar'] * $bk['harga_barang']), 0, ',', '.'); ?></td>

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


        <!-- Modal for Detail -->
        <div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="detailModalLabel">Detail Penjualan</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="modal-detail-content"></div>
                    </div>
                    <div class="modal-footer">
                        <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
                        <button type="button" class="btn btn-primary" id="printPdfBtn">Print PDF</button>
                    </div>
                </div>
            </div>
        </div>

        <script>
            $('#detailModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var customer = button.data('customer');
                var tanggal = button.data('tanggal');

                $.ajax({
                    url: '<?= base_url('penjualan/detail') ?>/' + encodeURIComponent(customer) + '/' + encodeURIComponent(tanggal),
                    type: 'GET',
                    success: function(response) {
                        $('#modal-detail-content').html(response);
                    }
                });
            });

            $('#printPdfBtn').on('click', function() {
                var customer = $('#detailModal').data('customer');
                var tanggal = $('#detailModal').data('tanggal');
                window.location.href = '<?= base_url('penjualan/print_detail_customer') ?>/' + encodeURIComponent(customer) + '/' + encodeURIComponent(tanggal);
            });
        </script>
    </div>
</div>