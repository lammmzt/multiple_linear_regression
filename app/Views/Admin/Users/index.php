<?= $this->extend('Template/index'); ?>
<?= $this->section('konten'); ?>
<div class="row">
    <div class="col-lg-12 mb-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <!-- kiri kanan -->
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="m-0 font-weight-bold text-primary">Data User</h6>
                    </div>
                    <div class="col-md-6">
                        <a class="btn btn-primary btn-sm float-right" href="#" data-toggle="modal" data-target="#add"><i
                                class="fas fa-plus"></i> Tambah Data</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <?php if (session()->getFlashdata('success')) : ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Selamat!</strong> <?= session()->getFlashdata('success'); ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <?php endif; ?>
                <?php if (session()->getFlashdata('error')) : ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Maaf!</strong> <?= session()->getFlashdata('error'); ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <?php endif; ?>
                <div class="table-responsive">
                    <table class="table table-bordered dataTable" width="100%" cellspacing="0">
                        <thead class="thead-light">
                            <tr class="text-center">
                                <th>No</th>
                                <th>Username</th>
                                <th>Nama User</th>
                                <th>Role</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1;
                            foreach ($users as $key => $value) { ?>
                            <tr>
                                <td class="text-center" width="50px"><?= $no++; ?></td>
                                <td><?= $value['username']; ?></td>
                                <td><?= $value['nama_user']; ?></td>
                                <td class="text-center"><?= $value['role']; ?></td>
                                <td class="text-center">
                                    <a haref="#" class="btn btn-warning btn-sm" data-toggle="modal"
                                        data-target="#edit<?= $value['id_user']; ?>"> <i class="fas fa-edit"></i>
                                        Edit</a>
                                    <a href="<?= base_url('Users/hapus/' . $value['id_user']); ?>"
                                        class="btn btn-danger btn-sm"
                                        onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')"> <i
                                            class="fas fa-trash"></i> Hapus</a>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal add -->
<div class="modal fade" id="add" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="<?= base_url('Users/simpan'); ?>" method="post">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">Tambah Data User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-4">
                    <div class="form-group">
                        <label for="username">Username <span class="text-danger">*</span></label>
                        <input type="text" name="username" id="username" class="form-control" required
                            placeholder="Masukkan Username">
                    </div>
                    <div class="form-group mt-2">
                        <label for="password">Password <span class="text-danger">*</span></label>
                        <input type="password" name="password" id="password" class="form-control" required
                            placeholder="Masukkan Password">
                    </div>
                    <div class="form-group mt-2">
                        <label for="nama_user">Nama User <span class="text-danger">*</span></label>
                        <input type="text" name="nama_user" id="nama_user" class="form-control" required
                            placeholder="Masukkan Nama User">
                    </div>
                    <div class="form-group mt-2">
                        <label for="role">Role <span class="text-danger">*</span></label>
                        <select name="role" id="role" class="form-control" required placeholder="Masukkan Role">
                            <option value="">Pilih Role</option>
                            <option value="Admin">Admin</option>
                            <option value="User">User</option>
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- edit -->
<?php foreach ($users as $key => $value) { ?>
<div class="modal fade" id="edit<?= $value['id_user']; ?>" tabindex="-1" role="dialog" aria-labelledby="editModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="<?= base_url('Users/ubah'); ?>" method="post">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Data User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-4">
                    <input type="hidden" name="id_user" value="<?= $value['id_user']; ?>">
                    <div class="form-group mt-2">
                        <label for="username">Username <span class="text-danger">*</span></label>
                        <input type="text" name="username" id="username" class="form-control" required
                            value="<?= $value['username']; ?>">
                    </div>
                    <input type="hidden" name="password_lama" value="<?= $value['password']; ?>">
                    <div class="form-group mt-2">
                        <label for="password">Password</label>
                        <input type="text" name="password" id="password" class="form-control" value=""
                            placeholder="Masukkan Password Jika Ingin Diubah">
                    </div>
                    <div class="form-group mt-2">
                        <label for="nama_user">Nama User <span class="text-danger">*</span></label>
                        <input type="text" name="nama_user" id="nama_user" class="form-control" required
                            value="<?= $value['nama_user']; ?>">
                    </div>

                    <div class="form-group mt-2">
                        <label for="role">Role</label>
                        <select name="role" id="role" class="form-control" required>
                            <option value="">Pilih Role <span class="text-danger">*</span></option>
                            <option value="Admin" <?= ($value['role'] == 'Admin') ? 'selected' : ''; ?>>Admin</option>
                            <option value="User" <?= ($value['role'] == 'User') ? 'selected' : ''; ?>>User</option>
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Ubah</button>
                </div>
            </form>

        </div>
    </div>
</div>
<?php } ?>
<?= $this->endSection('kontent'); ?>
<?= $this->section('script'); ?>
<script type="text/javascript">
// set time out alert
setTimeout(function() {
    $('.alert').fadeOut('slow');
}, 3000);
</script>
<?= $this->endSection('script'); ?>