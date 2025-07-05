<?= $this->extend('Template/index'); ?>
<?= $this->section('konten'); ?>
<?php 
ini_set('memory_limit', '256M');
?>
<div class="row">
    <div class="col-lg-12 mb-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <!-- kiri kanan -->
                <div class="row">
                    <div class="col-md-4">
                        <h6 class="m-0 font-weight-bold text-primary float-left">Data Barang</h6>
                    </div>
                    <div class="col-md-8">
                        <a class="btn btn-primary btn-sm float-right" href="#" data-toggle="modal" data-target="#add"><i
                                class="fas fa-plus"></i> Tambah</a>
                        <!-- import -->
                        <a class="btn btn-success btn-sm float-right mx-2" href="#" data-toggle="modal"
                            data-target="#import"><i class="fas fa-file-import"></i> Import</a>
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
                                <th>Kode Barang</th>
                                <th>Nama Barang</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1;
                            foreach ($barang as $key => $value) { ?>
                            <tr>
                                <td class="text-center" width="50px"><?= $no++; ?></td>
                                <td><?= $value['id_barang']; ?></td>
                                <td><?= $value['nama_barang']; ?></td>
                                <td class="text-center">
                                    <a haref="#" class="btn btn-warning btn-sm" data-toggle="modal"
                                        data-target="#edit<?= $value['id_barang']; ?>"> <i class="fas fa-edit"></i>
                                        Edit</a>
                                    <a class="btn btn-danger btn-sm" data-toggle="modal"
                                        data-target="#hapus<?= $value['id_barang']; ?>"> <i class="fas fa-trash"></i>
                                        Hapus</a>
                                </td>
                            </tr>
                            <!-- modal hapus -->
                            <div class="modal fade" id="hapus<?= $value['id_barang']; ?>" tabindex="-1" role="dialog"
                                aria-labelledby="editModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <form action="<?= base_url('Barang/hapus'); ?>" method="post">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editModalLabel">Hapus Data Barang</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body p-4">
                                                <input type="hidden" name="id_barang"
                                                    value="<?= $value['id_barang']; ?>">
                                                <p>Apakah Anda yakin ingin menghapus data barang
                                                    <strong><?= $value['nama_barang']; ?></strong> ?
                                                </p>
                                            </div>

                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">Tutup</button>
                                                <button type="submit" class="btn btn-danger">Hapus</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- modal import -->
<div class="modal fade" id="import" tabindex="-1" role="dialog" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form enctype="multipart/form-data" id="form-import">
                <!-- <form enctype="multipart/form-data" id="form-import" method="post"
                action="<?= base_url('Barang/import'); ?>"> -->
                <div class="modal-header">
                    <h5 class="modal-title" id="importModalLabel">Import Data Barang</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-4">
                    <div class="form-group mt-2">
                        <label for="file">File Excel <span class="text-danger">*</span></label>
                        <input type="file" name="file" id="file" class="form-control" required accept=".xls, .xlsx">
                        <!-- download template -->
                        <a href="<?= base_url('Assets//Template/TEMPLATE_IMPORT_BARANG.xlsx'); ?>"
                            class="btn btn-primary btn-sm mt-2" target="_blank"><i class="fas fa-download"></i> Download
                            Template</a>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary" id="btn-import">Import</button>

                </div>
            </form>
        </div>
    </div>
</div>
<!-- modal hasil import -->
<div class="modal fade" id="hasil-import" role="dialog" aria-labelledby="hasilImportModalLabel" aria-hidden="true"
    data-backdrop="false" keyboard="false">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="hasilImportModalLabel">Hasil Import Data Barang</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4">
                <h5 class="text-center mb-4 text-black">List data yang gagal import</h5>

                <div class="table-responsive hasil_import_barang">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead class="thead-light">
                            <tr class="text-center">
                                <th>No</th>
                                <th>Nama Barang</th>
                                <th>Error</th>
                            </tr>
                        </thead>
                        <tbody id="hasil_import_barang">
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <span>Total Data : <span id="totalData"></span></span>
                                        </div>
                                        <div>
                                            <span>Total Sukses : <span id="totalSukses"></span></span>
                                        </div>
                                        <div>
                                            <span>Total Error : <span id="totalError"></span></span>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                    <style>
                    .hasil_import_barang {
                        overflow-y: auto;
                        height: 300px;
                    }

                    .hasil_import_barang thead,
                    .hasil_import_barang tfoot {
                        position: sticky;
                    }

                    .hasil_import_barang thead {
                        top: 0;
                        background-color: #f8f9fc;
                    }

                    .hasil_import_barang tfoot {
                        bottom: 0;
                        background-color: #f8f9fc;
                    }

                    .hasil_import_barang tbody {
                        overflow-y: auto;
                        height: 300px;
                    }

                    .hasil_import_barang tbody::-webkit-scrollbar {
                        width: 6px;
                    }

                    .hasil_import_barang tbody::-webkit-scrollbar-thumb {
                        background-color: #007bff;
                        border-radius: 10px;
                    }

                    .hasil_import_barang tbody::-webkit-scrollbar-track {
                        background-color: #f8f9fc;
                    }

                    .hasil_import_barang tbody::-webkit-scrollbar-thumb:hover {
                        background-color: #0056b3;
                    }

                    .hasil_import_barang tbody::-webkit-scrollbar-thumb:active {
                        background-color: #003d7f;
                    }

                    .hasil_import_barang thead {
                        overflow: hidden;
                    }

                    .hasil_import_barang tbody {
                        overflow: auto;
                    }

                    .hasil_import_barang tfoot {
                        overflow: hidden;
                    }

                    .hasil_import_barang thead th {
                        position: sticky;
                        top: 0;
                        z-index: 1;
                    }


                    .hasil_import_barang tfoot th {
                        position: sticky;
                        bottom: 0;
                        z-index: 1;
                    }

                    .hasil_import_barang thead th {
                        background-color: #f8f9fc;
                    }

                    .hasil_import_barang tfoot th {
                        background-color: #f8f9fc;
                    }

                    .hasil_import_barang tbody::-webkit-scrollbar {
                        width: 6px;
                    }

                    .hasil_import_barang tbody::-webkit-scrollbar-thumb {
                        background-color: #007bff;
                        border-radius: 10px;
                    }

                    .hasil_import_barang tbody::-webkit-scrollbar-track {
                        background-color: #f8f9fc;
                    }

                    .hasil_import_barang tbody::-webkit-scrollbar-thumb:hover {
                        background-color: #0056b3;
                    }

                    .hasil_import_barang tbody::-webkit-scrollbar-thumb:active {
                        background-color: #003d7f;
                    }
                    </style>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal add -->
<div class="modal fade" id="add" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="<?= base_url('Barang/simpan'); ?>" method="post">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">Tambah Data Barang</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-4">
                    <div class="form-group mt-2">
                        <label for="nama_barang">Nama Barang <span class="text-danger">*</span></label>
                        <input type="text" name="nama_barang" id="nama_barang" class="form-control" required
                            placeholder="Masukkan Nama Barang">
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
<?php foreach ($barang as $key => $value) { ?>
<div class="modal fade" id="edit<?= $value['id_barang']; ?>" tabindex="-1" role="dialog"
    aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="<?= base_url('Barang/ubah'); ?>" method="post">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Data Barang</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-4">
                    <input type="hidden" name="id_barang" value="<?= $value['id_barang']; ?>">

                    <div class="form-group mt-2">
                        <label for="nama_barang">Nama Barang <span class="text-danger">*</span></label>
                        <input type="text" name="nama_barang" id="nama_barang" class="form-control" required
                            value="<?= $value['nama_barang']; ?>">
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
$(document).ready(function() {
    // import
    $('#form-import').submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            url: '<?= base_url('Barang/import'); ?>',
            type: 'POST',
            data: formData,
            dataType: 'json',
            contentType: false,
            processData: false,
            beforeSend: function() {
                $('#btn-import').attr('disabled', 'disabled');
                $('#btn-import').html(
                    '<i class="fas fa-spinner fa-spin"></i> Loading...');
            },
            success: function(response) {
                // hide modal import
                $('#import').modal('hide');

                if (response.error) {
                    alert(response.data);
                } else {
                    $('#hasil-import').modal('show');
                    var html = '';
                    if (response.data.errors.length > 0) {

                        $.each(response.data.errors, function(key, value) {
                            html += '<tr>';
                            html += '<td class="text-center">' + value.no +
                                '</td>';
                            html += '<td>' + value.nama_barang + '</td>';
                            html += '<td>' + value.error + '</td>';
                            html += '</tr>';
                        });

                    } else {
                        html += '<tr>';
                        html +=
                            '<td colspan="3" class="text-center">Tidak ada data yang gagal diimport</td>';
                        html += '</tr>';
                    }

                    $('#hasil_import_barang').html(html);

                    // total data
                    $('#totalData').html(response.data.total_data);
                    $('#totalSukses').html(response.data.success);
                    $('#totalError').html(response.data.failed);

                }
                $('#btn-import').removeAttr('disabled');
                $('#btn-import').html('Import');
            },
            error: function() {
                alert('Error');
                $('#btn-import').removeAttr('disabled');
                $('#btn-import').html('Import');
            }
        });
    });

    // close modal hasil import
    $('#hasil-import').on('hidden.bs.modal', function() {
        location.reload();
    });
});
// set time out alert
setTimeout(function() {
    $('.alert').fadeOut('slow');
}, 3000);
</script>
<?= $this->endSection('script'); ?>