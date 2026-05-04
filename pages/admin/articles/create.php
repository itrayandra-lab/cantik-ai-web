<?php
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/helpers.php';
requireLogin();

$pdo = getDB();
$categories = $pdo->query("SELECT * FROM article_categories WHERE is_active=1 ORDER BY sort_order")->fetchAll();
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title       = trim($_POST['title'] ?? '');
    $slug        = trim($_POST['slug'] ?? '');
    $excerpt     = trim($_POST['excerpt'] ?? '');
    $content     = $_POST['content'] ?? '';
    $categoryId  = $_POST['category_id'] ?: null;
    $status      = $_POST['status'] ?? 'draft';
    $scheduledAt = $_POST['scheduled_at'] ?? null;
    $seoTitle    = trim($_POST['seo_title'] ?? '');
    $seoDesc     = trim($_POST['seo_description'] ?? '');
    $seoKeywords = trim($_POST['seo_keywords'] ?? '');
    $schemaType  = $_POST['schema_type'] ?? 'BlogPosting';
    $isFeatured  = isset($_POST['is_featured']) ? 1 : 0;
    $tags        = array_filter(array_map('trim', explode(',', $_POST['tags'] ?? '')));

    // Validation
    if (!$title)   $errors[] = 'Judul wajib diisi.';
    if (!$content) $errors[] = 'Konten wajib diisi.';
    if (strlen($seoTitle) > 70)  $errors[] = 'SEO Title maksimal 70 karakter.';
    if (strlen($seoDesc)  > 160) $errors[] = 'SEO Description maksimal 160 karakter.';

    // Auto-generate slug
    if (!$slug) $slug = generateSlug($title);

    // Check slug unique
    $existing = $pdo->prepare("SELECT id FROM articles WHERE slug = ?");
    $existing->execute([$slug]);
    if ($existing->fetch()) {
        $slug = $slug . '-' . time();
    }

    // Estimate read time (~200 words/min)
    $wordCount = str_word_count(strip_tags($content));
    $readTime  = max(1, ceil($wordCount / 200));

    // Handle featured image upload
    $featuredImage    = null;
    $featuredImageAlt = trim($_POST['featured_image_alt'] ?? '');
    if (!empty($_FILES['featured_image']['name'])) {
        $upload = uploadImage($_FILES['featured_image'], 'articles');
        if (isset($upload['error'])) {
            $errors[] = $upload['error'];
        } else {
            $featuredImage = $upload['path'];
        }
    }

    // Published at
    $publishedAt = null;
    if ($status === 'published') $publishedAt = date('Y-m-d H:i:s');
    if ($status === 'scheduled' && $scheduledAt) {
        $scheduledAt = date('Y-m-d H:i:s', strtotime($scheduledAt));
    } else {
        $scheduledAt = null;
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare("
            INSERT INTO articles
              (category_id, author_id, title, slug, excerpt, content, featured_image, featured_image_alt,
               seo_title, seo_description, seo_keywords, schema_type, status, scheduled_at, published_at,
               read_time, is_featured)
            VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)
        ");
        $stmt->execute([
            $categoryId, $_SESSION['admin_user_id'], $title, $slug, $excerpt, $content,
            $featuredImage, $featuredImageAlt, $seoTitle, $seoDesc, $seoKeywords,
            $schemaType, $status, $scheduledAt, $publishedAt, $readTime, $isFeatured
        ]);
        $articleId = $pdo->lastInsertId();

        // Save tags
        foreach ($tags as $tagName) {
            $tagSlug = generateSlug($tagName);
            $pdo->prepare("INSERT IGNORE INTO article_tags (name, slug) VALUES (?,?)")->execute([$tagName, $tagSlug]);
            $tagRow = $pdo->prepare("SELECT id FROM article_tags WHERE slug=?")->execute([$tagSlug]);
            $tagId  = $pdo->query("SELECT id FROM article_tags WHERE slug='$tagSlug'")->fetchColumn();
            if ($tagId) {
                $pdo->prepare("INSERT IGNORE INTO article_tag_pivot (article_id, tag_id) VALUES (?,?)")->execute([$articleId, $tagId]);
            }
        }

        logActivity($_SESSION['admin_user_id'], 'article_create', "Artikel dibuat: $title");
        setFlash('success', 'Artikel berhasil disimpan!');
        header('Location: /admin/articles/index.php');
        exit;
    }
}

