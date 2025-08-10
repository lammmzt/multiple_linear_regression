<?php 
namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\penjualanModel;
use App\Models\barangModel;
use Hermawan\DataTables\DataTable;
use CodeIgniter\HTTP\ResponseInterface;
class Penjualan extends BaseController
{
    public function index(): string // tampikan data Penjualan
    {
        $model = new penjualanModel(); // panggil model Penjualan
        $modelBarang = new barangModel(); // panggil model barang
        // dd($data);
        $data['Barang'] = $modelBarang->findAll(); // get all data barang
        $data['title'] = 'Penjualan'; // set judul
        $data['active'] = 'Penjualan'; // set active menu
        return view('Admin/Penjualan/index', $data); // mengirim data ke view
    }

    public function ajaxDataTables()
    {
        $model = new penjualanModel(); // panggil model Penjualan
        // $status_pengecekan = $this->request->getPost('status_pengecekan');
       
        $builder = $model->ajaxDataPenjualan();
        // dd($builder->findAll());
        return DataTable::of($builder)
            
            ->add('action', function ($row) {
                return '
                    <a href="#" class="btn btn-warning btn-sm btn_edit" data-toggle="modal" data-id="' . $row->id_penjualan . '"> <i class="fas fa-edit"></i> Edit</a>
                    <a href="#" class="btn btn-danger btn-sm btn_hapus" data-toggle="modal" data-id="' . $row->id_penjualan . '"> <i class="fas fa-trash"></i> Hapus</a>
                ';
            }, 'last')
            ->toJson(true);
    }
    
    public function simpan() // simpan data users
    { 
        $model = new penjualanModel(); // panggil model Penjualan
        $data = [ // set data
            'id_barang' => $this->request->getPost('id_barang'),
            'qty_barang' => $this->request->getPost('qty_barang'),
            'penjualan_bersih' => $this->request->getPost('penjualan_bersih'),
            'tgl_penjualan' => $this->request->getPost('tgl_penjualan'),
            'created_at' => date('Y-m-d H:i:s')
        ];
        session()->setFlashdata('success', 'Data Berhasil Disimpan'); // set flashdata
        $model->save($data); // insert data ke tabel Penjualan
        return redirect()->to('/Penjualan'); // redirect ke halaman Penjualan
    }

    public function edit() // edit data Penjualan
    {
        $model = new penjualanModel(); // panggil model Penjualan
        $id_penjualan = $this->request->getPost('id_penjualan'); // ambil id Penjualan
        $data = $model->find($id_penjualan); // ambil data Penjualan
        
        return $this->response->setJSON([ // set response json
            'error' => false,
            'data' => $data,
            'status' => '200'
        ]);
    }
    
    public function ubah() // ubah data Penjualan
    {
        $model = new penjualanModel(); // panggil model Penjualan
        // dd($this->request->getPost());
        $id_penjualan = $this->request->getPost('id_penjualan');
        $data = [ 
            'id_barang' => $this->request->getPost('id_barang'),
            'qty_barang' => $this->request->getPost('qty_barang'),
            'penjualan_bersih' => $this->request->getPost('penjualan_bersih'),
            'tgl_penjualan' => $this->request->getPost('tgl_penjualan'),
            'updated_at' => date('Y-m-d H:i:s')
        ]; 
        // dd($data, $id_penjualan);
        session()->setFlashdata('success', 'Data Berhasil Diubah'); // set flashdata
        $model->update($id_penjualan, $data); // update data Penjualan
        return redirect()->to('/Penjualan'); // redirect ke halaman Penjualan
    }

