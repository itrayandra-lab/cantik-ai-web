<?php
$pageTitle = 'Menu Items';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../config/database.php';

$pdo = getDB();

// Get all menus for selector
$allMenus = $pdo->query("SELECT * FROM nav_menus ORDER BY name")->fetchAll();
$selectedMenuId = (int)($_GET['menu_id'] ?? ($allMenus[0]['id'] ?? 0));

// Handle delete
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $item = $pdo->prepare("SELECT * FROM nav_menu_items WHERE id = ?");
    $item->execute([(int)$_GET['delete']]);
    $item = $item->fetch();
    if ($item && $item['icon_image']) {
        deleteUploadedFile($item['icon_image']);
    }
    // Also delete children
    $pdo->prepare("DELETE FROM nav_menu_items WHERE parent_id = ?")->execute([(int)$_GET['delete']]);
    $pdo->prepare("DELETE FROM nav_menu_items WHERE id = ?")->execute([(int)$_GET['delete']]);
    logActivity($_SESSION['admin_user_id'], 'delete_menu_item', 'Deleted menu item ID: ' . $_GET['delete']);
    setFlash('success', 'Menu item berhasil dihapus.');
    redirect('/admin/menus/items.php?menu_id=' . $selectedMenuId);
}

// Handle toggle active
if (isset($_GET['toggle']) && is_numeric($_GET['toggle'])) {
    $pdo->prepare("UPDATE nav_menu_items SET is_active = NOT is_active WHERE id = ?")->execute([(int)$_GET['toggle']]);
    setFlash('success', 'Status item berhasil diubah.');
    redirect('/admin/menus/items.php?menu_id=' . $selectedMenuId);
}

// Handle sort order update (AJAX)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'sort') {
    header('Content-Type: application/json');
    $orders = json_decode($_POST['orders'] ?? '[]', true);
    foreach ($orders as $o) {
        $pdo->prepare("UPDATE nav_menu_items SET sort_order = ? WHERE id = ?")->execute([(int)$o['order'], (int)$o['id']]);
    }
    echo json_encode(['success' => true]);
    exit;
}

// Fetch items for selected menu (top-level only, with children)
$topItems = [];
if ($selectedMenuId) {
    $stmt = $pdo->prepare("SELECT * FROM nav_menu_items WHERE menu_id = ? AND parent_id IS NULL ORDER BY sort_order, id");
    $stmt->execute([$selectedMenuId]);
    $topItems = $stmt->fetchAll();

    foreach ($topItems as &$item) {
        $sub = $pdo->prepare("SELECT * FROM nav_menu_items WHERE parent_id = ? ORDER BY sort_order, id");
        $sub->execute([$item['id']]);
        $item['children'] = $sub->fetchAll();
    }
    unset($item);
}

// Get selected menu info
$selectedMenu = null;
if ($selectedMenuId) {
    $stmt = $pdo->prepare("SELECT * FROM nav_menus WHERE id = ?");
    $stmt->execute([$selectedMenuId]);
    $selectedMenu = $stmt->fetch();
}
?>

<style>
.menu-selector { display: flex; align-items: center; gap: 12px; margin-bottom: 20px; flex-wrap: wrap; }
.menu-selector label { font-size: 13px; color: var(--text-secondary); font-weight: 500; }
.menu-selector select { padding: 8px 12px; background: var(--bg-surface); border: 1px solid var(--border); border-radius: 8px; color: var(--text-primary); font-size: 14px; outline: none; cursor: pointer; }
.menu-selector select:focus { border-color: var(--accent-cyan); }

