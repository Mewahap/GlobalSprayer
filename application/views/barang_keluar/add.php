<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-bottom-primary">
            <div class="card-header bg-white py-3">
                <div class="row">
                    <div class="col">
                        <h4 class="h5 align-middle m-0 font-weight-bold text-primary">
                            Form tambah barang keluar
                        </h4>
                    </div>
                    <div class="col-auto">
                        <a href="<?= base_url('barangkeluar') ?>" class="btn btn-sm btn-secondary btn-icon-split">
                            <span class="icon">
                                <i class="fa fa-arrow-left"></i>
                            </span>
                            <span class="text">
                                Back
                            </span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <?= $this->session->flashdata('pesan'); ?>
                <?= form_open('', [], ['id_barang_keluar' => $id_barang_keluar, 'user_id' => $this->session->userdata('login_session')['user']]); ?>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="id_barang_keluar">ID Transaksi Barang Keluar</label>
                    <div class="col-md-4">
                        <input value="<?= $id_barang_keluar; ?>" type="text" readonly="readonly" class="form-control">
                        <?= form_error('id_barang_keluar', '<small class="text-danger">', '</small>'); ?>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="customer">Customer</label>
                    <div class="col-md-5">
                        <select name="customer" id="customer" class="custom-select">
                            <option value="" selected disabled>Pilih Customer</option>
                            <?php foreach ($customer as $b) : ?>
                                <option value="<?= $b['id_customer'] ?>"><?= $b['nama'] ?></option>
                            <?php endforeach; ?>
                        </select>
                        <?= form_error('customer', '<small class="text-danger">', '</small>'); ?>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="tanggal_keluar">Tanggal Keluar</label>
                    <div class="col-md-4">
                        <input value="<?= set_value('tanggal_keluar', date('Y-m-d')); ?>" name="tanggal_keluar" id="tanggal_keluar" type="text" class="form-control date" placeholder="Tanggal Masuk...">
                        <?= form_error('tanggal_keluar', '<small class="text-danger">', '</small>'); ?>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="totalBarang">Total Barang</label>
                    <div class="col-md-5">
                        <input type="number" id="totalBarang" class="totalBarang form-control" value="1">
                    </div>
                </div>
                <div id="barangKeluar">
                    <div class="row form-group">
                        <label class="col-md-4 text-md-right" for="barang_id">Barang</label>
                        <div class="col-md-5">
                            <div class="input-group">
                                <select name="barang_id[]" id="barang_id" class="barang_id custom-select">
                                    <option value="" selected disabled>Pilih Barang</option>
                                    <?php foreach ($barang as $b) : ?>
                                        <option value="<?= $b['id_barang'] ?>"><?= $b['id_barang'] . ' | ' . $b['nama_barang'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="input-group-append">
                                    <a class="btn btn-primary" href="<?= base_url('barang/add'); ?>"><i class="fa fa-plus"></i></a>
                                </div>
                            </div>
                            <?= form_error('barang_id', '<small class="text-danger">', '</small>'); ?>
                        </div>
                    </div>
                    <div class="row form-group">
                        <label class="col-md-4 text-md-right" for="stok">Stok</label>
                        <div class="col-md-5">
                            <input readonly="readonly" id="stok" type="number" class="stok form-control">
                        </div>
                    </div>
                    <div class="row form-group">
                        <label class="col-md-4 text-md-right" for="jumlah_keluar">Jumlah Keluar</label>
                        <div class="col-md-5">
                            <div class="input-group">
                                <input value="<?= set_value('jumlah_keluar'); ?>" name="jumlah_keluar[]" id="jumlah_keluar" type="number" class="jumlah_keluar form-control" placeholder="Jumlah Keluar...">
                                <div class="input-group-append">
                                    <span class="input-group-text" id="satuan">Pcs</span>
                                </div>
                            </div>
                            <?= form_error('jumlah_keluar', '<small class="text-danger">', '</small>'); ?>
                        </div>
                    </div>
                    <div class="row form-group">
                        <label class="col-md-4 text-md-right" for="total_stok">Total Stok</label>
                        <div class="col-md-5">
                            <input readonly="readonly" id="total_stok" type="number" class="total_stok form-control">
                        </div>
                    </div>
                </div>
                <!-- <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="lokasi">Lokasi</label>
                    <div class="col-md-5">
                        <input value="<?= set_value('lokasi'); ?>" name="lokasi" id="lokasi" type="text" class="form-control" placeholder="Lokasi Pemgiriman...">
                        <?= form_error('lokasi', '<small class="text-danger">', '</small>'); ?>
                    </div>
                </div> -->
                <input type="hidden" name="lokasi" value="Gudang">
                <div class="row form-group">
                    <div class="col offset-md-4">
                        <button type="submit" class="btn btn-primary">Save</button>
                        <button type="reset" class="btn btn-secondary">Reset</button>
                    </div>
                </div>
                <?= form_close(); ?>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#totalBarang').on('keyup change', function() {
            var totalBarang = $(this).val();
            var html = '';
            for (let i = 0; i < totalBarang; i++) {
                html += `
                    <div class="row form-group">
                        <label class="col-md-4 text-md-right" for="barang_id">Barang</label>
                        <div class="col-md-5">
                            <div class="input-group">
                                <select name="barang_id[]" id="barang_id" class="barang_id custom-select">
                                    <option value="" selected disabled>Pilih Barang</option>
                                    <?php foreach ($barang as $b) : ?>
                                        <option value="<?= $b['id_barang'] ?>"><?= $b['id_barang'] . ' | ' . $b['nama_barang'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="input-group-append">
                                    <a class="btn btn-primary" href="<?= base_url('barang/add'); ?>"><i class="fa fa-plus"></i></a>
                                </div>
                            </div>
                            <?= form_error('barang_id', '<small class="text-danger">', '</small>'); ?>
                        </div>
                    </div>
                    <div class="row form-group">
                        <label class="col-md-4 text-md-right" for="stok">Stok</label>
                        <div class="col-md-5">
                            <input readonly="readonly" id="stok" type="number" class="stok form-control">
                        </div>
                    </div>
                    <div class="row form-group">
                        <label class="col-md-4 text-md-right" for="jumlah_keluar">Jumlah Keluar</label>
                        <div class="col-md-5">
                            <div class="input-group">
                                <input value="<?= set_value('jumlah_keluar'); ?>" name="jumlah_keluar[]" id="jumlah_keluar" type="number" class="jumlah_keluar form-control" placeholder="Jumlah Keluar...">
                                <div class="input-group-append">
                                    <span class="input-group-text" id="satuan">PCS</span>
                                </div>
                            </div>
                            <?= form_error('jumlah_keluar', '<small class="text-danger">', '</small>'); ?>
                        </div>
                    </div>
                    <div class="row form-group">
                        <label class="col-md-4 text-md-right" for="total_stok">Total Stok</label>
                        <div class="col-md-5">
                            <input readonly="readonly" id="total_stok" type="number" class="total_stok form-control">
                        </div>
                    </div>
                `;
            }
            $('#barangKeluar').html(html);

            // let satuan = $('.satuan');
            // let stok = $('.stok');
            // let total = $('.total_stok');
            // let jumlah = $('.jumlah_keluar');

            $(document).on('change', '.barang_id', function() {
                let url = '<?= base_url('barang/getstok/'); ?>' + this.value;
                let stok = $(this).closest('.row').next().find('.stok');
                let satuan = $(this).closest('.row').next().next().find('.satuan');
                let total = $(this).closest('.row').next().next().next().find('.total_stok');
                let jumlah = $(this).closest('.row').next().next().next().next().find('.jumlah_keluar');
                $.getJSON(url, function(data) {
                    satuan.html(data.nama_satuan);
                    stok.val(data.stok);
                    total.val(data.stok);
                    jumlah.focus();
                });
            });

            $(document).on('keyup', '.jumlah_masuk', function() {
                let total = $(this).closest('.row').next().find('.total_stok');
                let totalStok = parseInt(stok.val()) + parseInt(this.value);
                total.val(Number(totalStok));
            });

            $(document).on('keyup', '.jumlah_keluar', function() {
                let total = $(this).closest('.row').next().find('.total_stok');
                let totalStok = parseInt(stok.val()) - parseInt(this.value);
                total.val(Number(totalStok));
            });
        });
    });
</script>