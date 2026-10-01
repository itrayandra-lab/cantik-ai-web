<?php
/**
 * pages/ai-erp.php
 * Halaman AI ERP — Connected Intelligence for Cosmetic Manufacturing
 *
 * Routing: /ai-erp
 *   - dev/Herd (nginx) → index.php:96 "Halaman statis lain" sudah otomatis
 *     memetakan /ai-erp ke pages/ai-erp.php, tidak perlu ubah index.php.
 *   - production (Apache/LiteSpeed) → rules di .htaccess.
 *
 * Catatan penting soal CSS global:
 * 1. light-theme.css punya `h1,h2,h3,h4,h5,h6 { color:#2d1b2e !important }`.
 *    Semua heading di section gelap WAJIB pakai `color:... !important` sendiri.
 * 2. sintraweb.shared.min.css punya selector `[class*="tag"]`,
 *    `[class*="badge"]`, `[class*="progress"]` ber-!important yang menempel
 *    ke elemen apa pun yang namanya mengandung kata itu. Karena itu nama
 *    kelas modul memakai "__eyebrow", bukan "__tag" / "__badge".
 *
 * Aturan copywriting halaman ini:
 *   badge/eyebrow, headline, tombol CTA  →  Bahasa Inggris
 *   deskripsi & body copy                →  Bahasa Indonesia
 *   kata "beauty" tidak dipakai sama sekali.
 */

$siteUrl   = 'https://cantik.ai';
$pageTitle = 'AI ERP for Cosmetic Manufacturing | Cantik.AI';
$pageDesc  = 'Satu ekosistem AI untuk manufaktur kosmetik: dari formulasi dan bahan aktif, perencanaan '
    . 'produksi (PPIC), kontrol kualitas, hingga kepatuhan BPOM & Halal dalam satu sistem AI ERP.';
$canonical = $siteUrl . '/ai-erp';
$ogImage   = $siteUrl . '/assets/img/og-image.jpg';

// Halaman ini tidak punya section #faq, jadi link FAQ di footer
// diarahkan ke homepage (layouts/footer.php:18).
$showFaq = false;

/* ================================================================
   WHATSAPP — semua CTA mengarah ke nomor yang sama, pesan berbeda
   ================================================================ */
$waNumber = '6281121912390';

$waMsgHero = 'Halo Tim Cantik.AI, saya tertarik untuk diskusi lebih lanjut mengenai AI ERP untuk Cosmetic Manufacturing.';
$waMsgFinal = 'Halo Tim Cantik.AI, saya ingin berkonsultasi mengenai penerapan AI ERP untuk manufaktur kosmetik kami. Boleh minta jadwal diskusi?';

// Satu-satunya sumber link WhatsApp di halaman ini.
$wa = static fn (string $msg): string => 'https://wa.me/' . $waNumber . '?text=' . rawurlencode($msg);

/* ================================================================
   01 — HERO
   ================================================================ */
$heroDesc = 'Hubungkan seluruh rantai pasok manufaktur kosmetik Anda — mulai dari manajemen bahan aktif R&D, '
    . 'perencanaan produksi (PPIC), kontrol kualitas (QC), hingga kepatuhan BPOM & Halal dalam satu '
    . 'ekosistem AI yang terpadu dan presisi.';

/* Alur proses di panel kanan hero — memakai label yang sama dengan
   process track di final CTA supaya narasi konsisten dari atas ke bawah. */
$heroFlow = [
    ['k' => '01', 't' => 'PPIC Planning'],
    ['k' => '02', 't' => 'Raw Materials'],
    ['k' => '03', 't' => 'Manufacturing & QC'],
    ['k' => '04', 't' => 'BPOM & Halal'],
];

/* Modul yang disebut di panel hero — ringkas saja, detail penuh ada di section 03. */
$heroModules = [
    'Production Planning',
    'Ingredient Traceability',
    'Manufacturing & QC',
    'Regulatory Compliance',
];

/* ================================================================
   02 — PROBLEM & VALUE PROPOSITION
   ================================================================ */
$problemIntro = 'ERP konvensional menganggap bahan baku seperti barang cetakan biasa. Industri manufaktur '
    . 'kosmetik memerlukan penanganan presisi untuk stabilitas bahan aktif, pengujian lot, dan kepatuhan '
    . 'regulasi yang ketat.';

$challenges = [
    [
        'title' => 'Active Ingredients & Shelf-Life Management',
        'd'     => 'Pelacakan otomatis sensitivitas suhu, kelembapan, Certificate of Analysis (CoA), serta '
                 . 'peringatan penurunan efikasi bahan aktif sebelum kadaluarsa.',
        'color' => '#b84d7a',
    ],
    [
        'title' => 'Siloed R&D, PPIC, and Regulatory Teams',
        'd'     => 'Sinkronisasi instan dari AI Formulator langsung ke BMR (Batch Manufacturing Record) dan '
                 . 'Bill of Materials (BOM) pabrik tanpa pengerjaan ulang manual.',
        'color' => '#6b4a9e',
    ],
    [
        'title' => 'Compliance & Regulatory Risk',
        'd'     => 'Penapisan bahan Halal otomatis dan otomatisasi penyusunan dokumen yang siap diaudit BPOM '
                 . 'secara real-time.',
        'color' => '#1f8a70',
    ],
];

