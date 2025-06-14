<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Auth extends BaseController
{
    public function index()
    {
        return view('auth/login');
    }

    public function logincheck()
    {
        $email = trim($this->request->getPost('email'));
        $password = trim($this->request->getPost('password'));

        $db = \Config\Database::connect();
        $builder = $db->table('users');
        $result = $builder->getWhere(['email' => $email])->getResultArray();

        if (sizeof($result) > 0) {
            if (password_verify($password, $result[0]['password'])) {
                $session = session();
                $session->set('isLoggedIn', 1);
                return redirect()->to('/');
            } else {
                return redirect()->back()->with('error', 'Invalid Password.');
            }
        } else {
            return redirect()->back()->with('error', 'Invalid Email.');
        }
    }

    public function logout()
    {
        $session = session();
        $session->remove('isLoggedIn');
        return redirect()->to('/login');
    }
}
