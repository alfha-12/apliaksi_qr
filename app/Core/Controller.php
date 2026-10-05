<?php
namespace App\Core;

class Controller
{
    protected function view(string $view, array $data = [])
    {
        extract($data);
        
        $viewFile = __DIR__ . '/../Views/' . $view . '.php';
        
        if (!file_exists($viewFile)) {
            die("View tidak ditemukan: {$view}");
        }
        
        require_once __DIR__ . '/../Views/layouts/header.php';
        require_once $viewFile;
        require_once __DIR__ . '/../Views/layouts/footer.php';
    }

    protected function viewAdmin(string $view, array $data = [])
    {
        extract($data);
        
        $viewFile = __DIR__ . '/../Views/' . $view . '.php';
        
        if (!file_exists($viewFile)) {
            die("View tidak ditemukan: {$view}");
        }
        
        require_once __DIR__ . '/../Views/layouts/admin_header.php';
        require_once $viewFile;
        require_once __DIR__ . '/../Views/layouts/admin_footer.php';
    }

    protected function redirect(string $url)
    {
        header('Location: ' . url($url));
        exit;
    }

    protected function json(array $data, int $status = 200)
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    protected function isLoggedIn(): bool
    {
        return isset($_SESSION['admin_id']);
    }

    protected function requireLogin()
    {
        if (!$this->isLoggedIn()) {
            $this->redirect('/admin/login');
        }
    }

    protected function setFlash(string $type, string $message)
    {
        $_SESSION['flash'] = ['type' => $type, 'message' => $message];
    }

    protected function getFlash()
    {
        if (isset($_SESSION['flash'])) {
            $flash = $_SESSION['flash'];
            unset($_SESSION['flash']);
            return $flash;
        }
        return null;
    }
}