/* ================================================================
   03 — MODUL UTAMA
   ================================================================ */
$modules = [
    [
        'num'   => '01',
        'id'    => 'ppic',
        'label' => 'Production Planning',
        'title' => 'Smart PPIC & Market-Driven Demand Forecasting',
        'd'     => 'Menghubungkan sinyal tren permintaan pasar dari modul KawalData langsung dengan inventaris '
                 . 'pabrik untuk mencegah kerugian akibat overstock atau out-of-stock.',
        'color' => '#b84d7a',
    ],
    [
        'num'   => '02',
        'id'    => 'traceability',
        'label' => 'Ingredient Traceability',
        'title' => 'Active Ingredient & Batch Quality Tracking',
        'd'     => 'Memantau nomor lot, masa kadaluarsa bahan aktif, dan sertifikat CoA di setiap tahapan '
                 . 'dengan sistem peringatan dini (early warning) sebelum bahan mengalami penurunan efektivitas.',
        'color' => '#c2557f',
    ],
    [
        'num'   => '03',
        'id'    => 'qc',
        'label' => 'Manufacturing & QC',
        'title' => 'Connected QC & Digital Batch Record (BMR)',
        'd'     => 'Digitalisasi penuh pengujian laboratorium QC mulai dari bahan mentah, bulk, hingga produk '
                 . 'jadi tanpa pencatatan kertas manual untuk meminimalkan risiko kontaminasi batch.',
        'color' => '#6b4a9e',
    ],
    [
        'num'   => '04',
        'id'    => 'compliance',
        'label' => 'Regulatory Compliance',
        'title' => 'Halal & BPOM Regulatory Audit Trail',
        'd'     => 'Menghasilkan dokumen rekam produksi dan Laporan Sistem Jaminan Halal (SJH) secara otomatis '
                 . 'yang siap diaudit kapan saja.',
        'color' => '#1f8a70',
    ],
];

/* ================================================================
   04 — CONNECTED ECOSYSTEM
   ================================================================ */
$ecoIntro = 'Menghubungkan riset R&D, pengadaan bahan baku, proses produksi pabrik, hingga strategi pemasaran '
    . 'dalam satu rantai informasi pintar yang saling terintegrasi.';

$chain = [
    [
        'k'     => 'R&D',
        't'     => 'R&D & Formulation',
        'd'     => 'Riset bahan dan formulasi langsung menjadi data master yang bisa dibaca mesin produksi.',
        'color' => '#b84d7a',
    ],
    [
        'k'     => 'PROCUREMENT',
        't'     => 'Raw Material Procurement',
        'd'     => 'Pemesanan, penerimaan lot, dan verifikasi CoA tercatat dalam satu alur tanpa paperwork ulang.',
        'color' => '#c2557f',
    ],
    [
        'k'     => 'FACTORY',
        't'     => 'Manufacturing & QC',
        'd'     => 'Rencana produksi, pemakaian bahan, dan hasil pengujian QC saling terkait otomatis.',
        'color' => '#6b4a9e',
    ],
    [
        'k'     => 'MARKETING',
        't'     => 'Marketing & Distribution',
        'd'     => 'Performa produk jadi dan stok siap kirim tersinkron ke strategi pemasaran brand Anda.',
        'color' => '#1f8a70',
    ],
];

/* ================================================================
   05 — FINAL CTA
   ================================================================ */
$finalDesc = 'Konsultasikan kebutuhan pabrik dan brand kosmetik Anda langsung dengan tim spesialis kami '
    . 'melalui WhatsApp. Dapatkan solusi operasional terintegrasi tanpa risiko kelalaian regulasi.';

/* ================================================================
   HEAD — JSON-LD + CSS
   ================================================================ */
$ld = [
    '@context'    => 'https://schema.org',
    '@type'       => 'WebPage',
    'name'        => 'AI ERP for Cosmetic Manufacturing',
    'description' => $pageDesc,
    'url'         => $canonical,
    'inLanguage'  => 'id',
    'about'       => array_map(static fn ($m) => [
        '@type' => 'SoftwareApplication',
        'name'  => 'AI ERP — ' . $m['label'],
        'applicationCategory' => 'BusinessApplication',
    ], $modules),
];

