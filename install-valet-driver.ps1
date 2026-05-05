# ============================================================
# Install Valet Driver untuk Cantik.AI Web
# Jalankan script ini setelah git pull
# Cara: klik kanan → Run with PowerShell
# ============================================================

Write-Host "🚀 Installing Cantik.AI Valet Driver..." -ForegroundColor Cyan

# Cari folder Drivers Herd
$username = $env:USERNAME
$driversPath = "C:\Users\$username\.config\herd\config\valet\Drivers"

if (-not (Test-Path $driversPath)) {
    New-Item -ItemType Directory -Path $driversPath -Force | Out-Null
    Write-Host "✅ Created Drivers folder" -ForegroundColor Green
}

$driverFile = "$driversPath\CantikAIValetDriver.php"

$driverContent = @'
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

        // /blog/slug
        if (preg_match('#^/blog/([a-z0-9][a-z0-9\-]*)$#', $path, $m)) {
            $_GET['slug'] = $m[1];
            return $sitePath . '/pages/blog/index.php';
        }

        // /blog
        if ($path === '/blog') {
            return $sitePath . '/pages/blog/index.php';
        }

        // /admin/*
        if (preg_match('#^/admin(/.*)?$#', $path, $m)) {
            $sub = isset($m[1]) ? $m[1] : '/index.php';
            if (substr($sub, -4) !== '.php') {
                $sub = rtrim($sub, '/') . '/index.php';
            }
            $file = $sitePath . '/pages/admin' . $sub;
            if (is_file($file)) return $file;
            return $sitePath . '/pages/admin/index.php';
        }

        // / or /home
        if ($path === '' || $path === '/' || $path === '/home') {
            return $sitePath . '/pages/index.html';
        }

        // Direct file
        if (is_file($sitePath . $path)) {
            return $sitePath . $path;
        }

        return $sitePath . '/pages/index.html';
    }
}
'@

# Tulis file tanpa BOM
$bytes = [System.Text.Encoding]::ASCII.GetBytes($driverContent)
[System.IO.File]::WriteAllBytes($driverFile, $bytes)

Write-Host "✅ Valet Driver installed at: $driverFile" -ForegroundColor Green

# Restart Herd
$herdBat = "C:\Users\$username\.config\herd\bin\herd.bat"
if (Test-Path $herdBat) {
    Write-Host "🔄 Restarting Herd..." -ForegroundColor Yellow
    & $herdBat restart 2>&1 | Out-Null
    Write-Host "✅ Herd restarted!" -ForegroundColor Green
} else {
    Write-Host "⚠️  Herd not found. Please restart Herd manually." -ForegroundColor Yellow
}

Write-Host ""
Write-Host "✅ Done! Buka http://cantik-ai-web.test/" -ForegroundColor Green
Write-Host ""
Write-Host "Jangan lupa:" -ForegroundColor White
Write-Host "  1. Import database: pages/admin/config/setup.sql" -ForegroundColor White
Write-Host "  2. Import database: pages/admin/config/articles_setup.sql" -ForegroundColor White
Write-Host "  3. Sesuaikan pages/admin/config/database.php (password MySQL)" -ForegroundColor White
Write-Host ""
Read-Host "Tekan Enter untuk keluar"
