<?php
/**
 * Front Controller - Entry Point
 */

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start session
session_start();

// Autoload
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $baseDir = __DIR__ . '/../app/';
    
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    
    $relativeClass = substr($class, $len);
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';
    
    if (file_exists($file)) {
        require $file;
    }
});

// Load helpers
require_once __DIR__ . '/../app/Core/helpers.php';

// Load config
$appConfig = require __DIR__ . '/../config/app.php';
date_default_timezone_set($appConfig['timezone']);

// Setup Router
use App\Core\Router;

$router = new Router();

// ========== PUBLIC ROUTES ==========
$router->get('/', 'HomeController@index');
$router->get('/p/{code}', 'PlaceController@show');
$router->post('/p/{code}/review', 'PlaceController@submitReview');

// ========== ADMIN AUTH ==========
$router->get('/admin/login', 'AuthController@loginForm');
$router->post('/admin/login', 'AuthController@login');
$router->get('/admin/logout', 'AuthController@logout');

// ========== ADMIN ROUTES ==========
$router->get('/admin', 'AdminController@dashboard');
$router->get('/admin/places', 'AdminController@places');
$router->get('/admin/places/create', 'AdminController@createPlaceForm');
$router->post('/admin/places/create', 'AdminController@createPlace');
$router->get('/admin/places/{id}', 'AdminController@placeDetail');
$router->get('/admin/places/{id}/edit', 'AdminController@editPlaceForm');
$router->post('/admin/places/{id}/edit', 'AdminController@updatePlace');
$router->post('/admin/places/{id}/delete', 'AdminController@deletePlace');

$router->get('/admin/reviews', 'AdminController@reviews');
$router->post('/admin/reviews/{id}/approve', 'AdminController@approveReview');
$router->post('/admin/reviews/{id}/reject', 'AdminController@rejectReview');
$router->post('/admin/reviews/{id}/delete', 'AdminController@deleteReview');

// Dispatch
$router->dispatch();