$extraHead = '<script type="application/ld+json">'
    /* JSON_HEX_AMP: "&" di dalam JSON-LD ditulis sebagai & supaya
       parser HTML tidak pernah salah baca blok <script>. */
    . json_encode($ld, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_AMP)
    . '</script>' . "\n" . <<<'CSS'
<style>
/* ══ BRAND TYPOGRAPHY — disamakan dengan halaman feature (Cantik.AI) ══
   Satu-satunya font: GT Walsheim Pro. layouts/header.php:44 memaksa body
   ke 'Segoe UI', jadi kita override di .aerp-page (kelas, bukan body)
   supaya rules itu kalah. */
.aerp-page {
  font-family:'GT Walsheim Pro',Arial,sans-serif;
  font-size:1.0625rem; font-weight:400; line-height:1.5;
  -webkit-font-smoothing:antialiased; -moz-osx-font-smoothing:grayscale;
  color:#2d1a24;
}
.aerp-page h1, .aerp-page h2, .aerp-page h3,
.aerp-eyebrow, .aerp-flow__t, .aerp-chain__k, .aerp-chain__t {
  font-family:'GT Walsheim Pro',Arial,sans-serif;
}
.aerp-page h1, .aerp-page h2, .aerp-page h3 {
  letter-spacing:-.03em; font-weight:500;
}

