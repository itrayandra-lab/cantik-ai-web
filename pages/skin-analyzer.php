<?php
/**
 * pages/skin-analyzer.php
 * Halaman Skin Analyzer — Understand Your Skin. Personalize Your Care.
 *
 * Routing: /skin-analyzer
 *   - dev/Herd (nginx) → index.php fallback "halaman statis lain" memetakan
 *     /skin-analyzer ke pages/skin-analyzer.php.
 *   - production (Apache/LiteSpeed) → rules di .htaccess.
 *
 * Catatan penting soal CSS global (sama seperti pages/ai-erp.php):
 * 1. light-theme.css punya `h1..h6 { color:#2d1b2e !important }` → semua
 *    heading WAJIB punya `color:...!important` sendiri.
 * 2. sintraweb.shared.min.css punya `[class*="tag"]`, `[class*="badge"]`,
 *    `[class*="progress"]` ber-!important. Karena itu:
 *      - eyebrow  → "__eyebrow" (bukan __tag/__badge)
 *      - bar skor → "skan-meter" (bukan __progress)
 *
 * Aturan copywriting halaman ini:
 *   badge/eyebrow, headline, tombol CTA  →  Bahasa Inggris
 *   deskripsi & body copy                →  Bahasa Indonesia
 */

$siteUrl   = 'https://cantik.ai';
$pageTitle = 'Cantik.AI Skin Analyzer — AI-Powered Skin Analysis';
$pageDesc  = 'Skin Analyzer Cantik.AI membantu memahami kondisi kulit wajah melalui analisis berbasis AI '
    . 'dari tiga sudut pengambilan gambar: skin score, skin insight, rekomendasi perawatan, hingga '
    . 'rekomendasi produk dalam satu pengalaman digital.';
$canonical = $siteUrl . '/skin-analyzer';
$ogImage   = $siteUrl . '/assets/img/og-image.jpg';

// Halaman ini tidak punya section #faq → link FAQ footer diarahkan ke homepage.
$showFaq = false;

/* ================================================================
   LINK TARGET
   ================================================================ */
$appUrl   = 'https://skinanalyzer.cantik.ai/scan';
$waNumber = '6281121912390';

$waMsgConsult = 'Halo Tim Cantik.AI, saya ingin berkonsultasi mengenai Skin Analyzer — cara kerja, parameter analisis, serta bagaimana penerapannya. Boleh dibantu penjelasannya?';
$waMsgBiz     = 'Halo Tim Cantik.AI, saya ingin berkonsultasi mengenai penerapan Skin Analyzer untuk bisnis atau brand kami. Kapan kita bisa berdiskusi?';
$waMsgTeam    = 'Halo Tim Cantik.AI, saya ingin berbicara dengan tim untuk mengetahui lebih lanjut mengenai solusi Skin Analyzer.';

// Satu-satunya sumber link WhatsApp di halaman ini.
$wa = static fn (string $msg): string => 'https://wa.me/' . $waNumber . '?text=' . rawurlencode($msg);

/* ================================================================
   ICON SET — path SVG (stroke) untuk seluruh section
   ================================================================ */
function skan_icon(string $name): string
{
    $paths = [
        'front'   => '<circle cx="12" cy="12" r="9"/><path d="M9 10h.01M15 10h.01M9 15c.9.7 1.9 1 3 1s2.1-.3 3-1"/>',
        'left'    => '<circle cx="12" cy="12" r="9"/><path d="M8 10h.01M14 15c.9.7 1.9 1 3 1M16 8.5c1 .8 1.6 1.9 1.8 3.2"/>',
        'right'   => '<circle cx="12" cy="12" r="9"/><path d="M16 10h.01M10 15c-.9.7-1.9 1-3 1M8 8.5c-1 .8-1.6 1.9-1.8 3.2"/>',
        'ai'      => '<rect x="4" y="4" width="16" height="16" rx="4"/><path d="M9 9h6v6H9zM9 2v2M15 2v2M9 20v2M15 20v2M2 9h2M2 15h2M20 9h2M20 15h2"/>',
        'acne'    => '<circle cx="12" cy="12" r="5"/><path d="M12 7v-2M12 19v-2M7 12H5M19 12h-2M8.5 8.5L7 7M15.5 15.5L17 17M15.5 8.5L17 7M8.5 15.5L7 17"/>',
        'pores'   => '<circle cx="7" cy="7" r="1.6"/><circle cx="12" cy="7" r="1.6"/><circle cx="17" cy="7" r="1.6"/><circle cx="7" cy="12" r="1.6"/><circle cx="12" cy="12" r="1.6"/><circle cx="17" cy="12" r="1.6"/><circle cx="7" cy="17" r="1.6"/><circle cx="12" cy="17" r="1.6"/><circle cx="17" cy="17" r="1.6"/>',
        'wrinkles'=> '<path d="M3 8c2-2 4-2 6 0s4 2 6 0 4-2 6 0M3 13c2-2 4-2 6 0s4 2 6 0 4-2 6 0M3 18c2-2 4-2 6 0s4 2 6 0 4-2 6 0"/>',
        'spots'   => '<circle cx="8" cy="9" r="2"/><circle cx="15" cy="7" r="1.4"/><circle cx="13" cy="14" r="2.4"/><circle cx="6" cy="16" r="1.3"/><circle cx="17" cy="16" r="1.6"/>',
        'redness' => '<path d="M12 3c2 4 4 6 4 9a4 4 0 0 1-8 0c0-3 2-5 4-9z"/><path d="M12 21v-2"/>',
        'texture' => '<path d="M4 6h16M4 10h16M4 14h16M4 18h16"/>',
        'circle'  => '<path d="M20 14.5A8 8 0 1 1 9.5 4 6.5 6.5 0 0 0 20 14.5z"/>',
        'oil'     => '<path d="M12 3s6 6.5 6 10.5a6 6 0 0 1-12 0C6 9.5 12 3 12 3z"/>',
        'qr'      => '<rect x="4" y="4" width="6" height="6" rx="1"/><rect x="14" y="4" width="6" height="6" rx="1"/><rect x="4" y="14" width="6" height="6" rx="1"/><path d="M14 14h3v3M20 14v6h-6M14 20h.01"/>',
        'wa'      => '<path d="M12 3a9 9 0 0 0-7.7 13.6L3 21l4.5-1.2A9 9 0 1 0 12 3z"/><path d="M9 9c0 3 2 5 5 5 .8 0 1.4-.6 1.4-1.4l-1.6-.8-.9.9c-1-.4-1.7-1.1-2.1-2.1l.9-.9-.8-1.6C10.4 8.6 9.8 9.2 9 9z"/>',
        'report'  => '<path d="M6 3h9l4 4v14H6z"/><path d="M15 3v4h4M9 13h6M9 17h6M9 9h2"/>',
        'shield'  => '<path d="M12 3l7.5 3v6c0 4.2-3 7.7-7.5 9-4.5-1.3-7.5-4.8-7.5-9V6z"/><path d="M9 12l2 2 4-4"/>',
        'knowledge'=> '<path d="M4 5.5A2.5 2.5 0 0 1 6.5 3H12v16H6.5A2.5 2.5 0 0 0 4 21.5z"/><path d="M20 5.5A2.5 2.5 0 0 0 17.5 3H12v16h5.5a2.5 2.5 0 0 1 2.5 2.5z"/>',
        'verify'  => '<circle cx="12" cy="12" r="9"/><path d="M8.5 12.5l2.5 2.5 4.5-5"/>',
        'website' => '<circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3c2.5 2.7 3.8 5.7 3.8 9S14.5 18.3 12 21c-2.5-2.7-3.8-5.7-3.8-9S9.5 5.7 12 3z"/>',
        'mobile'  => '<rect x="7" y="3" width="10" height="18" rx="2.5"/><path d="M11 18h2"/>',
        'kiosk'   => '<rect x="4" y="3" width="16" height="12" rx="2"/><path d="M12 15v4M8 21h8M8 7h8M8 10h5"/>',
        'store'   => '<path d="M4 9l1.5-5h13L20 9M5 9v11h14V9M4 9h16"/><path d="M9 20v-6h6v6"/>',
        'event'   => '<path d="M12 3v3M12 18v3M3 12h3M18 12h3M5.6 5.6l2.1 2.1M16.3 16.3l2.1 2.1M18.4 5.6l-2.1 2.1M7.7 16.3l-2.1 2.1"/><circle cx="12" cy="12" r="3"/>',
        'consult' => '<path d="M4 5h16v11H8l-4 4z"/><path d="M8 9h8M8 12h5"/>',
        'cart'    => '<path d="M3 5h2l2.4 10.4a2 2 0 0 0 2 1.6h7.7a2 2 0 0 0 2-1.6L21 8H6"/><circle cx="10" cy="20" r="1.2"/><circle cx="18" cy="20" r="1.2"/>',
        'sun'     => '<circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4 12H2M22 12h-2M5.6 5.6L4.2 4.2M19.8 19.8l-1.4-1.4M18.4 5.6l1.4-1.4M4.2 19.8l1.4-1.4"/>',
        'moon'    => '<path d="M20 14.5A8 8 0 1 1 9.5 4 6.5 6.5 0 0 0 20 14.5z"/>',
        'brand'   => '<path d="M12 3l2.5 5.5L20 9.3l-4 4 1 5.7-5-2.7-5 2.7 1-5.7-4-4 5.5-.8z"/>',
        'retail'  => '<path d="M4 9l1.5-5h13L20 9M5 9v11h14V9M4 9h16"/><path d="M9 20v-6h6v6"/>',
        'clinic'  => '<path d="M12 3l8 4v5c0 4.5-3.2 8.3-8 9.7C7.2 20.3 4 16.5 4 12V7z"/><path d="M12 8v6M9 11h6"/>',
        'spark'   => '<path d="M12 3l1.8 5.2L19 10l-5.2 1.8L12 17l-1.8-5.2L5 10l5.2-1.8z"/><path d="M18 15l.7 2 2 .7-2 .7-.7 2-.7-2-2-.7 2-.7z"/>',
    ];
    $p = $paths[$name] ?? '';
    return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" '
        . 'stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' . $p . '</svg>';
}

