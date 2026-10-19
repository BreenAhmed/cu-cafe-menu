<?php
/**
 * Public digital menu
 */

declare(strict_types=1);

require_once __DIR__ . '/core/bootstrap.php';

if (empty($_SESSION[LANG_SESSION_KEY])) {
    redirect('/index.php');
}

$settings = (new Setting())->get();
$categories = (new Category())->all(true);
$items = (new MenuItem())->byCategory(null, true);

$lang = get_lang();
$cafeName = cafe_name($settings);
$welcomeText = $lang === 'ar'
    ? ($settings['welcome_text_ar'] ?: $settings['welcome_text'])
    : ($settings['welcome_text'] ?: '');

$primary = $settings['primary_color'] ?: '#226F54';
$secondary = $settings['secondary_color'] ?: '#D33C3C';
$logoUrl = logo_url($settings);

function render_item_card(array $item): string
{
    $name = e(localized($item, 'name'));
    $desc = e(localized($item, 'description'));
    $price = e(format_price($item['price']));
    $cat = e(localized([
        'name_en' => $item['category_name_en'] ?? '',
        'name_ar' => $item['category_name_ar'] ?? '',
    ], 'name'));

    $id = (int) $item['id'];

    return <<<HTML
    <article class="menu-card" id="item-{$id}" data-id="{$id}" data-category="{$item['category_id']}" data-name="{$name}">
        <div class="menu-card__deco" aria-hidden="true">
            <span class="seed seed--1"></span>
            <span class="seed seed--2"></span>
            <span class="seed seed--3"></span>
        </div>
        <div class="menu-card__top">
            <span class="menu-card__cat">{$cat}</span>
            <span class="menu-card__price">{$price}</span>
        </div>
        <h3 class="menu-card__name">{$name}</h3>
        <p class="menu-card__desc">{$desc}</p>
    </article>
    HTML;
}

$assistantItems = array_map(static function (array $item): array {
    return [
        'id' => (int) $item['id'],
        'name' => localized($item, 'name'),
        'description' => localized($item, 'description'),
        'category' => localized([
            'name_en' => $item['category_name_en'] ?? '',
            'name_ar' => $item['category_name_ar'] ?? '',
        ], 'name'),
        'price_formatted' => format_price($item['price']),
    ];
}, $items);
?>
<!DOCTYPE html>
<html lang="<?= e($lang) ?>" dir="<?= e(lang_dir()) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="theme-color" content="<?= e($primary) ?>">
    <meta name="description" content="<?= e($cafeName) ?> — <?= e(t('our_menu')) ?>">
    <title><?= e($cafeName) ?> · <?= e(t('menu')) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,700&family=Nunito:wght@400;600;700&family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>">
    <link rel="icon" href="<?= e($logoUrl) ?>" type="image/png">
    <style>
        :root {
            --brand-green: <?= e($primary) ?>;
            --brand-red: <?= e($secondary) ?>;
        }
    </style>
