<?php
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/helpers.php';
requireLogin();

$pdo = getDB();

// Handle POST BEFORE any HTML output
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'create') {
        $name  = trim($_POST['name'] ?? '');
        $slug  = generateSlug($name);
        $desc  = trim($_POST['description'] ?? '');
        $color = $_POST['color'] ?? '#b84d7a';
        if ($name) {
            $existing = $pdo->prepare("SELECT id FROM article_categories WHERE slug=?");
            $existing->execute([$slug]);
            if ($existing->fetch()) $slug .= '-' . time();
            $pdo->prepare("INSERT INTO article_categories (name, slug, description, color) VALUES (?,?,?,?)")
                ->execute([$name, $slug, $desc, $color]);
            setFlash('success', "Kategori '$name' berhasil ditambahkan.");
        }
    }

    if ($action === 'update') {
        $id     = (int)$_POST['id'];
        $name   = trim($_POST['name'] ?? '');
        $desc   = trim($_POST['description'] ?? '');
        $color  = $_POST['color'] ?? '#b84d7a';
        $active = isset($_POST['is_active']) ? 1 : 0;
        if ($name && $id) {
            $pdo->prepare("UPDATE article_categories SET name=?, description=?, color=?, is_active=? WHERE id=?")
                ->execute([$name, $desc, $color, $active, $id]);
            setFlash('success', 'Kategori berhasil diperbarui.');
        }
    }

    if ($action === 'delete') {
        $id = (int)$_POST['id'];
        $pdo->prepare("UPDATE articles SET category_id=NULL WHERE category_id=?")->execute([$id]);
        $pdo->prepare("DELETE FROM article_categories WHERE id=?")->execute([$id]);
        setFlash('success', 'Kategori berhasil dihapus.');
    }

    header('Location: /admin/articles/categories.php');
    exit;
}

$pageTitle = 'Kategori Artikel';
require_once __DIR__ . '/../includes/header.php';

$categories = $pdo->query("
    SELECT ac.*, COUNT(a.id) as article_count
    FROM article_categories ac
    LEFT JOIN articles a ON a.category_id = ac.id
    GROUP BY ac.id
    ORDER BY ac.sort_order, ac.name
")->fetchAll();
?>

<div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:24px;">
  <div>
    <h2 style="font-size:20px; font-weight:700;">📂 Kategori Artikel</h2>
    <p style="color:var(--text-muted); font-size:13px; margin-top:4px;">Kelola kategori untuk mengorganisir artikel</p>
  </div>
  <a href="/admin/articles/index.php" class="btn btn-ghost">← Kembali ke Artikel</a>
</div>

<div style="display:grid; grid-template-columns:1fr 380px; gap:24px; align-items:start;">

  <!-- List -->
  <div class="card">
    <div class="card-header">
      <h3 class="card-title">Daftar Kategori</h3>
    </div>
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>Nama</th>
            <th>Slug</th>
            <th>Artikel</th>
            <th>Status</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($categories)): ?>
            <tr><td colspan="5" style="text-align:center; color:var(--text-muted); padding:32px;">Belum ada kategori</td></tr>
          <?php else: ?>
            <?php foreach ($categories as $cat): ?>
            <tr>
              <td>
                <div style="display:flex; align-items:center; gap:8px;">
                  <span style="width:12px; height:12px; border-radius:50%; background:<?= htmlspecialchars($cat['color']) ?>; flex-shrink:0;"></span>
                  <strong><?= htmlspecialchars($cat['name']) ?></strong>
                </div>
                <?php if ($cat['description']): ?>
                  <div style="font-size:12px; color:var(--text-muted); margin-top:2px;"><?= htmlspecialchars($cat['description']) ?></div>
                <?php endif; ?>
              </td>
              <td style="font-size:12px; color:var(--text-muted);"><?= htmlspecialchars($cat['slug']) ?></td>
              <td><span class="badge badge-info"><?= $cat['article_count'] ?></span></td>
              <td>
                <span class="badge <?= $cat['is_active'] ? 'badge-success' : 'badge-danger' ?>">
                  <?= $cat['is_active'] ? 'Aktif' : 'Nonaktif' ?>
                </span>
              </td>
              <td>
                <div style="display:flex; gap:6px;">
                  <button onclick="editCategory(<?= htmlspecialchars(json_encode($cat)) ?>)" class="btn btn-ghost btn-sm">✏️</button>
                  <form method="POST" style="display:inline;" onsubmit="return confirm('Hapus kategori ini?')">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id" value="<?= $cat['id'] ?>">
                    <button type="submit" class="btn btn-danger btn-sm">🗑️</button>
                  </form>
                </div>
              </td>
            </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Form -->
  <div class="card" id="formCard">
    <h3 style="font-size:15px; font-weight:600; margin-bottom:16px;" id="formTitle">➕ Tambah Kategori</h3>
    <form method="POST" id="categoryForm">
      <input type="hidden" name="action" id="formAction" value="create">
      <input type="hidden" name="id" id="formId" value="">

      <div class="form-group">
        <label class="form-label">Nama Kategori <span class="required">*</span></label>
        <input type="text" name="name" id="formName" class="form-control" placeholder="Contoh: Regulasi & BPOM" required>
      </div>
      <div class="form-group">
        <label class="form-label">Deskripsi</label>
        <textarea name="description" id="formDesc" class="form-control" rows="2" placeholder="Deskripsi singkat kategori..."></textarea>
      </div>
      <div class="form-group">
        <label class="form-label">Warna Label</label>
        <div style="display:flex; align-items:center; gap:10px;">
          <input type="color" name="color" id="formColor" value="#b84d7a"
                 style="width:48px; height:36px; border:1px solid var(--border); border-radius:6px; cursor:pointer; background:none; padding:2px;">
          <span style="font-size:13px; color:var(--text-muted);">Warna badge kategori</span>
        </div>
      </div>
      <div class="form-group" id="activeField" style="display:none;">
        <label style="display:flex; align-items:center; gap:8px; cursor:pointer;">
          <input type="checkbox" name="is_active" id="formActive" value="1" checked style="width:16px; height:16px; accent-color:var(--accent-cyan);">
          <span style="font-size:13px;">Aktif</span>
        </label>
      </div>
      <div style="display:flex; gap:8px;">
        <button type="submit" class="btn btn-primary" style="flex:1; justify-content:center;" id="submitBtn">➕ Tambah</button>
        <button type="button" onclick="resetForm()" class="btn btn-ghost">Reset</button>
      </div>
    </form>
  </div>
</div>

<script>
function editCategory(cat) {
  document.getElementById('formTitle').textContent = '✏️ Edit Kategori';
  document.getElementById('formAction').value = 'update';
  document.getElementById('formId').value = cat.id;
  document.getElementById('formName').value = cat.name;
  document.getElementById('formDesc').value = cat.description || '';
  document.getElementById('formColor').value = cat.color || '#b84d7a';
  document.getElementById('formActive').checked = cat.is_active == 1;
  document.getElementById('activeField').style.display = 'block';
  document.getElementById('submitBtn').textContent = '💾 Simpan';
  document.getElementById('formCard').scrollIntoView({ behavior: 'smooth' });
}
function resetForm() {
  document.getElementById('formTitle').textContent = '➕ Tambah Kategori';
  document.getElementById('formAction').value = 'create';
  document.getElementById('formId').value = '';
  document.getElementById('formName').value = '';
  document.getElementById('formDesc').value = '';
  document.getElementById('formColor').value = '#b84d7a';
  document.getElementById('activeField').style.display = 'none';
  document.getElementById('submitBtn').textContent = '➕ Tambah';
}
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>


