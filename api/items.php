<?php
/**
 * AJAX menu items endpoint
 */

declare(strict_types=1);

require_once __DIR__ . '/../core/bootstrap.php';

$itemModel = new MenuItem();
$q = sanitize_string($_GET['q'] ?? '');
$categoryId = isset($_GET['category_id']) ? (int) $_GET['category_id'] : 0;

if ($q !== '') {
    $items = $itemModel->search($q, $categoryId > 0 ? $categoryId : null);
} else {
    $items = $itemModel->byCategory($categoryId > 0 ? $categoryId : null, true);
}

$payload = array_map(static function (array $item): array {
    return [
        'id' => (int) $item['id'],
        'category_id' => (int) $item['category_id'],
        'name' => localized($item, 'name'),
        'description' => localized($item, 'description'),
        'category' => localized([
            'name_en' => $item['category_name_en'] ?? '',
            'name_ar' => $item['category_name_ar'] ?? '',
        ], 'name'),
        'price' => (float) $item['price'],
        'price_formatted' => format_price($item['price']),
    ];
}, $items);

json_response([
    'success' => true,
    'count' => count($payload),
    'items' => $payload,
]);
