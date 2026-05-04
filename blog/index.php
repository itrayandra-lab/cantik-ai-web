<?php
/**
 * Blog Router
 * Herd/Nginx tidak baca .htaccess, routing dilakukan di sini.
 * Nginx default Herd: try_files $uri $uri/ /index.php
 * Jadi semua request ke /blog/* yang tidak ada file-nya akan masuk sini.
 */

$uri  = $_SERVER['REQUEST_URI'];
$path = parse_url($uri, PHP_URL_PATH);

// Bersihkan trailing slash dan ambil bagian setelah /blog/
$path = rtrim($path, '/');
$slug = '';

if (preg_match('#^/blog/([a-z0-9][a-z0-9\-]*)$#', $path, $m)) {
    $slug = $m[1];
}

if ($slug !== '') {
    // Detail artikel
    $_GET['slug'] = $slug;
    require __DIR__ . '/../pages/blog/article.php';
} else {
    // Listing blog
    require __DIR__ . '/../pages/blog/index.php';
}
