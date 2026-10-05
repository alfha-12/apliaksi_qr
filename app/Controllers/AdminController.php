<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Place;
use App\Models\Review;
use App\Models\Admin;

class AdminController extends Controller
{
    public function __construct()
    {
        // Auth check akan dilakukan di method masing-masing
    }

    public function dashboard()
    {
        $this->requireLogin();
        $adminId = (int) $_SESSION['admin_id'];
        
        $placeModel = new Place();
        $reviewModel = new Review();
        
        $stats = [
            'total_places'  => $placeModel->countByAdmin($adminId),
            'total_reviews' => $reviewModel->countAllByAdmin($adminId),
            'pending'       => $reviewModel->countPendingByAdmin($adminId),
            'review_stats'  => $reviewModel->getStatsByAdmin($adminId)
        ];
        
        $recentReviews = $reviewModel->allByAdmin($adminId, 10);
        $places = $placeModel->allByAdmin($adminId);
        $monthlyRecap = $reviewModel->getMonthlyRecapByAdmin($adminId);
        $yearlyRecap = $reviewModel->getYearlyRecapByAdmin($adminId);
        
        $this->viewAdmin('admin/dashboard', [
            'title'          => 'Dashboard Admin',
            'stats'          => $stats,
            'recentReviews'  => $recentReviews,
            'places'         => $places,
            'monthlyRecap'   => $monthlyRecap,
            'yearlyRecap'    => $yearlyRecap,
            'flash'          => $this->getFlash()
        ]);
    }

    // ========== PLACES ==========
    public function places()
    {
        $this->requireLogin();
        
        $placeModel = new Place();
        $places = $placeModel->allByAdmin((int) $_SESSION['admin_id']);
        
        $this->viewAdmin('admin/places', [
            'title'  => 'Kelola Tempat',
            'places' => $places,
            'flash'  => $this->getFlash()
        ]);
    }

    public function createPlaceForm()
    {
        $this->requireLogin();
        
        $placeModel = new Place();
        
        $this->viewAdmin('admin/place_form', [
            'title'      => 'Tambah Tempat Baru',
            'place'      => null,
            'categories' => $placeModel->getCategories(),
            'flash'      => $this->getFlash()
        ]);
    }

    public function createPlace()
    {
        $this->requireLogin();
        
        $placeModel = new Place();
        
        $name = trim($_POST['name'] ?? '');
        if (empty($name)) {
            $this->setFlash('error', 'Nama tempat wajib diisi.');
            $this->redirect('/admin/places/create');
            return;
        }
        
        $image = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $image = $this->uploadImage($_FILES['image']);
        }
        
        $id = $placeModel->create([
            'admin_id'    => (int) $_SESSION['admin_id'],
            'name'        => $name,
            'description' => trim($_POST['description'] ?? ''),
            'address'     => trim($_POST['address'] ?? ''),
            'category'    => $_POST['category'] ?? 'umum',
            'image'       => $image,
            'status'      => $_POST['status'] ?? 'active'
        ]);
        
