<?php
/**
 * Category management
 */

declare(strict_types=1);

require_once __DIR__ . '/../core/bootstrap.php';
require_admin();

$model = new Category();

if (is_post()) {
    if (!verify_csrf($_POST['csrf_token'] ?? null)) {
        flash('error', 'Invalid security token.');
        redirect('/admin/categories.php');
    }

    $action = $_POST['action'] ?? '';

    if ($action === 'create' || $action === 'update') {
        $data = [
            'name_en' => sanitize_string($_POST['name_en'] ?? ''),
            'name_ar' => sanitize_string($_POST['name_ar'] ?? ''),
            'sort_order' => (int) ($_POST['sort_order'] ?? 0),
            'status' => (int) ($_POST['status'] ?? 1) === 1 ? 1 : 0,
        ];

        if ($data['name_en'] === '' || $data['name_ar'] === '') {
            flash('error', 'English and Arabic names are required.');
            redirect('/admin/categories.php');
        }

        if ($action === 'create') {
            $model->create($data);
            flash('success', 'Category created.');
        } else {
            $id = (int) ($_POST['id'] ?? 0);
            if ($id > 0 && $model->find($id)) {
                $model->update($id, $data);
                flash('success', 'Category updated.');
            } else {
                flash('error', 'Category not found.');
            }
        }
    }

    if ($action === 'delete') {
        $id = (int) ($_POST['id'] ?? 0);
        if ($id > 0) {
            $model->delete($id);
            flash('success', 'Category deleted.');
        }
    }

    redirect('/admin/categories.php');
}

$categories = $model->all();
$adminPage = 'categories';
$adminTitle = 'Categories';
require __DIR__ . '/includes/header.php';
?>

<div class="page-head">
    <div>
        <h1>Categories</h1>
        <p>Organize your menu sections</p>
    </div>
    <button type="button" class="btn btn-primary" data-open-modal="createModal">Add Category</button>
</div>

<div class="panel">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>EN Name</th>
                    <th>AR Name</th>
                    <th>Order</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!$categories): ?>
                    <tr><td colspan="5">No categories yet.</td></tr>
                <?php else: ?>
                    <?php foreach ($categories as $cat): ?>
                        <tr>
                            <td><?= e($cat['name_en']) ?></td>
                            <td dir="rtl"><?= e($cat['name_ar']) ?></td>
                            <td><?= (int) $cat['sort_order'] ?></td>
                            <td>
                                <span class="badge <?= (int) $cat['status'] === 1 ? 'badge-on' : 'badge-off' ?>">
                                    <?= (int) $cat['status'] === 1 ? 'Active' : 'Hidden' ?>
                                </span>
                            </td>
                            <td>
                                <div class="actions">
                                    <button
                                        type="button"
                                        class="btn btn-secondary btn-sm"
                                        data-edit
                                        data-open-modal="editModal"
                                        data-id="<?= (int) $cat['id'] ?>"
                                        data-name_en="<?= e($cat['name_en']) ?>"
                                        data-name_ar="<?= e($cat['name_ar']) ?>"
                                        data-sort_order="<?= (int) $cat['sort_order'] ?>"
                                        data-status="<?= (int) $cat['status'] ?>"
                                    >Edit</button>
                                    <form method="post" onsubmit="return confirm('Delete this category and its items?');">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?= (int) $cat['id'] ?>">
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

<div class="modal-backdrop" id="createModal">
    <div class="modal">
        <h2>Add Category</h2>
        <form method="post">
            <?= csrf_field() ?>
            <input type="hidden" name="action" value="create">
            <div class="form-grid">
                <div class="form-group">
                    <label>Name (English)</label>
                    <input class="form-control" type="text" name="name_en" required>
                </div>
                <div class="form-group">
                    <label>Name (Arabic)</label>
                    <input class="form-control" type="text" name="name_ar" dir="rtl" required>
                </div>
                <div class="form-group">
                    <label>Sort Order</label>
                    <input class="form-control" type="number" name="sort_order" value="0">
                </div>
                <div class="form-group">
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
        <h2>Edit Category</h2>
        <form method="post">
            <?= csrf_field() ?>
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="id" data-field="id" value="">
            <div class="form-grid">
                <div class="form-group">
                    <label>Name (English)</label>
                    <input class="form-control" type="text" name="name_en" data-field="name_en" required>
                </div>
                <div class="form-group">
                    <label>Name (Arabic)</label>
                    <input class="form-control" type="text" name="name_ar" data-field="name_ar" dir="rtl" required>
                </div>
                <div class="form-group">
                    <label>Sort Order</label>
                    <input class="form-control" type="number" name="sort_order" data-field="sort_order" value="0">
                </div>
                <div class="form-group">
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
