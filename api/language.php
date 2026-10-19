<?php
/**
 * Set language via AJAX or redirect
 */

declare(strict_types=1);

require_once __DIR__ . '/../core/bootstrap.php';

$lang = $_POST['lang'] ?? $_GET['lang'] ?? '';

if (!in_array($lang, ['en', 'ar'], true)) {
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {
        json_response(['success' => false, 'message' => 'Invalid language'], 400);
    }
    redirect('/index.php');
}

set_lang($lang);

if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) || (isset($_SERVER['HTTP_ACCEPT']) && str_contains($_SERVER['HTTP_ACCEPT'], 'application/json'))) {
    json_response(['success' => true, 'lang' => $lang, 'dir' => lang_dir()]);
}

$redirect = $_GET['redirect'] ?? '/menu.php';
redirect($redirect);
