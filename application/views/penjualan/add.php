<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-bottom-primary">
            <div class="card-header bg-white py-3">
                <div class="row">
                    <div class="col">
                        <h4 class="h5 align-middle m-0 font-weight-bold text-primary">
                            Penjualan
                        </h4>
                    </div>
                    <div class="col-auto">
                        <a href="<?= base_url('penjualan') ?>" class="btn btn-sm btn-secondary btn-icon-split">
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
                <?= form_open('', [], ['id_penjualan' => $id_penjualan, 'user_id' => $this->session->userdata('login_session')['user']]); ?>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="id_penjualan">ID Penjualan</label>
                    <div class="col-md-4">
                        <input value="<?= $id_penjualan; ?>" type="text" readonly="readonly" class="form-control">
                        <?= form_error('id_penjualan', '<small class="text-danger">', '</small>'); ?>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="petugas">Petugas</label>
                    <div class="col-md-5">
                        <select name="petugas" id="petugas" class="custom-select">
                            <option value="" selected disabled>Pilih Petugas</option>
                            <?php foreach ($petugas as $b) : ?>
                                <option value="<?= $b['id'] ?>"><?= $b['nama'] ?></option>
                            <?php endforeach; ?>
                        </select>
                        <?= form_error('petugas', '<small class="text-danger">', '</small>'); ?>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="tanggal_penjualan">Tanggal Penjualan</label>
                    <div class="col-md-4">
                        <input value="<?= set_value('tanggal_penjualan', date('Y-m-d')); ?>" name="tanggal_penjualan" id="tanggal_penjualan" type="text" class="form-control date" placeholder="Tanggal Masuk...">
                        <?= form_error('tanggal_penjualan', '<small class="text-danger">', '</small>'); ?>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="barang_id">Barang</label>
                    <div class="col-md-5">
                        <div class="input-group">
                            <select name="barang_id" id="barang_id" class="custom-select">
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
                        <input readonly="readonly" id="stok" type="number" class="form-control">
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="jumlah_penjualan">Jumlah Penjualan</label>
                    <div class="col-md-5">
                        <div class="input-group">
                            <input value="<?= set_value('jumlah_penjualan'); ?>" name="jumlah_penjualan" id="jumlah_penjualan" type="number" class="form-control" placeholder="Jumlah penjualan...">
                            <div class="input-group-append">
                                <span class="input-group-text" id="satuan">Satuan</span>
                            </div>
                        </div>
                        <?= form_error('jumlah_penjualan', '<small class="text-danger">', '</small>'); ?>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="total_stok">Total Stok</label>
                    <div class="col-md-5">
                        <input readonly="readonly" id="total_stok" type="number" class="form-control">
                    </div>
                </div>
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