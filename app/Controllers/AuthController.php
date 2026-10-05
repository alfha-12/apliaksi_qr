<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Admin;

class AuthController extends Controller
{
    public function loginForm()
    {
        if ($this->isLoggedIn()) {
            $this->redirect('/admin');
        }
        
        $this->viewAdmin('auth/login', [
            'title' => 'Login Admin',
            'flash' => $this->getFlash()
        ]);
    }

    public function login()
    {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        
        if (empty($username) || empty($password)) {
            $this->setFlash('error', 'Username dan password wajib diisi.');
            $this->redirect('/admin/login');
            return;
        }
        
        $adminModel = new Admin();
        $admin = $adminModel->findByUsername($username);
        
        if (!$admin || !$adminModel->verifyPassword($password, $admin['password'])) {
            $this->setFlash('error', 'Username atau password salah.');
            $this->redirect('/admin/login');
            return;
        }
        
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_name'] = $admin['name'];
        $_SESSION['admin_username'] = $admin['username'];
        
        $this->redirect('/admin');
    }

    public function logout()
    {
        session_destroy();
        $this->redirect('/admin/login');
    }
}
