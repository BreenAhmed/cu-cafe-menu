<?php
/**
 * Language welcome screen
 */

declare(strict_types=1);

require_once __DIR__ . '/core/bootstrap.php';

// Skip welcome if language already chosen
if (!empty($_SESSION[LANG_SESSION_KEY]) && empty($_GET['change'])) {
    redirect('/menu.php');
}

$settings = (new Setting())->get();
$cafeNameEn = $settings['cafe_name'] ?: APP_NAME;
$cafeNameAr = $settings['cafe_name_ar'] ?: APP_NAME_AR;
$logoUrl = logo_url($settings);
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="theme-color" content="#226F54">
    <meta name="description" content="<?= e($cafeNameEn) ?> — Digital Menu">
    <title><?= e($cafeNameEn) ?> · Choose Language</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,700&family=Nunito:wght@400;600;700&family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>">
    <link rel="icon" href="<?= e($logoUrl) ?>" type="image/png">
</head>
<body class="welcome-page">
    <div class="welcome-bg" aria-hidden="true">
        <div class="welcome-blob welcome-blob--1"></div>
        <div class="welcome-blob welcome-blob--2"></div>
        <div class="welcome-blob welcome-blob--3"></div>
        <div class="seed-field"></div>
    </div>

    <main class="welcome-shell">
        <div class="welcome-card fade-up">
            <div class="welcome-logo">
                <img src="<?= e($logoUrl) ?>" alt="<?= e($cafeNameEn) ?>" class="welcome-logo__img">
            </div>

            <h2 class="welcome-prompt">Choose Your Language</h2>
            <p class="welcome-prompt-ar" dir="rtl">اختر لغتك</p>

            <div class="lang-actions">
                <a class="lang-btn lang-btn--en" href="<?= url('api/language.php?lang=en&redirect=/menu.php') ?>">
                    <span class="lang-label">English</span>
                </a>
                <a class="lang-btn lang-btn--ar" href="<?= url('api/language.php?lang=ar&redirect=/menu.php') ?>" dir="rtl">
                    <span class="lang-label">العربية</span>
                </a>
            </div>
        </div>
    </main>

    <script>
        localStorage.removeItem('wl_lang');
        document.querySelectorAll('.lang-btn').forEach((btn) => {
            btn.addEventListener('click', () => {
                const isAr = btn.classList.contains('lang-btn--ar');
                localStorage.setItem('wl_lang', isAr ? 'ar' : 'en');
            });
        });
    </script>
</body>
</html>
