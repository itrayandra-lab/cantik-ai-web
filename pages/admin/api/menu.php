<?php
/**
 * Public API: Get Menu by Slug
 * Usage: GET /admin/api/menu.php?slug=main-nav
 * Returns JSON menu structure with items and submenus
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once __DIR__ . '/../config/database.php';

$slug = trim($_GET['slug'] ?? '');

if (empty($slug)) {
    http_response_code(400);
    echo json_encode(['error' => 'Parameter slug diperlukan.']);
    exit;
}

$pdo = getDB();

// Get menu
$stmt = $pdo->prepare("SELECT * FROM nav_menus WHERE slug = ? AND is_active = 1");
$stmt->execute([$slug]);
$menu = $stmt->fetch();

if (!$menu) {
    http_response_code(404);
    echo json_encode(['error' => 'Menu tidak ditemukan.']);
    exit;
}

// Get top-level items
$stmt = $pdo->prepare("
    SELECT id, title, url, target, icon_image, icon_alt, css_class, sort_order
    FROM nav_menu_items
    WHERE menu_id = ? AND parent_id IS NULL AND is_active = 1
    ORDER BY sort_order, id
");
$stmt->execute([$menu['id']]);
$items = $stmt->fetchAll();

// Attach children
foreach ($items as &$item) {
    $sub = $pdo->prepare("
        SELECT id, title, url, target, icon_image, icon_alt, css_class, sort_order
        FROM nav_menu_items
        WHERE parent_id = ? AND is_active = 1
        ORDER BY sort_order, id
    ");
    $sub->execute([$item['id']]);
    $item['children'] = $sub->fetchAll();
}
unset($item);

echo json_encode([
    'menu' => [
        'id'          => $menu['id'],
        'name'        => $menu['name'],
        'slug'        => $menu['slug'],
    ],
    'items' => $items,
], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
