<?php
/**
 * Application Configuration
 */
return [
    'name'        => 'Barcode Review System',
    // IP Wi-Fi komputer ini. Gunakan alamat ini saat diakses dari HP pada Wi-Fi yang sama.
    'url'         => 'http://192.168.1.6/barcode-review-app/public',
    'debug'       => true,
    'timezone'    => 'Asia/Jakarta',
    'session_name'=> 'barcode_review_session',
    'upload_path' => __DIR__ . '/../public/uploads/',
    'qr_size'     => 300,
];
