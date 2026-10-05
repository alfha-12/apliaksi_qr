<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Place;

class HomeController extends Controller
{
    public function index()
    {
        $placeModel = new Place();
        $places = $placeModel->all();
        
        $this->view('home', [
            'places' => $places,
            'title'  => 'Beranda - Barcode Review'
        ]);
    }
}
