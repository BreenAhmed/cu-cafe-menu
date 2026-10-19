<?php
/**
 * Menu item management
 */

declare(strict_types=1);

require_once __DIR__ . '/../core/bootstrap.php';
require_admin();

$itemModel = new MenuItem();
$categoryModel = new Category();

if (is_post()) {
    if (!verify_csrf($_POST['csrf_token'] ?? null)) {
        flash('error', 'Invalid security token.');
        redirect('/admin/items.php');
    }

    $action = $_POST['action'] ?? '';

    if ($action === 'create' || $action === 'update') {
        $data = [
            'category_id' => (int) ($_POST['category_id'] ?? 0),
            'name_en' => sanitize_string($_POST['name_en'] ?? ''),
            'name_ar' => sanitize_string($_POST['name_ar'] ?? ''),
            'description_en' => sanitize_string($_POST['description_en'] ?? ''),
            'description_ar' => sanitize_string($_POST['description_ar'] ?? ''),
            'price' => $_POST['price'] ?? '',
            'status' => (int) ($_POST['status'] ?? 1) === 1 ? 1 : 0,
            'sort_order' => (int) ($_POST['sort_order'] ?? 0),
        ];

        if (
            $data['category_id'] <= 0 ||
            $data['name_en'] === '' ||
            $data['name_ar'] === '' ||
            !validate_price($data['price'])
        ) {
            flash('error', 'Please fill required fields with a valid price.');
            redirect('/admin/items.php');
        }

        $data['price'] = number_format((float) $data['price'], 2, '.', '');

        if ($action === 'create') {
            $itemModel->create($data);
            flash('success', 'Item created.');
        } else {
            $id = (int) ($_POST['id'] ?? 0);
            if ($id > 0 && $itemModel->find($id)) {
                $itemModel->update($id, $data);
                flash('success', 'Item updated.');
            } else {
                flash('error', 'Item not found.');
            }
        }
    }

    if ($action === 'delete') {
        $id = (int) ($_POST['id'] ?? 0);
        if ($id > 0) {
            $itemModel->delete($id);
            flash('success', 'Item deleted.');
        }
    }

    redirect('/admin/items.php');
}

$items = $itemModel->all();
$categories = $categoryModel->all();

$adminPage = 'items';
$adminTitle = 'Menu Items';
require __DIR__ . '/includes/header.php';
?>

<div class="page-head">
    <div>
        <h1>Menu Items</h1>
        <p>Add and edit drinks & bites</p>
    </div>
    <button type="button" class="btn btn-primary" data-open-modal="createModal" <?= !$categories ? 'disabled' : '' ?>>
        Add Item
    </button>
</div>

<?php if (!$categories): ?>
    <div class="alert alert-error">Create at least one category before adding items.</div>
<?php endif; ?>

<div class="panel">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>EN Name</th>
                    <th>AR Name</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Order</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!$items): ?>
                    <tr><td colspan="7">No items yet.</td></tr>
                <?php else: ?>
                    <?php foreach ($items as $item): ?>
                        <tr>
                            <td>
                                <strong><?= e($item['name_en']) ?></strong>
                                <?php if ($item['description_en']): ?>
                                    <div style="font-size:.85rem;color:rgba(67,41,31,.55);margin-top:4px;">
                                        <?= e(strlen($item['description_en']) > 60 ? substr($item['description_en'], 0, 57) . '…' : $item['description_en']) ?>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td dir="rtl"><?= e($item['name_ar']) ?></td>
                            <td><?= e($item['category_name_en'] ?? '—') ?></td>
                            <td><?= e(format_price($item['price'])) ?></td>
                            <td><?= (int) $item['sort_order'] ?></td>
                            <td>
                                <span class="badge <?= (int) $item['status'] === 1 ? 'badge-on' : 'badge-off' ?>">
                                    <?= (int) $item['status'] === 1 ? 'Active' : 'Hidden' ?>
                                </span>
                            </td>
                            <td>
                                <div class="actions">
                                    <button
                                        type="button"
                                        class="btn btn-secondary btn-sm"
                                        data-edit
                                        data-open-modal="editModal"
                                        data-id="<?= (int) $item['id'] ?>"
                                        data-category_id="<?= (int) $item['category_id'] ?>"
                                        data-name_en="<?= e($item['name_en']) ?>"
                                        data-name_ar="<?= e($item['name_ar']) ?>"
                                        data-description_en="<?= e($item['description_en'] ?? '') ?>"
                                        data-description_ar="<?= e($item['description_ar'] ?? '') ?>"
                                        data-price="<?= e((string) $item['price']) ?>"
                                        data-sort_order="<?= (int) $item['sort_order'] ?>"
                                        data-status="<?= (int) $item['status'] ?>"
                                    >Edit</button>
                                    <form method="post" onsubmit="return confirm('Delete this item?');">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?= (int) $item['id'] ?>">
                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
