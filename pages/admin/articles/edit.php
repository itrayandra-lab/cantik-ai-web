<?php
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/helpers.php';
requireLogin();

$pdo = getDB();
$id  = (int)($_GET['id'] ?? 0);

$article = $pdo->prepare("SELECT * FROM articles WHERE id = ?");
$article->execute([$id]);
$article = $article->fetch();
if (!$article) { setFlash('error', 'Artikel tidak ditemukan.'); header('Location: /admin/articles/index.php'); exit; }

// Get tags
$tagRows = $pdo->prepare("SELECT t.name FROM article_tags t JOIN article_tag_pivot p ON t.id=p.tag_id WHERE p.article_id=?");
$tagRows->execute([$id]);
$currentTags = implode(', ', array_column($tagRows->fetchAll(), 'name'));

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

    if (!$title)   $errors[] = 'Judul wajib diisi.';
    if (!$content) $errors[] = 'Konten wajib diisi.';
    if (strlen($seoTitle) > 70)  $errors[] = 'SEO Title maksimal 70 karakter.';
    if (strlen($seoDesc)  > 160) $errors[] = 'SEO Description maksimal 160 karakter.';

    if (!$slug) $slug = generateSlug($title);

    $existing = $pdo->prepare("SELECT id FROM articles WHERE slug = ? AND id != ?");
    $existing->execute([$slug, $id]);
    if ($existing->fetch()) $slug = $slug . '-' . time();

    $wordCount = str_word_count(strip_tags($content));
    $readTime  = max(1, ceil($wordCount / 200));

    $featuredImage    = $article['featured_image'];
    $featuredImageAlt = trim($_POST['featured_image_alt'] ?? '');
    if (!empty($_FILES['featured_image']['name'])) {
        $upload = uploadImage($_FILES['featured_image'], 'articles');
        if (isset($upload['error'])) {
            $errors[] = $upload['error'];
        } else {
            if ($featuredImage) deleteUploadedFile($featuredImage);
            $featuredImage = $upload['path'];
        }
    }

    $publishedAt = $article['published_at'];
    if ($status === 'published' && !$publishedAt) $publishedAt = date('Y-m-d H:i:s');
    if ($status === 'scheduled' && $scheduledAt) {
        $scheduledAt = date('Y-m-d H:i:s', strtotime($scheduledAt));
    } else {
        $scheduledAt = null;
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare("
            UPDATE articles SET
              category_id=?, title=?, slug=?, excerpt=?, content=?, featured_image=?, featured_image_alt=?,
              seo_title=?, seo_description=?, seo_keywords=?, schema_type=?, status=?,
              scheduled_at=?, published_at=?, read_time=?, is_featured=?
            WHERE id=?
        ");
        $stmt->execute([
            $categoryId, $title, $slug, $excerpt, $content, $featuredImage, $featuredImageAlt,
            $seoTitle, $seoDesc, $seoKeywords, $schemaType, $status,
            $scheduledAt, $publishedAt, $readTime, $isFeatured, $id
        ]);

        $pdo->prepare("DELETE FROM article_tag_pivot WHERE article_id=?")->execute([$id]);
        foreach ($tags as $tagName) {
            $tagSlug = generateSlug($tagName);
            $pdo->prepare("INSERT IGNORE INTO article_tags (name, slug) VALUES (?,?)")->execute([$tagName, $tagSlug]);
            $tagId = $pdo->query("SELECT id FROM article_tags WHERE slug='$tagSlug'")->fetchColumn();
            if ($tagId) {
                $pdo->prepare("INSERT IGNORE INTO article_tag_pivot (article_id, tag_id) VALUES (?,?)")->execute([$id, $tagId]);
            }
        }

        logActivity($_SESSION['admin_user_id'], 'article_update', "Artikel diupdate: $title");
        setFlash('success', 'Artikel berhasil diperbarui!');
        header('Location: /admin/articles/index.php');
        exit;
    }

    // Re-populate on error
    $article = array_merge($article, $_POST);
    $currentTags = $_POST['tags'] ?? '';
}

