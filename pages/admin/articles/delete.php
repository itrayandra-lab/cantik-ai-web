<?php
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/helpers.php';
requireLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /admin/articles/index.php');
    exit;
}

$id  = (int)($_POST['id'] ?? 0);
$pdo = getDB();

$article = $pdo->prepare("SELECT * FROM articles WHERE id = ?");
$article->execute([$id]);
$article = $article->fetch();

if ($article) {
    if ($article['featured_image']) deleteUploadedFile($article['featured_image']);
    $pdo->prepare("DELETE FROM articles WHERE id = ?")->execute([$id]);
    logActivity($_SESSION['admin_user_id'], 'article_delete', "Artikel dihapus: {$article['title']}");
    setFlash('success', 'Artikel berhasil dihapus.');
} else {
    setFlash('error', 'Artikel tidak ditemukan.');
}

header('Location: /admin/articles/index.php');
exit;

