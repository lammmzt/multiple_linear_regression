<?php 
namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\barangModel;
use CodeIgniter\HTTP\ResponseInterface;
use Hermawan\DataTables\DataTable;
class Barang extends BaseController
{
    public function index(): string // tampikan data Barang
    {
        $model = new barangModel(); // panggil model barang
        $data['barang'] = $model->findAll(); // ambil data Barang
        // dd($data);
        $data['title'] = 'Barang'; // set judul
        $data['active'] = 'Barang'; // set active menu
        return view('Admin/Barang/index', $data); // mengirim data ke view
    }

    public function ajaxDataTables()
    {
        $model = new barangModel(); // panggil model barangModel
       
        $builder = $model->ajaxDataPenjualan();
        // dd($builder->findAll());
        return DataTable::of($builder)
            
            ->add('action', function ($row) {
                return '
                    <a href="#" class="btn btn-warning btn-sm btn_edit" data-toggle="modal" data-id="' . $row->id_barang . '"> <i class="fas fa-edit"></i> Edit</a>
                    <a href="' . base_url('Penjualan/hapus/' . $row->id_barang) . '" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin ingin menghapus data ini?\')"> <i class="fas fa-trash"></i> Hapus</a>
                ';
            }, 'last')
            ->toJson(true);
    }
    
    public function simpan() // simpan data users
    { 
        $model = new barangModel(); // panggil model barang
        $id_barang = 'BRG-'.date('YmdHis').rand(100,9999); // generate id barang
        $data = [ // set data
            'id_barang' => $id_barang,
            'nama_barang' => $this->request->getPost('nama_barang'),
            'created_at' => date('Y-m-d H:i:s')
        ];
        session()->setFlashdata('success', 'Data Berhasil Disimpan'); // set flashdata
        $model->insert($data); // insert data ke tabel barang
        return redirect()->to('/Barang'); // redirect ke halaman barang
    }

    public function ubah() // ubah data barang
    {
        $model = new barangModel(); // panggil model barang
        $id = $this->request->getPost('id_barang'); // ambil id barang
        $data = [ // set data
            'nama_barang' => $this->request->getPost('nama_barang'),
            'updated_at' => date('Y-m-d H:i:s')
        ]; 
        session()->setFlashdata('success', 'Data Berhasil Diubah'); // set flashdata
        $model->update($id, $data); // update data barang
        return redirect()->to('/Barang'); // redirect ke halaman barang
    }

    public function import(){
        $file_excel = $this->request->getFile('file'); // get file excel
        $validation = \Config\Services::validation(); // get validation
        $model = new barangModel(); // panggil model barang
        $data_barang = $model->findAll(); // get all data barang
        // to array data barang
        $data_barang = array_column($data_barang, 'nama_barang');
        // dd($data_barang);
        // Define validation rules
        $validation->setRules([ // set rules
            'file' => [
                'rules' => 'uploaded[file]|ext_in[file,xls,xlsx,csv]',
                'errors' => [
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
                'data' => $validation->getErrors(),
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
        $no = 0; // set no
        $success = 0; // set success
        $failed = 0;   // set failed
        $errors = []; // set errors
        // dd($total_data);
        foreach ($data as $x => $col) { // loop data excel

            if ($x == 0) { // jika x = 0 (header)
                continue;
            }
            
            $no++; // increment no

            $nama_barang = $col[1]; // set nama barang
            if (empty($nama_barang)) { // jika nama barang kosong
                $failed++; // increment failed
                $errors[] = [ // set errors
                    'no' => $no,
                    'nama_barang' => $nama_barang,
                    'error' => 'Nama Barang Tidak Boleh Kosong'
                ];
                continue; // skip
            }
            if (in_array($nama_barang, $data_barang)) { // jika nama barang sudah ada
                $failed++; // increment failed
                $errors[] = [ // set errors
                    'no' => $no,    
                    'nama_barang' => $nama_barang,
                    'error' => 'Nama Barang Sudah Ada'
                ];
                continue; // skip
            }

            $model->insert([ // insert data barang
                'id_barang' => 'BRG-'.date('YmdHis').$no.rand(100,999), // generate id barang
                'nama_barang' => $nama_barang, // set nama barang
                'created_at' => date('Y-m-d H:i:s') // set created at
            ]);

            $success++; // increment success

            // add to array data barang
            $data_barang[] = $nama_barang; // add nama barang
        }

        return $this->response->setJSON([   // set json response
            'error' => false,  // set error false
            'data' => [ // set data
                'total_data' => $total_data,
                'success' => $success,
                'failed' => $failed,
                'errors' => $errors
            ],
            'status' => '200' // set status 200
        ]);
    }
    
    public function hapus() // hapus data barang
    {
        $model = new barangModel(); // panggil model barang
        $id = $this->request->getPost('id_barang'); // ambil id barang dari post
        $data = $model->find($id); // cari data barang berdasarkan id
        if (!$data) { // jika data tidak ditemukan
            session()->setFlashdata('error', 'Data Tidak Ditemukan'); // set flashdata error
            return redirect()->to('/Barang'); // redirect ke halaman barang
        }
        $model->delete($id); // hapus data barang
        session()->setFlashdata('success', 'Data Berhasil Dihapus'); // set flashdata
        return redirect()->to('/Barang'); // redirect ke halaman barang
    }
    
}
?>