$categoryOptions = static function (array $categories, $selected = null): string {
    $html = '';
    foreach ($categories as $cat) {
        $sel = ((int) $cat['id'] === (int) $selected) ? ' selected' : '';
        $html .= '<option value="' . (int) $cat['id'] . '"' . $sel . '>' . e($cat['name_en']) . '</option>';
    }
    return $html;
};
?>

<div class="modal-backdrop" id="createModal">
    <div class="modal">
        <h2>Add Item</h2>
        <form method="post">
            <?= csrf_field() ?>
            <input type="hidden" name="action" value="create">
            <div class="form-grid">
                <div class="form-group full">
                    <label>Category</label>
                    <select class="form-control" name="category_id" required>
                        <option value="">Select category</option>
                        <?= $categoryOptions($categories) ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Name (English)</label>
                    <input class="form-control" type="text" name="name_en" required>
                </div>
                <div class="form-group">
                    <label>Name (Arabic)</label>
                    <input class="form-control" type="text" name="name_ar" dir="rtl" required>
                </div>
                <div class="form-group">
                    <label>Description (English)</label>
                    <textarea class="form-control" name="description_en"></textarea>
                </div>
                <div class="form-group">
                    <label>Description (Arabic)</label>
                    <textarea class="form-control" name="description_ar" dir="rtl"></textarea>
                </div>
                <div class="form-group">
                    <label>Price (IQD)</label>
                    <input class="form-control" type="number" name="price" min="0" step="100" required>
                </div>
                <div class="form-group">
                    <label>Display Order</label>
                    <input class="form-control" type="number" name="sort_order" value="0">
                </div>
                <div class="form-group full">
                    <label>Status</label>
                    <select class="form-control" name="status">
                        <option value="1">Active</option>
                        <option value="0">Hidden</option>
                    </select>
                </div>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn btn-secondary" data-close-modal>Cancel</button>
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </form>
    </div>
</div>

<div class="modal-backdrop" id="editModal">
    <div class="modal">
        <h2>Edit Item</h2>
        <form method="post">
            <?= csrf_field() ?>
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="id" data-field="id" value="">
            <div class="form-grid">
                <div class="form-group full">
                    <label>Category</label>
                    <select class="form-control" name="category_id" data-field="category_id" required>
                        <?= $categoryOptions($categories) ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Name (English)</label>
                    <input class="form-control" type="text" name="name_en" data-field="name_en" required>
                </div>
                <div class="form-group">
                    <label>Name (Arabic)</label>
                    <input class="form-control" type="text" name="name_ar" data-field="name_ar" dir="rtl" required>
                </div>
                <div class="form-group">
                    <label>Description (English)</label>
                    <textarea class="form-control" name="description_en" data-field="description_en"></textarea>
                </div>
                <div class="form-group">
                    <label>Description (Arabic)</label>
                    <textarea class="form-control" name="description_ar" data-field="description_ar" dir="rtl"></textarea>
                </div>
                <div class="form-group">
                    <label>Price (IQD)</label>
                    <input class="form-control" type="number" name="price" data-field="price" min="0" step="100" required>
                </div>
                <div class="form-group">
                    <label>Display Order</label>
                    <input class="form-control" type="number" name="sort_order" data-field="sort_order" value="0">
                </div>
                <div class="form-group full">
                    <label>Status</label>
                    <select class="form-control" name="status" data-field="status">
                        <option value="1">Active</option>
                        <option value="0">Hidden</option>
                    </select>
                </div>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn btn-secondary" data-close-modal>Cancel</button>
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </form>
    </div>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
