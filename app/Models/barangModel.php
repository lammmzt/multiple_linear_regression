<?php 
namespace App\Models;

use CodeIgniter\Model;

class barangModel extends Model
{
    protected $table = 'barang'; // set tabel
    protected $primaryKey = 'id_barang'; // set primary key
    protected $allowedFields = ['id_barang', 'nama_barang', 'created_at', 'updated_at']; // set field yang diizinkan
    protected $useTimestamps = true; // set timestamps

    public function getBarang($id = false) // ambil data barang
    {
        if($id == false){ // jika id kosong
            return $this->findAll(); // ambil semua data barang
        }
        return $this->where(['id_barang' => $id])->first(); // ambil data barang berdasarkan id
    }

}

?>