        $this->setFlash('success', 'Tempat berhasil ditambahkan. QR Code siap dicetak.');
        $this->redirect('/admin/places/' . $id);
    }

    public function editPlaceForm(string $id)
    {
        $this->requireLogin();
        
        $placeModel = new Place();
        $place = $placeModel->findForAdmin((int)$id, (int) $_SESSION['admin_id']);
        
        if (!$place) {
            $this->setFlash('error', 'Tempat tidak ditemukan.');
            $this->redirect('/admin/places');
            return;
        }
        
        $this->viewAdmin('admin/place_form', [
            'title'      => 'Edit Tempat',
            'place'      => $place,
            'categories' => $placeModel->getCategories(),
            'flash'      => $this->getFlash()
        ]);
    }

    public function updatePlace(string $id)
    {
        $this->requireLogin();
        
        $placeModel = new Place();
        $place = $placeModel->findForAdmin((int)$id, (int) $_SESSION['admin_id']);
        
        if (!$place) {
            $this->setFlash('error', 'Tempat tidak ditemukan.');
            $this->redirect('/admin/places');
            return;
        }
        
        $data = [
            'name'        => trim($_POST['name'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'address'     => trim($_POST['address'] ?? ''),
            'category'    => $_POST['category'] ?? 'umum',
            'status'      => $_POST['status'] ?? 'active'
        ];
        
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $data['image'] = $this->uploadImage($_FILES['image']);
        }
        
        $placeModel->updateForAdmin((int)$id, (int) $_SESSION['admin_id'], $data);
        
        $this->setFlash('success', 'Tempat berhasil diperbarui!');
        $this->redirect('/admin/places');
    }

    public function deletePlace(string $id)
    {
        $this->requireLogin();
        
        $placeModel = new Place();
        $placeModel->deleteForAdmin((int)$id, (int) $_SESSION['admin_id']);
        
        $this->setFlash('success', 'Tempat berhasil dihapus.');
        $this->redirect('/admin/places');
    }

    public function placeDetail(string $id)
    {
        $this->requireLogin();
        
        $placeModel = new Place();
        $reviewModel = new Review();
        
        $place = $placeModel->findForAdmin((int)$id, (int) $_SESSION['admin_id']);
        
        if (!$place) {
            $this->setFlash('error', 'Tempat tidak ditemukan.');
            $this->redirect('/admin/places');
            return;
        }
        
        $reviews = $reviewModel->getByPlace((int)$id, false); // Semua review termasuk pending
        
        $config = require __DIR__ . '/../../config/app.php';
        $qrUrl = $config['url'] . '/p/' . $place['barcode_code'];
        
        $this->viewAdmin('admin/place_detail', [
            'title'  => 'Detail: ' . $place['name'],
            'place'  => $place,
            'reviews'=> $reviews,
            'qrUrl'  => $qrUrl,
            'flash'  => $this->getFlash()
        ]);
    }

    // ========== REVIEWS ==========
    public function reviews()
    {
        $this->requireLogin();
        
        $reviewModel = new Review();
        $reviews = $reviewModel->allByAdmin((int) $_SESSION['admin_id'], 100);
        
        $this->viewAdmin('admin/reviews', [
            'title'   => 'Kelola Ulasan',
            'reviews' => $reviews,
            'flash'   => $this->getFlash()
        ]);
    }

    public function approveReview(string $id)
    {
        $this->requireLogin();
        
        $reviewModel = new Review();
        $reviewModel->approveForAdmin((int)$id, (int) $_SESSION['admin_id']);
        
        $this->setFlash('success', 'Ulasan berhasil disetujui.');
        $this->redirect('/admin/reviews');
    }

    public function rejectReview(string $id)
    {
        $this->requireLogin();
        
        $reviewModel = new Review();
        $reviewModel->rejectForAdmin((int)$id, (int) $_SESSION['admin_id']);
        
        $this->setFlash('success', 'Ulasan berhasil ditolak/dihapus.');
        $this->redirect('/admin/reviews');
    }

    public function deleteReview(string $id)
    {
        $this->requireLogin();
        
        $reviewModel = new Review();
        $reviewModel->deleteForAdmin((int)$id, (int) $_SESSION['admin_id']);
        
        $this->setFlash('success', 'Ulasan berhasil dihapus.');
        $this->redirect('/admin/reviews');
    }

    // ========== HELPERS ==========
    private function uploadImage(array $file): ?string
    {
        $allowed = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
        
        if (!in_array($file['type'], $allowed)) {
            return null;
        }
        
        if ($file['size'] > 2 * 1024 * 1024) { // Max 2MB
            return null;
        }
        
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid('place_') . '.' . $ext;
        $dest = __DIR__ . '/../../public/uploads/' . $filename;
        
        if (move_uploaded_file($file['tmp_name'], $dest)) {
            return $filename;
        }
        
        return null;
    }
}
