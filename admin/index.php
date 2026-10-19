<?php
/**
 * Admin dashboard
 */

declare(strict_types=1);

require_once __DIR__ . '/../core/bootstrap.php';
require_admin();

$categoryModel = new Category();
$itemModel = new MenuItem();

$totalCategories = $categoryModel->count();
$totalItems = $itemModel->count();
$recentItems = $itemModel->recent(6);

$adminPage = 'dashboard';
$adminTitle = 'Dashboard';
require __DIR__ . '/includes/header.php';
?>

<div class="page-head">
    <div>
        <h1>Dashboard</h1>
        <p>Overview of your digital menu</p>
    </div>
</div>

<div class="stats">
    <div class="stat-card">
        <p class="stat-card__label">Categories</p>
        <p class="stat-card__value"><?= $totalCategories ?></p>
    </div>
    <div class="stat-card">
        <p class="stat-card__label">Menu Items</p>
        <p class="stat-card__value"><?= $totalItems ?></p>
    </div>
    <div class="stat-card">
        <p class="stat-card__label">Active Menu</p>
        <p class="stat-card__value" style="font-size:1.2rem;margin-top:14px;">
            <a class="btn btn-secondary btn-sm" href="<?= url('menu.php') ?>" target="_blank">Open Menu</a>
        </p>
    </div>
</div>

<div class="panel">
    <div class="panel__head">
        <h2>Recently Added Items</h2>
        <a class="btn btn-primary btn-sm" href="<?= url('admin/items.php') ?>">Manage Items</a>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Name (EN)</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Status</th>
                    <th>Added</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!$recentItems): ?>
                    <tr><td colspan="5">No items yet.</td></tr>
                <?php else: ?>
                    <?php foreach ($recentItems as $item): ?>
                        <tr>
                            <td><?= e($item['name_en']) ?></td>
                            <td><?= e($item['category_name_en'] ?? '—') ?></td>
                            <td><?= e(format_price($item['price'])) ?></td>
                            <td>
                                <span class="badge <?= (int) $item['status'] === 1 ? 'badge-on' : 'badge-off' ?>">
                                    <?= (int) $item['status'] === 1 ? 'Active' : 'Hidden' ?>
                                </span>
                            </td>
                            <td><?= e(date('M j, Y', strtotime($item['created_at']))) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
