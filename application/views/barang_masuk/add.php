<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-bottom-primary">
            <div class="card-header bg-white py-3">
                <div class="row">
                    <div class="col">
                        <h4 class="h5 align-middle m-0 font-weight-bold text-primary">
                            Form tambah barang masuk
                        </h4>
                    </div>
                    <div class="col-auto">
                        <a href="<?= base_url('barangmasuk') ?>" class="btn btn-sm btn-secondary btn-icon-split">
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
                <?= form_open('', [], ['id_barang_masuk' => $id_barang_masuk, 'user_id' => $this->session->userdata('login_session')['user']]); ?>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="id_barang_masuk">ID Transaksi Barang Masuk</label>
                    <div class="col-md-4">
                        <input value="<?= $id_barang_masuk; ?>" type="text" readonly="readonly" class="form-control">
                        <?= form_error('id_barang_masuk', '<small class="text-danger">', '</small>'); ?>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="tanggal_masuk">Tanggal Masuk</label>
                    <div class="col-md-4">
                        <input value="<?= set_value('tanggal_masuk', date('Y-m-d')); ?>" name="tanggal_masuk" id="tanggal_masuk" type="text" class="form-control date" placeholder="Tanggal Masuk...">
                        <?= form_error('tanggal_masuk', '<small class="text-danger">', '</small>'); ?>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="petugas_id">Petugas</label>
                    <div class="col-md-5">
                        <div class="input-group">
                            <?php
                            // Mengecek apakah fungsi is_petugas() mengembalikan true.
                            // Fungsi ini menentukan apakah pengguna saat ini adalah petugas.
                            if (is_petugas()) :
                                // Mengambil ID pengguna dari sesi login yang tersimpan di session.
                                $my_id = $this->session->userdata('login_session')['user'];

                                // Mengambil nama pengguna dari database berdasarkan ID yang didapatkan dari sesi.
                                $my_name = $this->admin->get('user', ['id_user' => $my_id])['nama'];
                            ?>
                                <!-- Menampilkan nama pengguna dalam input text yang tidak bisa diedit (readonly). -->
                                <input type="text" value="<?= $my_name; ?>" class="form-control" readonly="readonly">

                                <!-- Menyimpan ID petugas dalam input hidden untuk pengiriman form. -->
                                <input type="hidden" name="petugas_id" value="<?= $my_id; ?>">
                            <?php else : ?>
                                <!-- Jika pengguna bukan petugas, tampilkan dropdown untuk memilih petugas. -->
                                <select name="petugas_id" id="petugas_id" class="custom-select">
                                    <option value="" selected disabled>Pilih Petugas</option>
                                    <?php
                                    // Loop melalui daftar petugas dan buat opsi dropdown untuk masing-masing petugas.
                                    foreach ($petugas as $s) :
                                    ?>
                                        <!-- Setiap opsi memiliki nilai ID petugas dan menampilkan nama petugas. -->
                                        <option <?= set_select('petugas_id', $s['id_user']) ?> value="<?= $s['id_user'] ?>"><?= $s['nama'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            <?php endif; ?>
                        </div>
                        <!-- Menampilkan pesan kesalahan validasi form untuk field petugas_id. -->
                        <?= form_error('petugas_id', '<small class="text-danger">', '</small>'); ?>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="supplier_id">Supplier</label>
                    <div class="col-md-5">
                        <div class="input-group">
                            <select name="supplier_id" id="supplier_id" class="custom-select">
                                <option value="" selected disabled>Pilih Supplier</option>
                                <?php foreach ($supplier as $b) : ?>
                                    <option <?= set_select('supplier_id', $b['id_supplier']) ?> value="<?= $b['id_supplier'] ?>"><?= $b['nama_supplier'] ?></option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (is_admin()) : ?>
                                <div class="input-group-append">
                                    <a class="btn btn-primary" href="<?= base_url('supplier/add'); ?>"><i class="fa fa-plus"></i></a>
                                </div>
                            <?php endif; ?>
                        </div>
                        <?= form_error('supplier_id', '<small class="text-danger">', '</small>'); ?>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="barang_id">Barang</label>
                    <div class="col-md-5">
                        <div class="input-group">
                            <select name="barang_id" id="barang_id" class="custom-select">
                                <option value="" selected disabled>Pilih Barang</option>
                                <?php foreach ($barang as $b) : ?>
                                    <option <?= $this->uri->segment(3) == $b['id_barang'] ? 'selected' : '';  ?> <?= set_select('barang_id', $b['id_barang']) ?> value="<?= $b['id_barang'] ?>"><?= $b['id_barang'] . ' | ' . $b['nama_barang'] ?></option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (is_admin()) : ?>
                                <div class="input-group-append">
                                    <a class="btn btn-primary" href="<?= base_url('barang/add'); ?>"><i class="fa fa-plus"></i></a>
                                </div>
                            <?php endif; ?>
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
                    <label class="col-md-4 text-md-right" for="jumlah_masuk">Jumlah Masuk</label>
                    <div class="col-md-5">
                        <div class="input-group">
                            <input value="<?= set_value('jumlah_masuk'); ?>" name="jumlah_masuk" id="jumlah_masuk" type="number" class="form-control" placeholder="Jumlah Masuk...">
                            <div class="input-group-append">
                                <span class="input-group-text" id="satuan">Satuan</span>
                            </div>
                        </div>
                        <?= form_error('jumlah_masuk', '<small class="text-danger">', '</small>'); ?>
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