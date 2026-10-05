<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Place;
use App\Models\Review;

class PlaceController extends Controller
{
    public function show(string $code)
    {
        $placeModel = new Place();
        $reviewModel = new Review();
        
        $place = $placeModel->findByCode($code);
        
        if (!$place) {
            http_response_code(404);
            $this->view('places/not_found', [
                'title' => 'Tempat Tidak Ditemukan'
            ]);
            return;
        }
        
        $reviews = $reviewModel->getByPlace($place['id'], true);
        
        $this->view('places/show', [
            'place'   => $place,
            'reviews' => $reviews,
            'title'   => $place['name'] . ' - Ulasan',
            'flash'   => $this->getFlash()
        ]);
    }

    public function submitReview(string $code)
    {
        $placeModel = new Place();
        $reviewModel = new Review();
        
        $place = $placeModel->findByCode($code);
        
        if (!$place) {
            $this->setFlash('error', 'Tempat tidak ditemukan.');
            $this->redirect('/');
            return;
        }
        
        $rating = (int)($_POST['rating'] ?? 0);
        $name = trim($_POST['reviewer_name'] ?? '');
        $comment = trim($_POST['comment'] ?? '');
        
        if ($rating < 1 || $rating > 5) {
            $this->setFlash('error', 'Rating harus antara 1-5.');
            $this->redirect('/p/' . $code);
            return;
        }
        
        if (empty($comment)) {
            $this->setFlash('error', 'Komentar tidak boleh kosong.');
            $this->redirect('/p/' . $code);
            return;
        }
        
        $reviewModel->create([
            'place_id'      => $place['id'],
            'reviewer_name' => $name ?: 'Anonim',
            'rating'        => $rating,
            'comment'       => $comment,
            'is_approved'   => 0  // Perlu approval admin
        ]);
        
        $this->setFlash('success', 'Terima kasih! Ulasan Anda berhasil dikirim dan menunggu persetujuan admin.');
        $this->redirect('/p/' . $code);
    }
}
