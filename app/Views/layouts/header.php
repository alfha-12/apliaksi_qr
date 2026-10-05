<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? 'Barcode Review System') ?></title>
    <link rel="stylesheet" href="<?= e(asset('css/style.css')) ?>">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <a href="<?= e(url()) ?>" class="navbar-brand">
                📱 Barcode Review
            </a>
            <ul class="navbar-nav">
                <li><a href="<?= e(url()) ?>">Beranda</a></li>
                <li><a href="<?= e(url('admin/login')) ?>">Admin</a></li>
            </ul>
        </div>
    </nav>
    
    <main class="container">
        <?php if ($flash = $flash ?? null): ?>
            <div class="alert alert-<?= e($flash['type']) ?>">
                <?= e($flash['message']) ?>
            </div>
        <?php endif; ?>
