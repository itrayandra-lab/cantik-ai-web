<?php
/**
 * Save Menu Item (Create / Update)
 * Handles both top-level and submenu items with image upload
 */
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/helpers.php';
requireLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('/admin/menus/items.php');
}

$pdo = getDB();

$id           = (int)($_POST['id'] ?? 0);
$menu_id      = (int)($_POST['menu_id'] ?? 0);
$parent_id    = !empty($_POST['parent_id']) ? (int)$_POST['parent_id'] : null;
$title        = sanitize($_POST['title'] ?? '');
$url          = trim($_POST['url'] ?? '#');
$target       = in_array($_POST['target'] ?? '', ['_self', '_blank']) ? $_POST['target'] : '_self';
$css_class    = sanitize($_POST['css_class'] ?? '');
$icon_alt     = sanitize($_POST['icon_alt'] ?? '');
$sort_order   = (int)($_POST['sort_order'] ?? 0);
$is_active    = isset($_POST['is_active']) ? 1 : 0;
$remove_image = isset($_POST['remove_image']) && $_POST['remove_image'] == '1';
$redirect_id  = (int)($_POST['redirect_menu_id'] ?? $menu_id);

$errors = [];

if (empty($title)) $errors[] = 'Judul menu wajib diisi.';
if (empty($url))   $errors[] = 'URL wajib diisi.';
if (!$menu_id)     $errors[] = 'Menu group tidak valid.';

// Handle image upload
$icon_image = null;
$existingImage = null;

if ($id > 0) {
    $stmt = $pdo->prepare("SELECT icon_image FROM nav_menu_items WHERE id = ?");
    $stmt->execute([$id]);
    $row = $stmt->fetch();
    $existingImage = $row['icon_image'] ?? null;
}

if (!empty($_FILES['icon_image']['name'])) {
    $result = uploadImage($_FILES['icon_image'], 'menu-icons');
    if (isset($result['error'])) {
        $errors[] = $result['error'];
    } else {
        $icon_image = $result['path'];
        // Delete old image if replacing
        if ($existingImage) {
            deleteUploadedFile($existingImage);
        }
    }
} elseif ($remove_image && $existingImage) {
    deleteUploadedFile($existingImage);
    $icon_image = null; // will be set to NULL in DB
} else {
    $icon_image = $existingImage; // keep existing
}

if (!empty($errors)) {
    setFlash('error', implode(' | ', $errors));
    redirect('/admin/menus/items.php?menu_id=' . $redirect_id);
}

if ($id > 0) {
    // Update
    $stmt = $pdo->prepare("
        UPDATE nav_menu_items
        SET menu_id=?, parent_id=?, title=?, url=?, target=?,
            icon_image=?, icon_alt=?, css_class=?, sort_order=?, is_active=?
        WHERE id=?
    ");
    $stmt->execute([
        $menu_id, $parent_id, $title, $url, $target,
        $icon_image, $icon_alt ?: null, $css_class ?: null, $sort_order, $is_active,
        $id
    ]);
    logActivity($_SESSION['admin_user_id'], 'edit_menu_item', "Updated menu item: $title");
    setFlash('success', "Menu item \"$title\" berhasil diperbarui.");
} else {
    // Insert
    $stmt = $pdo->prepare("
        INSERT INTO nav_menu_items
            (menu_id, parent_id, title, url, target, icon_image, icon_alt, css_class, sort_order, is_active)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([
        $menu_id, $parent_id, $title, $url, $target,
        $icon_image, $icon_alt ?: null, $css_class ?: null, $sort_order, $is_active
    ]);
    logActivity($_SESSION['admin_user_id'], 'create_menu_item', "Created menu item: $title");
    setFlash('success', "Menu item \"$title\" berhasil ditambahkan.");
}

redirect('/admin/menus/items.php?menu_id=' . $redirect_id);