    public function import(){
        $file_excel = $this->request->getFile('file'); // get file excel
        $validation = \Config\Services::validation(); // get validation
        $modalPenjualan = new penjualanModel(); // panggil model Penjualan
        $modelBarang = new barangModel(); // panggil model barang
        $data_barang = $modelBarang->findAll(); // get all data barang
        // to array data barang id nama_barang dan data id barang saja
        $data_nama_barang = array_column($data_barang, 'id_barang', 'nama_barang');
        // dd($nama_barang);
        // Define validation rules
        $validation->setRules([ // set rules
            'file' => [
                'rules' => 'uploaded[file]|ext_in[file,xls,xlsx,csv]',
                'result' => [
                    'uploaded' => 'File tidak boleh kosong',
                    'required' => 'File tidak boleh kosong',
                    'ext_in' => 'File harus berupa xls, xlsx, csv'
                ]
            ]
        ]);

        // Validate the request data
        if (!$validation->run($this->request->getPost())) { // run validation
            return $this->response->setJSON([
                'error' => true,
                'data' => $validation->getresult(),
                'status' => '422'
            ]);
        }

        // Initialize the PhpSpreadsheet reader based on the file extension
        $ext = $file_excel->getClientExtension(); // get file extension
        if ($ext == 'xls') {
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
        } elseif ($ext == 'xlsx') {
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        } elseif ($ext == 'csv') {
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
        }

        $spreadsheet = $reader->load($file_excel); // load file excel
        $data = $spreadsheet->getActiveSheet()->toArray(); // get data excel

        // add total data to process
        $total_data = count($data) - 1;
        $no = 0;
        $success = 0;
        $failed = 0;
        $result = [];
        // dd($total_data);
        foreach ($data as $x => $col) {

            if ($x == 0) {
                continue;
            }
            
            $no++;

            $tgl_penjualan = $col[1];
            $nama_barang = $col[2];
            $penjualan_bersih = str_replace(',', '', $col[3]);
            $penjualan_bersih = str_replace('Rp', '', $penjualan_bersih);
            $penjualan_bersih = str_replace('.', '', $penjualan_bersih);
            $qty_barang = $col[4];

            if (empty($nama_barang) || empty($tgl_penjualan) || empty($qty_barang) || empty($penjualan_bersih)) {
                $failed++;
                $result[] = [
                    'no' => $no,
                    'data' => 'Baris ke-' . $no,
                    'status' => 'Failed',
                    'message' => 'Data tidak lengkap'
                ];
                continue;
            }
            // check if nama barang already exists
            if (array_key_exists($nama_barang, $data_nama_barang)) {
                $id_barang = $data_nama_barang[$nama_barang];
                // dd($id_barang);
                $modalPenjualan->save([
                    'id_barang' => $id_barang,
                    'qty_barang' => $qty_barang,
                    'penjualan_bersih' => $penjualan_bersih,
                    'tgl_penjualan' => date('Y-m-d', strtotime($tgl_penjualan)),
                    'created_at' => date('Y-m-d H:i:s')
                ]);
                $result[] = [
                    'no' => $no,
                    'data' => 'Baris ke-' . $no,
                    'status' => 'Success',
                    'message' => 'Data berhasil disimpan'
                ];
                $success++;
                continue;
            }

            $id = 'BRG-'.date('Y').'-'.rand(100,999);
            // check id barang
            while (array_key_exists($id, $data_nama_barang)) {
                $id = 'BRG-'.date('Y').'-'.rand(100,999);
            }
            // simpan data barang
            $modelBarang->insert([
                'id_barang' => $id,
                'nama_barang' => $nama_barang,
                'created_at' => date('Y-m-d H:i:s')
            ]);
            
            // add to array data barang
            $data_nama_barang[$nama_barang] = $id;
            
            // simpan data Penjualan
            $modalPenjualan->save([
                'id_barang' => $id,
                'qty_barang' => $qty_barang,
                'penjualan_bersih' => $penjualan_bersih,
                'tgl_penjualan' => date('Y-m-d', strtotime($tgl_penjualan)),
                'created_at' => date('Y-m-d H:i:s')
            ]);

            $result[] = [
                'no' => $no,
                'data' => 'Baris ke-' . $no,
                'status' => 'Success',
                'message' => 'Data berhasil disimpan'
            ];
            $success++;

            // add to array data barang
            $data_nama_barang[$nama_barang] = $id;
        }

        return $this->response->setJSON([
            'error' => false,
            'data' => [
                'total_data' => $total_data,
                'success' => $success,
                'failed' => $failed,
                'result' => $result
            ],
            'status' => '200'
        ]);
    }
    
    public function hapus() // hapus data Penjualan
    {
        $model = new penjualanModel(); // panggil model Penjualan
        $id = $this->request->getPost('id_penjualan'); // ambil id Penjualan dari post
        $data = $model->find($id); // cari data Penjualan berdasarkan id
        if (!$data) { // jika data tidak ditemukan
            session()->setFlashdata('error', 'Data Tidak Ditemukan'); // set flashdata error
            return redirect()->to('/Penjualan'); // redirect ke halaman Penjualan
        }
        $model->delete($id); // hapus data Penjualan
        session()->setFlashdata('success', 'Data Berhasil Dihapus'); // set flashdata
        return redirect()->to('/Penjualan'); // redirect ke halaman Penjualan
    }
    
}
?>