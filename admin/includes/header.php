<?php
declare(strict_types=1);

$adminPage = $adminPage ?? 'dashboard';
$adminTitle = $adminTitle ?? 'Dashboard';
$flash = get_flash();
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($adminTitle) ?> · CU Cafe Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600;9..144,700&family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= asset('css/admin.css') ?>">
    <link rel="icon" href="<?= asset('img/cu_logo.png') ?>" type="image/png">
</head>
<body>
<button type="button" class="mobile-toggle" id="sidebarToggle" aria-label="Menu">☰</button>
<div class="admin-shell">
    <aside class="sidebar" id="sidebar">
        <div class="sidebar__brand">
            <img src="<?= asset('img/cu_logo.png') ?>" alt="" style="object-fit:contain;background:transparent;">
            <span>CU Cafe</span>
        </div>
        <nav>
            <a class="nav-link <?= $adminPage === 'dashboard' ? 'is-active' : '' ?>" href="<?= url('admin/index.php') ?>">Dashboard</a>
            <a class="nav-link <?= $adminPage === 'categories' ? 'is-active' : '' ?>" href="<?= url('admin/categories.php') ?>">Categories</a>
            <a class="nav-link <?= $adminPage === 'items' ? 'is-active' : '' ?>" href="<?= url('admin/items.php') ?>">Menu Items</a>
            <a class="nav-link <?= $adminPage === 'settings' ? 'is-active' : '' ?>" href="<?= url('admin/settings.php') ?>">Settings</a>
            <a class="nav-link" href="<?= url('menu.php') ?>" target="_blank">View Menu</a>
        </nav>
        <div class="sidebar__foot">
            <p style="margin:0 0 10px;font-size:.85rem;opacity:.75;">
                Signed in as <?= e($_SESSION['wl_admin_user'] ?? 'admin') ?>
            </p>
            <a class="nav-link" href="<?= url('admin/logout.php') ?>">Logout</a>
        </div>
    </aside>
    <div class="admin-main">
        <?php if ($flash): ?>
            <div class="alert alert-<?= e($flash['type'] === 'success' ? 'success' : 'error') ?>">
                <?= e($flash['message']) ?>
            </div>
        <?php endif; ?>
