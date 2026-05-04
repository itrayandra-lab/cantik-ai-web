<?php
require_once __DIR__ . '/../../pages/admin/config/database.php';

$pdo = getDB();
$pdo->exec("UPDATE articles SET status='published', published_at=NOW() WHERE status='scheduled' AND scheduled_at <= NOW()");

$category = $_GET['category'] ?? '';
$tag      = $_GET['tag']      ?? '';
$page     = max(1, (int)($_GET['page'] ?? 1));
$perPage  = 9;

$where  = ["a.status = 'published'"];
$params = [];
if ($category) { $where[] = 'ac.slug = ?'; $params[] = $category; }
if ($tag)      { $where[] = 'EXISTS (SELECT 1 FROM article_tag_pivot atp JOIN article_tags at2 ON atp.tag_id=at2.id WHERE atp.article_id=a.id AND at2.slug=?)'; $params[] = $tag; }

$whereSQL  = implode(' AND ', $where);
$totalStmt = $pdo->prepare("SELECT COUNT(*) FROM articles a LEFT JOIN article_categories ac ON a.category_id=ac.id WHERE $whereSQL");
$totalStmt->execute($params);
$total      = $totalStmt->fetchColumn();
$totalPages = ceil($total / $perPage);
$offset     = ($page - 1) * $perPage;

