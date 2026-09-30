<?php
/**
 * index.php — Front controller (Valet/Herd + nginx)
 *
 * Kenapa perlu: Herd memakai nginx yang TIDAK membaca .htaccess.
 * Valet mencari front controller dengan urutan:
 *   1. {site}/{uri}   2. {site}/{uri}/index.php   3. {site}/index.php   4. {site}/index.html
 * Karena file ini ada, semua URL non-file dilayani di sini.
 *
 * Di production (Apache/LiteSpeed) routing tetap memakai .htaccess,
 * file ini hanya sebagai fallback/front controller.
 */

$root = __DIR__;
$uri  = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = '/' . trim(rawurldecode($uri ?? '/'), '/');
if ($path === '//') { $path = '/'; }

/* ---------- Helpers ---------- */
function notFound(string $root, string $path): void {
    http_response_code(404);
    $pageTitle = '404 — Halaman tidak ditemukan | Cantik.AI';
    $pageDesc  = 'Halaman yang Anda cari tidak ditemukan.';
    $canonical = 'https://cantik.ai';
    if (is_file($root . '/layouts/header.php')) {
        require $root . '/layouts/header.php';
        echo '<main style="max-width:640px;margin:80px auto;padding:0 2.5rem;text-align:center;'
           . 'font-family:\'Segoe UI\',system-ui,sans-serif">'
           . '<h1 style="font-size:56px;margin:0 0 8px;color:#1a0a12">404</h1>'
           . '<p style="font-size:18px;color:#6b3a52;margin:0 0 28px">Halaman tidak ditemukan.</p>'
           . '<a href="/" style="display:inline-block;padding:12px 28px;border-radius:8px;'
           . 'background:linear-gradient(135deg,#e8a0bf,#b84d7a);color:#fff;text-decoration:none;'
           . 'font-weight:600">&larr; Kembali ke Beranda</a></main>';
        require $root . '/layouts/footer.php';
    } else {
        echo '<h1>404 — Halaman tidak ditemukan</h1>';
    }
    exit;
}

/* ---------- / dan /home → Homepage ---------- */
if ($path === '/' || $path === '/home') {
    $home = $root . '/pages/home.html';
    if (is_file($home)) {
        readfile($home);
        return;
    }
    notFound($root, $path);
}

/* ---------- /blog & /blog/{slug} → pages/blog/index.php ---------- */
if ($path === '/blog') {
    require $root . '/pages/blog/index.php';
    return;
}
if (preg_match('#^/blog/([a-z0-9][a-z0-9\-]*)$#', $path, $m)) {
    $_GET['slug'] = $m[1];
    require $root . '/pages/blog/index.php';
    return;
}

/* ---------- /feature/{slug} → pages/feature/{slug}.php ---------- */
if (preg_match('#^/feature/([a-z0-9][a-z0-9\-]*)$#', $path, $m)) {
    $file = $root . '/pages/feature/' . $m[1] . '.php';
    if (is_file($file)) {
        require $file;
        return;
    }
    notFound($root, $path);
}

/* ---------- /admin/... → pages/admin/... ---------- */
if ($path === '/admin' || strpos($path, '/admin/') === 0) {
    $sub = trim(substr($path, strlen('/admin')), '/');

    if (strpos($sub, '..') !== false) {
        notFound($root, $path);
    }

    if ($sub === '') {
        $file = $root . '/pages/admin/index.php';
    } elseif (substr($sub, -4) === '.php') {
        $file = $root . '/pages/admin/' . $sub;
    } else {
        $file = $root . '/pages/admin/' . $sub . '.php';
    }

    if (is_file($file)) {
        require $file;
        return;
    }
    notFound($root, $path);
}

/* ---------- Halaman statis lain (tanpa .html) ---------- */
if (preg_match('#^/([a-z0-9][a-z0-9\-]*)$#', $path, $m)) {
    $candidates = [
        $root . '/pages/' . $m[1] . '.php',
        $root . '/pages/' . $m[1] . '/index.php',
    ];
    foreach ($candidates as $file) {
        if (is_file($file)) {
            require $file;
            return;
        }
    }
}

notFound($root, $path);
