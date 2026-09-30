<?php
/**
 * layouts/header.php
 * Header & Navbar untuk semua halaman publik (blog, pages, dll)
 */

$siteUrl   = 'https://cantik.ai';
$siteName  = 'Cantik.AI';
$pageTitle = isset($pageTitle) ? $pageTitle : $siteName;
$pageDesc  = isset($pageDesc)  ? $pageDesc  : 'Platform AI untuk Industri Kecantikan Indonesia.';
$canonical = isset($canonical) ? $canonical : $siteUrl . $_SERVER['REQUEST_URI'];
$ogImage   = isset($ogImage)   ? $ogImage   : $siteUrl . '/assets/img/og-default.png';
$bodyClass = isset($bodyClass) ? $bodyClass : '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($pageTitle) ?></title>
<meta name="description" content="<?= htmlspecialchars($pageDesc) ?>">
<meta name="robots" content="index, follow">
<link rel="canonical" href="<?= htmlspecialchars($canonical) ?>">
<meta property="og:type"        content="website">
<meta property="og:title"       content="<?= htmlspecialchars($pageTitle) ?>">
<meta property="og:description" content="<?= htmlspecialchars($pageDesc) ?>">
<meta property="og:url"         content="<?= htmlspecialchars($canonical) ?>">
<meta property="og:image"       content="<?= htmlspecialchars($ogImage) ?>">
<meta property="og:site_name"   content="<?= htmlspecialchars($siteName) ?>">
<meta name="twitter:card"        content="summary_large_image">
<meta name="twitter:title"       content="<?= htmlspecialchars($pageTitle) ?>">
<meta name="twitter:description" content="<?= htmlspecialchars($pageDesc) ?>">
<meta name="twitter:image"       content="<?= htmlspecialchars($ogImage) ?>">
<link rel="icon" href="/assets/img/cantik-ai-flaticon.png" type="image/png">
<link rel="apple-touch-icon" href="/assets/img/cantik-ai-flaticon.png">
<link rel="stylesheet" href="/assets/css/vendor/sintraweb.shared.min.css">
<link rel="stylesheet" href="/assets/css/main.css">
<link rel="stylesheet" href="/assets/css/navbar.css">

<?php if (isset($extraHead)) echo $extraHead; ?>

<style>
*, *::before, *::after { box-sizing: border-box; }
body { background:#fff; color:#1a0a12; font-family:'Segoe UI',system-ui,sans-serif; margin:0; }
</style>
</head>
<body class="<?= htmlspecialchars($bodyClass) ?>">

<!-- NAVBAR -->
<nav class="site-navbar">
  <div class="nav-inner">
    <a href="/" class="nav-logo">
      <img src="/assets/img/logo-colour-pink.png" alt="<?= htmlspecialchars($siteName) ?>">
    </a>
    <ul class="nav-menu" id="siteNavMenu"></ul>
    <div class="nav-cta">
      <a href="https://app.cantik.ai/" class="btn-nav-login">Login</a>
    </div>
    <button class="nav-hamburger" id="siteHamburger" aria-label="Menu" aria-expanded="false" aria-controls="siteDrawer">
      <span></span><span></span><span></span>
    </button>
  </div>
</nav>

<!-- Mobile Drawer -->
<div class="nav-backdrop" id="siteBackdrop"></div>
<div class="nav-drawer" id="siteDrawer">
  <div class="nav-drawer__header">Menu</div>
  <div class="nav-drawer__items" id="siteDrawerItems"></div>
  <div class="nav-drawer__footer">
    <a href="https://app.cantik.ai/" class="nav-drawer__login">Login</a>
  </div>
</div>

<script src="/assets/js/navbar.js" defer></script>
