<?php
$pageTitle = 'Log Aktivitas';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/config/database.php';

$pdo = getDB();

$page  = max(1, (int)($_GET['page'] ?? 1));
$limit = 25;
$offset = ($page - 1) * $limit;

$total = $pdo->query("SELECT COUNT(*) FROM activity_log")->fetchColumn();
$pages = ceil($total / $limit);

$logs = $pdo->prepare("
    SELECT al.*, au.full_name, au.username
    FROM activity_log al
    LEFT JOIN admin_users au ON al.user_id = au.id
    ORDER BY al.created_at DESC
    LIMIT $limit OFFSET $offset
");
$logs->execute();
$logs = $logs->fetchAll();

$actionColors = [
    'login'            => 'badge-success',
    'logout'           => 'badge-warning',
    'create_menu'      => 'badge-info',
    'edit_menu'        => 'badge-info',
    'delete_menu'      => 'badge-danger',
    'create_menu_item' => 'badge-purple',
    'edit_menu_item'   => 'badge-purple',
    'delete_menu_item' => 'badge-danger',
    'create_user'      => 'badge-success',
    'edit_user'        => 'badge-info',
    'delete_user'      => 'badge-danger',
];
?>

<div class="card">
  <div class="card-header">
    <h3 class="card-title">📊 Log Aktivitas</h3>
    <span style="color: var(--text-muted); font-size: 13px;"><?= number_format($total) ?> total entri</span>
  </div>

  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>#</th>
          <th>User</th>
          <th>Aksi</th>
          <th>Deskripsi</th>
          <th>IP Address</th>
          <th>Waktu</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($logs)): ?>
          <tr><td colspan="6" style="text-align:center; color: var(--text-muted); padding: 40px;">Belum ada log aktivitas</td></tr>
        <?php else: ?>
          <?php foreach ($logs as $i => $log): ?>
            <tr>
              <td style="color: var(--text-muted);"><?= $offset + $i + 1 ?></td>
              <td>
                <div style="font-weight: 600;"><?= htmlspecialchars($log['full_name'] ?? 'System') ?></div>
                <div style="font-size: 11px; color: var(--text-muted);">@<?= htmlspecialchars($log['username'] ?? '-') ?></div>
              </td>
              <td>
                <span class="badge <?= $actionColors[$log['action']] ?? 'badge-info' ?>">
                  <?= htmlspecialchars($log['action']) ?>
                </span>
              </td>
              <td style="color: var(--text-secondary); max-width: 300px;"><?= htmlspecialchars($log['description'] ?? '-') ?></td>
              <td style="color: var(--text-muted); font-size: 12px; font-family: monospace;"><?= htmlspecialchars($log['ip_address'] ?? '-') ?></td>
              <td style="color: var(--text-muted); font-size: 12px; white-space: nowrap;">
                <?= date('d M Y H:i', strtotime($log['created_at'])) ?>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <!-- Pagination -->
  <?php if ($pages > 1): ?>
    <div style="display: flex; justify-content: center; gap: 6px; margin-top: 20px; padding-top: 16px; border-top: 1px solid var(--border);">
      <?php for ($p = 1; $p <= $pages; $p++): ?>
        <a href="?page=<?= $p ?>"
           class="btn <?= $p === $page ? 'btn-primary' : 'btn-ghost' ?> btn-sm">
          <?= $p ?>
        </a>
      <?php endfor; ?>
    </div>
  <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
