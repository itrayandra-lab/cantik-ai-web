<?php
$pageTitle = 'Menu Groups';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../config/database.php';

$pdo = getDB();

// Handle delete
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM nav_menus WHERE id = ?");
    $stmt->execute([(int)$_GET['delete']]);
    logActivity($_SESSION['admin_user_id'], 'delete_menu', 'Deleted menu group ID: ' . $_GET['delete']);
    setFlash('success', 'Menu group berhasil dihapus.');
    redirect('/admin/menus/index.php');
}

// Handle toggle active
if (isset($_GET['toggle']) && is_numeric($_GET['toggle'])) {
    $stmt = $pdo->prepare("UPDATE nav_menus SET is_active = NOT is_active WHERE id = ?");
    $stmt->execute([(int)$_GET['toggle']]);
    setFlash('success', 'Status menu berhasil diubah.');
    redirect('/admin/menus/index.php');
}

$menus = $pdo->query("
    SELECT m.*, COUNT(i.id) as item_count
    FROM nav_menus m
    LEFT JOIN nav_menu_items i ON i.menu_id = m.id
    GROUP BY m.id
    ORDER BY m.created_at DESC
")->fetchAll();
?>

<div class="card">
  <div class="card-header">
    <h3 class="card-title">📋 Daftar Menu Groups</h3>
    <a href="/admin/menus/create.php" class="btn btn-primary">
      ➕ Tambah Menu Group
    </a>
  </div>

  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>#</th>
          <th>Nama</th>
          <th>Slug</th>
          <th>Deskripsi</th>
          <th>Items</th>
          <th>Status</th>
          <th>Dibuat</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($menus)): ?>
          <tr><td colspan="8" style="text-align:center; color: var(--text-muted); padding: 40px;">
            Belum ada menu group. <a href="/admin/menus/create.php" style="color: var(--accent-cyan);">Buat sekarang →</a>
          </td></tr>
        <?php else: ?>
          <?php foreach ($menus as $i => $menu): ?>
            <tr>
              <td style="color: var(--text-muted);"><?= $i + 1 ?></td>
              <td><strong><?= htmlspecialchars($menu['name']) ?></strong></td>
              <td><code style="background: var(--bg-card); padding: 2px 6px; border-radius: 4px; font-size: 12px;"><?= htmlspecialchars($menu['slug']) ?></code></td>
              <td style="color: var(--text-secondary);"><?= htmlspecialchars($menu['description'] ?? '-') ?></td>
              <td>
                <a href="/admin/menus/items.php?menu_id=<?= $menu['id'] ?>" class="badge badge-info">
                  <?= $menu['item_count'] ?> items
                </a>
              </td>
              <td>
                <a href="?toggle=<?= $menu['id'] ?>">
                  <?php if ($menu['is_active']): ?>
                    <span class="badge badge-success">● Aktif</span>
                  <?php else: ?>
                    <span class="badge badge-danger">● Nonaktif</span>
                  <?php endif; ?>
                </a>
              </td>
              <td style="color: var(--text-muted); font-size: 12px;"><?= date('d M Y', strtotime($menu['created_at'])) ?></td>
              <td>
                <div style="display: flex; gap: 6px;">
                  <a href="/admin/menus/items.php?menu_id=<?= $menu['id'] ?>" class="btn btn-ghost btn-sm btn-icon" title="Kelola Items">🔗</a>
                  <a href="/admin/menus/edit.php?id=<?= $menu['id'] ?>" class="btn btn-ghost btn-sm btn-icon" title="Edit">✏️</a>
                  <a href="?delete=<?= $menu['id'] ?>" class="btn btn-danger btn-sm btn-icon" title="Hapus"
                     data-confirm="Hapus menu group ini? Semua items di dalamnya juga akan terhapus!">🗑️</a>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
