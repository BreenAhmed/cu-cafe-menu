<?php
/**
 * Admin login
 */

declare(strict_types=1);

require_once __DIR__ . '/../core/bootstrap.php';

if (admin_logged_in()) {
    redirect('/admin/index.php');
}

$error = '';

if (is_post()) {
    if (!verify_csrf($_POST['csrf_token'] ?? null)) {
        $error = 'Invalid security token. Please try again.';
    } else {
        $username = sanitize_string($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        $admin = new Admin();
        if ($username !== '' && $admin->attemptLogin($username, $password)) {
            redirect('/admin/index.php');
        }

        $error = 'Invalid username or password.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login · CU Cafe</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,700&family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= asset('css/admin.css') ?>">
    <link rel="icon" href="<?= asset('img/cu_logo.png') ?>" type="image/png">
</head>
<body class="login-page">
    <div class="login-card">
        <img src="<?= asset('img/cu_logo.png') ?>" alt="CU Cafe" style="width:auto;height:72px;background:transparent;">
        <h1>Admin</h1>
        <p>Sign in to manage CU Cafe</p>

        <?php if ($error): ?>
            <div class="alert alert-error"><?= e($error) ?></div>
        <?php endif; ?>

        <form method="post" autocomplete="off">
            <?= csrf_field() ?>
            <div class="form-group">
                <label for="username">Username</label>
                <input class="form-control" type="text" id="username" name="username" required autofocus>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input class="form-control" type="password" id="password" name="password" required>
            </div>
            <button class="btn btn-primary btn-block" type="submit">Sign In</button>
        </form>
    </div>
</body>
</html>