$pageTitle = 'Edit Artikel';
require_once __DIR__ . '/../includes/header.php';
?>

<div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:24px;">
  <div>
    <h2 style="font-size:20px; font-weight:700;">✏️ Edit Artikel</h2>
    <p style="color:var(--text-muted); font-size:13px; margin-top:4px;"><?= htmlspecialchars($article['title']) ?></p>
  </div>
  <div style="display:flex; gap:8px;">
    <?php if ($article['status'] === 'published'): ?>
      <a href="/blog/<?= htmlspecialchars($article['slug']) ?>" target="_blank" class="btn btn-ghost">🌐 Lihat Artikel</a>
    <?php endif; ?>
    <a href="/admin/articles/index.php" class="btn btn-ghost">← Kembali</a>
  </div>
</div>

<?php if ($errors): ?>
  <div class="flash flash-error">❌ <?= implode('<br>', array_map('htmlspecialchars', $errors)) ?></div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data">
<div style="display:grid; grid-template-columns:1fr 340px; gap:24px; align-items:start;">

  <div style="display:flex; flex-direction:column; gap:20px;">
    <div class="card">
      <div class="form-group" style="margin-bottom:0;">
        <label class="form-label">Judul Artikel <span class="required">*</span></label>
        <input type="text" name="title" class="form-control" style="font-size:18px; font-weight:600;"
               value="<?= htmlspecialchars($article['title']) ?>" oninput="autoSlug(this.value)" required>
      </div>
      <div class="form-group" style="margin-top:12px; margin-bottom:0;">
        <label class="form-label">Slug URL</label>
        <div style="display:flex; align-items:center; gap:8px;">
          <span style="color:var(--text-muted); font-size:13px; white-space:nowrap;">/blog/</span>
          <input type="text" name="slug" id="slugInput" class="form-control" value="<?= htmlspecialchars($article['slug']) ?>">
        </div>
      </div>
    </div>

    <div class="card">
      <label class="form-label">Konten Artikel <span class="required">*</span></label>
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
        <?= $article['content'] ?>
      </div>
      <textarea name="content" id="contentInput" style="display:none;"><?= htmlspecialchars($article['content']) ?></textarea>
      <div class="form-hint" style="margin-top:8px;">
        <span id="wordCount">0</span> kata · estimasi <span id="readTime">0</span> menit baca
      </div>
    </div>

    <div class="card">
      <div class="form-group" style="margin-bottom:0;">
        <label class="form-label">Excerpt / Ringkasan</label>
        <textarea name="excerpt" class="form-control" rows="3"><?= htmlspecialchars($article['excerpt'] ?? '') ?></textarea>
      </div>
    </div>

    <div class="card">
      <h3 style="font-size:15px; font-weight:600; margin-bottom:16px;">🔍 SEO Settings</h3>
      <div class="form-group">
        <label class="form-label">SEO Title <span style="color:var(--text-muted); font-weight:400;">(maks. 70 karakter)</span></label>
        <input type="text" name="seo_title" class="form-control" maxlength="70"
               value="<?= htmlspecialchars($article['seo_title'] ?? '') ?>"
               oninput="updateCounter(this,'seoTitleCount',70)">
        <div class="form-hint"><span id="seoTitleCount"><?= strlen($article['seo_title'] ?? '') ?></span>/70 karakter</div>
      </div>
      <div class="form-group">
        <label class="form-label">SEO Description <span style="color:var(--text-muted); font-weight:400;">(maks. 160 karakter)</span></label>
        <textarea name="seo_description" class="form-control" rows="3" maxlength="160"
                  oninput="updateCounter(this,'seoDescCount',160)"><?= htmlspecialchars($article['seo_description'] ?? '') ?></textarea>
        <div class="form-hint"><span id="seoDescCount"><?= strlen($article['seo_description'] ?? '') ?></span>/160 karakter</div>
      </div>
      <div class="form-group">
        <label class="form-label">Keywords</label>
        <input type="text" name="seo_keywords" class="form-control" value="<?= htmlspecialchars($article['seo_keywords'] ?? '') ?>">
      </div>
      <div class="form-group" style="margin-bottom:0;">
        <label class="form-label">Schema Type</label>
        <select name="schema_type" class="form-control">
          <option value="BlogPosting" <?= ($article['schema_type'] ?? '') === 'BlogPosting' ? 'selected' : '' ?>>BlogPosting</option>
          <option value="Article"     <?= ($article['schema_type'] ?? '') === 'Article'     ? 'selected' : '' ?>>Article</option>
          <option value="NewsArticle" <?= ($article['schema_type'] ?? '') === 'NewsArticle' ? 'selected' : '' ?>>NewsArticle</option>
        </select>
      </div>
    </div>
  </div>

  <div style="display:flex; flex-direction:column; gap:16px;">
    <div class="card">
      <h3 style="font-size:14px; font-weight:600; margin-bottom:14px;">🚀 Publikasi</h3>
      <div class="form-group">
        <label class="form-label">Status</label>
        <select name="status" id="statusSelect" class="form-control" onchange="toggleSchedule(this.value)">
          <option value="draft"     <?= $article['status'] === 'draft'     ? 'selected' : '' ?>>📝 Draft</option>
          <option value="published" <?= $article['status'] === 'published' ? 'selected' : '' ?>>✅ Terbit</option>
          <option value="scheduled" <?= $article['status'] === 'scheduled' ? 'selected' : '' ?>>🕐 Terjadwal</option>
          <option value="archived"  <?= $article['status'] === 'archived'  ? 'selected' : '' ?>>📦 Arsip</option>
        </select>
      </div>
      <div id="scheduleField" style="display:none;">
        <div class="form-group">
          <label class="form-label">Jadwal Publikasi</label>
          <input type="datetime-local" name="scheduled_at" class="form-control"
                 value="<?= $article['scheduled_at'] ? date('Y-m-d\TH:i', strtotime($article['scheduled_at'])) : '' ?>">
        </div>
      </div>
      <?php if ($article['published_at']): ?>
        <div style="font-size:12px; color:var(--text-muted); margin-bottom:12px;">
          Terbit: <?= date('d M Y H:i', strtotime($article['published_at'])) ?>
        </div>
      <?php endif; ?>
      <div class="form-group" style="margin-bottom:0;">
        <label style="display:flex; align-items:center; gap:8px; cursor:pointer;">
          <input type="checkbox" name="is_featured" value="1" <?= $article['is_featured'] ? 'checked' : '' ?>
                 style="width:16px; height:16px; accent-color:var(--accent-cyan);">
          <span style="font-size:13px;">⭐ Artikel Featured</span>
        </label>
      </div>
      <div style="margin-top:16px; display:flex; flex-direction:column; gap:8px;">
        <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center;">💾 Simpan Perubahan</button>
      </div>
    </div>

    <div class="card">
      <h3 style="font-size:14px; font-weight:600; margin-bottom:14px;">📂 Kategori</h3>
      <select name="category_id" class="form-control">
        <option value="">— Pilih Kategori —</option>
        <?php foreach ($categories as $cat): ?>
          <option value="<?= $cat['id'] ?>" <?= $article['category_id'] == $cat['id'] ? 'selected' : '' ?>>
            <?= htmlspecialchars($cat['name']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="card">
      <h3 style="font-size:14px; font-weight:600; margin-bottom:14px;">🏷️ Tags</h3>
      <input type="text" name="tags" class="form-control" value="<?= htmlspecialchars($currentTags) ?>" placeholder="BPOM, niacinamide, skincare">
      <div class="form-hint">Pisahkan dengan koma.</div>
    </div>

    <div class="card">
      <h3 style="font-size:14px; font-weight:600; margin-bottom:14px;">🖼️ Gambar Utama</h3>
      <?php if ($article['featured_image']): ?>
        <img src="/<?= htmlspecialchars($article['featured_image']) ?>" style="width:100%; border-radius:8px; margin-bottom:10px; border:1px solid var(--border);">
      <?php endif; ?>
      <input type="file" name="featured_image" class="form-control" accept="image/*" onchange="previewImage(this)">
      <div id="imgPreviewWrap" style="margin-top:10px; display:none;">
        <img id="imgPreview" style="width:100%; border-radius:8px; border:1px solid var(--border);">
      </div>
      <div class="form-group" style="margin-top:10px; margin-bottom:0;">
        <label class="form-label">Alt Text</label>
        <input type="text" name="featured_image_alt" class="form-control" value="<?= htmlspecialchars($article['featured_image_alt'] ?? '') ?>">
      </div>
    </div>

    <div class="card">
      <h3 style="font-size:14px; font-weight:600; margin-bottom:10px;">📊 Statistik</h3>
      <div style="font-size:13px; color:var(--text-secondary);">
        <div style="margin-bottom:6px;">👁️ Views: <strong><?= number_format($article['view_count']) ?></strong></div>
        <div style="margin-bottom:6px;">⏱️ Estimasi baca: <strong><?= $article['read_time'] ?? '—' ?> menit</strong></div>
        <div>📅 Dibuat: <strong><?= date('d M Y', strtotime($article['created_at'])) ?></strong></div>
      </div>
    </div>
  </div>
</div>
</form>

<style>
#editor h2 { font-size:1.5em; font-weight:700; margin:1em 0 .5em; }
#editor h3 { font-size:1.25em; font-weight:600; margin:1em 0 .5em; }
#editor p  { margin-bottom:.8em; }
#editor ul, #editor ol { padding-left:1.5em; margin-bottom:.8em; }
#editor blockquote { border-left:3px solid var(--accent-cyan); padding-left:1em; color:var(--text-secondary); margin:1em 0; }
#editor pre { background:var(--bg-surface); padding:1em; border-radius:6px; font-family:monospace; overflow-x:auto; }
#editor a { color:var(--accent-cyan); }
</style>

<script>
function generateSlug(text) {
  return text.toLowerCase().trim().replace(/[^a-z0-9\s-]/g,'').replace(/[\s-]+/g,'-').replace(/^-+|-+$/g,'');
}
function autoSlug(val) {
  document.getElementById('slugInput').value = generateSlug(val);
}
function toggleSchedule(val) {
  document.getElementById('scheduleField').style.display = val === 'scheduled' ? 'block' : 'none';
}
toggleSchedule(document.getElementById('statusSelect').value);
function execCmd(cmd, val) {
  if (cmd === 'createLink') { const url = prompt('URL:'); if (url) document.execCommand(cmd, false, url); }
  else if (cmd === 'insertImage') { const url = prompt('URL gambar:'); if (url) document.execCommand(cmd, false, url); }
  else document.execCommand(cmd, false, val || null);
  document.getElementById('editor').focus();
}
function updateCounter(el, id, max) {
  document.getElementById(id).textContent = el.value.length;
}
const editor = document.getElementById('editor');
editor.addEventListener('input', function() {
  document.getElementById('contentInput').value = this.innerHTML;
  const words = this.innerText.trim() ? this.innerText.trim().split(/\s+/).length : 0;
  document.getElementById('wordCount').textContent = words;
  document.getElementById('readTime').textContent = Math.max(1, Math.ceil(words / 200));
});
// Init word count
const initWords = editor.innerText.trim() ? editor.innerText.trim().split(/\s+/).length : 0;
document.getElementById('wordCount').textContent = initWords;
document.getElementById('readTime').textContent = Math.max(1, Math.ceil(initWords / 200));

function previewImage(input) {
  if (input.files && input.files[0]) {
    const reader = new FileReader();
    reader.onload = e => { document.getElementById('imgPreview').src = e.target.result; document.getElementById('imgPreviewWrap').style.display = 'block'; };
    reader.readAsDataURL(input.files[0]);
  }
}
document.querySelector('form').addEventListener('submit', function() {
  document.getElementById('contentInput').value = editor.innerHTML;
});
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>