.items-tree { display: flex; flex-direction: column; gap: 8px; }
.menu-item-row {
  background: var(--bg-surface); border: 1px solid var(--border);
  border-radius: 10px; overflow: hidden;
}
.menu-item-main {
  display: flex; align-items: center; gap: 12px;
  padding: 12px 16px;
}
.menu-item-main:hover { background: rgba(255,255,255,.02); }
.item-icon-preview {
  width: 36px; height: 36px; border-radius: 8px;
  border: 1px solid var(--border); object-fit: contain;
  background: var(--bg-base); padding: 4px; flex-shrink: 0;
}
.item-icon-empty {
  width: 36px; height: 36px; border-radius: 8px;
  border: 1px dashed var(--border-light);
  display: flex; align-items: center; justify-content: center;
  color: var(--text-muted); font-size: 14px; flex-shrink: 0;
  background: var(--bg-base);
}
.item-info { flex: 1; min-width: 0; }
.item-title { font-size: 14px; font-weight: 600; }
.item-url { font-size: 12px; color: var(--text-muted); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 300px; }
.item-meta { display: flex; align-items: center; gap: 8px; margin-top: 4px; flex-wrap: wrap; }
.item-actions { display: flex; align-items: center; gap: 6px; flex-shrink: 0; }

.submenu-list {
  border-top: 1px solid var(--border);
  background: rgba(0,0,0,.15);
}
.submenu-item {
  display: flex; align-items: center; gap: 12px;
  padding: 10px 16px 10px 48px;
  border-bottom: 1px solid rgba(51,65,85,.4);
}
.submenu-item:last-child { border-bottom: none; }
.submenu-item:hover { background: rgba(255,255,255,.02); }
.submenu-arrow { color: var(--text-muted); font-size: 12px; margin-right: 4px; }

.add-submenu-row {
  padding: 8px 16px 8px 48px;
  border-top: 1px solid rgba(51,65,85,.4);
}

/* Modal form */
.upload-area {
  border: 2px dashed var(--border);
  border-radius: 10px; padding: 20px;
  text-align: center; cursor: pointer;
  transition: border-color .2s, background .2s;
  position: relative;
}
.upload-area:hover, .upload-area.dragover { border-color: var(--accent-cyan); background: rgba(6,182,212,.05); }
.upload-area input[type="file"] { position: absolute; inset: 0; opacity: 0; cursor: pointer; }
.upload-area .upload-icon { font-size: 32px; margin-bottom: 8px; }
.upload-area p { font-size: 13px; color: var(--text-secondary); }
.upload-area small { font-size: 11px; color: var(--text-muted); }
.current-image-preview { display: flex; align-items: center; gap: 10px; padding: 10px; background: var(--bg-base); border-radius: 8px; margin-top: 8px; }
.current-image-preview img { width: 40px; height: 40px; object-fit: contain; border-radius: 6px; }
.current-image-preview span { font-size: 12px; color: var(--text-secondary); }

/* ===== URL TYPE TABS ===== */
.url-type-tabs {
  display: flex; gap: 4px; margin-bottom: 10px;
  background: var(--bg-base); border-radius: 8px; padding: 4px;
  border: 1px solid var(--border);
}
.url-tab {
  flex: 1; padding: 6px 8px; border: none; border-radius: 6px;
  background: transparent; color: var(--text-muted);
  font-size: 12px; font-weight: 500; cursor: pointer;
  transition: background .15s, color .15s;
  white-space: nowrap;
}
.url-tab:hover { color: var(--text-primary); background: var(--bg-card); }
.url-tab.active {
  background: linear-gradient(135deg, rgba(6,182,212,.2), rgba(139,92,246,.2));
  color: var(--accent-cyan);
  border: 1px solid rgba(6,182,212,.3);
}

/* URL Input with prefix */
.url-input-wrap {
  display: flex; align-items: center;
  background: var(--bg-base); border: 1px solid var(--border);
  border-radius: 8px; overflow: hidden;
  transition: border-color .2s, box-shadow .2s;
}
.url-input-wrap:focus-within {
  border-color: var(--accent-cyan);
  box-shadow: 0 0 0 3px rgba(6,182,212,.12);
}
.url-prefix {
  padding: 9px 10px 9px 13px;
  color: var(--text-muted); font-size: 13px; font-weight: 600;
  background: rgba(255,255,255,.03);
  border-right: 1px solid var(--border);
  white-space: nowrap; flex-shrink: 0;
  user-select: none;
}
.url-sub-input {
  border: none !important; border-radius: 0 !important;
  box-shadow: none !important; background: transparent !important;
  flex: 1;
}
.url-sub-input:focus { box-shadow: none !important; }

