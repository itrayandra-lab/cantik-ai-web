<?php
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/helpers.php';
requireLogin();
$currentUser = getCurrentUser();
$flash = getFlash();
$currentPage = basename($_SERVER['PHP_SELF'], '.php');
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $pageTitle ?? 'Dashboard' ?> - Raymaizing Admin</title>
<style>
/* ===== RESET & BASE ===== */
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
:root {
  --bg-base:    #0f172a;
  --bg-surface: #1e293b;
  --bg-card:    #243044;
  --border:     #334155;
  --border-light: #3d4f6b;
  --text-primary:   #f1f5f9;
  --text-secondary: #94a3b8;
  --text-muted:     #64748b;
  --accent-cyan:    #06b6d4;
  --accent-purple:  #8b5cf6;
  --accent-green:   #10b981;
  --accent-red:     #ef4444;
  --accent-yellow:  #f59e0b;
  --sidebar-w: 260px;
  --topbar-h:  64px;
  --radius:    10px;
  --shadow:    0 4px 20px rgba(0,0,0,.35);
}
html, body { height: 100%; }
body {
  font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
  background: var(--bg-base);
  color: var(--text-primary);
  font-size: 14px;
  line-height: 1.6;
}
a { color: inherit; text-decoration: none; }
button { cursor: pointer; font-family: inherit; }
img { max-width: 100%; }

/* ===== LAYOUT ===== */
.admin-layout { display: flex; min-height: 100vh; }

