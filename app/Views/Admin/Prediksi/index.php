<?= $this->extend('Template/index'); ?>
<?= $this->section('konten'); ?>
<?php 
$bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
?>
<div class="row">
    <div class="col-lg-12 mb-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <!-- kiri kanan -->
                <div class="row">
                    <div class="col-md-4">
                        <h6 class="m-0 font-weight-bold text-primary float-left">Data Penjualan</h6>
                    </div>
                    <!-- <div class="col-md-8">
                        <a class="btn btn-primary btn-sm float-right" href="#" data-toggle="modal" data-target="#add"><i
                                class="fas fa-plus"></i> Tambah</a>
                        <a class="btn btn-success btn-sm float-right mx-2" href="#" data-toggle="modal"
                            data-target="#import"><i class="fas fa-file-import"></i> Import</a>
                    </div> -->
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
                <!-- Form Pilih Barang dan Periode -->
                <form method="post" action="<?= base_url('Prediksi') ?>" class="mb-4" id="formPrediksi">
                    <div class="row g-3">
                        <div class="col-md-3 mb-2">
                            <label for="id_barang" class="form-label">Pilih Barang</label>
                            <select name="id_barang" id="id_barang" class="form-select js-example-basic-multiple"
                                style="width: 100%;" required>
                                <option value="">-- Pilih Barang --</option>
                                <?php foreach ($data_barang as $barang): ?>
                                <option value="<?= $barang['id_barang'] ?>"
                                    <?= ($id_barang == $barang['id_barang']) ? 'selected' : '' ?>>
                                    <?= $barang['nama_barang'] ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-3 mb-2">
                            <label for="periode" class="form-label">Periode (YYYY-MM)</label>
                            <input type="month" name="periode" id="periode" class="form-control" value="<?= $periode ?>"
                                required>
                        </div>

                        <div class="col-md-3 mb-2">
                            <label for="penjualan_bersih" class="form-label">Penjualan Bersih</label>
                            <input type="text" name="penjualan_bersih" id="penjualan_bersih" class="form-control"
                                value="<?= ($penjualan_bersih != null) ? number_format($penjualan_bersih, 0, ',', '.') :'' ?>"
                                required placeholder="Penjualan Bersih" min="0">
                        </div>

                        <div class="col-md-3 d-flex align-items-end mb-2">
                            <button type="submit" class="btn btn-primary w-100">Prediksi</button>
                        </div>
                    </div>
                </form>

                <!-- Tampilkan Data Prediksi -->
                <?php if (!empty($data_penjualan)): 
                    ?>
                <!-- hasil prediksi -->
                <hr>
                <h5 class="my-2 font-weight-bold float-left">Hasil Prediksi</h5>
                <div class="alert alert-success mt-4 text-center">Hasil prediksi untuk bulan
                    <strong><?php $periode_bulan = explode('-', $periode);echo $bulan[$periode_bulan[1] - 1] ?></strong>
                    Tahun <strong> <?= $periode_bulan[0] ?></strong>
                    dengan harga
                    <strong>Rp. <?= number_format($penjualan_bersih, 0, ',', '.') ?></strong> adalah
                    <!-- <strong><?=$hasilPrediksi ?> Unit</strong> -->
                    <strong><?= round($hasilPrediksi, 0) ?> Unit</strong>
                </div>

                <!-- acordion detail proses prediksi -->
                <div class="accordion mt-4" id="accordionExample">
                    <div class="card">
                        <div class="card-header" id="headingOne">
                            <h2 class="mb-0">
                                <button
                                    class="btn btn-link text-decoration-none text-dark font-weight-bold w-100 text-align-center"
                                    type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true"
                                    aria-controls="collapseOne">
                                    Detail Proses Prediksi
                                </button>
                            </h2>
                        </div>

                        <div id="collapseOne" class="collapse" aria-labelledby="headingOne"
                            data-parent="#accordionExample">
                            <div class="card-body">
                                <h5 class="my-2 font-weight-bold float-left">Data Penjualan</h5>
                                <div class="table-responsive mt-4">
                                    <table class="table table-bordered table-hover table-striped" id="dataTable"
                                        width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th class="text-center " width="50">No</th>
                                                <th class="text-center" width="150">Bulan (x1)</th>
                                                <th class="text-center">Penjualan Bersih (x2)</th>
                                                <th class="text-center" width="100">Jumlah Terjual (y)</th>
                                                <th class="text-center">X1^2</th>
                                                <th class="text-center">X2^2</th>
                                                <th class="text-center">Y^2</th>
                                                <th class="text-center">X1Y</th>
                                                <th class="text-center">X2Y</th>
                                                <th class="text-center">X1X2</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $no = 1; 
                                                foreach ($data_set as $data): 
                                            ?>
                                            <tr>
                                                <td class="text-center"><?= $no++ ?></td>
                                                <td class="text-center"><?= $bulan[$data['bulan'] - 1] ?></td>
                                                <td>Rp. <?= number_format($data['net_sales'], 0, ',', '.') ?></td>
                                                <td class="text-center"><?= $data['jumlah_terjual'] ?></td>
                                                <td class="text-center"><?= $data['X1X1'] ?></td>
                                                <td class="text-center"><?= $data['X2X2'] ?></td>
                                                <td class="text-center"><?= $data['YY'] ?></td>
                                                <td class="text-center"><?= $data['X1Y'] ?></td>
                                                <td class="text-center"><?= $data['X2Y'] ?></td>
                                                <td class="text-center"><?= $data['X1X2'] ?></td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th class="text-center" colspan="2">
                                                    <!-- gambar sigma -->
                                                    Σ (Jumlah) : <?= count($data_set) ?>
                                                </th>
                                                <th class="text-center">Rp. <?= number_format($jumlahX2, 0, ',', '.') ?>
                                                </th>
                                                <th class="text-center"><?= $jumlahY ?></th>
                                                <th class="text-center"><?= $jumlahX1X1 ?></th>
                                                <th class="text-center"><?= $jumlahX2X2 ?></th>
                                                <th class="text-center"><?= $jumlahYY ?></th>
                                                <th class="text-center"><?= $jumlahX1Y ?></th>
                                                <th class="text-center"><?= $jumlahX2Y ?></th>
                                                <th class="text-center"><?= $jumlahX1X2 ?></th>

                                            </tr>
                                    </table>
                                </div>
                                <hr>
                                <!-- data prediksi -->
                                <h5 class="my-2 font-weight-bold float-left">Data Prediksi</h5>
                                <div class="table-responsive mt-4">
                                    <table class="table table-bordered table-hover table-striped" id="dataTable"
                                        width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th class="text-center">Σx1^2</th>
                                                <th class="text-center">Σx2^2</th>
                                                <th class="text-center">Σy^2</th>
                                                <th class="text-center">Σx1y</th>
                                                <th class="text-center">Σx2y</th>
                                                <th class="text-center">Σx1x2</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="text-center"><?= $sigx1x1 ?></td>
                                                <td class="text-center"><?= $sigx2x2 ?></td>
                                                <td class="text-center"><?= $sigyy ?></td>
                                                <td class="text-center"><?= $sigx1y ?></td>
                                                <td class="text-center"><?= $sigx2y ?></td>
                                                <td class="text-center"><?= $sigx1x2 ?></td>
                                            </tr>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <!-- b1 -->
                                                <th class="text-center" colspan="2">b1 = (Σx1y * Σx2^2 - Σx2y * Σx1x2) /
                                                    (Σx1^2 * Σx2^2
                                                    -
                                                    Σx1x2^2)</th>
                                                <th class="text-center" colspan="4"><?= $b1 ?></th>
                                            </tr>
                                            <tr>
                                                <!-- b2 -->
                                                <th class="text-center" colspan="2">b2 = (Σx2y * Σx1^2 - Σx1y * Σx1x2) /
                                                    (Σx1^2 * Σx2^2
                                                    -
                                                    Σx1x2^2)</th>
                                                <th class="text-center" colspan="4"><?= $b2 ?></th>
                                            </tr>
                                            <tr>
                                                <!-- a -->
                                                <th class="text-center" colspan="2">a = (Σy / n) - b1 * (Σx1 / n) - b2 *
                                                    (Σx2 / n)</th>
                                                <th class="text-center" colspan="4"><?= $a ?></th>
                                            </tr>
                                        </tfoot>

                                    </table>
                                </div>

                                <hr>
                                <h5 class="my-2 font-weight-bold float-left">Hasil Pengujian</h5>

                                <div class="table-responsive mt-4">
                                    <table class="table table-bordered table-hover table-striped" id="dataTable"
                                        width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th class="text-center " width="50">No</th>
                                                <th class="text-center" width="150">Bulan (x1)</th>
                                                <th class="text-center">Penjualan Bersih (x2)</th>
                                                <th class="text-center" width="100">Jumlah Terjual (y)</th>
                                                <th class="text-center" width="100">Ypred</th>
                                                <th class="text-center">Y - Ypred</th>
                                                <th class="text-center">Y - Ypred^2</th>
                                                <th class="text-center">Y - Yrata^2</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $no = 1; 
                                            foreach ($data_pengujian as $data): 
                                            ?>
                                            <tr>
                                                <td class="text-center"><?= $no++ ?></td>
                                                <td class="text-center"><?= $bulan[$data['bulan'] - 1] ?></td>
                                                <td>Rp. <?= number_format($data['net_sales'], 0, ',', '.') ?></td>
                                                <td class="text-center"><?= $data['jumlah_terjual'] ?></td>
                                                <td class="text-center"><?= $data['Ypred'] ?></td>
                                                <td class="text-center"><?= $data['YYpred'] ?></td>
                                                <td class="text-center"><?= $data['YYpred2'] ?></td>
                                                <td class="text-center"><?= $data['YYrata2'] ?></td>
                                            </tr>
                                            <?php endforeach; ?>

                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th class="text-center" colspan="2">
                                                    <!-- gambar sigma -->
                                                    Σ (Jumlah) : <?= count($data_pengujian) ?>
                                                </th>
                                                <th class="text-center">Rp. <?= number_format($jumlahX2, 0, ',', '.') ?>
                                                </th>
                                                <th class="text-center"><?= $jumlahY ?></th>
                                                <th class="text-center"><?= $jumlahYpred ?></th>
                                                <th class="text-center"><?= $jumlahYYpred ?></th>
                                                <th class="text-center"><?= $jumlahYYpred2 ?></th>
                                                <th class="text-center"><?= $jumlahYYrata2 ?></th>
                                            </tr>
                                            <tr>
                                                <th class="text-center" colspan="4">MAE = Σ|Y - Ypred| / n</th>
                                                <th class="text-center" colspan="4"><?= $mae ?></th>
                                            </tr>
                                            <tr>
                                                <th class="text-center" colspan="4">MSE = Σ(Y - Ypred)^2 / n</th>
                                                <th class="text-center" colspan="4"><?= $mse ?></th>
                                            </tr>
                                            <tr>
                                                <th class="text-center" colspan="4">RMSE = √MSE</th>
                                                <th class="text-center" colspan="4"><?= $rmse ?></th>
                                            </tr>
                                            <tr>
                                                <th class="text-center" colspan="4">R^2 = 1 - Σ(Y - Ypred)^2 / Σ(Y -
                                                    Yrata)^2</th>
                                                <th class="text-center" colspan="4"><?= $r2 ?></th>
                                            </tr>
                                            <tr>
                                                <th class="text-center" colspan="4">R Adjusment = 1 - ((1 - R^2) * (n -
                                                    1) / (n - k -1))
                                                </th>
                                                <th class="text-center" colspan="4"><?= $r_adjusment ?></th>
                                            </tr>
                                            <tr>
                                                <th class="text-center" colspan="4">Uji F = (R^2 / k) / ((1 - R^2)
                                                    / (n - K -1))</th>
                                                <th class="text-center" colspan="4"><?= $uji_f ?></th>
                                            </tr>
                                            <tr>
                                                <th class="text-center" colspan="4">MAPE = (ΣYpred / ΣY) * 100</th>
                                                <th class="text-center" colspan="4"><?= $mape ?> %</th>
                                            </tr>

                                        </tfoot>


                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php else: ?>
                <div class="alert alert-warning mt-4" style="display: block !important;">
                    <strong>Maaf!</strong> <?= $error ?>.
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection('kontent'); ?>
<?= $this->section('script'); ?>
<script type="text/javascript">
// format rupiah
function formatRupiah(angka) {
    var number_string = angka.toString().replace(/[^,\d]/g, ''),
        split = number_string.split(','),
        sisa = split[0].length % 3,
        rupiah = split[0].substr(0, sisa),
        ribuan = split[0].substr(sisa).match(/\d{3}/gi);

    if (ribuan) {
        separator = sisa ? '.' : '';
        rupiah += separator + ribuan.join('.');
    }

    rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
    return rupiah;
}

$('#penjualan_bersih').on('input', function() {
    var value = $(this).val().replace(/[^0-9.]/g, '');
    $(this).val(formatRupiah(value));
});
</script>
<?= $this->endSection('script'); ?>