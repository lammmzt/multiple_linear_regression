<?php 
namespace App\Models;

use CodeIgniter\Model;

class penjualanModel extends Model
{
    protected $table = 'penjualan'; // set tabel
    protected $primaryKey = 'id_penjualan'; // set primary key
    protected $allowedFields = ['id_barang','qty_barang', 'penjualan_bersih', 'tgl_penjualan', 'created_at', 'updated_at']; // set field yang diizinkan
    protected $useTimestamps = true; // set timestamps

    public function ajaxDataPenjualan($id_barang = false) // ambil data penjualan
    {
        return $this
            ->select('penjualan.id_penjualan, penjualan.qty_barang, penjualan.penjualan_bersih, penjualan.tgl_penjualan, barang.nama_barang') // select data penjualan dan nama barang
            ->join('barang', 'barang.id_barang = penjualan.id_barang');
           
    }
    public function getpenjualan($id = false) // ambil data penjualan
    {
        if($id == false){ // jika id kosong
            return $this
            ->select('penjualan.*, barang.nama_barang') // select data penjualan dan nama barang
            ->join('barang', 'barang.id_barang = penjualan.id_barang')
            ->orderBy('tgl_penjualan', 'DESC')
            ->findAll(); // ambil semua data penjualan
        }
        return $this
        ->select('penjualan.*, barang.nama_barang,') // select data penjualan dan nama barang
        ->join('barang', 'barang.id_barang = penjualan.id_barang')
        ->where(['id_penjualan' => $id])
        ->first(); // ambil data penjualan berdasarkan id
    }

    public function getDataPenjualan($id_barang, $periode)
    {
        return $this->select('MONTH(tgl_penjualan) AS bulan, SUM(penjualan_bersih) AS net_sales, SUM(qty_barang) AS jumlah_terjual')
            ->where('id_barang', $id_barang)
            ->where('DATE_FORMAT(tgl_penjualan, "%Y-%m") <', $periode)
            ->groupBy('MONTH(tgl_penjualan)')
            ->findAll();
    }

    public function getNetSales($id_barang, $periode)
    {
        return $this->select('SUM(penjualan_bersih) AS net_sales')
            ->where('id_barang', $id_barang)
            ->where('DATE_FORMAT(tgl_penjualan, "%Y-%m")', $periode)
            ->first();
    }

}

?>