/* External URL preview bar */
.url-preview-bar {
  display: flex; align-items: center; gap: 8px;
  margin-top: 8px; padding: 8px 12px;
  background: rgba(6,182,212,.06); border: 1px solid rgba(6,182,212,.2);
  border-radius: 8px;
}
.url-preview-icon { font-size: 14px; }
.url-preview-link {
  flex: 1; font-size: 12px; color: var(--accent-cyan);
  white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
  text-decoration: underline;
}
.url-preview-badge {
  font-size: 10px; color: var(--text-muted);
  background: var(--bg-card); padding: 2px 6px; border-radius: 4px;
  flex-shrink: 0;
}

/* Result preview bar */
.url-result-preview {
  display: flex; align-items: center; gap: 8px;
  margin-top: 10px; padding: 9px 12px;
  background: var(--bg-base); border: 1px solid var(--border);
  border-radius: 8px;
}
.url-result-label {
  font-size: 11px; font-weight: 600; color: var(--text-muted);
  text-transform: uppercase; letter-spacing: .05em; flex-shrink: 0;
}
.url-result-value {
  flex: 1; font-size: 13px; font-family: monospace;
  color: var(--accent-cyan); word-break: break-all;
}
.url-copy-btn {
  background: none; border: none; font-size: 14px; padding: 2px 4px;
  border-radius: 4px; color: var(--text-muted); cursor: pointer;
  transition: background .15s;
}
.url-copy-btn:hover { background: var(--bg-card); color: var(--text-primary); }
</style>

<!-- Menu Selector -->
<div class="menu-selector">
  <label>Pilih Menu:</label>
  <select onchange="location.href='/admin/menus/items.php?menu_id='+this.value">
    <?php foreach ($allMenus as $m): ?>
      <option value="<?= $m['id'] ?>" <?= $m['id'] == $selectedMenuId ? 'selected' : '' ?>>
        <?= htmlspecialchars($m['name']) ?>
      </option>
    <?php endforeach; ?>
  </select>
  <?php if ($selectedMenu): ?>
    <span class="badge <?= $selectedMenu['is_active'] ? 'badge-success' : 'badge-danger' ?>">
      <?= $selectedMenu['is_active'] ? '● Aktif' : '● Nonaktif' ?>
    </span>
  <?php endif; ?>
  <div style="margin-left: auto; display: flex; gap: 8px;">
    <a href="/admin/menus/index.php" class="btn btn-ghost btn-sm">⚙️ Kelola Groups</a>
    <?php if ($selectedMenuId): ?>
      <button class="btn btn-primary btn-sm" onclick="openModal('add', null, <?= $selectedMenuId ?>)">
        ➕ Tambah Menu Item
      </button>
    <?php endif; ?>
  </div>
</div>

<?php if (!$selectedMenuId || empty($allMenus)): ?>
  <div class="card" style="text-align: center; padding: 48px;">
    <div style="font-size: 48px; margin-bottom: 16px;">📋</div>
    <h3 style="margin-bottom: 8px;">Belum ada menu group</h3>
    <p style="color: var(--text-muted); margin-bottom: 20px;">Buat menu group terlebih dahulu sebelum menambahkan items.</p>
    <a href="/admin/menus/create.php" class="btn btn-primary">➕ Buat Menu Group</a>
  </div>
<?php else: ?>

