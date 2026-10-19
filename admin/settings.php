<?php
/**
 * Cafe settings
 */

declare(strict_types=1);

require_once __DIR__ . '/../core/bootstrap.php';
require_admin();

$model = new Setting();
$settings = $model->get();

if (is_post()) {
    if (!verify_csrf($_POST['csrf_token'] ?? null)) {
        flash('error', 'Invalid security token.');
        redirect('/admin/settings.php');
    }

    $logoName = $settings['logo'] ?? '';

    if (!empty($_FILES['logo']['name']) && is_uploaded_file($_FILES['logo']['tmp_name'])) {
        $file = $_FILES['logo'];
        $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp', 'image/svg+xml' => 'svg'];
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($file['tmp_name']) ?: '';

        if (!isset($allowed[$mime])) {
            flash('error', 'Logo must be JPG, PNG, WEBP, or SVG.');
            redirect('/admin/settings.php');
        }

        if ($file['size'] > 2 * 1024 * 1024) {
            flash('error', 'Logo must be under 2MB.');
            redirect('/admin/settings.php');
        }

        if (!is_dir(UPLOAD_PATH)) {
            mkdir(UPLOAD_PATH, 0755, true);
        }

        $logoName = 'logo_' . time() . '.' . $allowed[$mime];
        $dest = UPLOAD_PATH . '/' . $logoName;

        if (!move_uploaded_file($file['tmp_name'], $dest)) {
            flash('error', 'Failed to upload logo.');
            redirect('/admin/settings.php');
        }

        // Remove previous custom logo if present
        if (!empty($settings['logo'])) {
            $old = UPLOAD_PATH . '/' . basename($settings['logo']);
            if (is_file($old)) {
                @unlink($old);
            }
        }
    }

    $primary = sanitize_string($_POST['primary_color'] ?? '#226F54');
    $secondary = sanitize_string($_POST['secondary_color'] ?? '#D33C3C');

    if (!preg_match('/^#[0-9A-Fa-f]{6}$/', $primary)) {
        $primary = '#226F54';
    }
    if (!preg_match('/^#[0-9A-Fa-f]{6}$/', $secondary)) {
        $secondary = '#D33C3C';
    }

    $model->update([
        'cafe_name' => sanitize_string($_POST['cafe_name'] ?? 'CU Cafe'),
        'cafe_name_ar' => sanitize_string($_POST['cafe_name_ar'] ?? 'سي يو'),
        'logo' => $logoName,
        'whatsapp' => sanitize_string($_POST['whatsapp'] ?? ''),
        'facebook' => '',
        'instagram' => sanitize_string($_POST['instagram'] ?? ''),
        'primary_color' => $primary,
        'secondary_color' => $secondary,
        'welcome_text' => sanitize_string($_POST['welcome_text'] ?? ''),
        'welcome_text_ar' => sanitize_string($_POST['welcome_text_ar'] ?? ''),
        'contact_info' => '',
    ]);

    flash('success', 'Settings saved.');
    redirect('/admin/settings.php');
}

$logoUrl = logo_url($settings);

$adminPage = 'settings';
$adminTitle = 'Settings';
require __DIR__ . '/includes/header.php';
?>

<div class="page-head">
    <div>
        <h1>Settings</h1>
        <p>Brand, contact, and appearance</p>
    </div>
</div>

<div class="panel">
    <div class="panel__body">
        <form method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <div class="form-grid">
                <div class="form-group">
                    <label>Cafe Name (English)</label>
                    <input class="form-control" type="text" name="cafe_name" value="<?= e($settings['cafe_name'] ?? '') ?>" required>
                </div>
                <div class="form-group">
                    <label>Cafe Name (Arabic)</label>
                    <input class="form-control" type="text" name="cafe_name_ar" value="<?= e($settings['cafe_name_ar'] ?? '') ?>" dir="rtl" required>
                </div>
                <div class="form-group">
                    <label>WhatsApp Number</label>
                    <input class="form-control" type="text" name="whatsapp" value="<?= e($settings['whatsapp'] ?? '') ?>" placeholder="9647XXXXXXXXX">
                </div>
                <div class="form-group">
                    <label>Instagram URL</label>
                    <input class="form-control" type="url" name="instagram" value="<?= e($settings['instagram'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label>Primary Color</label>
                    <input class="form-control" type="color" name="primary_color" value="<?= e($settings['primary_color'] ?? '#226F54') ?>">
                </div>
                <div class="form-group">
                    <label>Secondary Color</label>
                    <input class="form-control" type="color" name="secondary_color" value="<?= e($settings['secondary_color'] ?? '#D33C3C') ?>">
                </div>
                <div class="form-group">
                    <label>Welcome Message (English)</label>
                    <textarea class="form-control" name="welcome_text"><?= e($settings['welcome_text'] ?? '') ?></textarea>
                </div>
                <div class="form-group">
                    <label>Welcome Message (Arabic)</label>
                    <textarea class="form-control" name="welcome_text_ar" dir="rtl"><?= e($settings['welcome_text_ar'] ?? '') ?></textarea>
                </div>
                <div class="form-group full">
                    <label>Logo</label>
                    <div style="display:flex;align-items:center;gap:16px;flex-wrap:wrap;margin-bottom:10px;">
                        <img src="<?= e($logoUrl) ?>" alt="Logo preview" style="height:64px;width:auto;max-width:180px;object-fit:contain;background:transparent;">
                        <input class="form-control" type="file" name="logo" accept=".jpg,.jpeg,.png,.webp,.svg,image/*" style="max-width:320px;">
                    </div>
                    <small style="color:rgba(67,41,31,.55);">Optional. JPG, PNG, WEBP, or SVG. Max 2MB.</small>
                </div>
            </div>
            <div style="margin-top:18px;">
                <button type="submit" class="btn btn-primary">Save Settings</button>
            </div>
        </form>
    </div>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