/* ================================================================
   01 — HERO
   ================================================================ */
$heroDesc = 'Skin Analyzer dari Cantik.ai membantu memahami kondisi kulit wajah melalui analisis berbasis AI '
    . 'dari tiga sudut pengambilan gambar — depan, kiri, dan kanan. Dapatkan hasil analisis yang lebih '
    . 'terstruktur, skor setiap parameter kulit, insight kondisi kulit, rekomendasi perawatan, hingga '
    . 'rekomendasi produk yang relevan.';
$heroNote = 'Dikembangkan dengan basis pengetahuan dermatologi dan berkolaborasi dengan dokter dermatologi '
    . 'dalam proses verifikasi data dan knowledge base.';

$heroViews = [
    ['k' => 'FRONT', 'i' => 'front'],
    ['k' => 'LEFT',  'i' => 'left'],
    ['k' => 'RIGHT', 'i' => 'right'],
];

/* ================================================================
   02 — HOW IT WORKS
   ================================================================ */
$howIntro = 'Proses analisis dimulai dengan tiga foto wajah dari sudut yang berbeda. Sistem kemudian '
    . 'memproses informasi tersebut untuk menghasilkan gambaran kondisi kulit yang lebih menyeluruh.';

$howSteps = [
    ['n' => '01', 'i' => 'front', 't' => 'Front View',  'd' => 'Ambil foto wajah dari posisi depan untuk memulai proses analisis.'],
    ['n' => '02', 'i' => 'left',  't' => 'Left View',   'd' => 'Ambil foto sisi kiri wajah untuk melengkapi informasi kondisi kulit.'],
    ['n' => '03', 'i' => 'right', 't' => 'Right View',  'd' => 'Ambil foto sisi kanan wajah untuk mendapatkan informasi dari sudut lainnya.'],
    ['n' => '04', 'i' => 'ai',    't' => 'AI Analysis', 'd' => 'Ketiga foto diproses oleh sistem untuk menghasilkan hasil analisis berdasarkan parameter kulit yang tersedia.'],
];

/* ================================================================
   03 — SKIN ANALYSIS (parameter)
   ================================================================ */
$analysisIntro = 'Hasil analisis memberikan informasi mengenai berbagai parameter kondisi kulit berdasarkan '
    . 'data visual yang diperoleh dari proses pengambilan gambar.';

$params = [
    ['i' => 'acne',    't' => 'Acne'],
    ['i' => 'pores',   't' => 'Pores'],
    ['i' => 'wrinkles','t' => 'Wrinkles'],
    ['i' => 'spots',   't' => 'Dark Spots'],
    ['i' => 'redness', 't' => 'Redness'],
    ['i' => 'texture', 't' => 'Skin Texture'],
    ['i' => 'circle',  't' => 'Dark Circle'],
    ['i' => 'oil',     't' => 'Oiliness'],
];

/* ================================================================
   04 — SKIN SCORE
   ================================================================ */
$scoreIntro = 'Setiap parameter analisis dapat ditampilkan dalam bentuk skor sehingga pengguna dapat melihat '
    . 'kondisi kulit secara lebih mudah dan terstruktur. Hasil tersebut kemudian dilengkapi dengan penjelasan '
    . 'mengenai area yang perlu diperhatikan.';

$scoreTotal = 82;
$scores = [
    ['t' => 'Acne',       'v' => 78, 'c' => '#b84d7a'],
    ['t' => 'Pores',      'v' => 86, 'c' => '#c2557f'],
    ['t' => 'Dark Spots', 'v' => 71, 'c' => '#6b4a9e'],
    ['t' => 'Wrinkles',   'v' => 80, 'c' => '#1f8a70'],
    ['t' => 'Redness',    'v' => 88, 'c' => '#c2557f'],
    ['t' => 'Texture',    'v' => 84, 'c' => '#1f8a70'],
];

/* ================================================================
   05 — SKIN INSIGHT
   ================================================================ */
$insightIntro = 'Angka saja tidak cukup. Hasil analisis diterjemahkan menjadi informasi yang lebih mudah '
    . 'dipahami sehingga pengguna dapat mengetahui kondisi kulitnya dan memahami area yang membutuhkan '
    . 'perhatian lebih.';
$insightItem = [
    't' => 'Pores — 72/100',
    'd' => 'Tampilan pori-pori terlihat pada beberapa area wajah. Fokus perawatan dapat diarahkan pada '
         . 'menjaga kebersihan kulit, membantu mengontrol sebum, serta mempertahankan kondisi skin barrier.',
];

/* ================================================================
   06 — PERSONALIZED CARE
   ================================================================ */
$careIntro = 'Berdasarkan hasil analisis, pengguna dapat memperoleh arahan perawatan yang disesuaikan dengan '
    . 'kebutuhan kondisi kulitnya, mulai dari rutinitas pagi hingga malam.';

$routines = [
    ['i' => 'sun',  't' => 'Morning Routine', 'steps' => ['Cleanse', 'Hydrate', 'Moisturize', 'Protect'], 'c' => '#c2557f'],
    ['i' => 'moon', 't' => 'Night Routine',   'steps' => ['Cleanse', 'Treat', 'Repair', 'Moisturize'],    'c' => '#6b4a9e'],
];

/* ================================================================
   07 — PRODUCT RECOMMENDATION
   ================================================================ */
$prodIntro = 'Hasil analisis dapat dihubungkan dengan rekomendasi produk berdasarkan kebutuhan kulit '
    . 'pengguna. Hal ini membantu pengguna menemukan produk yang relevan tanpa harus menelusuri terlalu '
    . 'banyak pilihan.';

$prodGroups = [
    ['t' => 'Acne',             'i' => 'acne',    'c' => '#b84d7a', 'items' => ['Acne Cleanser', 'Sebum Control Serum', 'Acne Spot Treatment']],
    ['t' => 'Dry Skin',         'i' => 'oil',     'c' => '#6b4a9e', 'items' => ['Hydrating Cleanser', 'Barrier Serum', 'Moisturizer']],
    ['t' => 'Uneven Skin Tone', 'i' => 'spark',   'c' => '#1f8a70', 'items' => ['Brightening Serum', 'Moisturizer', 'Sunscreen']],
];

/* ================================================================
   08 — DIGITAL RESULT
   ================================================================ */
$digitalIntro = 'Setelah analisis selesai, hasil dapat diakses secara digital sehingga pengguna tidak perlu '
    . 'mengingat atau mencatat hasil pemeriksaan secara manual.';

$digital = [
    ['i' => 'qr',     't' => 'QR Code',      'd' => 'Scan QR Code untuk membuka hasil analisis secara digital.'],
    ['i' => 'wa',     't' => 'WhatsApp',     'd' => 'Hasil analisis dapat dikirim langsung ke WhatsApp pengguna.'],
    ['i' => 'report', 't' => 'Digital Report','d' => 'Pengguna dapat kembali mengakses hasil analisis melalui halaman hasil yang tersedia.'],
];

/* ================================================================
   09 — DERMATOLOGY COLLABORATION
   ================================================================ */
$dermIntro = 'Skin Analyzer Cantik.ai dikembangkan dengan memadukan teknologi AI dan basis pengetahuan '
    . 'terkait kondisi kulit. Pengembangan data dan knowledge base dilakukan dengan melibatkan dokter '
    . 'dermatologi dalam proses verifikasi untuk membantu menjaga relevansi informasi yang digunakan dalam '
    . 'sistem.';

$derm = [
    ['i' => 'ai',        't' => 'AI Technology',          'd' => 'Membantu memproses dan menginterpretasikan data visual dari hasil pengambilan gambar.'],
    ['i' => 'knowledge', 't' => 'Dermatology Knowledge',  'd' => 'Basis pengetahuan terkait kondisi kulit digunakan sebagai salah satu referensi dalam pengembangan sistem.'],
    ['i' => 'verify',    't' => 'Expert Verification',    'd' => 'Data dan knowledge base melalui proses verifikasi bersama dokter dermatologi.'],
];
$dermNote = 'Skin Analyzer merupakan alat analisis berbasis AI untuk memberikan informasi mengenai kondisi '
    . 'kulit dan bukan pengganti diagnosis atau konsultasi medis dengan dokter.';

