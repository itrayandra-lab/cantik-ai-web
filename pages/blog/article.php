<?php
require_once __DIR__ . '/../../pages/admin/config/database.php';

$pdo  = getDB();
$slug = $_GET['slug'] ?? '';

if (!$slug) { header('Location: /blog/'); exit; }

$pdo->exec("UPDATE articles SET status='published', published_at=NOW() WHERE status='scheduled' AND scheduled_at <= NOW()");

$stmt = $pdo->prepare("
    SELECT a.*, ac.name AS category_name, ac.slug AS category_slug, ac.color AS category_color, au.full_name AS author_name
    FROM articles a
    LEFT JOIN article_categories ac ON a.category_id = ac.id
    LEFT JOIN admin_users au ON a.author_id = au.id
    WHERE a.slug = ? AND a.status = 'published'
");
$stmt->execute([$slug]);
$article = $stmt->fetch();

if (!$article) {
    http_response_code(404);
    $pageTitle = '404 — Artikel Tidak Ditemukan';
    $pageDesc  = '';
    require_once __DIR__ . '/../../layouts/header.php';
    echo '<div style="text-align:center;padding:120px 24px;">
        <div style="font-size:4rem;margin-bottom:16px;">🔍</div>
        <h1 style="font-size:2rem;color:#1a0a12;margin-bottom:8px;">Artikel Tidak Ditemukan</h1>
        <p style="color:#7a4d62;margin-bottom:24px;">Artikel yang Anda cari tidak ada atau sudah dihapus.</p>
        <a href="/blog/" style="display:inline-block;padding:10px 24px;background:linear-gradient(135deg,#e8a0bf,#b84d7a);color:#fff;border-radius:8px;text-decoration:none;font-weight:600;">← Kembali ke Blog</a>
    </div>';
    require_once __DIR__ . '/../../layouts/footer.php';
    exit;
}

// Increment view
$pdo->prepare("UPDATE articles SET view_count = view_count + 1 WHERE id = ?")->execute([$article['id']]);

// Tags
$tagStmt = $pdo->prepare("SELECT t.name, t.slug FROM article_tags t JOIN article_tag_pivot p ON t.id=p.tag_id WHERE p.article_id=?");
$tagStmt->execute([$article['id']]);
$tags = $tagStmt->fetchAll();

// Related
$relatedStmt = $pdo->prepare("
    SELECT title, slug, featured_image, featured_image_alt, published_at, read_time, excerpt
    FROM articles WHERE status='published' AND id != ? AND category_id = ?
    ORDER BY published_at DESC LIMIT 3
");
$relatedStmt->execute([$article['id'], $article['category_id']]);
$related = $relatedStmt->fetchAll();

$siteUrl      = 'https://cantik.ai';
$pageTitle    = ($article['seo_title'] ?: $article['title']) . ' — Cantik.AI Blog';
$pageDesc     = $article['seo_description'] ?: $article['excerpt'];
$canonical    = $article['canonical_url'] ?: "$siteUrl/blog/{$article['slug']}";
$ogImage      = $article['og_image'] ?: ($article['featured_image'] ? $siteUrl . '/' . $article['featured_image'] : $siteUrl . '/assets/img/og-default.png');
$publishedISO = date('c', strtotime($article['published_at']));
$modifiedISO  = date('c', strtotime($article['updated_at']));

$extraHead = '
<meta name="author" content="' . htmlspecialchars($article['author_name']) . '">
<meta property="og:type" content="article">
<meta property="og:image" content="' . htmlspecialchars($ogImage) . '">
<meta property="article:published_time" content="' . $publishedISO . '">
<meta property="article:modified_time" content="' . $modifiedISO . '">
<script type="application/ld+json">
{
  "@context":"https://schema.org",
  "@type":"' . htmlspecialchars($article['schema_type'] ?? 'BlogPosting') . '",
  "headline":' . json_encode($article['title']) . ',
  "description":' . json_encode($pageDesc) . ',
  "image":' . json_encode($ogImage) . ',
  "datePublished":"' . $publishedISO . '",
  "dateModified":"' . $modifiedISO . '",
  "author":{"@type":"Person","name":' . json_encode($article['author_name']) . '},
  "publisher":{"@type":"Organization","name":"Cantik.AI","logo":{"@type":"ImageObject","url":"' . $siteUrl . '/assets/img/logo-colour-pink.png"}},
  "mainEntityOfPage":{"@type":"WebPage","@id":' . json_encode($canonical) . '}
}
</script>
<style>
/* ── ARTICLE PAGE ── */
.article-page { background:#fafafa; }

/* Hero */
.article-hero {
  background: linear-gradient(180deg, #fff5f8 0%, #fff 100%);
  border-bottom: 1px solid rgba(184,77,122,.1);
  padding: 48px 0 60px;
}
.article-hero__inner {
  max-width: 1280px;
  margin: 0 auto;
  padding: 0 2.5rem;
}
.article-hero__breadcrumb { display:flex; align-items:center; gap:6px; font-size:13px; color:#9d7a8a; margin-bottom:20px; flex-wrap:wrap; }
.article-hero__breadcrumb a { color:#9d7a8a; text-decoration:none; transition:color .15s; }
.article-hero__breadcrumb a:hover { color:#b84d7a; }
.article-hero__breadcrumb span { color:rgba(184,77,122,.3); }
.article-hero__cat { display:inline-block; padding:4px 12px; border-radius:20px; font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.06em; margin-bottom:16px; }
.article-hero__title { font-size:clamp(1.75rem,4vw,2.75rem); font-weight:800; color:#1a0a12; line-height:1.2; margin-bottom:20px; max-width:760px; }
.article-hero__meta { display:flex; align-items:center; gap:16px; font-size:13px; color:#9d7a8a; flex-wrap:wrap; }
.article-hero__meta-dot { width:3px; height:3px; border-radius:50%; background:rgba(184,77,122,.3); }

/* Layout */
.article-layout { max-width:1280px; margin:0 auto; padding:40px 2.5rem 60px; display:grid; grid-template-columns:1fr 300px; gap:48px; align-items:start; }

/* Main content card */
.article-card {
  background:#fff;
  border-radius:20px;
  border:1px solid rgba(184,77,122,.08);
  box-shadow:0 4px 24px rgba(0,0,0,.06);
  overflow:hidden;
}
.article-card__img { width:100%; max-height:420px; object-fit:cover; display:block; }
.article-card__body { padding:40px; }

/* Article content typography */
.article-content { font-size:1.05rem; line-height:1.85; color:#2d1a24; }
.article-content h2 { font-size:1.5rem; font-weight:800; margin:2em 0 .6em; color:#1a0a12; padding-bottom:.4em; border-bottom:2px solid #fde8f0; }
.article-content h3 { font-size:1.2rem; font-weight:700; margin:1.75em 0 .5em; color:#2d1a24; }
.article-content h4 { font-size:1.05rem; font-weight:700; margin:1.5em 0 .4em; color:#4a2d3a; }
.article-content p  { margin-bottom:1.3em; }
.article-content ul, .article-content ol { padding-left:1.5em; margin-bottom:1.3em; }
.article-content li { margin-bottom:.5em; }
.article-content strong { color:#1a0a12; font-weight:700; }
.article-content em { color:#7a4d62; }
.article-content a { color:#b84d7a; text-decoration:underline; text-underline-offset:3px; }
.article-content a:hover { color:#9d3a62; }
.article-content blockquote {
  border-left:4px solid #b84d7a;
  padding:16px 20px;
  margin:1.5em 0;
  background:#fdf0f5;
  border-radius:0 12px 12px 0;
  color:#7a4d62;
  font-style:italic;
  font-size:1.05em;
}
.article-content pre {
  background:#1a0a12;
  color:#e8a0bf;
  padding:20px 24px;
  border-radius:12px;
  overflow-x:auto;
  font-size:.9rem;
  margin:1.5em 0;
  line-height:1.6;
}
.article-content code {
  background:#fdf0f5;
  color:#b84d7a;
  padding:2px 7px;
  border-radius:5px;
  font-size:.9em;
  font-family:monospace;
}
.article-content pre code { background:none; color:inherit; padding:0; }
.article-content img { max-width:100%; border-radius:12px; margin:1.5em 0; box-shadow:0 4px 20px rgba(0,0,0,.1); }
.article-content table { width:100%; border-collapse:collapse; margin:1.5em 0; font-size:.95rem; }
.article-content th { background:#fdf0f5; color:#b84d7a; font-weight:700; padding:10px 14px; border:1px solid rgba(184,77,122,.2); text-align:left; }
.article-content td { padding:10px 14px; border:1px solid rgba(184,77,122,.1); }
.article-content tr:nth-child(even) td { background:#fafafa; }
.article-content hr { border:none; border-top:2px solid #fde8f0; margin:2em 0; }

/* Tags */
.article-tags { margin-top:32px; padding-top:24px; border-top:1px solid rgba(184,77,122,.1); display:flex; align-items:center; gap:8px; flex-wrap:wrap; }
.article-tags__label { font-size:13px; color:#9d7a8a; font-weight:500; }
.tag-chip { display:inline-block; padding:4px 12px; border-radius:20px; font-size:12px; background:#fdf0f5; border:1px solid rgba(184,77,122,.2); color:#b84d7a; text-decoration:none; transition:all .15s; }
.tag-chip:hover { background:#b84d7a; color:#fff; border-color:#b84d7a; }

/* Share */
.article-share { margin-top:28px; padding:20px 24px; background:#fdf0f5; border-radius:12px; border:1px solid rgba(184,77,122,.15); }
.article-share__title { font-size:13px; font-weight:700; color:#4a2d3a; margin-bottom:12px; }
.article-share__btns { display:flex; gap:8px; flex-wrap:wrap; }
.share-btn { padding:7px 16px; border-radius:8px; font-size:13px; font-weight:600; text-decoration:none; transition:opacity .15s; }
.share-btn:hover { opacity:.85; }
.share-btn--wa { background:#25d366; color:#fff; }
.share-btn--tw { background:#1da1f2; color:#fff; }
.share-btn--li { background:#0077b5; color:#fff; }
.share-btn--copy { background:#fff; color:#4a2d3a; border:1px solid rgba(184,77,122,.2); cursor:pointer; }

/* Related */
.article-related { margin-top:32px; }
.article-related__title { font-size:1rem; font-weight:700; color:#1a0a12; margin-bottom:16px; }
.related-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(200px,1fr)); gap:16px; }
.related-card { background:#fff; border:1px solid rgba(184,77,122,.1); border-radius:12px; overflow:hidden; text-decoration:none; color:inherit; transition:transform .2s,box-shadow .2s; }
.related-card:hover { transform:translateY(-3px); box-shadow:0 6px 20px rgba(184,77,122,.1); }
.related-card__img { width:100%; height:120px; object-fit:cover; }
.related-card__placeholder { width:100%; height:120px; background:linear-gradient(135deg,#fff5f8,#fde8f0); display:flex; align-items:center; justify-content:center; font-size:2rem; }
.related-card__body { padding:12px 14px; }
.related-card__title { font-size:.875rem; font-weight:600; color:#1a0a12; line-height:1.4; margin-bottom:4px; }
.related-card__date { font-size:11px; color:#9d7a8a; }

/* Sidebar */
.article-sidebar { position:sticky; top:100px; }
.sidebar-card { background:#fff; border:1px solid rgba(184,77,122,.1); border-radius:16px; padding:20px; margin-bottom:16px; box-shadow:0 2px 12px rgba(184,77,122,.05); }
.sidebar-card__title { font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.07em; color:#b84d7a; margin-bottom:14px; }

@media(max-width:900px) {
  .article-layout { grid-template-columns:1fr; }
  .article-sidebar { position:static; }
  .article-card__body { padding:24px; }
}
@media(max-width:600px) {
  .article-hero { padding:40px 0 48px; }
  .article-hero__inner { padding:0 1.25rem; }
  .article-layout { padding:24px 1.25rem 40px; }
  .article-hero__title { font-size:1.6rem; }
}
</style>';

require_once __DIR__ . '/../../layouts/header.php';
?>

<div class="article-page">

  <!-- HERO -->
  <div class="article-hero">
    <div class="article-hero__inner">
      <!-- Breadcrumb -->
      <nav class="article-hero__breadcrumb" aria-label="breadcrumb">
        <a href="/">Beranda</a>
        <span>›</span>
        <a href="/blog/">Blog</a>
        <?php if ($article['category_name']): ?>
          <span>›</span>
          <a href="/blog/?category=<?= urlencode($article['category_slug']) ?>"><?= htmlspecialchars($article['category_name']) ?></a>
        <?php endif; ?>
      </nav>

      <!-- Category badge -->
      <?php if ($article['category_name']): ?>
        <a href="/blog/?category=<?= urlencode($article['category_slug']) ?>"
           class="article-hero__cat"
           style="background:<?= htmlspecialchars($article['category_color']) ?>33;color:<?= htmlspecialchars($article['category_color']) ?>;border:1px solid <?= htmlspecialchars($article['category_color']) ?>55;">
          <?= htmlspecialchars($article['category_name']) ?>
        </a>
      <?php endif; ?>

      <h1 class="article-hero__title"><?= htmlspecialchars($article['title']) ?></h1>

      <div class="article-hero__meta">
        <span>✍️ <?= htmlspecialchars($article['author_name']) ?></span>
        <span class="article-hero__meta-dot"></span>
        <span>📅 <?= date('d F Y', strtotime($article['published_at'])) ?></span>
        <?php if ($article['read_time']): ?>
          <span class="article-hero__meta-dot"></span>
          <span>⏱️ <?= $article['read_time'] ?> menit baca</span>
        <?php endif; ?>
        <span class="article-hero__meta-dot"></span>
        <span>👁️ <?= number_format($article['view_count']) ?> views</span>
      </div>
    </div>
  </div>

  <!-- LAYOUT -->
  <div class="article-layout">

    <!-- MAIN -->
    <main>
      <div class="article-card">
        <?php if ($article['featured_image']): ?>
          <img src="/<?= htmlspecialchars($article['featured_image']) ?>"
               alt="<?= htmlspecialchars($article['featured_image_alt'] ?? $article['title']) ?>"
               class="article-card__img" loading="eager">
        <?php endif; ?>

        <div class="article-card__body">
          <!-- Content -->
          <div class="article-content">
            <?= $article['content'] ?>
          </div>

          <!-- Tags -->
          <?php if ($tags): ?>
          <div class="article-tags">
            <span class="article-tags__label">🏷️</span>
            <?php foreach ($tags as $tag): ?>
              <a href="/blog/?tag=<?= urlencode($tag['slug']) ?>" class="tag-chip"><?= htmlspecialchars($tag['name']) ?></a>
            <?php endforeach; ?>
          </div>
          <?php endif; ?>

          <!-- Share -->
          <?php $shareUrl = urlencode($canonical); $shareTitle = urlencode($article['title']); ?>
          <div class="article-share">
            <div class="article-share__title">📤 Bagikan Artikel</div>
            <div class="article-share__btns">
              <a href="https://wa.me/?text=<?= $shareTitle ?>%20<?= $shareUrl ?>" target="_blank" rel="noopener" class="share-btn share-btn--wa">WhatsApp</a>
              <a href="https://twitter.com/intent/tweet?text=<?= $shareTitle ?>&url=<?= $shareUrl ?>" target="_blank" rel="noopener" class="share-btn share-btn--tw">Twitter/X</a>
              <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?= $shareUrl ?>" target="_blank" rel="noopener" class="share-btn share-btn--li">LinkedIn</a>
              <button onclick="navigator.clipboard.writeText('<?= htmlspecialchars($canonical) ?>').then(()=>{this.textContent='✅ Disalin!';setTimeout(()=>this.textContent='📋 Salin Link',2000)})" class="share-btn share-btn--copy">📋 Salin Link</button>
            </div>
          </div>

          <!-- Related -->
          <?php if ($related): ?>
          <div class="article-related">
            <h3 class="article-related__title">📚 Artikel Terkait</h3>
            <div class="related-grid">
              <?php foreach ($related as $r): ?>
                <a href="/blog/<?= htmlspecialchars($r['slug']) ?>" class="related-card">
                  <?php if ($r['featured_image']): ?>
                    <img src="/<?= htmlspecialchars($r['featured_image']) ?>" alt="<?= htmlspecialchars($r['title']) ?>" class="related-card__img" loading="lazy">
                  <?php else: ?>
                    <div class="related-card__placeholder">✨</div>
                  <?php endif; ?>
                  <div class="related-card__body">
                    <div class="related-card__title"><?= htmlspecialchars($r['title']) ?></div>
                    <div class="related-card__date"><?= date('d M Y', strtotime($r['published_at'])) ?></div>
                  </div>
                </a>
              <?php endforeach; ?>
            </div>
          </div>
          <?php endif; ?>

          <div style="margin-top:32px;padding-top:24px;border-top:1px solid rgba(184,77,122,.1);text-align:center;">
            <a href="/blog/" style="display:inline-flex;align-items:center;gap:6px;color:#b84d7a;font-size:14px;font-weight:600;text-decoration:none;">← Kembali ke Blog</a>
          </div>
        </div>
      </div>
    </main>

    <!-- SIDEBAR -->
    <aside class="article-sidebar">

      <!-- Author card -->
      <div class="sidebar-card">
        <div class="sidebar-card__title">✍️ Penulis</div>
        <div style="display:flex;align-items:center;gap:12px;">
          <div style="width:44px;height:44px;border-radius:50%;background:linear-gradient(135deg,#e8a0bf,#b84d7a);display:flex;align-items:center;justify-content:center;color:#fff;font-size:18px;font-weight:700;flex-shrink:0;">
            <?= strtoupper(substr($article['author_name'], 0, 1)) ?>
          </div>
          <div>
            <div style="font-size:14px;font-weight:600;color:#1a0a12;"><?= htmlspecialchars($article['author_name']) ?></div>
            <div style="font-size:12px;color:#9d7a8a;">Tim Cantik.AI</div>
          </div>
        </div>
      </div>

      <!-- Article info -->
      <div class="sidebar-card">
        <div class="sidebar-card__title">📋 Info Artikel</div>
        <div style="font-size:13px;color:#4a2d3a;display:flex;flex-direction:column;gap:8px;">
          <div style="display:flex;justify-content:space-between;">
            <span style="color:#9d7a8a;">Diterbitkan</span>
            <span><?= date('d M Y', strtotime($article['published_at'])) ?></span>
          </div>
          <?php if ($article['read_time']): ?>
          <div style="display:flex;justify-content:space-between;">
            <span style="color:#9d7a8a;">Waktu baca</span>
            <span><?= $article['read_time'] ?> menit</span>
          </div>
          <?php endif; ?>
          <div style="display:flex;justify-content:space-between;">
            <span style="color:#9d7a8a;">Views</span>
            <span><?= number_format($article['view_count']) ?></span>
          </div>
          <?php if ($article['category_name']): ?>
          <div style="display:flex;justify-content:space-between;align-items:center;">
            <span style="color:#9d7a8a;">Kategori</span>
            <a href="/blog/?category=<?= urlencode($article['category_slug']) ?>"
               style="padding:2px 8px;border-radius:10px;font-size:11px;font-weight:700;text-decoration:none;background:<?= htmlspecialchars($article['category_color']) ?>22;color:<?= htmlspecialchars($article['category_color']) ?>;">
              <?= htmlspecialchars($article['category_name']) ?>
            </a>
          </div>
          <?php endif; ?>
        </div>
      </div>

      <!-- CTA -->
      <div style="background:linear-gradient(135deg,rgba(232,160,191,.2),rgba(184,77,122,.15));border:1px solid rgba(184,77,122,.2);border-radius:16px;padding:20px;text-align:center;">
        <h3 style="font-size:.95rem;font-weight:700;color:#1a0a12;margin-bottom:8px;">Coba Cantik.AI</h3>
        <p style="font-size:12px;color:#7a4d62;margin-bottom:14px;line-height:1.6;">Platform AI khusus industri kecantikan Indonesia.</p>
        <a href="https://app.cantik.ai/" style="display:block;padding:9px 16px;background:linear-gradient(135deg,#e8a0bf,#b84d7a);color:#fff;border-radius:8px;font-size:13px;font-weight:600;text-decoration:none;transition:opacity .15s;">Mulai Gratis →</a>
      </div>

    </aside>
  </div>

</div>

<?php require_once __DIR__ . '/../../layouts/footer.php'; ?>
