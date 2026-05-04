<?php
$pageTitle = 'Tambah Menu Group';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../config/database.php';

$pdo = getDB();
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name        = sanitize($_POST['name'] ?? '');
    $slug        = sanitize($_POST['slug'] ?? '');
    $description = sanitize($_POST['description'] ?? '');
    $is_active   = isset($_POST['is_active']) ? 1 : 0;

    if (empty($name)) $errors[] = 'Nama menu wajib diisi.';
    if (empty($slug)) {
        $slug = generateSlug($name);
    } else {
        $slug = generateSlug($slug);
    }

    // Check slug unique
    if (!empty($slug)) {
        $check = $pdo->prepare("SELECT id FROM nav_menus WHERE slug = ?");
        $check->execute([$slug]);
        if ($check->fetch()) $errors[] = 'Slug sudah digunakan, gunakan slug lain.';
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare("INSERT INTO nav_menus (name, slug, description, is_active) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $slug, $description ?: null, $is_active]);
        $newId = $pdo->lastInsertId();
        logActivity($_SESSION['admin_user_id'], 'create_menu', "Created menu group: $name");
        setFlash('success', "Menu group \"$name\" berhasil dibuat.");
        redirect('/admin/menus/index.php');
    }
}
?>

<div style="max-width: 600px;">
  <div class="card">
    <div class="card-header">
      <h3 class="card-title">➕ Tambah Menu Group</h3>
      <a href="/admin/menus/index.php" class="btn btn-ghost btn-sm">← Kembali</a>
    </div>

    <?php if (!empty($errors)): ?>
      <div class="flash flash-error">
        ❌ <div><?php foreach ($errors as $e): ?><div><?= htmlspecialchars($e) ?></div><?php endforeach; ?></div>
      </div>
    <?php endif; ?>

    <form method="POST">
      <div class="form-group">
        <label class="form-label">Nama Menu <span class="required">*</span></label>
        <input type="text" name="name" class="form-control" placeholder="Contoh: Main Navigation"
               value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" required
               oninput="autoSlug(this.value)">
      </div>

      <div class="form-group">
        <label class="form-label">Slug <span class="required">*</span></label>
        <input type="text" name="slug" id="slug-field" class="form-control" placeholder="main-navigation"
               value="<?= htmlspecialchars($_POST['slug'] ?? '') ?>">
        <p class="form-hint">Digunakan sebagai identifier unik. Otomatis dibuat dari nama.</p>
      </div>

      <div class="form-group">
        <label class="form-label">Deskripsi</label>
        <textarea name="description" class="form-control" rows="3" placeholder="Deskripsi singkat menu ini..."><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
      </div>

      <div class="form-group">
        <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
          <input type="checkbox" name="is_active" value="1" <?= (!isset($_POST['is_active']) || $_POST['is_active']) ? 'checked' : '' ?>
                 style="width: 16px; height: 16px; accent-color: var(--accent-cyan);">
          <span class="form-label" style="margin: 0;">Aktifkan menu ini</span>
        </label>
      </div>

      <div style="display: flex; gap: 10px; margin-top: 8px;">
        <button type="submit" class="btn btn-primary">💾 Simpan Menu Group</button>
        <a href="/admin/menus/index.php" class="btn btn-ghost">Batal</a>
      </div>
    </form>
  </div>
</div>

<script>
function autoSlug(val) {
  var slug = val.toLowerCase()
    .replace(/[^a-z0-9\s-]/g, '')
    .replace(/[\s]+/g, '-')
    .replace(/-+/g, '-')
    .replace(/^-|-$/g, '');
  document.getElementById('slug-field').value = slug;
}
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>