/* ================================================================
   10 — BUSINESS SOLUTION
   ================================================================ */
$bizIntro = 'Skin Analyzer tidak hanya dirancang untuk penggunaan individual. Teknologi ini dapat diterapkan '
    . 'sebagai bagian dari pengalaman pelanggan pada berbagai jenis bisnis di industri kecantikan.';

$biz = [
    ['i' => 'brand',   't' => 'Cosmetic Brand',            'd' => 'Bantu pelanggan menemukan produk berdasarkan kebutuhan kondisi kulit mereka.'],
    ['i' => 'retail',  't' => 'Beauty Retail',             'd' => 'Berikan pengalaman konsultasi yang lebih interaktif dan personal.'],
    ['i' => 'clinic',  't' => 'Clinic & Beauty Business',  'd' => 'Gunakan analisis kulit sebagai bagian dari customer consultation journey.'],
    ['i' => 'event',   't' => 'Event & Activation',        'd' => 'Hadirkan pengalaman digital yang interaktif untuk meningkatkan engagement pengunjung.'],
    ['i' => 'cart',    't' => 'E-Commerce',                'd' => 'Hubungkan analisis kulit dengan pengalaman product discovery dan rekomendasi produk.'],
];

/* ================================================================
   11 — INTEGRATION
   ================================================================ */
$integrationIntro = 'Skin Analyzer dapat disesuaikan dengan kebutuhan dan customer journey masing-masing '
    . 'bisnis, baik sebagai pengalaman digital maupun sebagai bagian dari aktivitas offline.';

$integrations = [
    ['i' => 'website', 't' => 'Website'],
    ['i' => 'mobile',  't' => 'Mobile App'],
    ['i' => 'kiosk',   't' => 'Kiosk'],
    ['i' => 'store',   't' => 'Beauty Store'],
    ['i' => 'event',   't' => 'Event Activation'],
    ['i' => 'consult', 't' => 'Customer Consultation'],
    ['i' => 'cart',    't' => 'E-Commerce'],
];

/* ================================================================
   12 — BUSINESS FLOW
   ================================================================ */
$flowIntro = 'Alur perjalanan pelanggan, dari pengambilan foto wajah hingga pengalaman kecantikan yang '
    . 'personal dan terintegrasi.';

$flow = [
    '3 Face Photos',
    'AI Skin Analysis',
    'Skin Score',
    'Skin Insight',
    'Personalized Care',
    'Product Recommendation',
    'QR Code / WhatsApp',
    'Customer Experience',
];

/* ================================================================
   13 — MAIN BUSINESS CTA
   ================================================================ */
$bizCtaLead = 'Bangun pengalaman pelanggan yang lebih personal dengan menggabungkan analisis kulit, skin '
    . 'insights, rekomendasi perawatan, dan rekomendasi produk dalam satu customer journey.';
$bizCtaLead2 = 'Ceritakan kebutuhan bisnis Anda kepada tim Cantik.ai dan diskusikan bagaimana Skin Analyzer '
    . 'dapat diterapkan pada brand atau bisnis Anda.';
$bizCtaLead3 = 'Ingin mencoba lebih dulu? Anda dapat langsung mencoba Skin Analyzer secara mandiri, lalu '
    . 'berkonsultasi dengan tim kami untuk memahami lebih lanjut dan mendiskusikan penerapannya.';

/* ================================================================
   14 — CLOSING
   ================================================================ */
$closeDesc = 'Teknologi analisis kulit berbasis AI untuk membantu pengguna memahami kondisi kulit sekaligus '
    . 'membantu brand dan bisnis menghadirkan pengalaman kecantikan yang lebih personal dan terintegrasi.';

/* ================================================================
   HEAD — JSON-LD + CSS
   ================================================================ */
$ld = [
    '@context'    => 'https://schema.org',
    '@type'       => 'WebPage',
    'name'        => 'Cantik.AI Skin Analyzer',
    'description' => $pageDesc,
    'url'         => $canonical,
    'inLanguage'  => 'id',
    'about'       => [
        '@type'               => 'SoftwareApplication',
        'name'                => 'Cantik.AI Skin Analyzer',
        'applicationCategory' => 'HealthApplication',
        'operatingSystem'     => 'Web',
    ],
];

$extraHead = '<script type="application/ld+json">'
    // JSON_HEX_AMP: "&" di JSON-LD ditulis sebagai &amp; supaya parser HTML
    // tidak salah membaca blok <script>.
    . json_encode($ld, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_AMP)
    . '</script>' . "\n" . <<<'CSS'
<style>
/* ══ BRAND TYPOGRAPHY ══ */
.skan-page {
  background:#fff;
  font-family:'GT Walsheim Pro',Arial,sans-serif;
  font-size:1.0625rem; font-weight:400; line-height:1.5;
  -webkit-font-smoothing:antialiased; -moz-osx-font-smoothing:grayscale;
  color:#2d1a24;
}
.skan-page h1, .skan-page h2, .skan-page h3, .skan-page h4,
.skan-eyebrow, .skan-step__n {
  font-family:'GT Walsheim Pro',Arial,sans-serif;
}
.skan-page h1, .skan-page h2, .skan-page h3, .skan-page h4 {
  letter-spacing:-.03em; font-weight:500;
}