<div class="card">
  <div class="card-header">
    <h3 class="card-title">🔗 Items: <?= htmlspecialchars($selectedMenu['name'] ?? '') ?></h3>
    <span style="color: var(--text-muted); font-size: 13px;"><?= count($topItems) ?> top-level items</span>
  </div>

  <?php if (empty($topItems)): ?>
    <div style="text-align: center; padding: 48px; color: var(--text-muted);">
      <div style="font-size: 40px; margin-bottom: 12px;">🔗</div>
      <p>Belum ada menu item. Klik <strong>Tambah Menu Item</strong> untuk mulai.</p>
    </div>
  <?php else: ?>
    <div class="items-tree" id="items-tree">
      <?php foreach ($topItems as $item): ?>
        <div class="menu-item-row" data-id="<?= $item['id'] ?>">
          <!-- Top-level item -->
          <div class="menu-item-main">
            <span class="drag-handle" title="Drag to reorder">⠿</span>

            <!-- Icon/Image -->
            <?php if ($item['icon_image']): ?>
              <img src="/<?= htmlspecialchars($item['icon_image']) ?>"
                   alt="<?= htmlspecialchars($item['icon_alt'] ?? '') ?>"
                   class="item-icon-preview">
            <?php else: ?>
              <div class="item-icon-empty">🖼️</div>
            <?php endif; ?>

            <div class="item-info">
              <div class="item-title"><?= htmlspecialchars($item['title']) ?></div>
              <div class="item-url">🔗 <?= htmlspecialchars($item['url']) ?></div>
              <div class="item-meta">
                <?php if ($item['target'] === '_blank'): ?>
                  <span class="badge badge-warning" style="font-size: 10px;">↗ New Tab</span>
                <?php endif; ?>
                <?php if (!empty($item['children'])): ?>
                  <span class="badge badge-purple" style="font-size: 10px;">▼ <?= count($item['children']) ?> submenu</span>
                <?php endif; ?>
                <?php if ($item['css_class']): ?>
                  <span class="badge badge-info" style="font-size: 10px;">.<?= htmlspecialchars($item['css_class']) ?></span>
                <?php endif; ?>
              </div>
            </div>

            <div class="item-actions">
              <a href="?menu_id=<?= $selectedMenuId ?>&toggle=<?= $item['id'] ?>">
                <?php if ($item['is_active']): ?>
                  <span class="badge badge-success">● Aktif</span>
                <?php else: ?>
                  <span class="badge badge-danger">● Nonaktif</span>
                <?php endif; ?>
              </a>
              <button class="btn btn-ghost btn-sm btn-icon" title="Tambah Submenu"
                      onclick="openModal('add-sub', null, <?= $selectedMenuId ?>, <?= $item['id'] ?>)">➕</button>
              <button class="btn btn-ghost btn-sm btn-icon" title="Edit"
                      onclick="openModal('edit', <?= htmlspecialchars(json_encode($item)) ?>)">✏️</button>
              <a href="?menu_id=<?= $selectedMenuId ?>&delete=<?= $item['id'] ?>"
                 class="btn btn-danger btn-sm btn-icon" title="Hapus"
                 data-confirm="Hapus item ini? Semua submenu-nya juga akan terhapus!">🗑️</a>
            </div>
          </div>

          <!-- Submenu items -->
          <?php if (!empty($item['children'])): ?>
            <div class="submenu-list">
              <?php foreach ($item['children'] as $child): ?>
                <div class="submenu-item" data-id="<?= $child['id'] ?>">
                  <span class="drag-handle">⠿</span>
                  <span class="submenu-arrow">└─</span>

                  <?php if ($child['icon_image']): ?>
                    <img src="/<?= htmlspecialchars($child['icon_image']) ?>"
                         alt="<?= htmlspecialchars($child['icon_alt'] ?? '') ?>"
                         class="item-icon-preview" style="width:28px;height:28px;">
                  <?php else: ?>
                    <div class="item-icon-empty" style="width:28px;height:28px;font-size:12px;">🖼️</div>
                  <?php endif; ?>

                  <div class="item-info">
                    <div class="item-title" style="font-size: 13px;"><?= htmlspecialchars($child['title']) ?></div>
                    <div class="item-url">🔗 <?= htmlspecialchars($child['url']) ?></div>
                  </div>

                  <div class="item-actions">
                    <a href="?menu_id=<?= $selectedMenuId ?>&toggle=<?= $child['id'] ?>">
                      <?php if ($child['is_active']): ?>
                        <span class="badge badge-success" style="font-size: 10px;">● Aktif</span>
                      <?php else: ?>
                        <span class="badge badge-danger" style="font-size: 10px;">● Nonaktif</span>
                      <?php endif; ?>
                    </a>
                    <button class="btn btn-ghost btn-sm btn-icon" title="Edit"
                            onclick="openModal('edit', <?= htmlspecialchars(json_encode($child)) ?>)">✏️</button>
                    <a href="?menu_id=<?= $selectedMenuId ?>&delete=<?= $child['id'] ?>"
                       class="btn btn-danger btn-sm btn-icon" title="Hapus"
                       data-confirm="Hapus submenu item ini?">🗑️</a>
                  </div>
                </div>
              <?php endforeach; ?>
              <div class="add-submenu-row">
                <button class="btn btn-ghost btn-sm" onclick="openModal('add-sub', null, <?= $selectedMenuId ?>, <?= $item['id'] ?>)">
                  ➕ Tambah Submenu
                </button>
              </div>
            </div>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>