$pageTitle = 'Tulis Artikel Baru';
require_once __DIR__ . '/../includes/header.php';
?>

<div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:24px;">
  <div>
    <h2 style="font-size:20px; font-weight:700;">✏️ Tulis Artikel Baru</h2>
    <p style="color:var(--text-muted); font-size:13px; margin-top:4px;">Buat artikel dengan SEO optimal dan jadwal publikasi</p>
  </div>
  <a href="/admin/articles/index.php" class="btn btn-ghost">← Kembali</a>
</div>

<?php if ($errors): ?>
  <div class="flash flash-error">❌ <?= implode('<br>', array_map('htmlspecialchars', $errors)) ?></div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data">
<div style="display:grid; grid-template-columns:1fr 340px; gap:24px; align-items:start;">

  <!-- LEFT: Main Content -->
  <div style="display:flex; flex-direction:column; gap:20px;">

    <!-- Title -->
    <div class="card">
      <div class="form-group" style="margin-bottom:0;">
        <label class="form-label">Judul Artikel <span class="required">*</span></label>
        <input type="text" name="title" class="form-control" style="font-size:18px; font-weight:600;"
               placeholder="Masukkan judul artikel yang menarik..." value="<?= htmlspecialchars($_POST['title'] ?? '') ?>"
               oninput="autoSlug(this.value)" required>
      </div>
      <div class="form-group" style="margin-top:12px; margin-bottom:0;">
        <label class="form-label">Slug URL</label>
        <div style="display:flex; align-items:center; gap:8px;">
          <span style="color:var(--text-muted); font-size:13px; white-space:nowrap;">/blog/</span>
          <input type="text" name="slug" id="slugInput" class="form-control"
                 placeholder="url-artikel-anda" value="<?= htmlspecialchars($_POST['slug'] ?? '') ?>">
        </div>
        <div class="form-hint">Otomatis dari judul. Bisa diedit manual.</div>
      </div>
    </div>

    <!-- Content Editor -->
    <div class="card">
      <label class="form-label">Konten Artikel <span class="required">*</span></label>
      <!-- Toolbar -->
      <div style="display:flex; gap:4px; flex-wrap:wrap; padding:8px; background:var(--bg-base); border:1px solid var(--border); border-bottom:none; border-radius:8px 8px 0 0;">
        <?php
        $tools = [
          ['H2','formatBlock','h2'],['H3','formatBlock','h3'],['H4','formatBlock','h4'],
          ['B','bold',null],['I','italic',null],['U','underline',null],['S','strikeThrough',null],
          ['🔗','createLink',null],['📷','insertImage',null],
          ['• List','insertUnorderedList',null],['1. List','insertOrderedList',null],
          ['❝','formatBlock','blockquote'],['</> Code','formatBlock','pre'],
          ['↩','undo',null],['↪','redo',null],
        ];
        foreach ($tools as [$label, $cmd, $val]):
        ?>
          <button type="button" onclick="execCmd('<?= $cmd ?>','<?= $val ?>')"
                  style="padding:4px 8px; border-radius:4px; border:1px solid var(--border); background:var(--bg-card); color:var(--text-secondary); font-size:12px; cursor:pointer;">
            <?= $label ?>
          </button>
        <?php endforeach; ?>
      </div>
      <div id="editor" contenteditable="true"
           style="min-height:400px; padding:20px; background:var(--bg-base); border:1px solid var(--border); border-radius:0 0 8px 8px; outline:none; font-size:15px; line-height:1.8; color:var(--text-primary);">
        <?= $_POST['content'] ?? '' ?>
      </div>
      <textarea name="content" id="contentInput" style="display:none;"><?= htmlspecialchars($_POST['content'] ?? '') ?></textarea>
      <div class="form-hint" style="margin-top:8px;">
        <span id="wordCount">0</span> kata · estimasi <span id="readTime">0</span> menit baca
      </div>
    </div>

    <!-- Excerpt -->
    <div class="card">
      <div class="form-group" style="margin-bottom:0;">
        <label class="form-label">Excerpt / Ringkasan</label>
        <textarea name="excerpt" class="form-control" rows="3"
                  placeholder="Ringkasan singkat artikel (tampil di listing dan SEO)..."><?= htmlspecialchars($_POST['excerpt'] ?? '') ?></textarea>
        <div class="form-hint">Digunakan untuk preview di halaman blog dan meta description jika SEO Description kosong.</div>
      </div>
    </div>

    <!-- SEO -->
    <div class="card">
      <h3 style="font-size:15px; font-weight:600; margin-bottom:16px;">🔍 SEO Settings</h3>

      <div class="form-group">
        <label class="form-label">SEO Title <span style="color:var(--text-muted); font-weight:400;">(maks. 70 karakter)</span></label>
        <input type="text" name="seo_title" class="form-control" maxlength="70"
               placeholder="Judul untuk Google (kosongkan = pakai judul artikel)"
               value="<?= htmlspecialchars($_POST['seo_title'] ?? '') ?>"
               oninput="updateCounter(this,'seoTitleCount',70)">
        <div class="form-hint"><span id="seoTitleCount">0</span>/70 karakter</div>
      </div>

      <div class="form-group">
        <label class="form-label">SEO Description <span style="color:var(--text-muted); font-weight:400;">(maks. 160 karakter)</span></label>
        <textarea name="seo_description" class="form-control" rows="3" maxlength="160"
                  placeholder="Deskripsi untuk Google (kosongkan = pakai excerpt)"
                  oninput="updateCounter(this,'seoDescCount',160)"><?= htmlspecialchars($_POST['seo_description'] ?? '') ?></textarea>
        <div class="form-hint"><span id="seoDescCount">0</span>/160 karakter · Tampil di hasil pencarian Google</div>
      </div>

      <div class="form-group">
        <label class="form-label">Keywords</label>
        <input type="text" name="seo_keywords" class="form-control"
               placeholder="kosmetik, BPOM, formulasi, skincare (pisah koma)"
               value="<?= htmlspecialchars($_POST['seo_keywords'] ?? '') ?>">
      </div>

      <div class="form-group" style="margin-bottom:0;">
        <label class="form-label">Schema Type</label>
        <select name="schema_type" class="form-control">
          <option value="BlogPosting" <?= ($_POST['schema_type'] ?? '') === 'BlogPosting' ? 'selected' : '' ?>>BlogPosting (Artikel Blog)</option>
          <option value="Article"     <?= ($_POST['schema_type'] ?? '') === 'Article'     ? 'selected' : '' ?>>Article (Artikel Umum)</option>
          <option value="NewsArticle" <?= ($_POST['schema_type'] ?? '') === 'NewsArticle' ? 'selected' : '' ?>>NewsArticle (Berita)</option>
        </select>
        <div class="form-hint">Membantu Google memahami jenis konten untuk rich results.</div>
      </div>

      <!-- SEO Preview -->
      <div style="margin-top:20px; padding:16px; background:var(--bg-base); border-radius:8px; border:1px solid var(--border);">
        <div style="font-size:11px; color:var(--text-muted); margin-bottom:8px;">PREVIEW GOOGLE</div>
        <div id="previewTitle" style="color:#8ab4f8; font-size:18px; font-weight:400; margin-bottom:2px;">Judul artikel Anda</div>
        <div style="color:#4caf50; font-size:13px; margin-bottom:4px;">https://cantik.ai/blog/<span id="previewSlug">slug-artikel</span></div>
        <div id="previewDesc" style="color:#bdc1c6; font-size:14px; line-height:1.5;">Deskripsi artikel akan tampil di sini...</div>
      </div>
    </div>

  </div>

  <!-- RIGHT: Sidebar -->
  <div style="display:flex; flex-direction:column; gap:16px;">

    <!-- Publish -->
    <div class="card">
      <h3 style="font-size:14px; font-weight:600; margin-bottom:14px;">🚀 Publikasi</h3>

      <div class="form-group">
        <label class="form-label">Status</label>
        <select name="status" id="statusSelect" class="form-control" onchange="toggleSchedule(this.value)">
          <option value="draft"     <?= ($_POST['status'] ?? 'draft') === 'draft'     ? 'selected' : '' ?>>📝 Draft</option>
          <option value="published" <?= ($_POST['status'] ?? '') === 'published' ? 'selected' : '' ?>>✅ Terbit Sekarang</option>
          <option value="scheduled" <?= ($_POST['status'] ?? '') === 'scheduled' ? 'selected' : '' ?>>🕐 Jadwalkan</option>
          <option value="archived"  <?= ($_POST['status'] ?? '') === 'archived'  ? 'selected' : '' ?>>📦 Arsip</option>
        </select>
      </div>

      <div id="scheduleField" style="display:none;">
        <div class="form-group">
          <label class="form-label">Tanggal & Waktu Publikasi</label>
          <input type="datetime-local" name="scheduled_at" class="form-control"
                 value="<?= htmlspecialchars($_POST['scheduled_at'] ?? '') ?>"
                 min="<?= date('Y-m-d\TH:i') ?>">
          <div class="form-hint">Artikel akan otomatis terbit pada waktu ini.</div>
        </div>
      </div>

      <div class="form-group" style="margin-bottom:0;">
        <label style="display:flex; align-items:center; gap:8px; cursor:pointer;">
          <input type="checkbox" name="is_featured" value="1" <?= isset($_POST['is_featured']) ? 'checked' : '' ?>
                 style="width:16px; height:16px; accent-color:var(--accent-cyan);">
          <span style="font-size:13px;">⭐ Artikel Featured</span>
        </label>
        <div class="form-hint">Tampil di bagian unggulan halaman blog.</div>
      </div>

      <div style="margin-top:16px; display:flex; flex-direction:column; gap:8px;">
        <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center;">💾 Simpan Artikel</button>
        <button type="submit" name="status" value="draft" class="btn btn-ghost" style="width:100%; justify-content:center;">📝 Simpan sebagai Draft</button>
      </div>
    </div>

    <!-- Category -->
    <div class="card">
      <h3 style="font-size:14px; font-weight:600; margin-bottom:14px;">📂 Kategori</h3>
      <select name="category_id" class="form-control">
        <option value="">— Pilih Kategori —</option>
        <?php foreach ($categories as $cat): ?>
          <option value="<?= $cat['id'] ?>" <?= ($_POST['category_id'] ?? '') == $cat['id'] ? 'selected' : '' ?>>
            <?= htmlspecialchars($cat['name']) ?>
          </option>
        <?php endforeach; ?>
      </select>
      <div style="margin-top:10px;">
        <a href="/admin/articles/categories.php" style="font-size:12px; color:var(--accent-cyan);">+ Kelola Kategori</a>
      </div>
    </div>

    <!-- Tags -->
    <div class="card">
      <h3 style="font-size:14px; font-weight:600; margin-bottom:14px;">🏷️ Tags</h3>
      <input type="text" name="tags" class="form-control"
             placeholder="BPOM, niacinamide, skincare"
             value="<?= htmlspecialchars($_POST['tags'] ?? '') ?>">
      <div class="form-hint">Pisahkan dengan koma.</div>
    </div>

    <!-- Featured Image -->
    <div class="card">
      <h3 style="font-size:14px; font-weight:600; margin-bottom:14px;">🖼️ Gambar Utama</h3>
      <input type="file" name="featured_image" class="form-control" accept="image/*"
             onchange="previewImage(this)">
      <div id="imgPreviewWrap" style="margin-top:10px; display:none;">
        <img id="imgPreview" style="width:100%; border-radius:8px; border:1px solid var(--border);">
      </div>
      <div class="form-group" style="margin-top:10px; margin-bottom:0;">
        <label class="form-label">Alt Text Gambar</label>
        <input type="text" name="featured_image_alt" class="form-control"
               placeholder="Deskripsi gambar untuk SEO & aksesibilitas"
               value="<?= htmlspecialchars($_POST['featured_image_alt'] ?? '') ?>">
      </div>
    </div>

  </div>