/* ===== SIDEBAR ===== */
.sidebar {
  width: var(--sidebar-w);
  background: var(--bg-surface);
  border-right: 1px solid var(--border);
  display: flex; flex-direction: column;
  position: fixed; top: 0; left: 0; bottom: 0;
  z-index: 100;
  transition: transform .3s;
}
.sidebar-logo {
  padding: 20px 20px 16px;
  border-bottom: 1px solid var(--border);
  display: flex; align-items: center; gap: 12px;
}
.sidebar-logo .logo-icon {
  width: 40px; height: 40px;
  background: linear-gradient(135deg, var(--accent-cyan), var(--accent-purple));
  border-radius: 10px;
  display: flex; align-items: center; justify-content: center;
  font-size: 20px; flex-shrink: 0;
}
.sidebar-logo .logo-text h2 { font-size: 16px; font-weight: 700; background: linear-gradient(135deg, var(--accent-cyan), var(--accent-purple)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
.sidebar-logo .logo-text span { font-size: 11px; color: var(--text-muted); }

.sidebar-nav { flex: 1; overflow-y: auto; padding: 16px 12px; }
.nav-section-label {
  font-size: 10px; font-weight: 600; letter-spacing: .08em;
  color: var(--text-muted); text-transform: uppercase;
  padding: 12px 8px 6px;
}
.nav-item {
  display: flex; align-items: center; gap: 10px;
  padding: 9px 12px; border-radius: 8px;
  color: var(--text-secondary); font-size: 13.5px; font-weight: 500;
  transition: background .15s, color .15s;
  margin-bottom: 2px;
}
.nav-item:hover { background: var(--bg-card); color: var(--text-primary); }
.nav-item.active { background: linear-gradient(135deg, rgba(6,182,212,.15), rgba(139,92,246,.15)); color: var(--accent-cyan); border: 1px solid rgba(6,182,212,.2); }
.nav-item .nav-icon { font-size: 16px; width: 20px; text-align: center; flex-shrink: 0; }
.nav-item .nav-badge { margin-left: auto; background: var(--accent-cyan); color: #fff; font-size: 10px; font-weight: 700; padding: 1px 6px; border-radius: 20px; }

.sidebar-footer {
  padding: 16px 12px;
  border-top: 1px solid var(--border);
}
.user-card {
  display: flex; align-items: center; gap: 10px;
  padding: 10px 12px; border-radius: 8px;
  background: var(--bg-card);
}
.user-avatar {
  width: 36px; height: 36px; border-radius: 50%;
  background: linear-gradient(135deg, var(--accent-cyan), var(--accent-purple));
  display: flex; align-items: center; justify-content: center;
  font-size: 14px; font-weight: 700; flex-shrink: 0;
  overflow: hidden;
}
.user-avatar img { width: 100%; height: 100%; object-fit: cover; }
.user-info { flex: 1; min-width: 0; }
.user-info .name { font-size: 13px; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.user-info .role { font-size: 11px; color: var(--text-muted); }
.btn-logout {
  background: none; border: none; color: var(--text-muted);
  font-size: 16px; padding: 4px; border-radius: 6px;
  transition: color .15s, background .15s;
}
.btn-logout:hover { color: var(--accent-red); background: rgba(239,68,68,.1); }

/* ===== MAIN CONTENT ===== */
.main-content {
  margin-left: var(--sidebar-w);
  flex: 1; display: flex; flex-direction: column; min-height: 100vh;
}

/* ===== TOPBAR ===== */
.topbar {
  height: var(--topbar-h);
  background: var(--bg-surface);
  border-bottom: 1px solid var(--border);
  display: flex; align-items: center; justify-content: space-between;
  padding: 0 28px;
  position: sticky; top: 0; z-index: 50;
}
.topbar-left { display: flex; align-items: center; gap: 12px; }
.topbar-left h1 { font-size: 18px; font-weight: 600; }
.breadcrumb { display: flex; align-items: center; gap: 6px; color: var(--text-muted); font-size: 13px; }
.breadcrumb span { color: var(--text-secondary); }
.topbar-right { display: flex; align-items: center; gap: 12px; }
.topbar-btn {
  width: 36px; height: 36px; border-radius: 8px;
  background: var(--bg-card); border: 1px solid var(--border);
  display: flex; align-items: center; justify-content: center;
  font-size: 16px; color: var(--text-secondary);
  transition: border-color .15s, color .15s;
}
.topbar-btn:hover { border-color: var(--accent-cyan); color: var(--accent-cyan); }

/* ===== PAGE CONTENT ===== */
.page-content { flex: 1; padding: 28px; }

/* ===== FLASH MESSAGES ===== */
.flash {
  padding: 12px 16px; border-radius: 8px; margin-bottom: 20px;
  display: flex; align-items: center; gap: 10px; font-size: 13.5px;
  animation: slideDown .3s ease;
}
@keyframes slideDown { from { opacity:0; transform:translateY(-8px); } to { opacity:1; transform:translateY(0); } }
.flash-success { background: #064e3b; border: 1px solid var(--accent-green); color: #6ee7b7; }
.flash-error   { background: #450a0a; border: 1px solid var(--accent-red);   color: #fca5a5; }
.flash-warning { background: #451a03; border: 1px solid var(--accent-yellow); color: #fcd34d; }
.flash-info    { background: #0c2a4a; border: 1px solid var(--accent-cyan);   color: #67e8f9; }

/* ===== CARDS ===== */
.card {
  background: var(--bg-surface);
  border: 1px solid var(--border);
  border-radius: var(--radius);
  padding: 24px;
}
.card-header {
  display: flex; align-items: center; justify-content: space-between;
  margin-bottom: 20px; padding-bottom: 16px;
  border-bottom: 1px solid var(--border);
}
.card-title { font-size: 16px; font-weight: 600; }

/* ===== BUTTONS ===== */
.btn {
  display: inline-flex; align-items: center; gap: 6px;
  padding: 8px 16px; border-radius: 8px; font-size: 13.5px; font-weight: 500;
  border: none; transition: opacity .15s, transform .1s;
}
.btn:hover { opacity: .88; }
.btn:active { transform: scale(.97); }
.btn-primary { background: linear-gradient(135deg, var(--accent-cyan), var(--accent-purple)); color: #fff; }
.btn-success { background: var(--accent-green); color: #fff; }
.btn-danger  { background: var(--accent-red);   color: #fff; }
.btn-warning { background: var(--accent-yellow); color: #000; }
.btn-ghost   { background: var(--bg-card); border: 1px solid var(--border); color: var(--text-secondary); }
.btn-ghost:hover { border-color: var(--accent-cyan); color: var(--accent-cyan); }
.btn-sm { padding: 5px 10px; font-size: 12px; }
.btn-icon { padding: 7px; }

/* ===== FORMS ===== */
.form-group { margin-bottom: 18px; }
.form-label { display: block; font-size: 13px; font-weight: 500; color: var(--text-secondary); margin-bottom: 7px; }
.form-label .required { color: var(--accent-red); margin-left: 3px; }
.form-control {
  width: 100%; padding: 9px 13px;
  background: var(--bg-base); border: 1px solid var(--border);
  border-radius: 8px; color: var(--text-primary); font-size: 14px;
  outline: none; transition: border-color .2s, box-shadow .2s;
  font-family: inherit;
}
.form-control:focus { border-color: var(--accent-cyan); box-shadow: 0 0 0 3px rgba(6,182,212,.12); }
.form-control::placeholder { color: var(--text-muted); }
select.form-control { cursor: pointer; }
.form-hint { font-size: 12px; color: var(--text-muted); margin-top: 5px; }
.form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }

/* ===== TABLE ===== */
.table-wrap { overflow-x: auto; }
table { width: 100%; border-collapse: collapse; }
thead th {
  padding: 10px 14px; text-align: left;
  font-size: 11px; font-weight: 600; letter-spacing: .06em;
  text-transform: uppercase; color: var(--text-muted);
  border-bottom: 1px solid var(--border);
  white-space: nowrap;
}
tbody td {
  padding: 12px 14px; border-bottom: 1px solid rgba(51,65,85,.5);
  font-size: 13.5px; vertical-align: middle;
}
tbody tr:hover td { background: rgba(255,255,255,.02); }
tbody tr:last-child td { border-bottom: none; }

/* ===== BADGES ===== */
.badge {
  display: inline-flex; align-items: center; gap: 4px;
  padding: 2px 8px; border-radius: 20px; font-size: 11px; font-weight: 600;
}
.badge-success { background: rgba(16,185,129,.15); color: #34d399; border: 1px solid rgba(16,185,129,.3); }
.badge-danger  { background: rgba(239,68,68,.15);  color: #f87171; border: 1px solid rgba(239,68,68,.3); }
.badge-warning { background: rgba(245,158,11,.15); color: #fbbf24; border: 1px solid rgba(245,158,11,.3); }
.badge-info    { background: rgba(6,182,212,.15);  color: #22d3ee; border: 1px solid rgba(6,182,212,.3); }
.badge-purple  { background: rgba(139,92,246,.15); color: #a78bfa; border: 1px solid rgba(139,92,246,.3); }

/* ===== IMAGE PREVIEW ===== */
.img-preview-wrap {
  display: flex; align-items: center; gap: 12px; margin-top: 8px;
}
.img-preview {
  width: 48px; height: 48px; border-radius: 8px;
  border: 1px solid var(--border); object-fit: contain;
  background: var(--bg-base); padding: 4px;
}
.img-preview-placeholder {
  width: 48px; height: 48px; border-radius: 8px;
  border: 1px dashed var(--border);
  display: flex; align-items: center; justify-content: center;
  color: var(--text-muted); font-size: 20px;
  background: var(--bg-base);
}

/* ===== DRAG HANDLE ===== */
.drag-handle { cursor: grab; color: var(--text-muted); font-size: 16px; padding: 4px; }
.drag-handle:active { cursor: grabbing; }

/* ===== MODAL ===== */
.modal-overlay {
  position: fixed; inset: 0; background: rgba(0,0,0,.6);
  display: flex; align-items: center; justify-content: center;
  z-index: 200; opacity: 0; pointer-events: none;
  transition: opacity .2s;
}
.modal-overlay.open { opacity: 1; pointer-events: all; }
.modal {
  background: var(--bg-surface); border: 1px solid var(--border);
  border-radius: 16px; padding: 28px; width: 100%; max-width: 520px;
  max-height: 90vh; overflow-y: auto;
  transform: scale(.95); transition: transform .2s;
}
.modal-overlay.open .modal { transform: scale(1); }
.modal-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; }
.modal-title { font-size: 17px; font-weight: 600; }
.modal-close { background: none; border: none; color: var(--text-muted); font-size: 20px; padding: 4px; border-radius: 6px; }
.modal-close:hover { color: var(--text-primary); background: var(--bg-card); }

/* ===== STATS CARDS ===== */
.stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 24px; }
.stat-card {
  background: var(--bg-surface); border: 1px solid var(--border);
  border-radius: var(--radius); padding: 20px;
  display: flex; align-items: center; gap: 16px;
}
.stat-icon {
  width: 48px; height: 48px; border-radius: 12px;
  display: flex; align-items: center; justify-content: center;
  font-size: 22px; flex-shrink: 0;
}
.stat-icon.cyan   { background: rgba(6,182,212,.15); }
.stat-icon.purple { background: rgba(139,92,246,.15); }
.stat-icon.green  { background: rgba(16,185,129,.15); }
.stat-icon.yellow { background: rgba(245,158,11,.15); }
.stat-value { font-size: 26px; font-weight: 700; line-height: 1; }
.stat-label { font-size: 12px; color: var(--text-muted); margin-top: 3px; }

/* ===== RESPONSIVE ===== */
@media (max-width: 768px) {
  .sidebar { transform: translateX(-100%); }
  .sidebar.open { transform: translateX(0); }
  .main-content { margin-left: 0; }
  .form-row { grid-template-columns: 1fr; }
}
</style>
</head>
<body>
<div class="admin-layout">

<!-- SIDEBAR -->
<aside class="sidebar" id="sidebar">
  <div class="sidebar-logo">
    <div class="logo-icon">⚡</div>
    <div class="logo-text">
      <h2>Raymaizing</h2>
      <span>Admin Panel</span>
    </div>
  </div>

  <nav class="sidebar-nav">
    <div class="nav-section-label">Utama</div>
    <a href="/admin/index.php" class="nav-item <?= $currentPage === 'index' ? 'active' : '' ?>">
      <span class="nav-icon">🏠</span> Dashboard
    </a>

    <div class="nav-section-label">Navigasi</div>
    <a href="/admin/menus/index.php" class="nav-item <?= in_array($currentPage, ['index','create','edit']) && strpos($_SERVER['PHP_SELF'], '/menus/') !== false ? 'active' : '' ?>">
      <span class="nav-icon">📋</span> Menu Groups
    </a>
    <a href="/admin/menus/items.php" class="nav-item <?= $currentPage === 'items' ? 'active' : '' ?>">
      <span class="nav-icon">🔗</span> Menu Items
    </a>

    <div class="nav-section-label">Sistem</div>
    <a href="/admin/users/index.php" class="nav-item <?= strpos($_SERVER['PHP_SELF'], '/users/') !== false ? 'active' : '' ?>">
      <span class="nav-icon">👥</span> Pengguna
    </a>
    <a href="/admin/activity.php" class="nav-item <?= $currentPage === 'activity' ? 'active' : '' ?>">
      <span class="nav-icon">📊</span> Log Aktivitas
    </a>
    <a href="/admin/settings.php" class="nav-item <?= $currentPage === 'settings' ? 'active' : '' ?>">
      <span class="nav-icon">⚙️</span> Pengaturan
    </a>
  </nav>

  <div class="sidebar-footer">
    <div class="user-card">
      <div class="user-avatar">
        <?php if ($currentUser['avatar']): ?>
          <img src="/<?= htmlspecialchars($currentUser['avatar']) ?>" alt="avatar">
        <?php else: ?>
          <?= strtoupper(substr($currentUser['full_name'], 0, 1)) ?>
        <?php endif; ?>
      </div>
      <div class="user-info">
        <div class="name"><?= htmlspecialchars($currentUser['full_name']) ?></div>
        <div class="role"><?= ucfirst($currentUser['role']) ?></div>
      </div>
      <a href="/admin/logout.php" class="btn-logout" title="Logout">🚪</a>
    </div>
  </div>
</aside>

<!-- MAIN -->
<div class="main-content">
  <header class="topbar">
    <div class="topbar-left">
      <h1><?= $pageTitle ?? 'Dashboard' ?></h1>
    </div>
    <div class="topbar-right">
      <a href="/" target="_blank" class="topbar-btn" title="Lihat Website">🌐</a>
      <a href="/admin/logout.php" class="topbar-btn" title="Logout">🚪</a>
    </div>
  </header>

  <div class="page-content">
    <?php if ($flash): ?>
      <div class="flash flash-<?= $flash['type'] ?>">
        <?= $flash['type'] === 'success' ? '✅' : ($flash['type'] === 'error' ? '❌' : 'ℹ️') ?>
        <?= htmlspecialchars($flash['message']) ?>
      </div>
    <?php endif; ?>