$stmt = $pdo->prepare("
    SELECT a.*, ac.name AS category_name, ac.slug AS category_slug, ac.color AS category_color, au.full_name AS author_name
    FROM articles a
    LEFT JOIN article_categories ac ON a.category_id = ac.id
    LEFT JOIN admin_users au ON a.author_id = au.id
    WHERE $whereSQL
    ORDER BY a.is_featured DESC, a.published_at DESC
    LIMIT $perPage OFFSET $offset
");
$stmt->execute($params);
$articles = $stmt->fetchAll();

// Featured article (first if featured, else first published)
$featured = null;
$rest     = [];
foreach ($articles as $i => $a) {
    if (!$featured && ($a['is_featured'] || $i === 0)) {
        $featured = $a;
    } else {
        $rest[] = $a;
    }
}

$categories = $pdo->query("
    SELECT ac.*, COUNT(a.id) as cnt FROM article_categories ac
    LEFT JOIN articles a ON a.category_id=ac.id AND a.status='published'
    WHERE ac.is_active=1 GROUP BY ac.id ORDER BY ac.sort_order
")->fetchAll();

$popular = $pdo->query("
    SELECT title, slug, featured_image, published_at FROM articles
    WHERE status='published' ORDER BY view_count DESC LIMIT 4
")->fetchAll();

$siteUrl   = 'https://cantik.ai';
$pageTitle = 'Blog Cantik.AI — Insight Industri Kecantikan Indonesia';
$pageDesc  = 'Artikel terbaru seputar regulasi BPOM, formulasi kosmetik, strategi bisnis kecantikan, dan AI untuk industri kosmetik Indonesia.';
$canonical = $siteUrl . '/blog/';

$extraHead = '
<script type="application/ld+json">
{"@context":"https://schema.org","@type":"BreadcrumbList","itemListElement":[
  {"@type":"ListItem","position":1,"name":"Beranda","item":"' . $siteUrl . '"},
  {"@type":"ListItem","position":2,"name":"Blog","item":"' . $siteUrl . '/blog/"}
]}
</script>
<style>
/* ── BLOG PAGE ── */
.blog-page { background:#fafafa; min-height:100vh; }

/* Hero */
.blog-hero {
  background: linear-gradient(135deg, #2d1a24 0%, #1a0a12 100%);
  padding: 80px 0 60px;
  text-align: center;
  position: relative;
  overflow: hidden;
}
.blog-hero::before {
  content:"";
  position:absolute; inset:0;
  background: radial-gradient(ellipse 80% 60% at 50% 0%, rgba(184,77,122,.35) 0%, transparent 70%);
}
.blog-hero__inner { position:relative; z-index:1; max-width:1280px; margin:0 auto; padding:0 2.5rem; }
.blog-hero__label { display:inline-block; padding:4px 14px; border-radius:20px; background:rgba(232,160,191,.15); border:1px solid rgba(232,160,191,.3); color:#e8a0bf; font-size:12px; font-weight:600; letter-spacing:.06em; text-transform:uppercase; margin-bottom:20px; }
.blog-hero__title { font-size:clamp(2rem,5vw,3.25rem); font-weight:800; color:#fff; line-height:1.15; margin-bottom:16px; max-width:640px; }
.blog-hero__title span { background:linear-gradient(135deg,#e8a0bf,#b84d7a); -webkit-background-clip:text; -webkit-text-fill-color:transparent; }
.blog-hero__desc { font-size:1.05rem; color:rgba(255,255,255,.6); line-height:1.7; max-width:560px; }

/* Category filter tabs */
.blog-cats {
  background:#fff;
  border-bottom:1px solid rgba(184,77,122,.1);
  position:sticky; top:72px; z-index:100;
}
.blog-cats__inner { max-width:1280px; margin:0 auto; padding:0 2.5rem; display:flex; align-items:center; gap:8px; overflow-x:auto; scrollbar-width:none; }
.blog-cats__inner::-webkit-scrollbar { display:none; }
.blog-cats__tab {
  display:inline-flex; align-items:center; gap:6px;
  padding:14px 16px; font-size:13.5px; font-weight:500;
  color:#7a4d62; text-decoration:none; white-space:nowrap;
  border-bottom:2px solid transparent;
  transition:color .15s, border-color .15s;
  flex-shrink:0;
}
.blog-cats__tab:hover { color:#b84d7a; }
.blog-cats__tab.active { color:#b84d7a; border-bottom-color:#b84d7a; font-weight:600; }
.blog-cats__tab .cnt { background:#fdf0f5; color:#b84d7a; font-size:11px; font-weight:700; padding:1px 6px; border-radius:10px; }

/* Main layout */
.blog-main { max-width:1280px; margin:0 auto; padding:48px 2.5rem; display:grid; grid-template-columns:1fr 300px; gap:48px; align-items:start; }

/* Featured article */
.featured-card {
  display:grid; grid-template-columns:1fr 1fr; gap:0;
  background:#fff; border-radius:20px; overflow:hidden;
  border:1px solid rgba(184,77,122,.1);
  box-shadow:0 4px 24px rgba(184,77,122,.08);
  margin-bottom:40px;
  text-decoration:none; color:inherit;
  transition:box-shadow .2s, transform .2s;
}
.featured-card:hover { box-shadow:0 12px 40px rgba(184,77,122,.15); transform:translateY(-2px); }
.featured-card__img { position:relative; min-height:280px; overflow:hidden; }
.featured-card__img img { width:100%; height:100%; object-fit:cover; }
.featured-card__img-placeholder { width:100%; height:100%; min-height:280px; background:linear-gradient(135deg,#fde8f0,#f5c6d8); display:flex; align-items:center; justify-content:center; font-size:4rem; }
.featured-card__badge { position:absolute; top:16px; left:16px; background:#b84d7a; color:#fff; font-size:11px; font-weight:700; padding:4px 10px; border-radius:20px; letter-spacing:.04em; }
.featured-card__body { padding:32px; display:flex; flex-direction:column; justify-content:center; }
.featured-card__cat { display:inline-block; padding:3px 10px; border-radius:20px; font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.05em; margin-bottom:14px; }
.featured-card__title { font-size:1.4rem; font-weight:800; line-height:1.3; color:#1a0a12; margin-bottom:12px; }
.featured-card__excerpt { font-size:.9rem; color:#7a4d62; line-height:1.7; margin-bottom:20px; display:-webkit-box; -webkit-line-clamp:3; -webkit-box-orient:vertical; overflow:hidden; }
.featured-card__meta { display:flex; align-items:center; gap:12px; font-size:12px; color:#9d7a8a; }
.featured-card__read { display:inline-flex; align-items:center; gap:6px; margin-top:16px; font-size:13px; font-weight:600; color:#b84d7a; }
.featured-card__read::after { content:"→"; transition:transform .2s; }
.featured-card:hover .featured-card__read::after { transform:translateX(4px); }

/* Articles grid */
.articles-section h2 { font-size:1rem; font-weight:700; color:#9d7a8a; text-transform:uppercase; letter-spacing:.06em; margin-bottom:20px; }
.articles-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(280px,1fr)); gap:24px; }
.article-card { background:#fff; border:1px solid rgba(184,77,122,.08); border-radius:16px; overflow:hidden; transition:transform .2s,box-shadow .2s,border-color .2s; }
.article-card:hover { transform:translateY(-4px); border-color:rgba(184,77,122,.25); box-shadow:0 8px 32px rgba(184,77,122,.1); }
.article-card__img { position:relative; overflow:hidden; }
.article-card__img img { width:100%; height:180px; object-fit:cover; transition:transform .3s; }
.article-card:hover .article-card__img img { transform:scale(1.04); }
.article-card__img-placeholder { width:100%; height:180px; background:linear-gradient(135deg,#fff5f8,#fde8f0); display:flex; align-items:center; justify-content:center; font-size:2.5rem; }
.article-card__body { padding:18px 20px 20px; }
.article-card__cat { display:inline-block; padding:2px 9px; border-radius:20px; font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:.05em; margin-bottom:10px; }
.article-card__title { font-size:1rem; font-weight:700; line-height:1.4; color:#1a0a12; margin-bottom:8px; }
.article-card__title a { text-decoration:none; color:inherit; }
.article-card__title a:hover { color:#b84d7a; }
.article-card__excerpt { font-size:.825rem; color:#7a4d62; line-height:1.6; margin-bottom:14px; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden; }
.article-card__meta { display:flex; align-items:center; gap:10px; font-size:11px; color:#9d7a8a; }
.article-card__meta-dot { width:3px; height:3px; border-radius:50%; background:#d4a0b5; }

/* Empty state */
.empty-state { text-align:center; padding:80px 24px; color:#9d7a8a; }
.empty-state__icon { font-size:4rem; margin-bottom:16px; }
.empty-state__title { font-size:1.25rem; font-weight:700; color:#4a2d3a; margin-bottom:8px; }

/* Pagination */
.pagination { display:flex; justify-content:center; align-items:center; gap:6px; padding:48px 0 0; }
.pagination a, .pagination span { display:inline-flex; align-items:center; justify-content:center; width:38px; height:38px; border-radius:10px; font-size:13px; font-weight:500; border:1px solid rgba(184,77,122,.2); background:#fff; color:#7a4d62; text-decoration:none; transition:all .15s; }
.pagination a:hover { border-color:#b84d7a; color:#b84d7a; background:#fdf0f5; }
.pagination .active { background:#b84d7a; border-color:#b84d7a; color:#fff; font-weight:700; }
.pagination .dots { border:none; background:none; color:#9d7a8a; width:auto; }

/* Sidebar */
.blog-sidebar { position:sticky; top:130px; }
.sidebar-card { background:#fff; border:1px solid rgba(184,77,122,.1); border-radius:16px; padding:24px; margin-bottom:20px; }
.sidebar-card__title { font-size:12px; font-weight:700; text-transform:uppercase; letter-spacing:.07em; color:#b84d7a; margin-bottom:16px; display:flex; align-items:center; gap:6px; }

/* Filter active bar */
.filter-bar { display:flex; align-items:center; gap:10px; flex-wrap:wrap; margin-bottom:24px; padding:12px 16px; background:#fdf0f5; border-radius:10px; border:1px solid rgba(184,77,122,.15); }
.filter-bar__label { font-size:13px; color:#7a4d62; font-weight:500; }
.filter-bar__tag { padding:3px 10px; border-radius:20px; background:#b84d7a; color:#fff; font-size:12px; font-weight:600; }
.filter-bar__clear { margin-left:auto; font-size:12px; color:#9d7a8a; text-decoration:none; }
.filter-bar__clear:hover { color:#b84d7a; }

@media(max-width:900px) {
  .blog-main { grid-template-columns:1fr; }
  .blog-sidebar { position:static; }
  .featured-card { grid-template-columns:1fr; }
  .featured-card__img { min-height:220px; }
}
@media(max-width:600px) {
  .articles-grid { grid-template-columns:1fr; }
  .blog-hero { padding:60px 0 40px; }
}
</style>';

require_once __DIR__ . '/../../layouts/header.php';
?>

<div class="blog-page">
  <!-- MAIN CONTENT -->
  <div class="blog-main">
    <main>

      <?php if ($tag): ?>
        <div class="filter-bar">
          <span class="filter-bar__label">Tag:</span>
          <span class="filter-bar__tag">#<?= htmlspecialchars($tag) ?></span>
          <a href="/blog/" class="filter-bar__clear">× Hapus filter</a>
        </div>
      <?php endif; ?>

      <?php if (empty($articles)): ?>
        <div class="empty-state">
          <div class="empty-state__icon">📝</div>
          <div class="empty-state__title">Belum ada artikel</div>
          <p>Artikel akan segera hadir. Pantau terus!</p>
        </div>

      <?php else: ?>

        <?php if ($featured && $page === 1 && !$tag): ?>
        <!-- FEATURED -->
        <a href="/blog/<?= htmlspecialchars($featured['slug']) ?>" class="featured-card">
          <div class="featured-card__img">
            <?php if ($featured['featured_image']): ?>
              <img src="/<?= htmlspecialchars($featured['featured_image']) ?>" alt="<?= htmlspecialchars($featured['title']) ?>" loading="eager">
            <?php else: ?>
              <div class="featured-card__img-placeholder">✨</div>
            <?php endif; ?>
            <span class="featured-card__badge">⭐ Artikel Pilihan</span>
          </div>
          <div class="featured-card__body">
            <?php if ($featured['category_name']): ?>
              <span class="featured-card__cat"
                style="background:<?= htmlspecialchars($featured['category_color']) ?>22;color:<?= htmlspecialchars($featured['category_color']) ?>;border:1px solid <?= htmlspecialchars($featured['category_color']) ?>44;">
                <?= htmlspecialchars($featured['category_name']) ?>
              </span>
            <?php endif; ?>
            <h2 class="featured-card__title"><?= htmlspecialchars($featured['title']) ?></h2>
            <?php if ($featured['excerpt']): ?>
              <p class="featured-card__excerpt"><?= htmlspecialchars($featured['excerpt']) ?></p>
            <?php endif; ?>
            <div class="featured-card__meta">
              <span>✍️ <?= htmlspecialchars($featured['author_name']) ?></span>
              <span>📅 <?= date('d M Y', strtotime($featured['published_at'])) ?></span>
              <?php if ($featured['read_time']): ?><span>⏱️ <?= $featured['read_time'] ?> mnt</span><?php endif; ?>
            </div>
            <span class="featured-card__read">Baca Artikel</span>
          </div>
        </a>
        <?php endif; ?>

        <!-- ARTICLES GRID -->
        <?php $gridArticles = ($page === 1 && !$tag) ? $rest : $articles; ?>
        <?php if (!empty($gridArticles)): ?>
        <div class="articles-section">
          <?php if ($page === 1 && !$tag && $featured): ?>
            <h2>Artikel Terbaru</h2>
          <?php endif; ?>
          <div class="articles-grid">
            <?php foreach ($gridArticles as $a): ?>
            <article class="article-card" itemscope itemtype="https://schema.org/<?= htmlspecialchars($a['schema_type'] ?? 'BlogPosting') ?>">
              <div class="article-card__img">
                <a href="/blog/<?= htmlspecialchars($a['slug']) ?>">
                  <?php if ($a['featured_image']): ?>
                    <img src="/<?= htmlspecialchars($a['featured_image']) ?>" alt="<?= htmlspecialchars($a['featured_image_alt'] ?? $a['title']) ?>" loading="lazy" itemprop="image">
                  <?php else: ?>
                    <div class="article-card__img-placeholder">✨</div>
                  <?php endif; ?>
                </a>
              </div>
              <div class="article-card__body">
                <?php if ($a['category_name']): ?>
                  <a href="/blog/?category=<?= urlencode($a['category_slug']) ?>" class="article-card__cat"
                     style="background:<?= htmlspecialchars($a['category_color']) ?>22;color:<?= htmlspecialchars($a['category_color']) ?>;border:1px solid <?= htmlspecialchars($a['category_color']) ?>44;">
                    <?= htmlspecialchars($a['category_name']) ?>
                  </a>
                <?php endif; ?>
                <h2 class="article-card__title" itemprop="headline">
                  <a href="/blog/<?= htmlspecialchars($a['slug']) ?>"><?= htmlspecialchars($a['title']) ?></a>
                </h2>
                <?php if ($a['excerpt']): ?>
                  <p class="article-card__excerpt" itemprop="description"><?= htmlspecialchars($a['excerpt']) ?></p>
                <?php endif; ?>
                <div class="article-card__meta">
                  <span><?= htmlspecialchars($a['author_name']) ?></span>
                  <span class="article-card__meta-dot"></span>
                  <span><?= date('d M Y', strtotime($a['published_at'])) ?></span>
                  <?php if ($a['read_time']): ?>
                    <span class="article-card__meta-dot"></span>
                    <span><?= $a['read_time'] ?> mnt</span>
                  <?php endif; ?>
                </div>
              </div>
            </article>
            <?php endforeach; ?>
          </div>
        </div>
        <?php endif; ?>

        <!-- PAGINATION -->
        <?php if ($totalPages > 1): ?>
        <div class="pagination">
          <?php if ($page > 1): ?>
            <a href="?page=<?= $page-1 ?>&category=<?= urlencode($category) ?>&tag=<?= urlencode($tag) ?>">‹</a>
          <?php endif; ?>
          <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <?php if ($i === $page): ?>
              <span class="active"><?= $i ?></span>
            <?php elseif ($i === 1 || $i === $totalPages || abs($i - $page) <= 1): ?>
              <a href="?page=<?= $i ?>&category=<?= urlencode($category) ?>&tag=<?= urlencode($tag) ?>"><?= $i ?></a>
            <?php elseif (abs($i - $page) === 2): ?>
              <span class="dots">…</span>
            <?php endif; ?>
          <?php endfor; ?>
          <?php if ($page < $totalPages): ?>
            <a href="?page=<?= $page+1 ?>&category=<?= urlencode($category) ?>&tag=<?= urlencode($tag) ?>">›</a>
          <?php endif; ?>
        </div>
        <?php endif; ?>

      <?php endif; ?>
    </main>

    <!-- SIDEBAR -->
    <aside class="blog-sidebar">

      <!-- Popular -->
      <?php if (!empty($popular)): ?>
      <div class="sidebar-card">
        <div class="sidebar-card__title">🔥 Terpopuler</div>
        <?php foreach ($popular as $i => $p): ?>
          <a href="/blog/<?= htmlspecialchars($p['slug']) ?>" style="display:flex;align-items:baseline;gap:10px;padding:9px 0;border-bottom:1px solid rgba(184,77,122,.07);text-decoration:none;<?= $i === count($popular)-1 ? 'border-bottom:none;' : '' ?>">
            <span style="font-size:12px;font-weight:800;color:rgba(184,77,122,.35);min-width:18px;flex-shrink:0;"><?= str_pad($i+1, 2, '0', STR_PAD_LEFT) ?></span>
            <span style="font-size:13px;font-weight:500;color:#2d1a24;line-height:1.4;transition:color .15s;" onmouseover="this.style.color='#b84d7a'" onmouseout="this.style.color='#2d1a24'"><?= htmlspecialchars($p['title']) ?></span>
          </a>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>

      <!-- Categories -->
      <div class="sidebar-card">
        <div class="sidebar-card__title">📂 Kategori</div>
        <?php foreach ($categories as $cat): ?>
          <a href="/blog/?category=<?= urlencode($cat['slug']) ?>"
             style="display:flex;align-items:center;justify-content:space-between;padding:8px 0;border-bottom:1px solid rgba(184,77,122,.07);font-size:13px;color:<?= $category === $cat['slug'] ? '#b84d7a' : '#4a2d3a' ?>;text-decoration:none;font-weight:<?= $category === $cat['slug'] ? '600' : '400' ?>;">
            <span style="display:flex;align-items:center;gap:8px;">
              <span style="width:8px;height:8px;border-radius:50%;background:<?= htmlspecialchars($cat['color']) ?>;flex-shrink:0;"></span>
              <?= htmlspecialchars($cat['name']) ?>
            </span>
            <span style="background:#fdf0f5;color:#b84d7a;font-size:11px;font-weight:700;padding:1px 7px;border-radius:10px;"><?= $cat['cnt'] ?></span>
          </a>
        <?php endforeach; ?>
      </div>

      <!-- CTA -->
      <div style="background:linear-gradient(135deg,rgba(232,160,191,.2),rgba(184,77,122,.15));border:1px solid rgba(184,77,122,.2);border-radius:16px;padding:24px;text-align:center;">
        <h3 style="font-size:1rem;font-weight:700;color:#1a0a12;margin-bottom:8px;">Coba Cantik.AI Gratis</h3>
        <p style="font-size:13px;color:#7a4d62;margin-bottom:16px;line-height:1.6;">Platform AI khusus industri kecantikan Indonesia. Mulai sekarang, tanpa kartu kredit.</p>
        <a href="https://app.cantik.ai/" style="display:block;padding:10px 20px;background:linear-gradient(135deg,#e8a0bf,#b84d7a);color:#fff;border-radius:8px;font-size:13px;font-weight:600;text-decoration:none;">Mulai Sekarang →</a>
      </div>

    </aside>
  </div>

</div>

<?php require_once __DIR__ . '/../../layouts/footer.php'; ?>