</div>
</form>

<style>
#editor h2 { font-size:1.5em; font-weight:700; margin:1em 0 .5em; }
#editor h3 { font-size:1.25em; font-weight:600; margin:1em 0 .5em; }
#editor h4 { font-size:1.1em; font-weight:600; margin:1em 0 .5em; }
#editor p  { margin-bottom:.8em; }
#editor ul, #editor ol { padding-left:1.5em; margin-bottom:.8em; }
#editor blockquote { border-left:3px solid var(--accent-cyan); padding-left:1em; color:var(--text-secondary); margin:1em 0; }
#editor pre { background:var(--bg-surface); padding:1em; border-radius:6px; font-family:monospace; overflow-x:auto; }
#editor a { color:var(--accent-cyan); }
</style>

<script>
// Slug auto-generate
function generateSlug(text) {
  return text.toLowerCase().trim()
    .replace(/[^a-z0-9\s-]/g, '')
    .replace(/[\s-]+/g, '-')
    .replace(/^-+|-+$/g, '');
}
function autoSlug(val) {
  const slug = generateSlug(val);
  document.getElementById('slugInput').value = slug;
  document.getElementById('previewSlug').textContent = slug || 'slug-artikel';
}

// SEO preview
document.querySelector('[name="seo_title"]').addEventListener('input', function() {
  document.getElementById('previewTitle').textContent = this.value || document.querySelector('[name="title"]').value || 'Judul artikel Anda';
});
document.querySelector('[name="title"]').addEventListener('input', function() {
  const seoTitle = document.querySelector('[name="seo_title"]').value;
  if (!seoTitle) document.getElementById('previewTitle').textContent = this.value;
});
document.querySelector('[name="seo_description"]').addEventListener('input', function() {
  document.getElementById('previewDesc').textContent = this.value || 'Deskripsi artikel akan tampil di sini...';
});

