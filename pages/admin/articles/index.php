<?php
$pageTitle = 'Artikel';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../config/database.php';

$pdo = getDB();

// Filters
$status   = $_GET['status']   ?? '';
$category = $_GET['category'] ?? '';
$search   = $_GET['search']   ?? '';
$page     = max(1, (int)($_GET['page'] ?? 1));
$perPage  = 15;

$where  = ['1=1'];
$params = [];

if ($status)   { $where[] = 'a.status = ?';      $params[] = $status; }
if ($category) { $where[] = 'a.category_id = ?'; $params[] = $category; }
if ($search)   { $where[] = '(a.title LIKE ? OR a.excerpt LIKE ?)'; $params[] = "%$search%"; $params[] = "%$search%"; }

$whereSQL = implode(' AND ', $where);
$total    = $pdo->prepare("SELECT COUNT(*) FROM articles a WHERE $whereSQL");
$total->execute($params);
$totalRows  = $total->fetchColumn();
$totalPages = ceil($totalRows / $perPage);
$offset     = ($page - 1) * $perPage;

$stmt = $pdo->prepare("
    SELECT a.*, ac.name AS category_name, ac.color AS category_color, au.full_name AS author_name
    FROM articles a
    LEFT JOIN article_categories ac ON a.category_id = ac.id
    LEFT JOIN admin_users au ON a.author_id = au.id
    WHERE $whereSQL
    ORDER BY a.created_at DESC
    LIMIT $perPage OFFSET $offset
");
$stmt->execute($params);
$articles = $stmt->fetchAll();

$categories = $pdo->query("SELECT * FROM article_categories WHERE is_active=1 ORDER BY sort_order")->fetchAll();

// Counts per status
$counts = $pdo->query("SELECT status, COUNT(*) as cnt FROM articles GROUP BY status")->fetchAll(PDO::FETCH_KEY_PAIR);

// Auto-publish scheduled articles
$pdo->exec("UPDATE articles SET status='published', published_at=NOW() WHERE status='scheduled' AND scheduled_at <= NOW()");
?>

<div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:24px;">
  <div>
    <h2 style="font-size:20px; font-weight:700;">📝 Manajemen Artikel</h2>
    <p style="color:var(--text-muted); font-size:13px; margin-top:4px;">Kelola artikel blog dengan SEO & jadwal publikasi</p>
  </div>
  <a href="/admin/articles/create.php" class="btn btn-primary">✏️ Tulis Artikel Baru</a>
</div>

<!-- Status Tabs -->
<div style="display:flex; gap:8px; margin-bottom:20px; flex-wrap:wrap;">
  <?php
  $tabs = ['' => 'Semua', 'published' => 'Terbit', 'draft' => 'Draft', 'scheduled' => 'Terjadwal', 'archived' => 'Arsip'];
  foreach ($tabs as $val => $label):
    $cnt   = $val ? ($counts[$val] ?? 0) : array_sum($counts);
    $active = ($status === $val);
  ?>
  <a href="?status=<?= $val ?>&category=<?= urlencode($category) ?>&search=<?= urlencode($search) ?>"
     style="padding:6px 14px; border-radius:20px; font-size:13px; font-weight:500; border:1px solid <?= $active ? 'var(--accent-cyan)' : 'var(--border)' ?>; background:<?= $active ? 'rgba(6,182,212,.15)' : 'var(--bg-card)' ?>; color:<?= $active ? 'var(--accent-cyan)' : 'var(--text-secondary)' ?>;">
    <?= $label ?> <span style="opacity:.7;">(<?= $cnt ?>)</span>
  </a>
  <?php endforeach; ?>
</div>

<!-- Filters -->
<div class="card" style="margin-bottom:20px; padding:16px;">
  <form method="GET" style="display:flex; gap:12px; flex-wrap:wrap; align-items:flex-end;">
    <input type="hidden" name="status" value="<?= htmlspecialchars($status) ?>">
    <div style="flex:1; min-width:200px;">
      <input type="text" name="search" class="form-control" placeholder="🔍 Cari judul atau excerpt..." value="<?= htmlspecialchars($search) ?>">
    </div>
    <div>
      <select name="category" class="form-control">
        <option value="">Semua Kategori</option>
        <?php foreach ($categories as $cat): ?>
          <option value="<?= $cat['id'] ?>" <?= $category == $cat['id'] ? 'selected' : '' ?>><?= htmlspecialchars($cat['name']) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <button type="submit" class="btn btn-ghost">Filter</button>
    <a href="/admin/articles/index.php" class="btn btn-ghost">Reset</a>
  </form>
</div>

<!-- Table -->
<div class="card">
  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>Judul</th>
          <th>Kategori</th>
          <th>Status</th>
          <th>Penulis</th>
          <th>Jadwal / Terbit</th>
          <th>Views</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($articles)): ?>
          <tr><td colspan="7" style="text-align:center; color:var(--text-muted); padding:40px;">Belum ada artikel</td></tr>
        <?php else: ?>
          <?php foreach ($articles as $a): ?>
          <tr>
            <td>
              <div style="font-weight:600; max-width:300px;">
                <?= htmlspecialchars($a['title']) ?>
                <?php if ($a['is_featured']): ?><span class="badge badge-warning" style="margin-left:6px;">⭐ Featured</span><?php endif; ?>
              </div>
              <div style="font-size:11px; color:var(--text-muted); margin-top:3px;">/blog/<?= htmlspecialchars($a['slug']) ?></div>
            </td>
            <td>
              <?php if ($a['category_name']): ?>
                <span class="badge" style="background:<?= htmlspecialchars($a['category_color']) ?>22; color:<?= htmlspecialchars($a['category_color']) ?>; border-color:<?= htmlspecialchars($a['category_color']) ?>44;">
                  <?= htmlspecialchars($a['category_name']) ?>
                </span>
              <?php else: ?>
                <span style="color:var(--text-muted);">—</span>
              <?php endif; ?>
            </td>
            <td>
              <?php
              $badges = ['published'=>'badge-success','draft'=>'badge-warning','scheduled'=>'badge-info','archived'=>'badge-danger'];
              $labels = ['published'=>'✅ Terbit','draft'=>'📝 Draft','scheduled'=>'🕐 Terjadwal','archived'=>'📦 Arsip'];
              ?>
              <span class="badge <?= $badges[$a['status']] ?? 'badge-info' ?>"><?= $labels[$a['status']] ?? $a['status'] ?></span>
            </td>
            <td style="color:var(--text-secondary);"><?= htmlspecialchars($a['author_name']) ?></td>
            <td style="font-size:12px; color:var(--text-muted);">
              <?php if ($a['status'] === 'scheduled' && $a['scheduled_at']): ?>
                🕐 <?= date('d M Y H:i', strtotime($a['scheduled_at'])) ?>
              <?php elseif ($a['published_at']): ?>
                <?= date('d M Y', strtotime($a['published_at'])) ?>
              <?php else: ?>
                —
              <?php endif; ?>
            </td>
            <td style="color:var(--text-secondary);"><?= number_format($a['view_count']) ?></td>
            <td>
              <div style="display:flex; gap:6px;">
                <a href="/admin/articles/edit.php?id=<?= $a['id'] ?>" class="btn btn-ghost btn-sm">✏️ Edit</a>
                <?php if ($a['status'] === 'published'): ?>
                  <a href="/blog/<?= htmlspecialchars($a['slug']) ?>" target="_blank" class="btn btn-ghost btn-sm">🌐</a>
                <?php endif; ?>
                <button onclick="deleteArticle(<?= $a['id'] ?>, '<?= htmlspecialchars(addslashes($a['title'])) ?>')" class="btn btn-danger btn-sm">🗑️</button>
              </div>
            </td>
          </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <!-- Pagination -->
  <?php if ($totalPages > 1): ?>
  <div style="display:flex; justify-content:center; gap:8px; margin-top:20px; padding-top:16px; border-top:1px solid var(--border);">
    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
      <a href="?page=<?= $i ?>&status=<?= urlencode($status) ?>&category=<?= urlencode($category) ?>&search=<?= urlencode($search) ?>"
         style="padding:6px 12px; border-radius:6px; font-size:13px; border:1px solid <?= $i === $page ? 'var(--accent-cyan)' : 'var(--border)' ?>; background:<?= $i === $page ? 'rgba(6,182,212,.15)' : 'var(--bg-card)' ?>; color:<?= $i === $page ? 'var(--accent-cyan)' : 'var(--text-secondary)' ?>;">
        <?= $i ?>
      </a>
    <?php endfor; ?>
  </div>
  <?php endif; ?>
</div>

<form id="deleteForm" method="POST" action="/admin/articles/delete.php">
  <input type="hidden" name="id" id="deleteId">
</form>

<script>
function deleteArticle(id, title) {
  if (confirm('Hapus artikel "' + title + '"?\nTindakan ini tidak bisa dibatalkan.')) {
    document.getElementById('deleteId').value = id;
    document.getElementById('deleteForm').submit();
  }
}
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>


