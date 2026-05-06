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

<?php if (isset($extraHead)) echo $extraHead; ?>

<style>
*, *::before, *::after { box-sizing: border-box; }
body { background:#fff; color:#1a0a12; font-family:'Segoe UI',system-ui,sans-serif; margin:0; padding-top:72px; }

/* ===== NAVBAR ===== */
.site-navbar { position:fixed; top:0; left:0; right:0; height:72px; background:#fff; border-bottom:1px solid rgba(184,77,122,.12); box-shadow:0 2px 16px rgba(184,77,122,.08); z-index:1000; display:flex; align-items:center; }
.site-navbar .nav-inner { max-width:1280px; margin:0 auto; padding:0 2.5rem; width:100%; display:flex; align-items:center; justify-content:space-between; gap:24px; }
.site-navbar .nav-logo { display:flex; align-items:center; text-decoration:none; flex-shrink:0; }
.site-navbar .nav-logo img { height:36px; width:auto; display:block; }
.site-navbar .nav-menu { display:flex; align-items:center; gap:4px; list-style:none; margin:0; padding:0; flex:1; justify-content:center; }
.site-navbar .nav-menu li { position:relative; }
.site-navbar .nav-menu a { display:block; padding:8px 14px; font-size:14px; font-weight:500; font-family:'Inter',system-ui,-apple-system,sans-serif; letter-spacing:-0.01em; color:#2d1a24; text-decoration:none; transition:color .15s; white-space:nowrap; background:transparent !important; }
.site-navbar .nav-menu a:hover, .site-navbar .nav-menu a.active { color:#e8a0bf; }
.site-navbar .nav-menu .dropdown { position:absolute; top:calc(100% + 8px); left:0; background:#fff; border:1px solid rgba(184,77,122,.15); border-radius:12px; box-shadow:0 8px 32px rgba(184,77,122,.12); min-width:200px; padding:8px; opacity:0; pointer-events:none; transform:translateY(-6px); transition:opacity .2s,transform .2s; z-index:100; }
.site-navbar .nav-menu li:hover .dropdown { opacity:1; pointer-events:all; transform:translateY(0); }
.site-navbar .nav-menu .dropdown a { border-radius:8px; font-size:13.5px; padding:8px 12px; color:#2d1a24; }
.site-navbar .nav-menu .dropdown a:hover { color:#e8a0bf; }
.site-navbar .nav-cta { display:flex; align-items:center; gap:10px; flex-shrink:0; }
.btn-nav-login { padding:8px 20px; border-radius:8px; font-size:14px; font-weight:600; font-family:'Inter',system-ui,sans-serif; text-decoration:none; background:linear-gradient(135deg,#e8a0bf,#b84d7a); color:#fff; border:none; transition:opacity .15s; }
.btn-nav-login:hover { opacity:.85; }

/* ===== HAMBURGER ===== */
.nav-hamburger { display:none; flex-direction:column; gap:5px; cursor:pointer; padding:6px; border:none; background:none; z-index:1001; }
.nav-hamburger span { display:block; width:22px; height:2px; background:#2d1a24; border-radius:2px; transition:all .3s; }

/* ===== MOBILE DRAWER (slide dari kiri) ===== */
.nav-drawer {
  position: fixed;
  top: 0; left: 0; bottom: 0;
  width: 80vw; max-width: 300px;
  background: #fff;
  z-index: 1002;
  transform: translateX(-100%);
  transition: transform 0.3s ease;
  display: flex;
  flex-direction: column;
  box-shadow: 4px 0 24px rgba(0,0,0,.15);
  overflow-y: auto;
}
.nav-drawer.open { transform: translateX(0); }

.nav-drawer__header {
  padding: 20px 24px 16px;
  font-size: 17px;
  font-weight: 700;
  color: #1a0a12;
  border-bottom: 1px solid rgba(184,77,122,.1);
  flex-shrink: 0;
}

.nav-drawer__items { flex: 1; padding: 8px 0; }
.nav-drawer__items a {
  display: block;
  padding: 14px 24px;
  font-size: 15px;
  font-weight: 500;
  color: #2d1a24;
  text-decoration: none;
  border-bottom: 1px solid rgba(184,77,122,.07);
}
.nav-drawer__items a:hover { color: #b84d7a; background: #fdf0f5; }
.nav-drawer__items a:last-child { border-bottom: none; }

.nav-drawer__footer {
  padding: 20px 24px;
  border-top: 1px solid rgba(184,77,122,.1);
}
.nav-drawer__login {
  display: block;
  text-align: center;
  padding: 12px 24px;
  background: linear-gradient(135deg, #e8a0bf, #b84d7a);
  color: #fff;
  border-radius: 8px;
  font-size: 15px;
  font-weight: 600;
  text-decoration: none;
  box-shadow: 0 4px 12px rgba(184,77,122,.3);
}

/* Backdrop */
.nav-backdrop {
  display: none;
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,.5);
  z-index: 1001;
}
.nav-backdrop.open { display: block; }

@media(max-width:768px) {
  .site-navbar .nav-menu, .site-navbar .nav-cta { display:none !important; }
  .nav-hamburger { display:flex; order:1; }
  /* Mobile: hamburger kiri, logo kanan */
  .site-navbar .nav-logo { order:3; margin-left:auto; }
  .site-navbar .nav-inner { justify-content: flex-start; }
}
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
    <button class="nav-hamburger" id="siteHamburger" aria-label="Menu">
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

<script>
(function() {
  var path     = window.location.pathname;
  var hamburger = document.getElementById('siteHamburger');
  var drawer    = document.getElementById('siteDrawer');
  var backdrop  = document.getElementById('siteBackdrop');
  var drawerItems = document.getElementById('siteDrawerItems');
  var isOpen    = false;

  function openDrawer() {
    isOpen = true;
    drawer.classList.add('open');
    backdrop.classList.add('open');
    document.body.style.overflow = 'hidden';
  }

  function closeDrawer() {
    isOpen = false;
    drawer.classList.remove('open');
    backdrop.classList.remove('open');
    document.body.style.overflow = '';
  }

  hamburger.addEventListener('click', function(e) {
    e.stopPropagation();
    isOpen ? closeDrawer() : openDrawer();
  });

  backdrop.addEventListener('click', closeDrawer);

  drawerItems.addEventListener('click', function(e) {
    if (e.target.tagName === 'A') closeDrawer();
  });

  // Load menu dari API
  fetch('/pages/admin/api/menu.php?slug=main-nav')
    .then(function(r) { return r.json(); })
    .then(function(data) {
      if (!data.items) return;
      var nav = document.getElementById('siteNavMenu');

      data.items.forEach(function(item) {
        var active = path === item.url || path.startsWith(item.url + '/');

        // Desktop menu
        var li = document.createElement('li');
        if (item.children && item.children.length) {
          li.innerHTML = '<a href="' + item.url + '" class="' + (active ? 'active' : '') + '">' + item.title + ' ▾</a>'
            + '<div class="dropdown">' + item.children.map(function(c) {
              return '<a href="' + c.url + '">' + c.title + '</a>';
            }).join('') + '</div>';
        } else {
          li.innerHTML = '<a href="' + item.url + '" class="' + (active ? 'active' : '') + '">' + item.title + '</a>';
        }
        nav.appendChild(li);

        // Mobile drawer
        var a = document.createElement('a');
        a.href = item.url;
        a.textContent = item.title;
        if (active) a.style.color = '#b84d7a';
        drawerItems.appendChild(a);
      });
    })
    .catch(function() {
      // Fallback
      var nav = document.getElementById('siteNavMenu');
      [{title:'Beranda',url:'/'},{title:'Blog',url:'/blog/'}].forEach(function(l) {
        var li = document.createElement('li');
        li.innerHTML = '<a href="' + l.url + '">' + l.title + '</a>';
        nav.appendChild(li);
        var a = document.createElement('a');
        a.href = l.url; a.textContent = l.title;
        drawerItems.appendChild(a);
      });
    });
})();
</script>
