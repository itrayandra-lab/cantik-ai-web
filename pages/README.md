# Struktur Pages

Semua halaman publik dan admin dibuat di folder ini.

## Struktur

```
pages/
├── blog/           ← Halaman blog publik
│   ├── index.php   ← Listing artikel
│   └── article.php ← Detail artikel
├── admin/          ← Panel admin
│   ├── index.php   ← Dashboard
│   ├── login.php
│   ├── articles/   ← CRUD artikel
│   ├── menus/      ← CRUD menu
│   └── users/      ← Manajemen user
└── README.md       ← File ini
```

## Cara Buat Halaman Baru

1. Buat folder di `pages/nama-halaman/`
2. Buat `index.php` di dalamnya
3. Include layout header & footer:

```php
<?php
$pageTitle = 'Judul Halaman';
$pageDesc  = 'Deskripsi halaman untuk SEO';
require_once __DIR__ . '/../../layouts/header.php';
?>

<!-- Konten halaman di sini -->

<?php require_once __DIR__ . '/../../layouts/footer.php'; ?>
```

4. Buat entry point di root agar bisa diakses via URL:

```php
// /nama-halaman/index.php (di root)
<?php require __DIR__ . '/../pages/nama-halaman/index.php'; ?>
```

## Layouts

```
layouts/
├── header.php  ← Navbar + <head> HTML
└── footer.php  ← Footer + closing </body></html>
```

### Variabel untuk header.php

| Variabel      | Wajib | Keterangan                        |
|---------------|-------|-----------------------------------|
| `$pageTitle`  | ✅    | Judul halaman                     |
| `$pageDesc`   | ✅    | Meta description                  |
| `$canonical`  | ❌    | Canonical URL (default: auto)     |
| `$ogImage`    | ❌    | Open Graph image                  |
| `$extraHead`  | ❌    | HTML tambahan di dalam `<head>`   |
| `$bodyClass`  | ❌    | Class tambahan untuk `<body>`     |
