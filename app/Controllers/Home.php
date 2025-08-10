<?php

namespace App\Controllers;
use App\Models\barangModel;
use App\Models\penjualanModel;

class Home extends BaseController
{
    public function index(): string
    {
        $barangModel = new barangModel();
        $penjualanModel = new penjualanModel();
        $data['barang'] = $barangModel->countAllResults();
        $data['penjualan_bulan'] = $penjualanModel->select('SUM(qty_barang) AS total_penjualan')
            ->where('YEAR(tgl_penjualan)', date('Y'))
            ->where('MONTH(tgl_penjualan)', date('m'))
            ->first();
        $data['pendapatan_bulan'] = $penjualanModel->select('SUM(penjualan_bersih) AS total_pendapatan')
            ->where('YEAR(tgl_penjualan)', date('Y'))
            ->where('MONTH(tgl_penjualan)', date('m'))
            ->first();
        $data['pendapatan_tahun'] = $penjualanModel->select('SUM(penjualan_bersih) AS total_pendapatan')
            ->where('YEAR(tgl_penjualan)', date('Y'))
            ->first();
        // dd($data);
        if($this->request->getPost('tahun')){ // if year is selected
            $year = $this->request->getPost('tahun'); // get selected year
        }else{
            $year = date('Y'); // get current year
        }
        $data_penjualan_tahunan = [];
        for ($i = 1; $i <= 12; $i++) {
            $data_penjualan_tahunan[$i] = $penjualanModel->select('SUM(qty_barang) AS total_penjualan, sUM(penjualan_bersih) AS total_pendapatan')
                ->where('YEAR(tgl_penjualan)', $year)
                ->where('MONTH(tgl_penjualan)', $i)
                ->first();
            if ($data_penjualan_tahunan[$i]['total_penjualan'] == null) {
                $data_penjualan_tahunan[$i]['total_penjualan'] = 0;
            }
            if ($data_penjualan_tahunan[$i]['total_pendapatan'] == null) {
                $data_penjualan_tahunan[$i]['total_pendapatan'] = 0;
            }

            $data_penjualan_tahunan[$i]['total_pendapatan'] = $data_penjualan_tahunan[$i]['total_pendapatan'];
            $data_penjualan_tahunan[$i]['total_penjualan'] = $data_penjualan_tahunan[$i]['total_penjualan'];
        }
        // dd($data_penjualan_tahunan);
        $data['data_penjualan_tahunan'] = $data_penjualan_tahunan;
        $data['tahun'] = $year; // set year
        $data['title'] = 'Dashboard';
        $data['active'] = 'Dashboard';
        return view('Admin/Dashboard', $data);
    }
}