.aerp-wrap { max-width:1180px; margin:0 auto; }
.aerp-sec  { padding:82px 2.5rem; }
.aerp-sec--tint { background:#fdf8fb; }
.aerp-sec--edge { border-top:1px solid rgba(184,77,122,.1); }

/* ── Shared section header ─────────────────────────────────────── */
.aerp-head { margin-bottom:46px; }
.aerp-eyebrow {
  display:flex; align-items:center; gap:9px;
  width:fit-content; max-width:100%;
  margin:0 auto 18px; padding:8px 17px 8px 14px;
  border-radius:999px;
  background:rgba(184,77,122,.06);
  border:1px solid rgba(184,77,122,.2);
  font-size:11.5px; font-weight:500; letter-spacing:.14em;
  line-height:1; text-transform:uppercase; text-align:center;
  color:#b84d7a;
}
.aerp-eyebrow::before {
  content:''; width:6px; height:6px; flex-shrink:0;
  border-radius:50%; background:#e8a0bf;
}
.aerp-h2 {
  margin:0 auto 14px; max-width:820px; text-align:center;
  font-size:clamp(26px,3.6vw,40px); line-height:1.14; font-weight:500;
  letter-spacing:-.03em; color:#1a0a12 !important;
}
.aerp-h2 em, .aerp-final__title em {
  font-style:normal;
  background:linear-gradient(135deg,#e8a0bf,#b84d7a);
  -webkit-background-clip:text; background-clip:text; -webkit-text-fill-color:transparent;
}
.aerp-sub { margin:0 auto; max-width:680px; text-align:center; font-size:16.5px; line-height:1.72; color:#6b3a52; }

/* ── Buttons ───────────────────────────────────────────────────── */
.aerp-btn {
  display:inline-flex; align-items:center; gap:9px;
  padding:14px 30px; border-radius:12px;
  font-size:15px; font-weight:700; text-decoration:none; line-height:1;
  transition:opacity .18s, transform .18s, box-shadow .18s, background .18s, border-color .18s, color .18s;
}
.aerp-btn span { transition:transform .18s; }
.aerp-btn:hover span { transform:translateX(3px); }
.aerp-btn--primary {
  background:linear-gradient(135deg,#e8a0bf,#b84d7a); color:#fff;
  box-shadow:0 8px 24px rgba(184,77,122,.34);
}
.aerp-btn--primary:hover { opacity:.92; transform:translateY(-1px); box-shadow:0 12px 30px rgba(184,77,122,.4); }
.aerp-btn--line { background:#fff; border:1px solid rgba(184,77,122,.35); color:#b84d7a; }
.aerp-btn--line:hover { background:#fdf0f5; border-color:#b84d7a; transform:translateY(-1px); }
.aerp-btn--ghost { background:rgba(255,255,255,.05); border:1px solid rgba(255,255,255,.22); color:#fff; }
.aerp-btn--ghost:hover { background:rgba(232,160,191,.16); border-color:rgba(232,160,191,.5); transform:translateY(-1px); }

/* ══ 01 — HERO ═══════════════════════════════════════════════════ */
.aerp-hero {
  position:relative; overflow:hidden; text-align:left;
  padding:88px 2.5rem 84px;
  background:linear-gradient(155deg,#2d1a24 0%,#1a0a12 56%,#25112c 100%);
}
.aerp-hero__glow { position:absolute; inset:0; pointer-events:none;
  background:
    radial-gradient(ellipse 62% 54% at 50% -10%, rgba(184,77,122,.46) 0%, transparent 68%),
    radial-gradient(ellipse 46% 46% at 88% 106%, rgba(107,74,158,.34) 0%, transparent 70%);
}
.aerp-hero__grid {
  position:absolute; inset:0; pointer-events:none;
  background-image:
    linear-gradient(rgba(255,255,255,.05) 1px, transparent 1px),
    linear-gradient(90deg, rgba(255,255,255,.05) 1px, transparent 1px);
  background-size:64px 64px;
  -webkit-mask-image:radial-gradient(ellipse 72% 62% at 50% 28%, #000 0%, transparent 76%);
          mask-image:radial-gradient(ellipse 72% 62% at 50% 28%, #000 0%, transparent 76%);
}
.aerp-hero__inner {
  position:relative; z-index:1; max-width:1160px; margin:0 auto;
  display:grid; grid-template-columns:minmax(0,1.06fr) minmax(0,.94fr);
  gap:60px; align-items:center;
}
.aerp-hero__copy { min-width:0; text-align:left; }
.aerp-hero__title { margin:0 0 24px; }
/* Paksa semua elemen kolom kiri rata kiri, apa pun style global yang masuk. */
.aerp-hero__over,
.aerp-hero__display,
.aerp-hero__desc { text-align:left; margin-left:0; margin-right:0; }
.aerp-hero__over {
  display:inline-flex; align-items:center; gap:9px;
  margin:0 0 24px; padding:8px 17px 8px 14px;
  border-radius:999px;
  background:rgba(232,160,191,.12);
  border:1px solid rgba(232,160,191,.32);
  font-size:12px; font-weight:500; letter-spacing:.15em;
  line-height:1; text-transform:uppercase;
  color:#e8a0bf !important;
  -webkit-backdrop-filter:blur(4px); backdrop-filter:blur(4px);
}
.aerp-hero__over::before {
  content:''; width:6px; height:6px; flex-shrink:0;
  border-radius:50%; background:#e8a0bf;
  box-shadow:0 0 0 3px rgba(232,160,191,.22);
}
.aerp-hero__display {
  display:block; font-size:clamp(29px,4.4vw,50px); line-height:1.13;
  font-weight:500; letter-spacing:-.03em; color:#fff !important;
}
.aerp-hero__display em {
  font-style:normal;
  background:linear-gradient(120deg,#fbe3ec 0%,#e8a0bf 48%,#cf5f90 100%);
  -webkit-background-clip:text; background-clip:text; -webkit-text-fill-color:transparent;
}
.aerp-hero__desc {
  margin:0; max-width:600px;
  font-size:clamp(15.5px,1.6vw,17.5px); line-height:1.78; color:rgba(255,255,255,.8);
}
.aerp-hero__actions { margin-top:34px; display:flex; flex-wrap:wrap; gap:13px; justify-content:flex-start; }
.aerp-hero__actions .aerp-btn { margin:0; flex:0 0 auto; }

/* Panel kanan: alur proses + chip modul */
.aerp-panel {
  padding:28px 26px 26px; border-radius:22px;
  background:rgba(255,255,255,.05);
  border:1px solid rgba(255,255,255,.14);
  -webkit-backdrop-filter:blur(10px); backdrop-filter:blur(10px);
  box-shadow:0 24px 60px rgba(0,0,0,.3);
}
.aerp-panel__head {
  display:flex; align-items:center; gap:10px;
  padding-bottom:18px; margin-bottom:20px;
  border-bottom:1px solid rgba(255,255,255,.12);
}
.aerp-panel__dot {
  width:8px; height:8px; flex-shrink:0; border-radius:50%; background:#1f8a70;
  animation:aerpPulse 2s ease-in-out infinite;
}
@keyframes aerpPulse {
  0%,100% { box-shadow:0 0 0 3px rgba(31,138,112,.2); }
  50%     { box-shadow:0 0 0 6px rgba(31,138,112,.05); }
}
.aerp-panel__title { font-size:14px; font-weight:800; color:#fff; }
.aerp-panel__state {
  margin-left:auto; padding:3px 10px; border-radius:999px;
  background:rgba(31,138,112,.1); border:1px solid rgba(31,138,112,.3);
  font-size:10.5px; font-weight:800; letter-spacing:.14em; text-transform:uppercase; color:#4fc3a1;
}

/* Alur proses — step + garis penghubung vertikal */
.aerp-flow { list-style:none; margin:0; padding:0; }
.aerp-flow__item { position:relative; display:flex; gap:15px; padding-bottom:22px; }
.aerp-flow__item:last-child { padding-bottom:0; }
.aerp-flow__item::before {
  content:''; position:absolute; left:14px; top:32px; bottom:2px; width:1px;
  background:linear-gradient(180deg, rgba(232,160,191,.5), rgba(232,160,191,.12));
}
.aerp-flow__item:last-child::before { display:none; }
.aerp-flow__n {
  position:relative; z-index:1; flex-shrink:0;
  width:29px; height:29px;
  display:flex; align-items:center; justify-content:center;
  background:rgba(232,160,191,.14); border:1px solid rgba(232,160,191,.36);
  border-radius:50%;
  font-size:10.5px; font-weight:800; letter-spacing:.04em; color:#e8a0bf;
}
.aerp-flow__t { margin:5px 0 0; font-size:15.5px; font-weight:600; letter-spacing:-.01em; color:#fff; }

.aerp-panel__div {
  margin:24px 0 20px; padding-top:20px;
  border-top:1px solid rgba(255,255,255,.12);
}
.aerp-panel__label {
  margin:0 0 12px; font-size:10.5px; font-weight:800;
  letter-spacing:.16em; text-transform:uppercase; color:rgba(255,255,255,.42);
}
.aerp-panel__chips { display:flex; flex-wrap:wrap; gap:8px; }
.aerp-panel__chip {
  padding:7px 13px; border-radius:999px;
  background:rgba(255,255,255,.06); border:1px solid rgba(255,255,255,.14);
  font-size:12px; font-weight:500; color:rgba(255,255,255,.8);
}

/* ══ 02 — PROBLEM ═══════════════════════════════════════════════ */
.aerp-cards { display:grid; grid-template-columns:repeat(3,1fr); gap:20px; }
.aerp-card {
  position:relative; overflow:hidden;
  padding:32px 28px 30px; background:#fff;
  border:1px solid rgba(184,77,122,.12); border-radius:20px;
  transition:transform .22s, box-shadow .22s, border-color .22s;
}
.aerp-card::before { content:''; position:absolute; left:0; right:0; top:0; height:3px; background:var(--accent); }
.aerp-card::after {
  content:''; position:absolute; inset:0; pointer-events:none;
  background:radial-gradient(120% 90% at 0% 0%, var(--tint) 0%, transparent 62%);
}
.aerp-card:hover { transform:translateY(-4px); box-shadow:0 18px 40px rgba(184,77,122,.12); border-color:var(--accent); }
.aerp-card > * { position:relative; z-index:1; }
.aerp-card__mark {
  width:44px; height:44px; margin-bottom:20px;
  display:flex; align-items:center; justify-content:center;
  background:var(--tint); border:1px solid var(--border-c); border-radius:13px;
  font-size:20px; line-height:1; color:var(--accent);
}
.aerp-card__t { margin:0 0 12px; font-size:19px; font-weight:500; letter-spacing:-.02em; line-height:1.32; color:#1a0a12 !important; }
.aerp-card__d { margin:0; font-size:14.5px; line-height:1.72; color:#6b3a52; }

/* ══ 03 — MODULES ═══════════════════════════════════════════════ */
.aerp-modules { display:grid; grid-template-columns:repeat(2,1fr); gap:22px; }
.aerp-module {
  position:relative; overflow:hidden; display:flex; flex-direction:column;
  padding:34px 32px; background:#fff;
  border:1px solid rgba(184,77,122,.14); border-radius:22px;
  transition:transform .25s, box-shadow .25s, border-color .25s;
}
.aerp-module::before {
  content:''; position:absolute; inset:0; opacity:.6; pointer-events:none;
  background:radial-gradient(120% 90% at 0% 0%, var(--tint) 0%, transparent 62%);
}
.aerp-module::after { content:''; position:absolute; left:0; right:0; top:0; height:3px; background:var(--accent); }
.aerp-module:hover { transform:translateY(-4px); box-shadow:0 18px 44px rgba(184,77,122,.14); border-color:var(--accent); }
.aerp-module > * { position:relative; z-index:1; }
/* specificity 0,2,0 — HARUS menang dari `.aerp-module > *` di atas,
   kalau tidak nomor ini jadi elemen flow (position:relative) dan dorong card. */
.aerp-module > .aerp-module__num {
  position:absolute; top:20px; right:28px; z-index:0;
  font-size:44px; font-weight:800; line-height:1; letter-spacing:-.04em; color:var(--tint);
}
.aerp-module__eyebrow {
  margin:0 0 14px; padding-right:60px;
  font-size:11.5px; font-weight:800; letter-spacing:.16em;
  text-transform:uppercase; color:var(--accent);
}
.aerp-module__t { margin:0 0 12px; max-width:420px; font-size:21px; font-weight:500; letter-spacing:-.025em; line-height:1.3; color:#1a0a12 !important; }
.aerp-module__d { margin:0; max-width:460px; font-size:14.5px; line-height:1.75; color:#6b3a52; }
.aerp-module__link {
  margin-top:24px; display:inline-flex; align-items:center; gap:8px;
  align-self:flex-start;
  padding:12px 22px; border-radius:12px; text-decoration:none;
  font-size:14px; font-weight:700; color:#fff; background:var(--accent);
  transition:opacity .18s, transform .18s, box-shadow .18s;
}
.aerp-module__link:hover { opacity:.9; transform:translateY(-1px); box-shadow:0 8px 20px var(--tint-2); }
.aerp-module__link span { transition:transform .18s; }
.aerp-module__link:hover span { transform:translateX(3px); }

/* ══ 04 — ECOSYSTEM CHAIN ═══════════════════════════════════════ */
.aerp-chain { position:relative; display:grid; grid-template-columns:repeat(4,1fr); gap:20px; }
.aerp-chain::before {
  content:''; position:absolute; top:23px; left:11%; right:11%; height:2px;
  background:repeating-linear-gradient(90deg, rgba(184,77,122,.34) 0 8px, transparent 8px 15px);
}
.aerp-node { position:relative; z-index:1; text-align:center; }
.aerp-node__dot {
  position:relative; width:46px; height:46px; margin:0 auto 20px;
  display:flex; align-items:center; justify-content:center;
  background:#fff; border:2px solid var(--accent); border-radius:50%;
  box-shadow:0 0 0 6px #fdf8fb;
}
.aerp-node__dot svg { width:20px; height:20px; color:var(--accent); }
.aerp-node__k { margin:0 0 6px; font-size:10.5px; font-weight:800; letter-spacing:.16em; text-transform:uppercase; color:var(--accent); }
.aerp-node__t { margin:0 0 10px; font-size:17.5px; font-weight:500; letter-spacing:-.02em; line-height:1.32; color:#1a0a12 !important; }
.aerp-node__d { margin:0; font-size:13.5px; line-height:1.7; color:#6b3a52; }

/* ══ 05 — FINAL CTA ═════════════════════════════════════════════ */
.aerp-final {
  text-align:center; padding:60px 44px; border-radius:26px;
  background:linear-gradient(140deg,#fdf0f5 0%,#fff 42%,#f4f0fb 100%);
  border:1px solid rgba(184,77,122,.18);
  box-shadow:0 24px 60px rgba(184,77,122,.12);
}
.aerp-final__title {
  margin:0 auto 18px; max-width:820px;
  font-size:clamp(24px,3.4vw,38px); font-weight:500; letter-spacing:-.03em; line-height:1.18;
  color:#1a0a12 !important;
}
.aerp-final__lead { margin:0 auto; max-width:680px; font-size:16.5px; line-height:1.72; color:#6b3a52; }
.aerp-final__flow {
  margin:28px auto 0; display:inline-flex; flex-wrap:wrap; justify-content:center; gap:12px;
  padding:12px 24px; border-radius:999px;
  background:#fff; border:1px solid rgba(184,77,122,.18);
  font-size:12.5px; font-weight:800; letter-spacing:.14em; color:#b84d7a;
}
.aerp-final__actions { margin-top:30px; display:flex; flex-wrap:wrap; gap:13px; justify-content:center; align-items:center; }

/* CTA final — primary memakai gradient yang SAMA PERSIS dengan
   .aerp-btn--primary di hero supaya dua CTA itu terlihat identik. */
.aerp-cta-primary {
  padding:16px 34px;
  background:linear-gradient(135deg,#e8a0bf,#b84d7a);
  color:#fff; font-size:15.5px; font-weight:700; letter-spacing:.005em;
  box-shadow:0 10px 28px rgba(184,77,122,.34);
}
.aerp-cta-primary:hover {
  opacity:.92;
  box-shadow:0 16px 40px rgba(184,77,122,.45);
  transform:translateY(-2px);
}
.aerp-cta-glass {
  padding:16px 30px;
  background:rgba(255,255,255,.62);
  border:1px solid rgba(184,77,122,.26);
  -webkit-backdrop-filter:blur(10px); backdrop-filter:blur(10px);
  color:#9d5a76; font-size:15px; font-weight:600;
  box-shadow:0 4px 16px rgba(184,77,122,.07);
}
.aerp-cta-glass:hover {
  background:rgba(255,255,255,.9);
  border-color:rgba(184,77,122,.5);
  color:#b84d7a;
  transform:translateY(-2px);
  box-shadow:0 12px 30px rgba(184,77,122,.16);
}

/* ══ RESPONSIVE ══════════════════════════════════════════════════ */
@media(max-width:1080px) {
  .aerp-hero__inner { grid-template-columns:1fr; gap:44px; }
  .aerp-panel { max-width:560px; }
  .aerp-hero__desc { max-width:680px; }
  .aerp-cards { grid-template-columns:repeat(2,1fr); }
  .aerp-chain { grid-template-columns:repeat(2,1fr); gap:32px 20px; }
  .aerp-chain::before { display:none; }
}
@media(max-width:860px) {
  .aerp-modules { grid-template-columns:1fr; }
}
@media(max-width:640px) {
  .aerp-sec { padding:56px 1.25rem; }
  .aerp-hero { padding:60px 1.25rem 56px; }
  .aerp-cards, .aerp-chain { grid-template-columns:1fr; }
  .aerp-module { padding:26px 22px; }
  .aerp-panel { padding:24px 20px 22px; }
  .aerp-final { padding:40px 20px; }
  .aerp-final__flow { gap:8px; letter-spacing:.1em; }
  .aerp-final__actions .aerp-btn { width:100%; justify-content:center; }
  .aerp-hero__actions .aerp-btn { width:100%; justify-content:center; }
  .aerp-cta-primary, .aerp-cta-glass { padding:15px 22px; }
}
</style>
CSS;

require_once __DIR__ . '/../layouts/header.php';
?>

<div class="aerp-page">

  <!-- ══════════ 01 — HERO ══════════ -->
  <header class="aerp-hero">
    <div class="aerp-hero__glow" aria-hidden="true"></div>
    <div class="aerp-hero__grid" aria-hidden="true"></div>

    <div class="aerp-hero__inner">
      <div class="aerp-hero__copy">
        <h1 class="aerp-hero__title">
          <span class="aerp-hero__over">Connected Intelligence for Cosmetic Manufacturing</span>
          <span class="aerp-hero__display">Smart Operations, Fully Integrated<br><em>from Formulation to Warehouse</em></span>
        </h1>

        <p class="aerp-hero__desc"><?= htmlspecialchars($heroDesc) ?></p>

        <div class="aerp-hero__actions">
          <a class="aerp-btn aerp-btn--primary" href="<?= htmlspecialchars($wa($waMsgHero)) ?>"
             target="_blank" rel="noopener">
            Start WhatsApp Discussion <span aria-hidden="true">&rarr;</span>
          </a>
          <a class="aerp-btn aerp-btn--ghost" href="#modules">Explore AI ERP Modules</a>
        </div>
      </div>

      <div class="aerp-panel">
        <div class="aerp-panel__head">
          <span class="aerp-panel__dot" aria-hidden="true"></span>
          <span class="aerp-panel__title">Connected Operations</span>
          <span class="aerp-panel__state">Live</span>
        </div>

        <ol class="aerp-flow">
          <?php foreach ($heroFlow as $f): ?>
            <li class="aerp-flow__item">
              <span class="aerp-flow__n" aria-hidden="true"><?= htmlspecialchars($f['k']) ?></span>
              <p class="aerp-flow__t"><?= htmlspecialchars($f['t']) ?></p>
            </li>
          <?php endforeach; ?>
        </ol>

        <div class="aerp-panel__div">
          <p class="aerp-panel__label">Modules</p>
          <div class="aerp-panel__chips">
            <?php foreach ($heroModules as $m): ?>
              <span class="aerp-panel__chip"><?= htmlspecialchars($m) ?></span>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>
  </header>

  <!-- ══════════ 02 — PROBLEM & VALUE PROPOSITION ══════════ -->
  <section class="aerp-sec">
    <div class="aerp-wrap">
      <div class="aerp-head">
        <p class="aerp-eyebrow">Cosmetic Manufacturing Challenges</p>
        <h2 class="aerp-h2">Cosmetic Manufacturing Requires <em>Intelligence, Not Just Record Keeping</em></h2>
        <p class="aerp-sub"><?= htmlspecialchars($problemIntro) ?></p>
      </div>

      <div class="aerp-cards">
        <?php foreach ($challenges as $i => $c): ?>
          <article class="aerp-card"
                   style="--accent:<?= htmlspecialchars($c['color']) ?>;--tint:<?= htmlspecialchars($c['color']) ?>1f;--border-c:<?= htmlspecialchars($c['color']) ?>33;">
            <div class="aerp-card__mark" aria-hidden="true">
              <?php /* ikon garis, sama persis gaya dengan node di section 04 */ ?>
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                   stroke-linecap="round" stroke-linejoin="round" width="20" height="20">
                <?php if ($i === 0): ?>
                  <path d="M3 12h4l3 8 4-16 3 8h4"/>
                <?php elseif ($i === 1): ?>
                  <rect x="3" y="3" width="7" height="7" rx="1.5"/>
                  <rect x="14" y="14" width="7" height="7" rx="1.5"/>
                  <path d="M13.5 6.5H18a3 3 0 0 1 3 3V10"/>
                  <path d="M10.5 17.5H6a3 3 0 0 1-3-3V14"/>
                <?php else: ?>
                  <path d="M12 3l7.5 3v6c0 4.2-3 7.7-7.5 9-4.5-1.3-7.5-4.8-7.5-9V6z"/>
                  <path d="M9 12l2 2 4-4"/>
                <?php endif; ?>
              </svg>
            </div>
            <h3 class="aerp-card__t"><?= htmlspecialchars($c['title']) ?></h3>
            <p class="aerp-card__d"><?= htmlspecialchars($c['d']) ?></p>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- ══════════ 03 — MODUL UTAMA ══════════ -->
  <section class="aerp-sec aerp-sec--tint aerp-sec--edge" id="modules">
    <div class="aerp-wrap">
      <div class="aerp-head">
        <p class="aerp-eyebrow">Modular AI ERP System</p>
        <h2 class="aerp-h2">End-to-End Modules <em>Tailored for Cosmetic Manufacturing</em></h2>
      </div>

      <div class="aerp-modules">
        <?php foreach ($modules as $m): ?>
          <article class="aerp-module" id="<?= htmlspecialchars($m['id']) ?>"
                   style="--accent:<?= htmlspecialchars($m['color']) ?>;--tint:<?= htmlspecialchars($m['color']) ?>1f;--tint-2:<?= htmlspecialchars($m['color']) ?>14;--border-c:<?= htmlspecialchars($m['color']) ?>33;">
            <span class="aerp-module__num" aria-hidden="true"><?= htmlspecialchars($m['num']) ?></span>

            <p class="aerp-module__eyebrow"><?= htmlspecialchars($m['label']) ?></p>
            <h3 class="aerp-module__t"><?= htmlspecialchars($m['title']) ?></h3>
            <p class="aerp-module__d"><?= htmlspecialchars($m['d']) ?></p>

            <a class="aerp-module__link" href="<?= htmlspecialchars($wa($waMsgHero)) ?>"
               target="_blank" rel="noopener">
              Discuss This Module <span aria-hidden="true">&rarr;</span>
            </a>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- ══════════ 04 — CONNECTED ECOSYSTEM ══════════ -->
  <section class="aerp-sec">
    <div class="aerp-wrap">
      <div class="aerp-head">
        <p class="aerp-eyebrow">Connected Ecosystem</p>
        <h2 class="aerp-h2">Single Source of Truth <em>Across Your Operations</em></h2>
        <p class="aerp-sub"><?= htmlspecialchars($ecoIntro) ?></p>
      </div>

      <div class="aerp-chain">
        <?php foreach ($chain as $n): ?>
          <article class="aerp-node" style="--accent:<?= htmlspecialchars($n['color']) ?>;">
            <div class="aerp-node__dot" aria-hidden="true">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                   stroke-linecap="round" stroke-linejoin="round">
                <?php switch ($n['k']): case 'R&D': ?>
                  <path d="M9 3h6M10 3v6l-5 9a2 2 0 0 0 1.8 3h10.4A2 2 0 0 0 19 18l-5-9V3"/>
                  <path d="M7.5 15h9"/>
                <?php case 'PROCUREMENT': ?>
                  <path d="M3 5h2l2.4 10.4a2 2 0 0 0 2 1.6h7.7a2 2 0 0 0 2-1.6L21 8H6"/>
                  <circle cx="10" cy="20" r="1.2"/><circle cx="18" cy="20" r="1.2"/>
                <?php case 'FACTORY': ?>
                  <path d="M3 21V10l5 3V10l5 3V7l8 4v10z"/>
                  <path d="M7 21v-4h3v4M14 21v-4h3v4"/>
                <?php case 'MARKETING': ?>
                  <path d="M4 9v6h4l6 4V5L8 9z"/>
                  <path d="M17.5 8.5a5 5 0 0 1 0 7"/>
                <?php endswitch; ?>
              </svg>
            </div>
            <p class="aerp-node__k"><?= htmlspecialchars($n['k']) ?></p>
            <h3 class="aerp-node__t"><?= htmlspecialchars($n['t']) ?></h3>
            <p class="aerp-node__d"><?= htmlspecialchars($n['d']) ?></p>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- ══════════ 05 — FINAL CTA ══════════ -->
  <section class="aerp-sec aerp-sec--tint aerp-sec--edge">
    <div class="aerp-wrap">
      <div class="aerp-final">
        <p class="aerp-eyebrow">From Formulation to Warehouse</p>
        <h2 class="aerp-final__title">
          Transform Your <em>Cosmetic Manufacturing Operations</em> Today
        </h2>
        <p class="aerp-final__lead"><?= htmlspecialchars($finalDesc) ?></p>

        <p class="aerp-final__flow">PPIC Planning &rarr; Raw Materials &rarr; Manufacturing &amp; QC &rarr; BPOM &amp; Halal</p>

        <div class="aerp-final__actions">
          <a class="aerp-btn aerp-cta-primary" href="<?= htmlspecialchars($wa($waMsgFinal)) ?>"
             target="_blank" rel="noopener">
            Discuss via WhatsApp <span aria-hidden="true">&rarr;</span>
          </a>
          <a class="aerp-btn aerp-cta-glass" href="<?= htmlspecialchars($wa($waMsgFinal)) ?>"
             target="_blank" rel="noopener">Schedule Live Demo</a>
        </div>
      </div>
    </div>
  </section>

</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>