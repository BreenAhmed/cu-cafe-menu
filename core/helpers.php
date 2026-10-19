<?php
/**
 * Global helper functions
 */

declare(strict_types=1);

function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function redirect(string $path): never
{
    $url = str_starts_with($path, 'http') ? $path : BASE_URL . $path;
    header('Location: ' . $url);
    exit;
}

function url(string $path = ''): string
{
    return BASE_URL . '/' . ltrim($path, '/');
}

function asset(string $path): string
{
    return url('assets/' . ltrim($path, '/'));
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="csrf_token" value="' . e(csrf_token()) . '">';
}

function verify_csrf(?string $token): bool
{
    return is_string($token)
        && isset($_SESSION['csrf_token'])
        && hash_equals($_SESSION['csrf_token'], $token);
}

function get_lang(): string
{
    if (!empty($_SESSION[LANG_SESSION_KEY]) && in_array($_SESSION[LANG_SESSION_KEY], ['en', 'ar'], true)) {
        return $_SESSION[LANG_SESSION_KEY];
    }
    return DEFAULT_LANG;
}

function set_lang(string $lang): void
{
    $_SESSION[LANG_SESSION_KEY] = in_array($lang, ['en', 'ar'], true) ? $lang : DEFAULT_LANG;
}

function is_rtl(): bool
{
    return get_lang() === 'ar';
}

function lang_dir(): string
{
    return is_rtl() ? 'rtl' : 'ltr';
}

function t(string $key, ?array $replace = null): string
{
    static $translations = null;

    if ($translations === null) {
        $lang = get_lang();
        $file = BASE_PATH . '/lang/' . $lang . '.php';
        $translations = file_exists($file) ? require $file : [];
    }

    $text = $translations[$key] ?? $key;

    if ($replace) {
        foreach ($replace as $search => $value) {
            $text = str_replace(':' . $search, (string) $value, $text);
        }
    }

    return $text;
}

function localized(array $row, string $field): string
{
    $lang = get_lang();
    $key = $field . '_' . $lang;
    $fallback = $field . '_en';
    return (string) ($row[$key] ?? $row[$fallback] ?? '');
}

function cafe_name(array $settings): string
{
    if (get_lang() === 'ar') {
        return (string) ($settings['cafe_name_ar'] ?: ($settings['cafe_name'] ?? APP_NAME_AR));
    }

    return (string) ($settings['cafe_name'] ?: APP_NAME);
}

function logo_url(array $settings): string
{
    $logo = trim((string) ($settings['logo'] ?? ''));

    if ($logo !== '') {
        $uploadPath = UPLOAD_PATH . '/' . basename($logo);
        if (is_file($uploadPath)) {
            return url('uploads/' . basename($logo));
        }

        $assetPath = BASE_PATH . '/assets/img/' . basename($logo);
        if (is_file($assetPath)) {
            return asset('img/' . basename($logo));
        }
    }

    if (is_file(BASE_PATH . '/assets/img/cu_logo.png')) {
        return asset('img/cu_logo.png');
    }

    return asset('img/logo.svg');
}

function format_price(float|string $price): string
{
    return number_format((float) $price, 0);
}

function flash(string $type, string $message): void
{
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function get_flash(): ?array
{
    if (!empty($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

function is_post(): bool
{
    return $_SERVER['REQUEST_METHOD'] === 'POST';
}

function json_response(array $data, int $status = 200): never
{
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

function require_admin(): void
{
    if (empty($_SESSION[ADMIN_SESSION_KEY])) {
        redirect('/admin/login.php');
    }
}

function admin_logged_in(): bool
{
    return !empty($_SESSION[ADMIN_SESSION_KEY]);
}

function sanitize_string(?string $value): string
{
    return trim(strip_tags($value ?? ''));
}

function validate_price($value): bool
{
    return is_numeric($value) && (float) $value >= 0;
}
