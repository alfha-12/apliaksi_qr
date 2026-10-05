<?php
/**
 * Helper Functions
 */

function e(?string $string): string
{
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}

function asset(string $path): string
{
    $config = require __DIR__ . '/../../config/app.php';
    return rtrim($config['url'], '/') . '/' . ltrim($path, '/');
}

function url(string $path = ''): string
{
    $config = require __DIR__ . '/../../config/app.php';
    return rtrim($config['url'], '/') . '/' . ltrim($path, '/');
}

function starRating(float $rating): string
{
    $full = floor($rating);
    $half = ($rating - $full) >= 0.5 ? 1 : 0;
    $empty = 5 - $full - $half;
    
    $html = '';
    for ($i = 0; $i < $full; $i++) {
        $html .= '<span class="star full">★</span>';
    }
    if ($half) {
        $html .= '<span class="star half">★</span>';
    }
    for ($i = 0; $i < $empty; $i++) {
        $html .= '<span class="star empty">☆</span>';
    }
    return $html;
}

function timeAgo(string $datetime): string
{
    $time = strtotime($datetime);
    $diff = time() - $time;
    
    if ($diff < 60) return 'baru saja';
    if ($diff < 3600) return floor($diff / 60) . ' menit yang lalu';
    if ($diff < 86400) return floor($diff / 3600) . ' jam yang lalu';
    if ($diff < 2592000) return floor($diff / 86400) . ' hari yang lalu';
    
    return date('d M Y', $time);
}

function generateCode(int $length = 8): string
{
    return strtoupper(substr(bin2hex(random_bytes($length)), 0, $length));
}
