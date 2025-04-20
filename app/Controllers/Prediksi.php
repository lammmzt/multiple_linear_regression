<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\penjualanModel;
use App\Models\barangModel;

class Prediksi extends BaseController
{
    
    public function index(){
        $barangModel = new barangModel(); // panggil model barang
        $penjualanModel = new penjualanModel(); // panggil model penjualan

        $id_barang = $this->request->getPost('id_barang') ?? null; // ambil id barang
        $periode = $this->request->getPost('periode') ?? date('Y-m'); // ambil periode penjualan (x1)
        $penjualan_bersih = $this->request->getPost('penjualan_bersih') ?? null; // ambil penjualan bersih (x2)
        $penjualan_bersih = str_replace('.', '', $penjualan_bersih); // hapus titik pada penjualan bersih
        $penjualan_bersih = str_replace(',', '.', $penjualan_bersih); // ganti koma dengan titik pada penjualan bersih
        $data_set = [];
        // dd($penjualan_bersih, $periode, $id_barang);
        $dataPenjualan = $id_barang ? $penjualanModel->select('MONTH(tgl_penjualan) AS bulan, SUM(penjualan_bersih) AS net_sales, SUM(qty_barang) AS jumlah_terjual')
            ->where('id_barang', $id_barang)
            ->where('DATE_FORMAT(tgl_penjualan, "%Y-%m") <', $periode)
            ->orderBy('MONTH(tgl_penjualan)', 'ASC')
            ->groupBy('MONTH(tgl_penjualan)')
            ->findAll() : []; // ambil data penjualan berdasarkan id barang dan periode
        
            // dd($dataPenjualan);
        
        if (empty($dataPenjualan)) { // jika data penjualan kosong
            return view('Admin/Prediksi/index', [ // tampilkan view prediksi
                'title' => 'Prediksi Penjualan', // set judul
                'active' => 'Prediksi',
                'data_set' => [], // set data prediksi
                'hasilPrediksi' => 0, // set hasil prediksi
                'mape' => 0, // set mape( Mean Absolute Percentage Error atau kesalahan persentase absolut rata-rata)
                'data_barang' => $barangModel->findAll(), // ambil semua data barang
                'data_penjualan' => $dataPenjualan, // ambil semua data penjualan
                'periode' => $periode, // set periode
                'id_barang' => $id_barang, // set id barang
                'penjualan_bersih' => $penjualan_bersih, // set penjualan bersih
                'error' => 'Data penjualan tidak ditemukan', // set pesan error
                 
            ]);
        }

        // inisisalisasi variabel untuk data set
        $y = $x1 = $x2 = $x1x1 = $x2x2 = $yy = $x1y = $x2y = $x1x2 = 0; // set variabel regresi
        
         // Inisialisasi variabel untuk prediksi
        $jumlahX1 = $jumlahX2 = $jumlahY = $jumlahX1Y = $jumlahX2Y = $jumlahX1X2 = $jumlahYY = 0; // set variabel regresi
        $jumlahX1X1 = $jumlahX2X2 = 0; // set variabel regresi

        
        foreach ($dataPenjualan as $data) { // loop data penjualan
            $bulan = intval($data['bulan']); // set bulan
            $net_sales = floatval($data['net_sales']); // set net sales
            $jumlahTerjual = intval($data['jumlah_terjual']); // set jumlah terjual
            $X1 = $bulan; // set x1
            $X2 = $net_sales; // set x2
            $Y = $jumlahTerjual; // set y
            $X1X1 = $X1 * $X1; // set x1x1
            $X2X2 = $X2 * $X2; // set x2x2
            $YY = $Y * $Y; // set y*y
            $X1Y = $X1 * $Y; // set x1y
            $X2Y = $X2 * $Y; // set x2y
            $X1X2 = $X1 * $X2; // set x1x2
            
            $data_set[] = [ // set data prediksi
                'bulan' => $bulan, // set bulan
                'net_sales' => $net_sales, // set net sales
                'jumlah_terjual' => $jumlahTerjual, // set jumlah terjual
                'X1' => $X1, // set x1
                'X2' => $X2, // set x2
                'Y' => $Y, // set y
                'X1X1' => $X1X1, // set x1x1
                'X2X2' => $X2X2, // set x2x2
                'YY' => $YY, // set y*y
                'X1Y' => $X1Y, // set x1y
                'X2Y' => $X2Y, // set x2y
                'X1X2' => $X1X2, // set x1x2
            ];

            $jumlahX1 += $X1; // jumlahkan x1
            $jumlahX2 += $X2; // jumlahkan x2
            $jumlahY += $Y; // jumlahkan y
            $jumlahX1Y += $X1Y; // jumlahkan x1y
            $jumlahX2Y += $X2Y; // jumlahkan x2y
            $jumlahX1X2 += $X1X2; // jumlahkan x1x2
            $jumlahX1X1 += $X1X1; // jumlahkan x1x1
            $jumlahX2X2 += $X2X2; // jumlahkan x2x2
            $jumlahYY += $YY; // jumlahkan y*y
            
        }
        // dd($data_set);
        $n = count($data_set); // hitung jumlah data
        $k = 2; // set jumlah variabel independen
        $sigx1x1 = $jumlahX1X1-(pow($jumlahX1, 2)/$n); // hitung sigma x1x1
        $sigx2x2 = $jumlahX2X2-(pow($jumlahX2, 2)/$n); // hitung sigma x2x2
        $sigyy = $jumlahYY-(pow($jumlahY, 2)/$n); // hitung sigma y*y
        $sigx1y = $jumlahX1Y-($jumlahX1*$jumlahY/$n); // hitung sigma x1y
        $sigx2y = $jumlahX2Y-($jumlahX2*$jumlahY/$n); // hitung sigma x2y
        $sigx1x2 = $jumlahX1X2-($jumlahX1*$jumlahX2/$n); // hitung sigma x1x2
        // dd($sigx1x1, $sigx2x2, $sigyy, $sigx1y, $sigx2y, $sigx1x2);
        // validasi pembagi tidak boleh 0
        if ($sigx1x1*$sigx2x2-pow($sigx1x2, 2) == 0) { // jika pembagi 0
            return view('Admin/Prediksi/index', [ // tampilkan view prediksi
                'title' => 'Prediksi Penjualan', // set judul
                'active' => 'Prediksi',
                'data_set' => [], // set data prediksi
                'hasilPrediksi' => 0, // set hasil prediksi
                'mape' => 0, // set mape( Mean Absolute Percentage Error atau kesalahan persentase absolut rata-rata)
                'data_barang' => $barangModel->findAll(), // ambil semua data barang
                'data_penjualan' => [], // ambil semua data penjualan
                'periode' => $periode, // set periode
                'id_barang' => $id_barang, // set id barang
                'penjualan_bersih' => $penjualan_bersih, // set penjualan bersih
                'error' => 'Jumlah data terlalu sedikit atau tidak ada perubahan pada data penjualan', // set pesan error
            ]);
        }
        $b1 = ($sigx1y*$sigx2x2-$sigx2y*$sigx1x2)/($sigx1x1*$sigx2x2-pow($sigx1x2, 2)); // hitung b1
        $b2 = ($sigx2y*$sigx1x1-$sigx1y*$sigx1x2)/($sigx1x1*$sigx2x2-pow($sigx1x2, 2)); // hitung b2
        $a = ($jumlahY/$n)-$b1*($jumlahX1/$n)-$b2*($jumlahX2/$n); // hitung a

        // Prediksi untuk bulan berikutnya
        $bulanPrediksi = intval(explode('-', $periode)[1]); // set bulan prediksi
        $net_sales_prediksi = $a + $b1 * $bulanPrediksi + $b2 * $penjualan_bersih; // hitung net sales prediksi

         // Inisialisasi variabel metrik evaluasi regresi (regression) multiple linear
        $jumlahYpred = $jumlahYYpred = $jumlahYYpred2 = $jumlahYYrata2 = $jumlahYpred =  $mae = $rmse = $r2 = $r_adjusment = $uji_f = $mse = $mape = 0; // set variabel evaluasi
       
        $data_pengujian = [] ; // set data pengujian
        foreach ($data_set as $data) { // loop data set
            $Y = $data['jumlah_terjual']; // set y
            $X1 = $data['bulan']; // set x1
            $X2 = $data['net_sales']; // set x2
            $Ypred = $a + $b1 * $X1 + $b2 * $X2; // hitung y prediksi
            $YYpred = $Y - $Ypred; // hitung y - y prediksi
            $YYpred2 = pow($Y - $Ypred, 2); // hitung y - y prediksi kuadrat
            $YYrata2 = pow($Y - ($jumlahY/$n), 2); // hitung y - y rata-rata kuadrat

            $data_pengujian[] = [ // set data pengujian
                'bulan' => $X1, // set x1
                'net_sales' => $X2, // set x2
                'jumlah_terjual' => $Y, // set y
                'Ypred' => $Ypred, // set y prediksi
                'YYpred' => $YYpred, // set y - y prediksi
                'YYpred2' => $YYpred2, // set y - y prediksi kuadrat
                'YYrata2' => $YYrata2, // set y - y rata-rata kuadrat
            ];

            
            $jumlahYpred += $Ypred; // jumlahkan y prediksi
            $jumlahYYpred += $YYpred; // jumlahkan y - y prediksi
            $jumlahYYpred2 += $YYpred2; // jumlahkan y - y prediksi kuadrat
            $jumlahYYrata2 += $YYrata2; // jumlahkan y - y rata-rata kuadrat
            $mae += abs($Y - $Ypred); // jumlahkan mae
        }
        // dd($data_pengujian);
        // validasi untuk r_adjusment tidak boleh 0
        if ($n-$k-1 == 0) { // jika pembagi 0
            return view('Admin/Prediksi/index', [ // tampilkan view prediksi
                'title' => 'Prediksi Penjualan', // set judul
                'active' => 'Prediksi',
                'data_set' => [], // set data prediksi
                'hasilPrediksi' => 0, // set hasil prediksi
                'mape' => 0, // set mape( Mean Absolute Percentage Error atau kesalahan persentase absolut rata-rata)
                'data_barang' => $barangModel->findAll(), // ambil semua data barang
                'data_penjualan' => [], // ambil semua data penjualan
                'periode' => $periode, // set periode
                'id_barang' => $id_barang, // set id barang
                'penjualan_bersih' => $penjualan_bersih, // set penjualan bersih
                'error' => 'Jumlah data terlalu sedikit atau tidak ada perubahan pada data penjualan', // set pesan error
            ]);
        }
        
        $mse = $jumlahYYpred2/$n; // hitung mse
        $rmse = sqrt($mse); // hitung rmse
        $r2 = 1-($jumlahYYpred2/$jumlahYYrata2); // hitung r2 (koefisien determinasi) 
        $r_adjusment = 1-((1-$r2)*($n-1)/($n-$k-1)); // hitung r adjusment
        $uji_f = ($r2/$k)/((1-$r2)/($n-$k-1)); // hitung uji f
        $mape = ($jumlahYpred/$jumlahY)*100; // hitung mape
        $mae = $mae/$n; // hitung mae
        
        // dd($a, $b1, $b2, $net_sales_prediksi);
        return view('Admin/Prediksi/index', [ // tampilkan view prediksi
            'title' => 'Prediksi Penjualan', // set judul
            'active' => 'Prediksi', // set active menu
            'data_set' => $data_set, // set data prediksi
            'data_barang' => $barangModel->findAll(), // ambil semua data barang
            'periode' => $periode, // set periode
            'id_barang' => $id_barang, // set id barang
            'data_penjualan' => $dataPenjualan, // set data penjualan
            'penjualan_bersih' => $penjualan_bersih, // set penjualan bersih
            'jumlahY' => $jumlahY, // set jumlah y
            'jumlahX1' => $jumlahX1, // set jumlah x1
            'jumlahX2' => $jumlahX2, // set jumlah x2
            'jumlahX1Y' => $jumlahX1Y, // set jumlah x1y
            'jumlahX2Y' => $jumlahX2Y, // set jumlah x2y
            'jumlahX1X2' => $jumlahX1X2, // set jumlah x1x2
            'jumlahX1X1' => $jumlahX1X1, // set jumlah x1x1
            'jumlahX2X2' => $jumlahX2X2, // set jumlah x2x2
            'jumlahYY' => $jumlahYY, // set jumlah y*y
            'n' => $n, // set jumlah data
            'sigx1x1' => $sigx1x1, // set sigma x1x1
            'sigx2x2' => $sigx2x2, // set sigma x2x2
            'sigyy' => $sigyy, // set sigma y*y
            'sigx1y' => $sigx1y, // set sigma x1y
            'sigx2y' => $sigx2y, // set sigma x2y
            'sigx1x2' => $sigx1x2, // set sigma x1x2
            'a' => $a, // set a
            'b1' => $b1, // set b1
            'b2' => $b2, // set b2
            'data_pengujian' => $data_pengujian, // set data pengujian
            'jumlahYpred' => $jumlahYpred, // set jumlah y prediksi
            'jumlahYYpred' => $jumlahYYpred, // set jumlah y - y prediksi
            'jumlahYYpred2' => $jumlahYYpred2, // set jumlah y - y prediksi kuadrat
            'jumlahYYrata2' => $jumlahYYrata2, // set jumlah y - y rata-rata kuadrat
            'mae' => $mae, // set mae
            'rmse' => $rmse, // set rmse
            'r2' => $r2, // set r2
            'r_adjusment' => $r_adjusment, // set r adjusment
            'uji_f' => $uji_f, // set uji f
            'mse' => $mse, // set mse
            'mape' => $mape, // set mape
            'hasilPrediksi' => $net_sales_prediksi, // set hasil prediksi
        ]);

    }
}