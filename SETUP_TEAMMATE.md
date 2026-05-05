# Setup Guide untuk Teman

Setelah `git pull`, ada beberapa langkah manual yang harus dilakukan karena tidak ikut Git.

---

## 1. Buat Valet Driver (WAJIB)

Buat file baru di:
```
C:\Users\[USERNAME]\.config\herd\config\valet\Drivers\CantikAIValetDriver.php
```

Isi file:
```php
<?php

namespace Valet\Drivers\Custom;

use Valet\Drivers\BasicValetDriver;

class CantikAIValetDriver extends BasicValetDriver
{
    public function serves($sitePath, $siteName, $uri): bool
    {
        return $siteName === 'cantik-ai-web';
    }

    public function isStaticFile($sitePath, $siteName, $uri)
    {
        $filePath = $sitePath . $uri;
        if (is_file($filePath) && substr($uri, -4) !== '.php') {
            return $filePath;
        }
        return false;
    }

    public function frontControllerPath($sitePath, $siteName, $uri): string
    {
        $path = parse_url($uri, PHP_URL_PATH);
        $path = rtrim($path, '/');

        if (preg_match('#^/blog/([a-z0-9][a-z0-9\-]*)$#', $path, $m)) {
            $_GET['slug'] = $m[1];
            return $sitePath . '/pages/blog/index.php';
        }

        if ($path === '/blog') {
            return $sitePath . '/pages/blog/index.php';
        }

        if (preg_match('#^/admin(/.*)?$#', $path, $m)) {
            $sub = isset($m[1]) ? $m[1] : '/index.php';
            if (substr($sub, -4) !== '.php') {
                $sub = rtrim($sub, '/') . '/index.php';
            }
            $file = $sitePath . '/pages/admin' . $sub;
            if (is_file($file)) return $file;
            return $sitePath . '/pages/admin/index.php';
        }

        if ($path === '' || $path === '/' || $path === '/home') {
            return $sitePath . '/pages/index.html';
        }

        if (is_file($sitePath . $path)) {
            return $sitePath . $path;
        }

        return $sitePath . '/pages/index.html';
    }
}
```

Setelah buat file, restart Herd:
```
herd restart
```

---

## 2. Setup Database

Buat database `cantikai-db` lalu jalankan SQL berikut secara berurutan:

```
pages/admin/config/setup.sql
pages/admin/config/articles_setup.sql
```

Cara via MySQL CLI:
```bash
mysql -u root -h 127.0.0.1 cantikai-db < pages/admin/config/setup.sql
mysql -u root -h 127.0.0.1 cantikai-db < pages/admin/config/articles_setup.sql
```

---

## 3. Cek Konfigurasi Database

Edit file `pages/admin/config/database.php` sesuaikan dengan setting lokal:
```php
define('DB_HOST', '127.0.0.1');
define('DB_USER', 'root');
define('DB_PASS', '');          // sesuaikan password MySQL
define('DB_NAME', 'cantikai-db');
```

---

## 4. Login Admin

- URL: `http://cantik-ai-web.test/admin/login.php`
- Username: `it.rayandra@gmail.com`
- Password: `123`

---

## URL Penting

| Halaman | URL |
|---------|-----|
| Home | http://cantik-ai-web.test/ |
| Blog | http://cantik-ai-web.test/blog/ |
| Admin | http://cantik-ai-web.test/admin/ |
| Login Admin | http://cantik-ai-web.test/admin/login.php |
