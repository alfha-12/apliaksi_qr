<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? 'Admin') ?> - Barcode Review</title>
    <link rel="stylesheet" href="<?= e(asset('css/style.css')) ?>">
</head>
<body>
<?php if (isset($_SESSION['admin_id'])): ?>
<div class="admin-layout">
    <aside class="sidebar">
        <div class="sidebar-brand">Admin Panel</div>
        <ul class="sidebar-nav">
            <li><a href="<?= e(url('admin')) ?>" class="<?= parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) === parse_url(url('admin'), PHP_URL_PATH) ? 'active' : '' ?>">Dashboard</a></li>
            <li><a href="<?= e(url('admin/places')) ?>" class="<?= strpos($_SERVER['REQUEST_URI'], '/admin/places') !== false ? 'active' : '' ?>">Kelola Tempat</a></li>
            <li><a href="<?= e(url('admin/reviews')) ?>" class="<?= strpos($_SERVER['REQUEST_URI'], '/admin/reviews') !== false ? 'active' : '' ?>">Kelola Ulasan</a></li>
            <li><a href="<?= e(url()) ?>" target="_blank">Lihat Website</a></li>
            <li><a href="<?= e(url('admin/logout')) ?>">Logout</a></li>
        </ul>
    </aside>
    <div class="admin-content">
        <div class="admin-header">
            <h2 style="font-size:1.15rem;font-weight:600;"><?= e($title ?? 'Admin') ?></h2>
            <span style="color:var(--text-muted);font-size:0.9rem;">
                Halo, <strong><?= e($_SESSION['admin_name'] ?? 'Admin') ?></strong>
            </span>
        </div>
        <div class="admin-body">
            <?php if ($flash = $flash ?? null): ?>
                <div class="alert alert-<?= e($flash['type']) ?>">
                    <?= e($flash['message']) ?>
                </div>
            <?php endif; ?>
<?php else: ?>
    <!-- Login page tidak pakai sidebar -->
<?php endif; ?>