<?php endif; ?>

<!-- ===== MODAL: Add / Edit Menu Item ===== -->
<div class="modal-overlay" id="item-modal">
  <div class="modal">
    <div class="modal-header">
      <h3 class="modal-title" id="modal-title">Tambah Menu Item</h3>
      <button class="modal-close" onclick="closeModal()">✕</button>
    </div>

    <form method="POST" action="/admin/menus/save-item.php" enctype="multipart/form-data" id="item-form">
      <input type="hidden" name="id" id="f-id">
      <input type="hidden" name="menu_id" id="f-menu-id" value="<?= $selectedMenuId ?>">
      <input type="hidden" name="parent_id" id="f-parent-id">
      <input type="hidden" name="redirect_menu_id" value="<?= $selectedMenuId ?>">

      <!-- Icon/Image Upload -->
      <div class="form-group">
        <label class="form-label">🖼️ Icon / Gambar Menu</label>
        <div class="upload-area" id="upload-area">
          <input type="file" name="icon_image" id="f-icon-file" accept="image/*" onchange="previewImage(this)">
          <div class="upload-icon">📁</div>
          <p>Klik atau drag gambar ke sini</p>
          <small>JPG, PNG, SVG, WEBP — Maks. 2MB</small>
        </div>
        <!-- Preview new upload -->
        <div id="new-image-preview" style="display:none; margin-top: 8px;">
          <div class="current-image-preview">
            <img id="preview-img" src="" alt="preview">
            <span id="preview-name"></span>
            <button type="button" onclick="clearImage()" class="btn btn-danger btn-sm" style="margin-left: auto;">✕ Hapus</button>
          </div>
        </div>
        <!-- Current image (edit mode) -->
        <div id="current-image-wrap" style="display:none; margin-top: 8px;">
          <div class="current-image-preview">
            <img id="current-img" src="" alt="current">
            <span>Gambar saat ini</span>
            <label style="margin-left: auto; display: flex; align-items: center; gap: 6px; cursor: pointer; font-size: 12px; color: var(--text-muted);">
              <input type="checkbox" name="remove_image" value="1" id="f-remove-image"
                     style="accent-color: var(--accent-red);">
              Hapus gambar
            </label>
          </div>
        </div>
        <div class="form-group" style="margin-top: 10px; margin-bottom: 0;">
          <label class="form-label" style="font-size: 12px;">Alt text gambar</label>
          <input type="text" name="icon_alt" id="f-icon-alt" class="form-control" placeholder="Deskripsi gambar untuk aksesibilitas">
        </div>
      </div>

      <!-- Title -->
      <div class="form-group">
        <label class="form-label">Judul Menu <span class="required">*</span></label>
        <input type="text" name="title" id="f-title" class="form-control" placeholder="Contoh: Beranda" required>
      </div>

      <!-- URL Section -->
      <div class="form-group">
        <label class="form-label">URL / Link <span class="required">*</span></label>

        <!-- URL Type Tabs -->
        <div class="url-type-tabs">
          <button type="button" class="url-tab active" data-type="internal" onclick="setUrlType('internal')">
            🏠 Internal
          </button>
          <button type="button" class="url-tab" data-type="external" onclick="setUrlType('external')">
            🌐 External
          </button>
          <button type="button" class="url-tab" data-type="anchor" onclick="setUrlType('anchor')">
            ⚓ Anchor
          </button>
          <button type="button" class="url-tab" data-type="custom" onclick="setUrlType('custom')">
            ✏️ Custom
          </button>
        </div>

        <!-- Internal URL -->
        <div class="url-panel" id="url-panel-internal">
          <div class="url-input-wrap">
            <span class="url-prefix" id="url-prefix-internal">/</span>
            <input type="text" id="url-internal" class="form-control url-sub-input"
                   placeholder="contoh: beranda, fitur/ai, pricing"
                   oninput="syncUrl('internal')">
          </div>
          <p class="form-hint">Path halaman di website ini. Contoh: <code>beranda</code> → <code>/beranda</code></p>
        </div>

        <!-- External URL -->
        <div class="url-panel" id="url-panel-external" style="display:none;">
          <div class="url-input-wrap">
            <span class="url-prefix">🔗</span>
            <input type="url" id="url-external" class="form-control url-sub-input"
                   placeholder="https://example.com/halaman"
                   oninput="syncUrl('external')">
          </div>
          <p class="form-hint">URL lengkap ke website lain. Harus diawali <code>https://</code> atau <code>http://</code></p>
          <div class="url-preview-bar" id="ext-preview" style="display:none;">
            <span class="url-preview-icon">🌐</span>
            <a id="ext-preview-link" href="#" target="_blank" class="url-preview-link">—</a>
            <span class="url-preview-badge">↗ Buka di tab baru</span>
          </div>
        </div>

        <!-- Anchor -->
        <div class="url-panel" id="url-panel-anchor" style="display:none;">
          <div class="url-input-wrap">
            <span class="url-prefix">#</span>
            <input type="text" id="url-anchor" class="form-control url-sub-input"
                   placeholder="contoh: section-harga, tentang-kami"
                   oninput="syncUrl('anchor')">
          </div>
          <p class="form-hint">Scroll ke elemen dengan ID tertentu di halaman. Contoh: <code>section-harga</code> → <code>#section-harga</code></p>
        </div>

        <!-- Custom -->
        <div class="url-panel" id="url-panel-custom" style="display:none;">
          <div class="url-input-wrap">
            <span class="url-prefix">✏️</span>
            <input type="text" id="url-custom" class="form-control url-sub-input"
                   placeholder="Masukkan URL bebas, contoh: javascript:void(0)"
                   oninput="syncUrl('custom')">
          </div>
          <p class="form-hint">URL bebas — bisa berupa path, link eksternal, javascript, mailto:, tel:, dll.</p>
        </div>

        <!-- Hidden actual URL field -->
        <input type="hidden" name="url" id="f-url" required>

        <!-- Live URL Preview -->
        <div class="url-result-preview" id="url-result-preview">
          <span class="url-result-label">URL:</span>
          <span class="url-result-value" id="url-result-value">/</span>
          <button type="button" class="url-copy-btn" onclick="copyUrl()" title="Salin URL">📋</button>
        </div>
      </div>

      <div class="form-row">
        <!-- Target -->
        <div class="form-group">
          <label class="form-label">Buka di</label>
          <select name="target" id="f-target" class="form-control" onchange="onTargetChange()">
            <option value="_self">🔁 Tab yang sama</option>
            <option value="_blank">↗ Tab baru</option>
          </select>
        </div>

        <!-- Sort Order -->
        <div class="form-group">
          <label class="form-label">Urutan</label>
          <input type="number" name="sort_order" id="f-sort" class="form-control" value="0" min="0">
        </div>
      </div>

      <!-- CSS Class -->
      <div class="form-group">
        <label class="form-label">CSS Class <span style="color: var(--text-muted); font-weight: 400;">(opsional)</span></label>
        <input type="text" name="css_class" id="f-css" class="form-control" placeholder="Contoh: nav-highlight">
      </div>

      <!-- Active -->
      <div class="form-group">
        <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
          <input type="checkbox" name="is_active" id="f-active" value="1" checked
                 style="width: 16px; height: 16px; accent-color: var(--accent-cyan);">
          <span class="form-label" style="margin: 0;">Aktifkan item ini</span>
        </label>
      </div>

      <div style="display: flex; gap: 10px; margin-top: 4px;">
        <button type="submit" class="btn btn-primary">💾 Simpan</button>
        <button type="button" class="btn btn-ghost" onclick="closeModal()">Batal</button>
      </div>
    </form>
  </div>