// Counter
function updateCounter(el, counterId, max) {
  document.getElementById(counterId).textContent = el.value.length;
  el.style.borderColor = el.value.length > max * 0.9 ? 'var(--accent-yellow)' : '';
}

// Schedule toggle
function toggleSchedule(val) {
  document.getElementById('scheduleField').style.display = val === 'scheduled' ? 'block' : 'none';
}
toggleSchedule(document.getElementById('statusSelect').value);

// Editor commands
function execCmd(cmd, val) {
  if (cmd === 'createLink') {
    const url = prompt('Masukkan URL:');
    if (url) document.execCommand(cmd, false, url);
  } else if (cmd === 'insertImage') {
    const url = prompt('Masukkan URL gambar:');
    if (url) document.execCommand(cmd, false, url);
  } else {
    document.execCommand(cmd, false, val || null);
  }
  document.getElementById('editor').focus();
}

// Sync editor to textarea & word count
const editor = document.getElementById('editor');
const contentInput = document.getElementById('contentInput');
editor.addEventListener('input', function() {
  contentInput.value = this.innerHTML;
  const text = this.innerText;
  const words = text.trim() ? text.trim().split(/\s+/).length : 0;
  document.getElementById('wordCount').textContent = words;
  document.getElementById('readTime').textContent = Math.max(1, Math.ceil(words / 200));
});

// Image preview
function previewImage(input) {
  if (input.files && input.files[0]) {
    const reader = new FileReader();
    reader.onload = e => {
      document.getElementById('imgPreview').src = e.target.result;
      document.getElementById('imgPreviewWrap').style.display = 'block';
    };
    reader.readAsDataURL(input.files[0]);
  }
}

// Sync on submit
document.querySelector('form').addEventListener('submit', function() {
  contentInput.value = editor.innerHTML;
});
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>