.skan-wrap { max-width:1180px; margin:0 auto; }
.skan-sec  { padding:82px 2.5rem; }
.skan-sec--tint { background:#fdf8fb; }
.skan-sec--edge { border-top:1px solid rgba(184,77,122,.1); }
.skan-sec--dark {
  background:linear-gradient(160deg,#2d1a24 0%,#1a0a12 58%,#25112c 100%);
  color:#fff;
}

/* ── Section header ─────────────────────────────────── */
.skan-head { margin-bottom:46px; }
.skan-eyebrow {
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
.skan-eyebrow::before {
  content:''; width:6px; height:6px; flex-shrink:0;
  border-radius:50%; background:#e8a0bf;
}
.skan-h2 {
  margin:0 auto 14px; max-width:840px; text-align:center;
  font-size:clamp(26px,3.6vw,40px); line-height:1.14; font-weight:500;
  letter-spacing:-.03em; color:#1a0a12 !important;
}
.skan-h2 em {
  font-style:normal;
  background:linear-gradient(135deg,#e8a0bf,#b84d7a);
  -webkit-background-clip:text; background-clip:text; -webkit-text-fill-color:transparent;
}
.skan-sub { margin:0 auto; max-width:700px; text-align:center; font-size:16.5px; line-height:1.72; color:#6b3a52; }

.skan-sec--dark .skan-eyebrow { background:rgba(232,160,191,.12); border-color:rgba(232,160,191,.32); color:#e8a0bf; }
.skan-sec--dark .skan-h2 { color:#fff !important; }
.skan-sec--dark .skan-sub { color:rgba(255,255,255,.78); }

/* ── Buttons ────────────────────────────────────────── */
.skan-btn {
  display:inline-flex; align-items:center; gap:9px;
  padding:14px 30px; border-radius:12px;
  font-size:15px; font-weight:700; text-decoration:none; line-height:1;
  transition:opacity .18s, transform .18s, box-shadow .18s, background .18s, border-color .18s, color .18s;
}
.skan-btn span { transition:transform .18s; }
.skan-btn:hover span { transform:translateX(3px); }
.skan-btn--primary {
  background:linear-gradient(135deg,#e8a0bf,#b84d7a); color:#fff;
  box-shadow:0 8px 24px rgba(184,77,122,.34);
}
.skan-btn--primary:hover { opacity:.92; transform:translateY(-1px); box-shadow:0 12px 30px rgba(184,77,122,.4); }
.skan-btn--line { background:#fff; border:1px solid rgba(184,77,122,.35); color:#b84d7a; }
.skan-btn--line:hover { background:#fdf0f5; border-color:#b84d7a; transform:translateY(-1px); }
.skan-btn--ghost { background:rgba(255,255,255,.05); border:1px solid rgba(255,255,255,.22); color:#fff; }
.skan-btn--ghost:hover { background:rgba(232,160,191,.16); border-color:rgba(232,160,191,.5); transform:translateY(-1px); }
.skan-actions { display:flex; flex-wrap:wrap; gap:13px; }
.skan-actions--center { justify-content:center; margin-top:34px; }

/* ══ 01 — HERO ════════════════════════════════════════ */
.skan-hero {
  position:relative; overflow:hidden;
  padding:90px 2.5rem 86px;
  background:linear-gradient(155deg,#2d1a24 0%,#1a0a12 56%,#25112c 100%);
}
.skan-hero__glow { position:absolute; inset:0; pointer-events:none;
  background:
    radial-gradient(ellipse 62% 54% at 50% -10%, rgba(184,77,122,.46) 0%, transparent 68%),
    radial-gradient(ellipse 46% 46% at 88% 108%, rgba(107,74,158,.34) 0%, transparent 70%);
}
.skan-hero__grid {
  position:absolute; inset:0; pointer-events:none;
  background-image:
    linear-gradient(rgba(255,255,255,.05) 1px, transparent 1px),
    linear-gradient(90deg, rgba(255,255,255,.05) 1px, transparent 1px);
  background-size:64px 64px;
  -webkit-mask-image:radial-gradient(ellipse 72% 62% at 50% 28%, #000 0%, transparent 76%);
          mask-image:radial-gradient(ellipse 72% 62% at 50% 28%, #000 0%, transparent 76%);
}
.skan-hero__inner {
  position:relative; z-index:1; max-width:1160px; margin:0 auto;
  display:grid; grid-template-columns:minmax(0,1.06fr) minmax(0,.94fr);
  gap:60px; align-items:center;
}
.skan-hero__copy { min-width:0; }
.skan-hero__over {
  display:inline-flex; align-items:center; gap:9px;
  margin:0 0 24px; padding:8px 17px 8px 14px;
  border-radius:999px;
  background:rgba(232,160,191,.12); border:1px solid rgba(232,160,191,.32);
  font-size:12px; font-weight:500; letter-spacing:.15em; line-height:1;
  text-transform:uppercase; color:#e8a0bf !important;
  -webkit-backdrop-filter:blur(4px); backdrop-filter:blur(4px);
}
.skan-hero__over::before {
  content:''; width:6px; height:6px; flex-shrink:0; border-radius:50%; background:#e8a0bf;
  box-shadow:0 0 0 3px rgba(232,160,191,.22);
}
.skan-hero__display {
  display:block; margin:0 0 24px;
  font-size:clamp(29px,4.4vw,50px); line-height:1.13;
  font-weight:500; letter-spacing:-.03em; color:#fff !important;
}
.skan-hero__display em {
  font-style:normal;
  background:linear-gradient(120deg,#fbe3ec 0%,#e8a0bf 48%,#cf5f90 100%);
  -webkit-background-clip:text; background-clip:text; -webkit-text-fill-color:transparent;
}
.skan-hero__desc { margin:0; max-width:600px; font-size:clamp(15.5px,1.6vw,17.5px); line-height:1.78; color:rgba(255,255,255,.8); }
.skan-hero__note {
  margin:22px 0 0; padding-left:14px; max-width:560px;
  border-left:2px solid rgba(232,160,191,.5);
  font-size:13.5px; line-height:1.65; color:rgba(255,255,255,.6);
}
.skan-hero__actions { margin-top:34px; }
.skan-hero__hint { margin:16px 0 0; max-width:560px; font-size:13.5px; line-height:1.65; color:rgba(255,255,255,.62); }

/* Panel kanan hero */
.skan-panel {
  padding:28px 26px 26px; border-radius:22px;
  background:rgba(255,255,255,.05); border:1px solid rgba(255,255,255,.14);
  -webkit-backdrop-filter:blur(10px); backdrop-filter:blur(10px);
  box-shadow:0 24px 60px rgba(0,0,0,.3);
}
.skan-panel__head {
  display:flex; align-items:center; gap:10px;
  padding-bottom:18px; margin-bottom:20px; border-bottom:1px solid rgba(255,255,255,.12);
}
.skan-panel__dot {
  width:8px; height:8px; flex-shrink:0; border-radius:50%; background:#1f8a70;
  animation:skanPulse 2s ease-in-out infinite;
}
@keyframes skanPulse {
  0%,100% { box-shadow:0 0 0 3px rgba(31,138,112,.2); }
  50%     { box-shadow:0 0 0 6px rgba(31,138,112,.05); }
}
.skan-panel__title { font-size:14px; font-weight:800; color:#fff; }
.skan-panel__state {
  margin-left:auto; padding:3px 10px; border-radius:999px;
  background:rgba(31,138,112,.1); border:1px solid rgba(31,138,112,.3);
  font-size:10.5px; font-weight:800; letter-spacing:.14em; text-transform:uppercase; color:#4fc3a1;
}
.skan-views { display:grid; grid-template-columns:repeat(3,1fr); gap:10px; }
.skan-view {
  padding:16px 8px 13px; border-radius:14px; text-align:center;
  background:rgba(255,255,255,.05); border:1px solid rgba(255,255,255,.12);
}
.skan-view svg { width:24px; height:24px; color:#e8a0bf; }
.skan-view__k { margin:9px 0 0; font-size:10.5px; font-weight:800; letter-spacing:.12em; text-transform:uppercase; color:rgba(255,255,255,.72); }
.skan-panel__div { margin:24px 0 0; padding-top:20px; border-top:1px solid rgba(255,255,255,.12); }
.skan-panel__label { margin:0 0 14px; font-size:10.5px; font-weight:800; letter-spacing:.16em; text-transform:uppercase; color:rgba(255,255,255,.42); }
.skan-panel__score { display:flex; align-items:baseline; gap:8px; margin:0 0 16px; }
.skan-panel__score b { font-size:36px; font-weight:800; line-height:1; letter-spacing:-.03em;
  background:linear-gradient(120deg,#fbe3ec,#e8a0bf 55%,#cf5f90);
  -webkit-background-clip:text; background-clip:text; -webkit-text-fill-color:transparent; }
.skan-panel__score span { font-size:13px; color:rgba(255,255,255,.55); }

/* Meter (progress bar) — nama kelas menghindari kata "progress" */
.skan-meter { margin-bottom:13px; }
.skan-meter:last-child { margin-bottom:0; }
.skan-meter__top { display:flex; justify-content:space-between; align-items:baseline; margin-bottom:7px; }
.skan-meter__label { font-size:13px; font-weight:600; color:rgba(255,255,255,.82); }
.skan-meter__val { font-size:13px; font-weight:800; color:#e8a0bf; }
.skan-meter__track {
  height:7px; border-radius:999px; overflow:hidden;
  background:rgba(255,255,255,.1);
}
.skan-meter__fill {
  height:100%; border-radius:999px; width:calc(var(--v) * 1%);
  background:linear-gradient(90deg,#e8a0bf,var(--accent,#b84d7a));
}

/* ══ Section header rata kiri (varian) ════════════════ */
.skan-head--left { text-align:left; }
.skan-head--left .skan-h2, .skan-head--left .skan-sub { margin-left:0; margin-right:0; text-align:left; }
.skan-head--left .skan-eyebrow { margin-left:0; margin-right:0; }

/* ══ 02 — HOW IT WORKS ════════════════════════════════ */
.skan-steps { position:relative; display:grid; grid-template-columns:repeat(4,1fr); gap:20px; }
.skan-steps::before {
  content:''; position:absolute; top:30px; left:8%; right:8%; height:2px; z-index:0;
  background:repeating-linear-gradient(90deg, rgba(184,77,122,.34) 0 8px, transparent 8px 15px);
}
.skan-step { position:relative; z-index:1; padding:26px 22px; text-align:center;
  background:#fff; border:1px solid rgba(184,77,122,.14); border-radius:18px;
  transition:transform .22s, box-shadow .22s, border-color .22s; }
.skan-step:hover { transform:translateY(-4px); box-shadow:0 18px 40px rgba(184,77,122,.12); border-color:#b84d7a; }
.skan-step__ic {
  width:60px; height:60px; margin:0 auto 18px; border-radius:50%;
  display:flex; align-items:center; justify-content:center;
  background:#fdf0f5; border:2px solid rgba(184,77,122,.3); box-shadow:0 0 0 6px #fdf8fb;
}
.skan-step__ic svg { width:26px; height:26px; color:#b84d7a; }
.skan-step__n { margin:0 0 6px; font-size:11px; font-weight:800; letter-spacing:.16em; color:#c2557f; }
.skan-step__t { margin:0 0 10px; font-size:18px; font-weight:500; letter-spacing:-.02em; color:#1a0a12 !important; }
.skan-step__d { margin:0; font-size:14px; line-height:1.7; color:#6b3a52; }

/* ══ 03 — PARAMETERS ══════════════════════════════════ */
.skan-params { display:grid; grid-template-columns:repeat(4,1fr); gap:16px; }
.skan-param {
  display:flex; align-items:center; gap:14px;
  padding:20px 20px; background:#fff; border-radius:16px;
  border:1px solid rgba(184,77,122,.14);
  transition:transform .2s, box-shadow .2s, border-color .2s;
}
.skan-param:hover { transform:translateY(-3px); box-shadow:0 14px 30px rgba(184,77,122,.12); border-color:#b84d7a; }
.skan-param__ic {
  width:44px; height:44px; flex-shrink:0; border-radius:12px;
  display:flex; align-items:center; justify-content:center;
  background:#fdf0f5; border:1px solid rgba(184,77,122,.18);
}
.skan-param__ic svg { width:22px; height:22px; color:#b84d7a; }
.skan-param__t { margin:0; font-size:15.5px; font-weight:600; letter-spacing:-.01em; color:#1a0a12 !important; }

/* ══ 04 — SKIN SCORE ══════════════════════════════════ */
.skan-score {
  display:grid; grid-template-columns:minmax(0,.8fr) minmax(0,1.2fr); gap:0;
  background:#fff; border:1px solid rgba(184,77,122,.16); border-radius:24px; overflow:hidden;
  box-shadow:0 20px 50px rgba(184,77,122,.1);
}
.skan-score__side {
  padding:44px 36px;
  background:linear-gradient(160deg,#2d1a24 0%,#1a0a12 60%,#25112c 100%);
  color:#fff; display:flex; flex-direction:column; justify-content:center;
}
.skan-score__label { margin:0 0 16px; font-size:11.5px; font-weight:800; letter-spacing:.16em; text-transform:uppercase; color:rgba(232,160,191,.9); }
.skan-score__big { display:flex; align-items:baseline; gap:6px; margin:0; }
.skan-score__big b {
  font-size:clamp(56px,9vw,84px); font-weight:800; line-height:1; letter-spacing:-.04em;
  background:linear-gradient(120deg,#fbe3ec,#e8a0bf 52%,#cf5f90);
  -webkit-background-clip:text; background-clip:text; -webkit-text-fill-color:transparent;
}
.skan-score__big span { font-size:20px; color:rgba(255,255,255,.5); }
.skan-score__cap { margin:18px 0 0; font-size:14px; line-height:1.6; color:rgba(255,255,255,.68); }
.skan-score__list { padding:38px 40px; }
.skan-score__row { display:flex; justify-content:space-between; align-items:baseline; margin-bottom:8px; }
.skan-score__row .skan-meter__label { color:#3a1f2c; }
.skan-score__row .skan-meter__val { color:#b84d7a; }
.skan-score__list .skan-meter { margin-bottom:22px; }
.skan-score__list .skan-meter:last-child { margin-bottom:0; }
.skan-meter--light .skan-meter__track { background:rgba(184,77,122,.12); }

/* ══ 05 — SKIN INSIGHT ════════════════════════════════ */
.skan-insight {
  display:grid; grid-template-columns:minmax(0,1fr) minmax(0,1.15fr); gap:40px; align-items:center;
  padding:44px 44px; background:#fff; border:1px solid rgba(184,77,122,.16); border-radius:24px;
  box-shadow:0 20px 50px rgba(184,77,122,.1);
}
.skan-insight__demo {
  padding:30px 28px; border-radius:20px;
  background:linear-gradient(150deg,#fdf0f5,#fff 55%,#f4f0fb);
  border:1px solid rgba(184,77,122,.18);
}
.skan-insight__top { display:flex; align-items:center; gap:14px; margin-bottom:20px; }
.skan-insight__ic {
  width:48px; height:48px; flex-shrink:0; border-radius:14px;
  display:flex; align-items:center; justify-content:center;
  background:#fff; border:1px solid rgba(184,77,122,.22);
}
.skan-insight__ic svg { width:24px; height:24px; color:#b84d7a; }
.skan-insight__name { margin:0; font-size:18px; font-weight:600; letter-spacing:-.02em; color:#1a0a12 !important; }
.skan-insight__k { margin:2px 0 0; font-size:11.5px; font-weight:800; letter-spacing:.12em; text-transform:uppercase; color:#b84d7a; }
.skan-insight__copy { }
.skan-insight__title { margin:0 0 14px; font-size:24px; font-weight:500; letter-spacing:-.025em; line-height:1.25; color:#1a0a12 !important; }
.skan-insight__text { margin:0; font-size:16px; line-height:1.75; color:#6b3a52; }

/* ══ 06 — PERSONALIZED CARE ═══════════════════════════ */
.skan-care { display:grid; grid-template-columns:repeat(2,1fr); gap:24px; }
.skan-routine {
  padding:36px 32px; background:#fff; border:1px solid rgba(184,77,122,.14); border-radius:22px;
  transition:transform .22s, box-shadow .22s;
}
.skan-routine:hover { transform:translateY(-4px); box-shadow:0 18px 44px rgba(184,77,122,.12); }
.skan-routine__head { display:flex; align-items:center; gap:14px; margin-bottom:26px; }
.skan-routine__ic {
  width:48px; height:48px; flex-shrink:0; border-radius:14px;
  display:flex; align-items:center; justify-content:center;
  background:var(--tint); border:1px solid var(--border-c);
}
.skan-routine__ic svg { width:24px; height:24px; color:var(--accent); }
.skan-routine__t { margin:0; font-size:20px; font-weight:500; letter-spacing:-.02em; color:#1a0a12 !important; }
.skan-flow { display:flex; flex-wrap:wrap; align-items:center; gap:10px; }
.skan-flow__s { padding:10px 16px; border-radius:999px; background:#fdf8fb; border:1px solid rgba(184,77,122,.16);
  font-size:14px; font-weight:600; color:#4a2436; }
.skan-flow__a { font-size:15px; font-weight:800; color:#c2557f; }

/* ══ 07 — PRODUCT RECOMMENDATION ══════════════════════ */
.skan-prods { display:grid; grid-template-columns:repeat(3,1fr); gap:22px; }
.skan-prod {
  position:relative; overflow:hidden; padding:34px 30px; background:#fff;
  border:1px solid rgba(184,77,122,.14); border-radius:22px;
  transition:transform .25s, box-shadow .25s, border-color .25s;
}
.skan-prod::before { content:''; position:absolute; left:0; right:0; top:0; height:3px; background:var(--accent); }
.skan-prod::after { content:''; position:absolute; inset:0; pointer-events:none;
  background:radial-gradient(120% 90% at 0% 0%, var(--tint) 0%, transparent 62%); }
.skan-prod:hover { transform:translateY(-4px); box-shadow:0 18px 44px rgba(184,77,122,.14); border-color:var(--accent); }
.skan-prod > * { position:relative; z-index:1; }
.skan-prod__head { display:flex; align-items:center; gap:12px; margin-bottom:22px; }
.skan-prod__ic { width:44px; height:44px; flex-shrink:0; border-radius:12px; display:flex; align-items:center; justify-content:center;
  background:var(--tint); border:1px solid var(--border-c); }
.skan-prod__ic svg { width:22px; height:22px; color:var(--accent); }
.skan-prod__t { margin:0; font-size:18px; font-weight:600; letter-spacing:-.02em; color:#1a0a12 !important; }
.skan-prod__list { list-style:none; margin:0; padding:0; }
.skan-prod__list li {
  display:flex; align-items:center; gap:10px; padding:12px 0;
  border-bottom:1px dashed rgba(184,77,122,.16);
  font-size:15px; color:#4a2436;
}
.skan-prod__list li:last-child { border-bottom:none; padding-bottom:0; }
.skan-prod__list li::before { content:''; width:6px; height:6px; border-radius:50%; background:var(--accent); flex-shrink:0; }

/* ══ 08 — DIGITAL RESULT ══════════════════════════════ */
.skan-digital { display:grid; grid-template-columns:repeat(3,1fr); gap:22px; }
.skan-digital__card {
  padding:36px 30px; text-align:center; background:#fff; border:1px solid rgba(184,77,122,.14); border-radius:22px;
  transition:transform .22s, box-shadow .22s, border-color .22s;
}
.skan-digital__card:hover { transform:translateY(-4px); box-shadow:0 18px 40px rgba(184,77,122,.12); border-color:#b84d7a; }
.skan-digital__ic {
  width:60px; height:60px; margin:0 auto 20px; border-radius:18px;
  display:flex; align-items:center; justify-content:center;
  background:linear-gradient(150deg,#fdf0f5,#f4f0fb); border:1px solid rgba(184,77,122,.2);
}
.skan-digital__ic svg { width:28px; height:28px; color:#b84d7a; }
.skan-digital__t { margin:0 0 10px; font-size:18px; font-weight:500; letter-spacing:-.02em; color:#1a0a12 !important; }
.skan-digital__d { margin:0; font-size:14.5px; line-height:1.7; color:#6b3a52; }

/* ══ 09 — DERMATOLOGY ═════════════════════════════════ */
.skan-derm { display:grid; grid-template-columns:repeat(3,1fr); gap:22px; }
.skan-derm__card {
  padding:34px 30px; background:rgba(255,255,255,.05); border:1px solid rgba(255,255,255,.14); border-radius:22px;
  transition:transform .22s, background .22s, border-color .22s;
}
.skan-derm__card:hover { transform:translateY(-4px); background:rgba(255,255,255,.08); border-color:rgba(232,160,191,.4); }
.skan-derm__ic {
  width:48px; height:48px; margin-bottom:20px; border-radius:14px;
  display:flex; align-items:center; justify-content:center;
  background:rgba(232,160,191,.14); border:1px solid rgba(232,160,191,.3);
}
.skan-derm__ic svg { width:24px; height:24px; color:#e8a0bf; }
.skan-derm__t { margin:0 0 12px; font-size:18px; font-weight:500; letter-spacing:-.02em; color:#fff !important; }
.skan-derm__d { margin:0; font-size:14.5px; line-height:1.72; color:rgba(255,255,255,.74); }
.skan-note {
  margin:30px auto 0; max-width:820px; padding:16px 22px; text-align:center;
  border-radius:14px; background:rgba(255,255,255,.05); border:1px solid rgba(255,255,255,.14);
  font-size:13.5px; line-height:1.65; color:rgba(255,255,255,.62);
}

/* ══ 10 — BUSINESS ════════════════════════════════════ */
.skan-biz { display:grid; grid-template-columns:repeat(3,1fr); gap:20px; }
.skan-biz__card {
  padding:30px 26px; background:#fff; border:1px solid rgba(184,77,122,.14); border-radius:20px;
  transition:transform .22s, box-shadow .22s, border-color .22s;
}
.skan-biz__card:hover { transform:translateY(-4px); box-shadow:0 18px 40px rgba(184,77,122,.12); border-color:#b84d7a; }
.skan-biz__ic {
  width:46px; height:46px; margin-bottom:18px; border-radius:13px;
  display:flex; align-items:center; justify-content:center;
  background:#fdf0f5; border:1px solid rgba(184,77,122,.18);
}
.skan-biz__ic svg { width:23px; height:23px; color:#b84d7a; }
.skan-biz__t { margin:0 0 10px; font-size:17.5px; font-weight:600; letter-spacing:-.02em; color:#1a0a12 !important; }
.skan-biz__d { margin:0; font-size:14px; line-height:1.7; color:#6b3a52; }

/* ══ 11 — INTEGRATION ═════════════════════════════════ */
.skan-integrations { display:flex; flex-wrap:wrap; justify-content:center; gap:14px; }
.skan-chip {
  display:inline-flex; align-items:center; gap:11px;
  padding:14px 22px; border-radius:999px;
  background:#fff; border:1px solid rgba(184,77,122,.2);
  font-size:15px; font-weight:600; color:#4a2436;
  transition:transform .2s, box-shadow .2s, border-color .2s, background .2s;
}
.skan-chip:hover { transform:translateY(-3px); box-shadow:0 12px 26px rgba(184,77,122,.14); border-color:#b84d7a; background:#fdf0f5; }
.skan-chip svg { width:20px; height:20px; color:#b84d7a; }

/* ══ 12 — BUSINESS FLOW ═══════════════════════════════ */
.skan-bflow { max-width:520px; margin:0 auto; }
.skan-bflow__item { position:relative; text-align:center; }
.skan-bflow__node {
  display:flex; align-items:center; justify-content:center; gap:12px;
  padding:18px 24px; border-radius:16px;
  background:#fff; border:1px solid rgba(184,77,122,.18);
  font-size:16px; font-weight:600; letter-spacing:-.01em; color:#1a0a12;
  box-shadow:0 8px 22px rgba(184,77,122,.07);
}
.skan-bflow__node b { display:inline-flex; align-items:center; justify-content:center;
  width:26px; height:26px; flex-shrink:0; border-radius:50%;
  background:#fdf0f5; border:1px solid rgba(184,77,122,.24);
  font-size:11.5px; font-weight:800; color:#b84d7a; }
.skan-bflow__arrow { padding:10px 0; font-size:16px; font-weight:800; color:#c2557f; }
.skan-bflow__item:last-child .skan-bflow__arrow { display:none; }

/* ══ 13 — BUSINESS CTA ════════════════════════════════ */
.skan-bizcta {
  text-align:center; padding:60px 44px; border-radius:26px;
  background:linear-gradient(140deg,#fdf0f5 0%,#fff 42%,#f4f0fb 100%);
  border:1px solid rgba(184,77,122,.18);
  box-shadow:0 24px 60px rgba(184,77,122,.12);
}
.skan-bizcta__title { margin:0 auto 18px; max-width:820px; font-size:clamp(24px,3.4vw,38px);
  font-weight:500; letter-spacing:-.03em; line-height:1.18; color:#1a0a12 !important; }
.skan-bizcta__lead { margin:0 auto; max-width:680px; font-size:16.5px; line-height:1.72; color:#6b3a52; }
.skan-bizcta__lead + .skan-bizcta__lead { margin-top:14px; }
.skan-bizcta__flow {
  margin:28px auto 0; display:inline-flex; flex-wrap:wrap; justify-content:center; gap:12px;
  padding:12px 24px; border-radius:999px; background:#fff; border:1px solid rgba(184,77,122,.18);
  font-size:12.5px; font-weight:800; letter-spacing:.12em; text-transform:uppercase; color:#b84d7a;
}
.skan-bizcta__actions { margin-top:30px; display:flex; flex-wrap:wrap; gap:13px; justify-content:center; align-items:center; }

/* CTA gradient identik dengan .skan-btn--primary di hero */
.skan-cta-primary {
  padding:16px 34px;
  background:linear-gradient(135deg,#e8a0bf,#b84d7a);
  color:#fff; font-size:15.5px; font-weight:700; box-shadow:0 10px 28px rgba(184,77,122,.34);
}
.skan-cta-primary:hover { opacity:.92; box-shadow:0 16px 40px rgba(184,77,122,.45); transform:translateY(-2px); }
.skan-cta-glass {
  padding:16px 30px;
  background:rgba(255,255,255,.62); border:1px solid rgba(184,77,122,.26);
  -webkit-backdrop-filter:blur(10px); backdrop-filter:blur(10px);
  color:#9d5a76; font-size:15px; font-weight:600; box-shadow:0 4px 16px rgba(184,77,122,.07);
}
.skan-cta-glass:hover { background:rgba(255,255,255,.9); border-color:rgba(184,77,122,.5); color:#b84d7a; transform:translateY(-2px); box-shadow:0 12px 30px rgba(184,77,122,.16); }

/* ══ 14 — CLOSING ═════════════════════════════════════ */
.skan-close {
  position:relative; overflow:hidden; text-align:center;
  padding:80px 2.5rem; border-radius:0;
  background:linear-gradient(155deg,#2d1a24 0%,#1a0a12 56%,#25112c 100%);
}
.skan-close__glow { position:absolute; inset:0; pointer-events:none;
  background:radial-gradient(ellipse 60% 70% at 50% 0%, rgba(184,77,122,.4) 0%, transparent 68%); }
.skan-close__inner { position:relative; z-index:1; max-width:820px; margin:0 auto; }
.skan-close__title { margin:0 0 18px; font-size:clamp(26px,3.8vw,44px); font-weight:500;
  letter-spacing:-.03em; line-height:1.16; color:#fff !important; }
.skan-close__desc { margin:0 auto; max-width:660px; font-size:16.5px; line-height:1.72; color:rgba(255,255,255,.78); }
.skan-close__actions { margin-top:32px; display:flex; flex-wrap:wrap; gap:13px; justify-content:center; }

/* ══ RESPONSIVE ═══════════════════════════════════════ */
@media(max-width:1080px) {
  .skan-hero__inner { grid-template-columns:1fr; gap:44px; }
  .skan-panel { max-width:560px; }
  .skan-hero__desc { max-width:680px; }
  .skan-steps { grid-template-columns:repeat(2,1fr); gap:20px; }
  .skan-steps::before { display:none; }
  .skan-params { grid-template-columns:repeat(2,1fr); }
  .skan-prods, .skan-digital, .skan-derm, .skan-biz { grid-template-columns:repeat(2,1fr); }
  .skan-score { grid-template-columns:1fr; }
  .skan-insight { grid-template-columns:1fr; gap:28px; }
}
@media(max-width:640px) {
  .skan-sec { padding:56px 1.25rem; }
  .skan-hero { padding:62px 1.25rem 58px; }
  .skan-close { padding:60px 1.25rem; }
  .skan-steps, .skan-params, .skan-care, .skan-prods, .skan-digital, .skan-derm, .skan-biz { grid-template-columns:1fr; }
  .skan-score__side { padding:34px 26px; }
  .skan-score__list { padding:30px 26px; }
  .skan-insight { padding:30px 22px; }
  .skan-bizcta { padding:40px 20px; }
  .skan-actions .skan-btn, .skan-bizcta__actions .skan-btn, .skan-close__actions .skan-btn { width:100%; justify-content:center; }
  .skan-cta-primary, .skan-cta-glass { padding:15px 22px; }
}
</style>
CSS;

require_once __DIR__ . '/../layouts/header.php';
?>

<div class="skan-page">

  <!-- ══════════ 01 — HERO ══════════ -->
  <header class="skan-hero">
    <div class="skan-hero__glow" aria-hidden="true"></div>
    <div class="skan-hero__grid" aria-hidden="true"></div>

    <div class="skan-hero__inner">
      <div class="skan-hero__copy">
        <p class="skan-hero__over">AI-Powered Skin Analysis</p>
        <h1 class="skan-hero__display">Understand Your Skin.<br><em>Personalize Your Care.</em></h1>
        <p class="skan-hero__desc"><?= htmlspecialchars($heroDesc) ?></p>
        <p class="skan-hero__note"><?= htmlspecialchars($heroNote) ?></p>

        <div class="skan-actions skan-hero__actions">
          <a class="skan-btn skan-btn--primary" href="<?= htmlspecialchars($appUrl) ?>" target="_blank" rel="noopener">
            Try Skin Analyzer <span aria-hidden="true">&rarr;</span>
          </a>
          <a class="skan-btn skan-btn--ghost" href="<?= htmlspecialchars($wa($waMsgConsult)) ?>" target="_blank" rel="noopener">
            Book a Consultation
          </a>
        </div>
        <p class="skan-hero__hint">Coba Skin Analyzer langsung dalam hitungan menit, atau konsultasikan kebutuhan Anda dengan tim kami untuk memahami cara kerja dan penerapannya.</p>
      </div>

      <div class="skan-panel">
        <div class="skan-panel__head">
          <span class="skan-panel__dot" aria-hidden="true"></span>
          <span class="skan-panel__title">Skin Analysis Engine</span>
          <span class="skan-panel__state">AI</span>
        </div>

        <div class="skan-views">
          <?php foreach ($heroViews as $v): ?>
            <div class="skan-view">
              <?= skan_icon($v['i']) ?>
              <p class="skan-view__k"><?= htmlspecialchars($v['k']) ?></p>
            </div>
          <?php endforeach; ?>
        </div>

        <div class="skan-panel__div">
          <p class="skan-panel__label">Analysis Preview</p>
          <div class="skan-panel__score">
            <b><?= (int) $scoreTotal ?></b><span>/ 100 skin score</span>
          </div>
          <?php foreach (array_slice($scores, 0, 3) as $s): ?>
            <div class="skan-meter" style="--accent:<?= htmlspecialchars($s['c']) ?>;">
              <div class="skan-meter__top">
                <span class="skan-meter__label"><?= htmlspecialchars($s['t']) ?></span>
                <span class="skan-meter__val"><?= (int) $s['v'] ?></span>
              </div>
              <div class="skan-meter__track">
                <div class="skan-meter__fill" style="--v:<?= (int) $s['v'] ?>"></div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </header>

  <!-- ══════════ 02 — HOW IT WORKS ══════════ -->
  <section class="skan-sec">
    <div class="skan-wrap">
      <div class="skan-head">
        <p class="skan-eyebrow">How It Works</p>
        <h2 class="skan-h2">Three Views. <em>One Complete Skin Insight.</em></h2>
        <p class="skan-sub"><?= htmlspecialchars($howIntro) ?></p>
      </div>

      <div class="skan-steps">
        <?php foreach ($howSteps as $s): ?>
          <article class="skan-step">
            <div class="skan-step__ic"><?= skan_icon($s['i']) ?></div>
            <p class="skan-step__n"><?= htmlspecialchars($s['n']) ?></p>
            <h3 class="skan-step__t"><?= htmlspecialchars($s['t']) ?></h3>
            <p class="skan-step__d"><?= htmlspecialchars($s['d']) ?></p>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- ══════════ 03 — SKIN ANALYSIS ══════════ -->
  <section class="skan-sec skan-sec--tint skan-sec--edge">
    <div class="skan-wrap">
      <div class="skan-head">
        <p class="skan-eyebrow">Skin Analysis</p>
        <h2 class="skan-h2">See What Your Skin <em>Is Telling You.</em></h2>
        <p class="skan-sub"><?= htmlspecialchars($analysisIntro) ?></p>
      </div>

      <div class="skan-params">
        <?php foreach ($params as $p): ?>
          <div class="skan-param">
            <span class="skan-param__ic"><?= skan_icon($p['i']) ?></span>
            <p class="skan-param__t"><?= htmlspecialchars($p['t']) ?></p>
          </div>
        <?php endforeach; ?>
      </div>

      <div class="skan-actions skan-actions--center">
        <a class="skan-btn skan-btn--primary" href="<?= htmlspecialchars($appUrl) ?>" target="_blank" rel="noopener">
          Explore Your Skin <span aria-hidden="true">&rarr;</span>
        </a>
      </div>
    </div>
  </section>

  <!-- ══════════ 04 — SKIN SCORE ══════════ -->
  <section class="skan-sec">
    <div class="skan-wrap">
      <div class="skan-head">
        <p class="skan-eyebrow">Personalized Skin Score</p>
        <h2 class="skan-h2">More Than a Score. <em>A Clearer Understanding of Your Skin.</em></h2>
        <p class="skan-sub"><?= htmlspecialchars($scoreIntro) ?></p>
      </div>

      <div class="skan-score">
        <div class="skan-score__side">
          <p class="skan-score__label">Your Skin Score</p>
          <p class="skan-score__big"><b><?= (int) $scoreTotal ?></b><span>/ 100</span></p>
          <p class="skan-score__cap">Ringkasan skor dari seluruh parameter kondisi kulit yang dianalisis.</p>
        </div>
        <div class="skan-score__list">
          <?php foreach ($scores as $s): ?>
            <div class="skan-meter skan-meter--light" style="--accent:<?= htmlspecialchars($s['c']) ?>;">
              <div class="skan-score__row">
                <span class="skan-meter__label"><?= htmlspecialchars($s['t']) ?></span>
                <span class="skan-meter__val"><?= (int) $s['v'] ?></span>
              </div>
              <div class="skan-meter__track">
                <div class="skan-meter__fill" style="--v:<?= (int) $s['v'] ?>"></div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>

      <div class="skan-actions skan-actions--center">
        <a class="skan-btn skan-btn--line" href="<?= htmlspecialchars($appUrl) ?>" target="_blank" rel="noopener">
          View Full Analysis <span aria-hidden="true">&rarr;</span>
        </a>
      </div>
    </div>
  </section>

  <!-- ══════════ 05 — SKIN INSIGHT ══════════ -->
  <section class="skan-sec skan-sec--tint skan-sec--edge">
    <div class="skan-wrap">
      <div class="skan-head">
        <p class="skan-eyebrow">Skin Insight</p>
        <h2 class="skan-h2">Turn Skin Data <em>Into Meaningful Insights.</em></h2>
        <p class="skan-sub"><?= htmlspecialchars($insightIntro) ?></p>
      </div>

      <div class="skan-insight">
        <div class="skan-insight__demo">
          <div class="skan-insight__top">
            <span class="skan-insight__ic"><?= skan_icon('pores') ?></span>
            <div>
              <p class="skan-insight__name"><?= htmlspecialchars($insightItem['t']) ?></p>
              <p class="skan-insight__k">Insight</p>
            </div>
          </div>
          <div class="skan-meter skan-meter--light" style="--accent:#b84d7a;">
            <div class="skan-meter__track">
              <div class="skan-meter__fill" style="--v:72"></div>
            </div>
          </div>
        </div>

        <div class="skan-insight__copy">
          <h3 class="skan-insight__title">Angka saja tidak cukup.</h3>
          <p class="skan-insight__text"><?= htmlspecialchars($insightItem['d']) ?></p>
        </div>
      </div>
    </div>
  </section>

  <!-- ══════════ 06 — PERSONALIZED CARE ══════════ -->
  <section class="skan-sec">
    <div class="skan-wrap">
      <div class="skan-head">
        <p class="skan-eyebrow">Personalized Care</p>
        <h2 class="skan-h2">Know Your Skin. <em>Know What to Do Next.</em></h2>
        <p class="skan-sub"><?= htmlspecialchars($careIntro) ?></p>
      </div>

      <div class="skan-care">
        <?php foreach ($routines as $r): ?>
          <article class="skan-routine" style="--accent:<?= htmlspecialchars($r['c']) ?>;--tint:<?= htmlspecialchars($r['c']) ?>1f;--border-c:<?= htmlspecialchars($r['c']) ?>33;">
            <div class="skan-routine__head">
              <span class="skan-routine__ic"><?= skan_icon($r['i']) ?></span>
              <h3 class="skan-routine__t"><?= htmlspecialchars($r['t']) ?></h3>
            </div>
            <div class="skan-flow">
              <?php foreach ($r['steps'] as $i => $st): ?>
                <?php if ($i > 0): ?><span class="skan-flow__a" aria-hidden="true">&rarr;</span><?php endif; ?>
                <span class="skan-flow__s"><?= htmlspecialchars($st) ?></span>
              <?php endforeach; ?>
            </div>
          </article>
        <?php endforeach; ?>
      </div>

      <div class="skan-actions skan-actions--center">
        <a class="skan-btn skan-btn--primary" href="<?= htmlspecialchars($appUrl) ?>" target="_blank" rel="noopener">
          Explore Your Care Routine <span aria-hidden="true">&rarr;</span>
        </a>
      </div>
    </div>
  </section>

  <!-- ══════════ 07 — PRODUCT RECOMMENDATION ══════════ -->
  <section class="skan-sec skan-sec--tint skan-sec--edge">
    <div class="skan-wrap">
      <div class="skan-head">
        <p class="skan-eyebrow">Product Recommendation</p>
        <h2 class="skan-h2">From Skin Analysis <em>to the Right Product.</em></h2>
        <p class="skan-sub"><?= htmlspecialchars($prodIntro) ?></p>
      </div>

      <div class="skan-prods">
        <?php foreach ($prodGroups as $g): ?>
          <article class="skan-prod" style="--accent:<?= htmlspecialchars($g['c']) ?>;--tint:<?= htmlspecialchars($g['c']) ?>1f;--border-c:<?= htmlspecialchars($g['c']) ?>33;">
            <div class="skan-prod__head">
              <span class="skan-prod__ic"><?= skan_icon($g['i']) ?></span>
              <h3 class="skan-prod__t"><?= htmlspecialchars($g['t']) ?></h3>
            </div>
            <ul class="skan-prod__list">
              <?php foreach ($g['items'] as $it): ?>
                <li><?= htmlspecialchars($it) ?></li>
              <?php endforeach; ?>
            </ul>
          </article>
        <?php endforeach; ?>
      </div>

      <div class="skan-actions skan-actions--center">
        <a class="skan-btn skan-btn--line" href="<?= htmlspecialchars($appUrl) ?>" target="_blank" rel="noopener">
          Explore Recommended Products <span aria-hidden="true">&rarr;</span>
        </a>
      </div>
    </div>
  </section>

  <!-- ══════════ 08 — DIGITAL RESULT ══════════ -->
  <section class="skan-sec">
    <div class="skan-wrap">
      <div class="skan-head">
        <p class="skan-eyebrow">Your Skin Report</p>
        <h2 class="skan-h2">Your Results, <em>Wherever You Go.</em></h2>
        <p class="skan-sub"><?= htmlspecialchars($digitalIntro) ?></p>
      </div>

      <div class="skan-digital">
        <?php foreach ($digital as $d): ?>
          <article class="skan-digital__card">
            <div class="skan-digital__ic"><?= skan_icon($d['i']) ?></div>
            <h3 class="skan-digital__t"><?= htmlspecialchars($d['t']) ?></h3>
            <p class="skan-digital__d"><?= htmlspecialchars($d['d']) ?></p>
          </article>
        <?php endforeach; ?>
      </div>

      <div class="skan-actions skan-actions--center">
        <a class="skan-btn skan-btn--primary" href="<?= htmlspecialchars($appUrl) ?>" target="_blank" rel="noopener">
          Get Your Skin Report <span aria-hidden="true">&rarr;</span>
        </a>
      </div>
    </div>
  </section>

  <!-- ══════════ 09 — DERMATOLOGY COLLABORATION ══════════ -->
  <section class="skan-sec skan-sec--dark">
    <div class="skan-wrap">
      <div class="skan-head">
        <p class="skan-eyebrow">Dermatology Collaboration</p>
        <h2 class="skan-h2">AI Technology, Supported by <em>Dermatology Knowledge.</em></h2>
        <p class="skan-sub"><?= htmlspecialchars($dermIntro) ?></p>
      </div>

      <div class="skan-derm">
        <?php foreach ($derm as $d): ?>
          <article class="skan-derm__card">
            <div class="skan-derm__ic"><?= skan_icon($d['i']) ?></div>
            <h3 class="skan-derm__t"><?= htmlspecialchars($d['t']) ?></h3>
            <p class="skan-derm__d"><?= htmlspecialchars($d['d']) ?></p>
          </article>
        <?php endforeach; ?>
      </div>

      <p class="skan-note"><?= htmlspecialchars($dermNote) ?></p>
    </div>
  </section>

  <!-- ══════════ 10 — BUSINESS SOLUTION ══════════ -->
  <section class="skan-sec" id="business">
    <div class="skan-wrap">
      <div class="skan-head">
        <p class="skan-eyebrow">For Your Business</p>
        <h2 class="skan-h2">Bring AI Skin Analysis <em>Into Your Customer Experience.</em></h2>
        <p class="skan-sub"><?= htmlspecialchars($bizIntro) ?></p>
      </div>

      <div class="skan-biz">
        <?php foreach ($biz as $b): ?>
          <article class="skan-biz__card">
            <div class="skan-biz__ic"><?= skan_icon($b['i']) ?></div>
            <h3 class="skan-biz__t"><?= htmlspecialchars($b['t']) ?></h3>
            <p class="skan-biz__d"><?= htmlspecialchars($b['d']) ?></p>
          </article>
        <?php endforeach; ?>
      </div>

      <div class="skan-actions skan-actions--center">
        <a class="skan-btn skan-btn--primary" href="<?= htmlspecialchars($wa($waMsgBiz)) ?>" target="_blank" rel="noopener">
          Explore Business Solutions <span aria-hidden="true">&rarr;</span>
        </a>
      </div>
    </div>
  </section>

  <!-- ══════════ 11 — INTEGRATION ══════════ -->
  <section class="skan-sec skan-sec--tint skan-sec--edge">
    <div class="skan-wrap">
      <div class="skan-head">
        <p class="skan-eyebrow">Flexible Integration</p>
        <h2 class="skan-h2">Designed to <em>Fit Your Business.</em></h2>
        <p class="skan-sub"><?= htmlspecialchars($integrationIntro) ?></p>
      </div>

      <div class="skan-integrations">
        <?php foreach ($integrations as $it): ?>
          <span class="skan-chip"><?= skan_icon($it['i']) ?><?= htmlspecialchars($it['t']) ?></span>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- ══════════ 12 — BUSINESS FLOW ══════════ -->
  <section class="skan-sec">
    <div class="skan-wrap">
      <div class="skan-head">
        <p class="skan-eyebrow">Customer Journey</p>
        <h2 class="skan-h2">From Skin Analysis <em>to Personalized Beauty Experience.</em></h2>
        <p class="skan-sub"><?= htmlspecialchars($flowIntro) ?></p>
      </div>

      <div class="skan-bflow">
        <?php foreach ($flow as $i => $step): ?>
          <div class="skan-bflow__item">
            <div class="skan-bflow__node"><b aria-hidden="true"><?= str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT) ?></b><?= htmlspecialchars($step) ?></div>
            <div class="skan-bflow__arrow" aria-hidden="true">&darr;</div>
          </div>
        <?php endforeach; ?>
      </div>

      <div class="skan-actions skan-actions--center">
        <a class="skan-btn skan-btn--primary" href="<?= htmlspecialchars($wa($waMsgBiz)) ?>" target="_blank" rel="noopener">
          Build Your Skin Analysis Experience <span aria-hidden="true">&rarr;</span>
        </a>
      </div>
    </div>
  </section>

  <!-- ══════════ 13 — MAIN BUSINESS CTA ══════════ -->
  <section class="skan-sec skan-sec--tint skan-sec--edge">
    <div class="skan-wrap">
      <div class="skan-bizcta">
        <p class="skan-eyebrow">Partner with Cantik.AI</p>
        <h2 class="skan-bizcta__title">Ready to Bring <em>AI Skin Analysis</em> to Your Business?</h2>
        <p class="skan-bizcta__lead"><?= htmlspecialchars($bizCtaLead) ?></p>
        <p class="skan-bizcta__lead"><?= htmlspecialchars($bizCtaLead2) ?></p>
        <p class="skan-bizcta__lead"><?= htmlspecialchars($bizCtaLead3) ?></p>

        <p class="skan-bizcta__flow">Skin Analysis &rarr; Skin Insight &rarr; Care &rarr; Product Recommendation</p>

        <div class="skan-bizcta__actions">
          <a class="skan-btn skan-cta-primary" href="<?= htmlspecialchars($wa($waMsgConsult)) ?>" target="_blank" rel="noopener">
            Book a Consultation <span aria-hidden="true">&rarr;</span>
          </a>
          <a class="skan-btn skan-cta-glass" href="<?= htmlspecialchars($wa($waMsgTeam)) ?>" target="_blank" rel="noopener">
            Talk to Our Team
          </a>
        </div>
      </div>
    </div>
  </section>

  <!-- ══════════ 14 — CLOSING ══════════ -->
  <section class="skan-close">
    <div class="skan-close__glow" aria-hidden="true"></div>
    <div class="skan-close__inner">
      <p class="skan-eyebrow">Cantik.AI Skin Analyzer</p>
      <h2 class="skan-close__title">Understand Skin. Personalize Care. <em>Grow Your Business.</em></h2>
      <p class="skan-close__desc"><?= htmlspecialchars($closeDesc) ?></p>

      <div class="skan-close__actions">
        <a class="skan-btn skan-cta-primary" href="<?= htmlspecialchars($appUrl) ?>" target="_blank" rel="noopener">
          Try Skin Analyzer <span aria-hidden="true">&rarr;</span>
        </a>
        <a class="skan-btn skan-btn--ghost" href="<?= htmlspecialchars($wa($waMsgConsult)) ?>" target="_blank" rel="noopener">
          Book a Consultation
        </a>
      </div>
    </div>
  </section>

</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
