<?= $this->extend('Template/index'); ?>
<?= $this->section('konten'); ?>
<div class="row">
    <div class="col-lg-12 mb-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <!-- kiri kanan -->
                <div class="row">
                    <div class="col-md-4">
                        <h6 class="m-0 font-weight-bold text-primary float-left">Data Penjualan</h6>
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
                <!-- <div class="row">
                    <form action="<?= base_url('Penjualan'); ?>" method="post">
                        <div class="form-group mx-2">
                            <label for="nama_barang">Nama Barang</label>
                            <select name="id_barang" id="filter_id_barang" class="form-control" style="width: 100%;"
                                required>
                                <option value="">Pilih</option>
                                <?php foreach ($Barang as $key => $value) { ?>
                                <option value="<?= $value['id_barang']; ?>"><?= $value['nama_barang']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </form>
                </div> -->
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0" id="table_data_penjualan">
                        <thead class="thead-light">
                            <tr class="text-center">
                                <th>No</th>
                                <th>Tgl Penjualan</th>
                                <th>Nama Barang</th>
                                <th>Qty Barang</th>
                                <th>Harga Jual</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>

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
            <!-- <form enctype="multipart/form-data" id="form-import"> -->
            <form enctype="multipart/form-data" id="form-import" method="post"
                action="<?= base_url('Penjualan/import'); ?>">
                <div class="modal-header">
                    <h5 class="modal-title" id="importModalLabel">Import Data Penjualan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-4">
                    <div class="form-group mt-2">
                        <label for="file">File Excel <span class="text-danger">*</span></label>
                        <input type="file" name="file" id="file" class="form-control" required accept=".xls, .xlsx">
                        <!-- download template -->
                        <a href="<?= base_url('Assets/Template/FORMAT_IMPORT_PENJUALAN.xlsx'); ?>"
                            class="btn btn-primary btn-sm mt-2" target="_blank"><i class="fas fa-download"></i>
                            Download
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
                <h5 class="modal-title" id="hasilImportModalLabel">Hasil Import Data Penjualan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4">

                <div class="table-responsive hasil_import_Penjualan">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead class="thead-light">
                            <tr class="text-center">
                                <th>No</th>
                                <th>Data</th>
                                <th>Status</th>
                                <th>Ket</th>
                            </tr>
                        </thead>
                        <tbody id="hasil_import_Penjualan">
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4">
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
                    .hasil_import_Penjualan {
                        overflow-y: auto;
                        height: 300px;
                    }

                    .hasil_import_Penjualan thead,
                    .hasil_import_Penjualan tfoot {
                        position: sticky;
                    }

                    .hasil_import_Penjualan thead {
                        top: 0;
                        background-color: #f8f9fc;
                    }

                    .hasil_import_Penjualan tfoot {
                        bottom: 0;
                        background-color: #f8f9fc;
                    }

                    .hasil_import_Penjualan tbody {
                        overflow-y: auto;
                        height: 300px;
                    }

                    .hasil_import_Penjualan tbody::-webkit-scrollbar {
                        width: 6px;
                    }

                    .hasil_import_Penjualan tbody::-webkit-scrollbar-thumb {
                        background-color: #007bff;
                        border-radius: 10px;
                    }

                    .hasil_import_Penjualan tbody::-webkit-scrollbar-track {
                        background-color: #f8f9fc;
                    }

                    .hasil_import_Penjualan tbody::-webkit-scrollbar-thumb:hover {
                        background-color: #0056b3;
                    }

                    .hasil_import_Penjualan tbody::-webkit-scrollbar-thumb:active {
                        background-color: #003d7f;
                    }

                    .hasil_import_Penjualan thead {
                        overflow: hidden;
                    }

                    .hasil_import_Penjualan tbody {
                        overflow: auto;
                    }

                    .hasil_import_Penjualan tfoot {
                        overflow: hidden;
                    }

                    .hasil_import_Penjualan thead th {
                        position: sticky;
                        top: 0;
                        z-index: 1;
                    }


                    .hasil_import_Penjualan tfoot th {
                        position: sticky;
                        bottom: 0;
                        z-index: 1;
                    }

                    .hasil_import_Penjualan thead th {
                        background-color: #f8f9fc;
                    }

                    .hasil_import_Penjualan tfoot th {
                        background-color: #f8f9fc;
                    }

                    .hasil_import_Penjualan tbody::-webkit-scrollbar {
                        width: 6px;
                    }

                    .hasil_import_Penjualan tbody::-webkit-scrollbar-thumb {
                        background-color: #007bff;
                        border-radius: 10px;
                    }

                    .hasil_import_Penjualan tbody::-webkit-scrollbar-track {
                        background-color: #f8f9fc;
                    }

                    .hasil_import_Penjualan tbody::-webkit-scrollbar-thumb:hover {
                        background-color: #0056b3;
                    }

                    .hasil_import_Penjualan tbody::-webkit-scrollbar-thumb:active {
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
<div class="modal fade" id="add" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="<?= base_url('Penjualan/simpan'); ?>" method="post">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">Tambah Data Penjualan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-4">
                    <div class="form-group mt-2">
                        <label for="tgl_penjualan">Tgl Penjualan <span class="text-danger">*</span></label>
                        <input type="date" name="tgl_penjualan" id="tgl_penjualan" class="form-control" required>
                    </div>
                    <div class="form-group mt-2">
                        <label for="id_barang">Nama Barang <span class="text-danger">*</span></label>
                        <select name="id_barang" id="id_barang" class="form-control js-example-basic-multiple"
                            style="width: 100%;" required>
                            <option value="">Pilih</option>
                            <?php foreach ($Barang as $key => $value) { ?>
                            <option value="<?= $value['id_barang']; ?>"><?= $value['nama_barang']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group mt-2">
                        <label for="qty_barang">Qty Barang <span class="text-danger">*</span></label>
                        <input type="number" name="qty_barang" id="qty_barang" class="form-control" required
                            placeholder="Masukkan Qty Barang">
                    </div>
                    <div class="form-group mt-2">
                        <label for="penjualan_bersih">Penjualan Bersih <span class="text-danger">*</span></label>
                        <input type="number" name="penjualan_bersih" id="penjualan_bersih" class="form-control" required
                            placeholder="Masukkan Penjualan Bersih">
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
<div class="modal fade" id="edit" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="<?= base_url('Penjualan/ubah'); ?>" method="post">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Data Penjualan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-4">
                    <input type="hidden" name="id_penjualan" value="">
                    <div class="form-group mt-2">
                        <label for="tgl_penjualan">Tgl Penjualan <span class="text-danger">*</span></label>
                        <input type="date" name="tgl_penjualan" id="tgl_penjualan" class="form-control" value=""
                            required>

                    </div>

                    <div class="form-group mt-2">
                        <label for="id_barang">Nama Barang <span class="text-danger">*</span></label>
                        <select name="id_barang" id="edit_id_barang" class="form-control"
                            style="width: 100%; z-index: 999;" required>
                            <option value="">Pilih</option>
                            <?php foreach ($Barang as $key => $value) { ?>
                            <option value="<?= $value['id_barang']; ?>"><?= $value['nama_barang']; ?></option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="form-group mt-2">
                        <label for="qty_barang">Qty Barang <span class="text-danger">*</span></label>
                        <input type="number" name="qty_barang" id="qty_barang" class="form-control" value="" required
                            placeholder="Masukkan Qty Barang">
                    </div>

                    <div class="form-group mt-2">
                        <label for="penjualan_bersih">Penjualan Bersih <span class="text-danger">*</span></label>
                        <input type="number" name="penjualan_bersih" id="penjualan_bersih" class="form-control" value=""
                            required placeholder="Masukkan Penjualan Bersih">
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
<?= $this->endSection('kontent'); ?>
<?= $this->section('script'); ?>
<script type="text/javascript">
$(document).ready(function() {
    function dataTablesPenjualan() {
        $('#table_data_penjualan').DataTable({
            processing: true,
            serverSide: true,
            scrollCollapse: true,
            autoWidth: false,
            responsive: true,
            paging: true,
            searching: true,
            ajax: {
                url: '<?= base_url('Penjualan/ajaxDataTables') ?>',
                type: 'GET',
            },
            "lengthMenu": [
                [5, 10, 25, 50, -1],
                [5, 10, 25, 50, "All"]
            ],

            columns: [{
                    data: null,
                    sortable: false,
                    searchable: false,
                    className: 'text-center',
                    // title: 'No',
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                {
                    data: 'tgl_penjualan',
                    name: 'tgl_penjualan',
                    className: 'text-center'
                },
                {
                    data: 'nama_barang',
                    name: 'nama_barang',
                },
                {
                    data: 'qty_barang',
                    name: 'qty_barang',
                    className: 'text-center'
                },
                {
                    data: 'penjualan_bersih',
                    name: 'penjualan_bersih',
                    render: $.fn.dataTable.render.number('.', ',', 0, 'Rp '),
                    className: 'text-right'
                },
                {
                    data: 'action',
                    orderable: false,
                    searchable: false,
                    className: 'text-center'
                }
            ],
            order: [
                [1, 'desc']
            ],
        });
    }

    dataTablesPenjualan();
    // filter select2
    $('#filter_id_barang').select2({
        allowClear: true
    });
    // import
    $('#form-import').submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            url: '<?= base_url('Penjualan/import'); ?>',
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
                // reload datatables
                $('#table_data_penjualan').DataTable().ajax.reload();
                if (response.error) {
                    alert(response.data);
                } else {
                    $('#hasil-import').modal('show');
                    var html = '';
                    if (response.data.result.length > 0) {

                        $.each(response.data.result, function(key, value) {
                            html += '<tr' + (value.status == 'Failed' ?
                                ' class="table-danger"' : '') + '>';
                            html += '<td class="text-center">' + value.no +
                                '</td>';
                            html += '<td>' + value.data + '</td>';
                            html += '<td>' + value.message + '</td>';
                            html += '<td>' + value.status + '</td>';
                            html += '</tr>';
                        });

                    } else {
                        html += '<tr>';
                        html +=
                            '<td colspan="4" class="text-center">Tidak ada data yang gagal diimport</td>';
                        html += '</tr>';
                    }

                    $('#hasil_import_Penjualan').html(html);

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


    // edit data
    $('#table_data_penjualan').on('click', '.btn_edit', function() {
        // alert('edit');
        $.ajax({
            url: '<?= base_url('Penjualan/edit'); ?>',
            type: 'POST',
            dataType: 'json',
            data: {
                id_penjualan: $(this).attr('data-id')
            },
            success: function(response) {
                if (response.error) {
                    alert(response.data);
                } else {
                    $('#edit').modal('show');
                    $('#edit input[name="id_penjualan"]').val(response.data.id_penjualan);
                    $('#edit input[name="tgl_penjualan"]').val(response.data.tgl_penjualan);
                    $('#edit input[name="qty_barang"]').val(response.data.qty_barang);
                    $('#edit input[name="penjualan_bersih"]').val(response.data
                        .penjualan_bersih);
                    $('#edit select[name="id_barang"]').val(response.data.id_barang)
                        .trigger(
                            'change');
                    $('#edit select[name="id_barang"]').select2({
                        placeholder: 'Pilih',
                        allowClear: true
                    });


                }
            },
            error: function() {
                alert('Error');
            }
        });

    });

    // close modal hasil import
    // $('#hasil-import').on('hidden.bs.modal', function() {
    //     location.reload();
    // });
});

// select2 when open edit modal click
$('#edit').on('shown.bs.modal', function() {
    $('.select2_nama_barang').select2({
        theme: 'bootstrap4',
        placeholder: 'Pilih',
        allowClear: true
    });
});

// set time out alert
setTimeout(function() {
    $('.alert').fadeOut('slow');
}, 3000);
</script>
<?= $this->endSection('script'); ?>