</head>
<body class="menu-page">
    <div class="page-atmosphere" aria-hidden="true">
        <div class="atm-blob atm-blob--a"></div>
        <div class="atm-blob atm-blob--b"></div>
        <div class="seed-field seed-field--soft"></div>
        <div class="floaties">
            <span class="floatie floatie--melon floatie--1">
                <svg viewBox="0 0 48 48" fill="none"><path d="M8 28c0-12 10-22 22-22 2 14-6 28-22 34 0-4 0-8 0-12z" fill="#D33C3C"/><path d="M8 40c16-6 24-20 22-34 6 2 10 10 10 18 0 12-10 22-22 22-4 0-8-2-10-6z" fill="#226F54"/><ellipse cx="18" cy="24" rx="1.6" ry="2.4" fill="#43291F" transform="rotate(-20 18 24)"/><ellipse cx="24" cy="30" rx="1.6" ry="2.4" fill="#43291F" transform="rotate(15 24 30)"/><ellipse cx="16" cy="32" rx="1.4" ry="2.2" fill="#43291F" transform="rotate(-5 16 32)"/></svg>
            </span>
            <span class="floatie floatie--seed floatie--2"></span>
            <span class="floatie floatie--leaf floatie--3">
                <svg viewBox="0 0 40 40" fill="none"><path d="M8 28c8-18 22-22 28-22-2 14-10 26-28 28 0-2 0-4 0-6z" fill="#226F54" opacity=".85"/><path d="M10 30c10-8 18-18 22-26" stroke="#E2E7D8" stroke-width="1.5" stroke-linecap="round"/></svg>
            </span>
            <span class="floatie floatie--cup floatie--4">
                <svg viewBox="0 0 40 40" fill="none"><path d="M10 12h16l-2 18H12L10 12z" fill="#D33C3C" opacity=".9"/><path d="M26 14h4a4 4 0 010 8h-3.2" stroke="#226F54" stroke-width="2" stroke-linecap="round"/><path d="M12 8c2-3 6-3 8 0M18 7c2-3 6-2 8 1" stroke="#226F54" stroke-width="1.6" stroke-linecap="round"/></svg>
            </span>
            <span class="floatie floatie--seed floatie--5"></span>
            <span class="floatie floatie--heart floatie--6">
                <svg viewBox="0 0 36 36" fill="none"><path d="M18 30s-10-6.5-10-14a5.5 5.5 0 0110-3 5.5 5.5 0 0110 3c0 7.5-10 14-10 14z" fill="#D33C3C" opacity=".85"/></svg>
            </span>
            <span class="floatie floatie--melon floatie--7">
                <svg viewBox="0 0 48 48" fill="none"><path d="M8 28c0-12 10-22 22-22 2 14-6 28-22 34 0-4 0-8 0-12z" fill="#D33C3C"/><path d="M8 40c16-6 24-20 22-34 6 2 10 10 10 18 0 12-10 22-22 22-4 0-8-2-10-6z" fill="#226F54"/><ellipse cx="18" cy="24" rx="1.6" ry="2.4" fill="#43291F" transform="rotate(-20 18 24)"/></svg>
            </span>
            <span class="floatie floatie--seed floatie--8"></span>
            <span class="floatie floatie--spark floatie--9"></span>
            <span class="floatie floatie--leaf floatie--10">
                <svg viewBox="0 0 40 40" fill="none"><path d="M8 28c8-18 22-22 28-22-2 14-10 26-28 28 0-2 0-4 0-6z" fill="#2f8a6a" opacity=".8"/><path d="M10 30c10-8 18-18 22-26" stroke="#E2E7D8" stroke-width="1.5" stroke-linecap="round"/></svg>
            </span>
            <span class="floatie floatie--dot floatie--11"></span>
            <span class="floatie floatie--seed floatie--12"></span>
        </div>
    </div>

    <header class="site-header">
        <div class="site-header__inner">
            <a class="brand-lockup" href="<?= url('menu.php') ?>">
                <img src="<?= e($logoUrl) ?>" alt="<?= e($cafeName) ?>" class="brand-lockup__logo">
            </a>
            <div class="header-actions">
                <a class="icon-chip" href="<?= url('index.php?change=1') ?>" title="<?= e(t('switch_language')) ?>">
                    <span><?= e(t('switch_language')) ?></span>
                </a>
            </div>
        </div>
    </header>

    <main>
        <section class="hero">
            <div class="hero__content fade-up">
                <p class="hero__eyebrow"><?= e(t('welcome_title')) ?></p>
                <h1 class="hero__brand"><?= e($cafeName) ?></h1>
                <?php if ($welcomeText): ?>
                    <p class="hero__message"><?= e($welcomeText) ?></p>
                <?php endif; ?>
            </div>
        </section>

        <section class="toolbar sticky-toolbar">
            <div class="search-wrap">
                <svg class="search-icon" viewBox="0 0 24 24" aria-hidden="true">
                    <circle cx="11" cy="11" r="7" fill="none" stroke="currentColor" stroke-width="2"/>
                    <path d="M16.5 16.5L21 21" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
                <input
                    type="search"
                    id="menuSearch"
                    class="search-input"
                    placeholder="<?= e(t('search_placeholder')) ?>"
                    autocomplete="off"
                    enterkeyhint="search"
                >
            </div>

            <div class="category-rail" id="categoryRail" role="tablist" aria-label="<?= e(t('categories')) ?>">
                <button type="button" class="cat-pill is-active" data-category="0" role="tab" aria-selected="true">
                    <?= e(t('all_categories')) ?>
                </button>
                <?php foreach ($categories as $cat): ?>
                    <button
                        type="button"
                        class="cat-pill"
                        data-category="<?= (int) $cat['id'] ?>"
                        role="tab"
                        aria-selected="false"
                    >
                        <?= e(localized($cat, 'name')) ?>
                    </button>
                <?php endforeach; ?>
            </div>
        </section>

        <section class="menu-section">
            <div class="section-heading fade-up">
                <h2><?= e(t('our_menu')) ?></h2>
            </div>

            <div id="menuGrid" class="menu-feed" aria-live="polite">
                <?php
                $grouped = [];
                foreach ($items as $item) {
                    $cid = (int) $item['category_id'];
                    if (!isset($grouped[$cid])) {
                        $grouped[$cid] = [
                            'name' => localized([
                                'name_en' => $item['category_name_en'] ?? '',
                                'name_ar' => $item['category_name_ar'] ?? '',
                            ], 'name'),
                            'items' => [],
                        ];
                    }
                    $grouped[$cid]['items'][] = $item;
                }
                foreach ($grouped as $cid => $group):
                ?>
                    <section class="menu-cat-block" data-category="<?= (int) $cid ?>" id="category-<?= (int) $cid ?>">
                        <div class="menu-cat-heading">
                            <span class="menu-cat-heading__seed" aria-hidden="true"></span>
                            <h3 class="menu-cat-heading__title"><?= e($group['name']) ?></h3>
                            <span class="menu-cat-heading__line" aria-hidden="true"></span>
                        </div>
                        <div class="menu-grid">
                            <?php foreach ($group['items'] as $item): ?>
                                <?= render_item_card($item) ?>
                            <?php endforeach; ?>
                        </div>
                    </section>
                <?php endforeach; ?>
            </div>

            <div id="emptyState" class="empty-state" hidden>
                <img
                    src="<?= asset('img/assistant.png') ?>"
                    alt=""
                    class="empty-state__logo"
                    width="120"
                    height="104"
                >
                <h3><?= e(t('no_items')) ?></h3>
                <p><?= e(t('no_items_hint')) ?></p>
            </div>
        </section>
    </main>

    <footer class="site-footer">
        <?php if (!empty($settings['whatsapp']) || !empty($settings['instagram'])): ?>
            <div class="social-row">
                <?php if (!empty($settings['whatsapp'])): ?>
                    <a class="social-link" href="https://wa.me/<?= e(preg_replace('/\D+/', '', $settings['whatsapp'])) ?>" target="_blank" rel="noopener">
                        <?= e(t('whatsapp')) ?>
                    </a>
                <?php endif; ?>
                <?php if (!empty($settings['instagram'])): ?>
                    <a class="social-link" href="<?= e($settings['instagram']) ?>" target="_blank" rel="noopener">Instagram</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        <p class="footer-note"><?= e(t('made_with')) ?></p>
    </footer>

    <button type="button" class="back-top" id="backTop" aria-label="<?= e(t('back_to_top')) ?>">↑</button>

    <div class="assistant" id="assistant">
        <div class="assistant__panel" id="assistantPanel" hidden>
            <div class="assistant__bubble">
                <button
                    type="button"
                    class="assistant__minimize"
                    id="assistantMinimize"
                    aria-label="<?= e(t('assistant_minimize')) ?>"
                    title="<?= e(t('assistant_minimize')) ?>"
                >−</button>
                <p class="assistant__hello" id="assistantHello"><?= e(t('assistant_hello')) ?></p>
                <p class="assistant__label" id="assistantLabel"><?= e(t('assistant_suggest')) ?></p>
                <div class="assistant__pick" id="assistantPick">
                    <span class="assistant__cat" id="assistantCat"></span>
                    <strong class="assistant__name" id="assistantName"></strong>
                    <span class="assistant__desc" id="assistantDesc"></span>
                    <span class="assistant__price" id="assistantPrice"></span>
                </div>
                <div class="assistant__actions">
                    <button type="button" class="assistant__btn assistant__btn--ghost" id="assistantShuffle">
                        <?= e(t('assistant_try_another')) ?>
                    </button>
                    <button type="button" class="assistant__btn assistant__btn--solid" id="assistantShow">
                        <?= e(t('assistant_view')) ?>
                    </button>
                </div>
            </div>
        </div>
        <button
            type="button"
            class="assistant__fab"
            id="assistantToggle"
            aria-expanded="false"
            aria-controls="assistantPanel"
            aria-label="<?= e(t('assistant_open')) ?>"
            data-label-open="<?= e(t('assistant_open')) ?>"
            data-label-close="<?= e(t('assistant_close')) ?>"
        >
            <img src="<?= asset('img/assistant.png') ?>" alt="<?= e(t('assistant_name')) ?>" class="assistant__avatar">
            <span class="assistant__pulse" aria-hidden="true"></span>
        </button>
    </div>

    <script>
        window.WL = {
            baseUrl: <?= json_encode(BASE_URL) ?>,
            apiItems: <?= json_encode(url('api/items.php')) ?>,
            lang: <?= json_encode($lang) ?>,
            items: <?= json_encode($assistantItems, JSON_UNESCAPED_UNICODE) ?>,
            i18n: {
                noItems: <?= json_encode(t('no_items')) ?>,
                noItemsHint: <?= json_encode(t('no_items_hint')) ?>,
                loading: <?= json_encode(t('loading')) ?>,
                assistantSuggest: <?= json_encode(t('assistant_suggest')) ?>,
                assistantHello: <?= json_encode(t('assistant_hello')) ?>
            }
        };
        localStorage.setItem('wl_lang', <?= json_encode($lang) ?>);
    </script>
    <script src="<?= asset('js/app.js') ?>"></script>
</body>
</html>
