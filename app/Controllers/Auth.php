<?php 
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\usersModel;

class Auth extends BaseController
{
    public function index() // menampilkan halaman login
    {
        $data['title'] = 'Login'; // membuat judul halaman
        $data['active'] = 'Login'; // membuat link active
        $data['validation'] = \Config\Services::validation(); // membuat validasi form
        return view('Auth/index', $data); // menampilkan view Auth/login
    }

    public function login() // proses login
    {
        $usersModel = new usersModel(); // membuat instance dari model usersModel
        $username = $this->request->getPost('username'); // mengambil data username dari form
        $password = $this->request->getPost('password'); // mengambil data password dari form
        $users = $usersModel->where('username', $username)->first(); // mengambil data users berdasarkan username
        if($users) // jika data users ditemukan
        {
            if(password_verify($password, $users['password'])) // jika password sesuai
            {
                $data = array(
                    'id_user' => $users['id_user'], // mengambil data id_user dari database
                    'nama_user' => $users['nama_user'], // mengambil data nama_user dari database
                    'role' => $users['role'], // mengambil data role dari database
                    'loggin' => TRUE // membuat session masuk_login,
                );
                session()->set($data); // membuat session
                return redirect()->to(base_url('Dashboard')); // mengalihkan ke halaman Dashboard
            }else{
                session()->setFlashdata('error', 'Password Salah'); // menampilkan pesan error
                return redirect()->to(base_url('Auth'))->withInput(); // mengalihkan ke halaman Auth
            }
        }else{
            session()->setFlashdata('error', 'Username Tidak Ditemukan'); // menampilkan pesan error
            return redirect()->to(base_url('Auth'))->withInput(); // mengalihkan ke halaman Auth
        }
    }

    public function logout() // proses logout
    {
        session()->destroy(); // menghapus semua session
        return redirect()->to(base_url('Auth')); // mengalihkan ke halaman Auth
    }
}
?>