</div>

<script>
var currentMenuId = <?= $selectedMenuId ?>;
var activeUrlType = 'internal';

// ===== URL TYPE MANAGEMENT =====
function setUrlType(type) {
  activeUrlType = type;

  // Toggle tabs
  document.querySelectorAll('.url-tab').forEach(function(t) {
    t.classList.toggle('active', t.dataset.type === type);
  });

  // Toggle panels
  ['internal','external','anchor','custom'].forEach(function(t) {
    document.getElementById('url-panel-' + t).style.display = t === type ? 'block' : 'none';
  });

  // Auto-set target for external
  if (type === 'external') {
    document.getElementById('f-target').value = '_blank';
  } else if (type === 'internal' || type === 'anchor') {
    document.getElementById('f-target').value = '_self';
  }

  syncUrl(type);
}

function syncUrl(type) {
  var val = '';

  if (type === 'internal') {
    var raw = (document.getElementById('url-internal').value || '').trim();
    raw = raw.replace(/^\/+/, ''); // strip leading slashes
    val = raw ? '/' + raw : '/';
  } else if (type === 'external') {
    val = (document.getElementById('url-external').value || '').trim();
    // Show/hide preview bar
    var previewBar = document.getElementById('ext-preview');
    if (val && (val.startsWith('http://') || val.startsWith('https://'))) {
      previewBar.style.display = 'flex';
      var link = document.getElementById('ext-preview-link');
      link.href = val;
      link.textContent = val;
    } else {
      previewBar.style.display = 'none';
    }
  } else if (type === 'anchor') {
    var raw = (document.getElementById('url-anchor').value || '').trim();
    raw = raw.replace(/^#+/, '');
    val = raw ? '#' + raw : '#';
  } else if (type === 'custom') {
    val = (document.getElementById('url-custom').value || '').trim();
  }

  document.getElementById('f-url').value = val;
  document.getElementById('url-result-value').textContent = val || '—';
}

function onTargetChange() {
  // If user manually picks _blank on external, show hint
}

function copyUrl() {
  var val = document.getElementById('f-url').value;
  if (!val) return;
  navigator.clipboard.writeText(val).then(function() {
    var btn = document.querySelector('.url-copy-btn');
    btn.textContent = '✅';
    setTimeout(function() { btn.textContent = '📋'; }, 1500);
  });
}

// Detect URL type from existing value and populate correct panel
function detectAndSetUrl(url) {
  if (!url || url === '#') {
    setUrlType('internal');
    document.getElementById('url-internal').value = '';
    return;
  }
  if (url.startsWith('http://') || url.startsWith('https://')) {
    setUrlType('external');
    document.getElementById('url-external').value = url;
    syncUrl('external');
  } else if (url.startsWith('#')) {
    setUrlType('anchor');
    document.getElementById('url-anchor').value = url.slice(1);
    syncUrl('anchor');
  } else if (url.startsWith('/')) {
    setUrlType('internal');
    document.getElementById('url-internal').value = url.slice(1);
    syncUrl('internal');
  } else {
    setUrlType('custom');
    document.getElementById('url-custom').value = url;
    syncUrl('custom');
  }
}

// ===== MODAL =====
function openModal(mode, itemData, menuId, parentId) {
  var modal = document.getElementById('item-modal');
  var title = document.getElementById('modal-title');

  // Reset form
  document.getElementById('item-form').reset();
  document.getElementById('f-id').value = '';
  document.getElementById('f-parent-id').value = '';
  document.getElementById('new-image-preview').style.display = 'none';
  document.getElementById('current-image-wrap').style.display = 'none';
  document.getElementById('f-active').checked = true;

  // Reset URL panels
  document.getElementById('url-internal').value = '';
  document.getElementById('url-external').value = '';
  document.getElementById('url-anchor').value = '';
  document.getElementById('url-custom').value = '';
  document.getElementById('ext-preview').style.display = 'none';
  setUrlType('internal');

  if (mode === 'add') {
    title.textContent = '➕ Tambah Menu Item';
    document.getElementById('f-menu-id').value = menuId || currentMenuId;
  } else if (mode === 'add-sub') {
    title.textContent = '➕ Tambah Submenu';
    document.getElementById('f-menu-id').value = menuId || currentMenuId;
    document.getElementById('f-parent-id').value = parentId;
  } else if (mode === 'edit' && itemData) {
    title.textContent = '✏️ Edit Menu Item';
    document.getElementById('f-id').value = itemData.id;
    document.getElementById('f-menu-id').value = itemData.menu_id;
    document.getElementById('f-parent-id').value = itemData.parent_id || '';
    document.getElementById('f-title').value = itemData.title || '';
    document.getElementById('f-target').value = itemData.target || '_self';
    document.getElementById('f-sort').value = itemData.sort_order || 0;
    document.getElementById('f-css').value = itemData.css_class || '';
    document.getElementById('f-icon-alt').value = itemData.icon_alt || '';
    document.getElementById('f-active').checked = itemData.is_active == 1;

    // Detect and populate URL
    detectAndSetUrl(itemData.url || '/');

    if (itemData.icon_image) {
      document.getElementById('current-img').src = '/' + itemData.icon_image;
      document.getElementById('current-image-wrap').style.display = 'block';
    }
  }

  modal.classList.add('open');
}

function closeModal() {
  document.getElementById('item-modal').classList.remove('open');
}

function previewImage(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();
    reader.onload = function(e) {
      document.getElementById('preview-img').src = e.target.result;
      document.getElementById('preview-name').textContent = input.files[0].name;
      document.getElementById('new-image-preview').style.display = 'block';
      document.getElementById('current-image-wrap').style.display = 'none';
    };
    reader.readAsDataURL(input.files[0]);
  }
}

function clearImage() {
  document.getElementById('f-icon-file').value = '';
  document.getElementById('new-image-preview').style.display = 'none';
}

// Close modal on overlay click
document.getElementById('item-modal').addEventListener('click', function(e) {
  if (e.target === this) closeModal();
});

// Drag & drop visual
var uploadArea = document.getElementById('upload-area');
if (uploadArea) {
  uploadArea.addEventListener('dragover', function(e) { e.preventDefault(); this.classList.add('dragover'); });
  uploadArea.addEventListener('dragleave', function() { this.classList.remove('dragover'); });
  uploadArea.addEventListener('drop', function(e) {
    e.preventDefault(); this.classList.remove('dragover');
    var files = e.dataTransfer.files;
    if (files.length) {
      document.getElementById('f-icon-file').files = files;
      previewImage(document.getElementById('f-icon-file'));
    }
  });
}

// Init URL result on page load
syncUrl('internal');
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>


