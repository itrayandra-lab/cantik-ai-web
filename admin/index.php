<?php
$pageTitle = 'Dashboard';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/config/database.php';

$pdo = getDB();

// Stats
$totalMenus = $pdo->query("SELECT COUNT(*) FROM nav_menus WHERE is_active = 1")->fetchColumn();
$totalItems = $pdo->query("SELECT COUNT(*) FROM nav_menu_items WHERE is_active = 1")->fetchColumn();
$totalUsers = $pdo->query("SELECT COUNT(*) FROM admin_users WHERE is_active = 1")->fetchColumn();
$recentActivities = $pdo->query("SELECT al.*, au.full_name FROM activity_log al LEFT JOIN admin_users au ON al.user_id = au.id ORDER BY al.created_at DESC LIMIT 10")->fetchAll();
?>

<div class="stats-grid">
  <div class="stat-card">
    <div class="stat-icon cyan">📋</div>
    <div>
      <div class="stat-value"><?= $totalMenus ?></div>
      <div class="stat-label">Menu Groups</div>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon purple">🔗</div>
    <div>
      <div class="stat-value"><?= $totalItems ?></div>
      <div class="stat-label">Menu Items</div>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon green">👥</div>
    <div>
      <div class="stat-value"><?= $totalUsers ?></div>
      <div class="stat-label">Admin Users</div>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon yellow">📊</div>
    <div>
      <div class="stat-value"><?= count($recentActivities) ?></div>
      <div class="stat-label">Recent Activities</div>
    </div>
  </div>
</div>

<div class="card">
  <div class="card-header">
    <h3 class="card-title">🕒 Aktivitas Terbaru</h3>
    <a href="/admin/activity.php" class="btn btn-ghost btn-sm">Lihat Semua →</a>
  </div>
  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>User</th>
          <th>Aksi</th>
          <th>Deskripsi</th>
          <th>IP Address</th>
          <th>Waktu</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($recentActivities)): ?>
          <tr><td colspan="5" style="text-align:center; color: var(--text-muted); padding: 32px;">Belum ada aktivitas</td></tr>
        <?php else: ?>
          <?php foreach ($recentActivities as $log): ?>
            <tr>
              <td><?= htmlspecialchars($log['full_name'] ?? 'System') ?></td>
              <td><span class="badge badge-info"><?= htmlspecialchars($log['action']) ?></span></td>
              <td style="color: var(--text-secondary);"><?= htmlspecialchars($log['description'] ?? '-') ?></td>
              <td style="color: var(--text-muted); font-size: 12px;"><?= htmlspecialchars($log['ip_address'] ?? '-') ?></td>
              <td style="color: var(--text-muted); font-size: 12px;"><?= timeAgo($log['created_at']) ?></td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
