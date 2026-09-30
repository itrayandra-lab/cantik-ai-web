<?php
/**
 * pages/feature/ai-for-cosmetic-industry.php
 * Halaman AI for Cosmetic Industry — AI Expert Team
 *
 * Routing: /feature/ai-for-cosmetic-industry  (index.php + .htaccess sudah handle)
 *
 * Catatan penting soal CSS global:
 * light-theme.css punya `h1,h2,h3,h4,h5,h6 { color:#2d1b2e !important }`.
 * Semua heading di section gelap wajib pakai `color:... !important` sendiri,
 * kalau tidak teksnya jadi gelap di atas latar gelap.
 */

$siteUrl   = 'https://cantik.ai';
$pageTitle = 'AI for Cosmetic Industry — Your AI Expert Team | Cantik.AI';
$pageDesc  = 'Empat spesialis AI untuk industri kosmetik: formulasi, regulasi BPOM/global, sertifikasi halal, dan validasi ilmiah. Pendampingan ahli di setiap tahap pengembangan produk kosmetik Anda.';
$canonical = $siteUrl . '/feature/ai-for-cosmetic-industry';
$ogImage   = $siteUrl . '/assets/img/og-image.jpg';

// Footer memakai ini untuk mengarahkan link "FAQ" ke section FAQ di halaman ini.
$showFaq = true;

// Ganti kalau app sudah punya route khusus per expert
$appUrl = 'https://app.cantik.ai/';

$heroSign = 'SCIENCE &middot; INTELLIGENCE &middot; BEAUTY &middot; IMPACT';

/* ================================================================
   01 — HERO
   ================================================================ */
$heroId = 'Satu platform AI untuk mempermudah eksplorasi bahan, penyusunan formula, '
    . 'pemenuhan sertifikasi, dan validasi riset ilmiah kosmetik Anda.';

/* ================================================================
   02 — VALUE PROPOSITION
   ================================================================ */
$valueIntro = 'Mengembangkan produk kosmetik unggulan bukan sekadar menemukan tekstur yang tepat, '
    . 'melainkan mengintegrasikan sains, keamanan, kepatuhan hukum, dan pembuktian klaim '
    . 'secara menyeluruh:';

$pillars = [
    [
        'label' => 'Science',
        'd'     => 'Memahami mekanisme kerja active ingredients, interaksi molekuler, dan bukti efektivitas klinisnya.',
        'color' => '#b84d7a',
    ],
    [
        'label' => 'Formulation',
        'd'     => 'Mengubah konsep produk kreatif menjadi strategi formulasi riil yang stabil dan siap diuji di laboratorium.',
        'color' => '#c2557f',
    ],
    [
        'label' => 'Regulatory',
        'd'     => 'Menavigasi batasan penggunaan bahan, pedoman klaim kosmetik, dan aturan kepatuhan sebelum produk meluncur ke pasar.',
        'color' => '#6b4a9e',
    ],
    [
        'label' => 'Certification',
        'd'     => 'Memahami kesiapan dokumen dan kritis tidaknya suatu bahan sebelum masuk ke proses sertifikasi halal.',
        'color' => '#1f8a70',
    ],
    [
        'label' => 'Validation',
        'd'     => 'Memperkuat setiap klaim dan keputusan produk dengan dukungan bukti ilmiah transparan, bukan sekadar asumsi.',
        'color' => '#2563a8',
    ],
];

/* ================================================================
   03 — PRODUCT DEVELOPMENT JOURNEY
   ================================================================ */
$journeyIntro = 'Dari konsep awal hingga produk siap edar, setiap tahap membutuhkan keputusan yang presisi. '
    . 'Cantik.AI hadir memberikan panduan ahli di setiap langkah:';

$journey = [
    [
        'num'   => '01',
        'step'  => 'Research',
        'title' => 'Understand What Matters',
        'd'     => 'Mengeksplorasi literatur ilmiah, tren pasar terkini, potensi bahan baku, dan peluang inovasi produk.',
        'color' => '#b84d7a',
    ],
    [
        'num'   => '02',
        'step'  => 'Formulate',
        'title' => 'Turn Ideas Into Products',
        'd'     => 'Menerjemahkan konsep abstrak menjadi racikan formula yang praktis, stabil, dan efektif.',
        'color' => '#c2557f',
    ],
    [
        'num'   => '03',
        'step'  => 'Comply',
        'title' => 'Meet Regulatory Requirements',
        'd'     => 'Memastikan keamanan bahan, batas kadar maksimum, aturan kewajiban label, dan keabsahan klaim kosmetik.',
        'color' => '#6b4a9e',
    ],
    [
        'num'   => '04',
        'step'  => 'Certify',
        'title' => 'Prepare for Certification',
        'd'     => 'Menyeleksi titik kritis bahan baku, alur produksi, dan pemenuhan kriteria Sistem Jaminan Halal (SJH).',
        'color' => '#1f8a70',
    ],
    [
        'num'   => '05',
        'step'  => 'Validate',
        'title' => 'Support With Scientific Evidence',
        'd'     => 'Menyusun metodologi pengujian, mengevaluasi hasil uji efikasi, dan memvalidasi substansi klaim produk.',
        'color' => '#2563a8',
    ],
];

$questions = [
    ['Formulate', 'How do we formulate it?'],
    ['Comply',    'Is it compliant?'],
    ['Certify',   'Is it certifiable?'],
    ['Validate',  'Is there scientific evidence?'],
];

/* ================================================================
   04 — AI EXPERT TEAM
   ================================================================ */
$teamIntro = 'Empat spesialis AI yang dirancang untuk mendampingi berbagai sudut pandang pengembangan produk kosmetik Anda:';

$experts = [
    [
        'num'   => '01',
        'id'    => 'formulator',
        'emoji' => '🧪',
        'name'  => 'Formulator',
        'role'  => 'Cosmetics &amp; Skincare Formulation Expert',
        'tag'   => 'Formulation &amp; Product Development',
        'lead'  => 'Your strategic formulation partner, powered by cosmetic science.',
        'desc'  => 'Partner strategis formulasi Anda yang didukung oleh sains kosmetik lanjutan. Membantu mengeksplorasi bahan aktif, mengembangkan racikan produk, mengevaluasi kompatibilitas emulsi, mengatasi masalah stabilitas, hingga menyempurnakan tekstur dan sensori produk.',
        'askLabel' => 'Ask Formulator',
        'explore' => [
            'Pemilihan Bahan Baku',
            'Pengembangan Formula',
            'Resolusi Masalah Stabilitas',
            'Optimasi Bahan Aktif',
            'Sensori &amp; Tekstur',
        ],
        'cta'   => 'Ask Formulator',
        'color' => '#b84d7a',
    ],
    [
        'num'   => '02',
        'id'    => 'regulator',
        'emoji' => '📋',
        'name'  => 'Regulator',
        'role'  => 'Cosmetic Regulatory Compliance Expert',
        'tag'   => 'Regulatory &amp; Compliance',
        'lead'  => 'Your guide through the global cosmetic regulatory landscape.',
        'desc'  => 'Navigator handal Anda dalam melintasi lanskap regulasi kosmetik nasional maupun global. Membantu memahami batasan kadar bahan, panduan penyusunan klaim kosmetik, ketentuan pendaftaran BPOM, hingga persyaratan pendaftaran pasar ekspor.',
        'askLabel' => 'Ask Regulator',
        'explore' => [
            'Aturan Regulasi BPOM/Global',
            'Pembatasan Bahan Baku',
            'Panduan Klaim Kosmetik',
            'Ketentuan Label &amp; Kemasan',
            'Panduan Pasar Ekspor',
        ],
        'cta'   => 'Ask Regulator',
        'color' => '#6b4a9e',
    ],
    [
        'num'   => '03',
        'id'    => 'halal',
        'emoji' => '☪️',
        'name'  => 'Halal',
        'role'  => 'Halal Cosmetic Certification Expert',
        'tag'   => 'Halal &amp; Certification',
        'lead'  => 'Your guide to halal readiness and certification.',
        'desc'  => 'Panduan lengkap menuju kesiapan dan pemenuhan sertifikasi halal. Membantu menganalisis status halal bahan baku, mengidentifikasi titik kritis bahan, memeriksa matriks fasilitas produksi, dan menyiapkan dokumen Sistem Jaminan Halal (SJH).',
        'askLabel' => 'Ask Halal',
        'explore' => [
            'Penapisan Bahan Halal',
            'Analisis Titik Kritis Bahan',
            'Fasilitas &amp; Alur Produksi',
            'Penyiapan Dokumen SJH',
            'Audit Kesiapan Sertifikasi',
        ],
        'cta'   => 'Ask Halal',
        'color' => '#1f8a70',
    ],
    [
        'num'   => '04',
        'id'    => 'scientist',
        'emoji' => '🔬',
        'name'  => 'Scientist',
        'role'  => 'Cosmetic R&amp;D &amp; Testing Expert',
        'tag'   => 'R&amp;D &amp; Scientific Research',
        'lead'  => 'Your scientific advisor for cosmetic R&amp;D and testing.',
        'desc'  => 'Penasihat ilmiah R&D untuk riset dan metodologi pengujian kosmetik. Membantu menerjemahkan jurnal ilmiah rumit, menjelaskan mekanisme kerja molekuler, merancang metode uji efikasi, dan memvalidasi klaim sains produk Anda.',
        'askLabel' => 'Ask Scientist',
        'explore' => [
            'Riset Ilmiah &amp; Jurnal',
            'Mekanisme Kerja Bahan',
            'Metodologi Uji Produk',
            'Evaluasi Efikasi Klinis',
            'Pembuktian Sains Klaim',
        ],
        'cta'   => 'Ask Scientist',
        'color' => '#2563a8',
    ],
];

/* ================================================================
   05 — PRACTICAL DEMO
   ================================================================ */
$demoIntro = 'Gunakan spesialis AI yang tepat untuk pertanyaan yang spesifik — dan ubah kompleksitas '
    . 'pengetahuan kosmetik menjadi langkah aksi yang praktis dan terukur.';

$angles = [
    [
        'emoji' => '🧪',
        'role'  => 'Formulator',
        'q'     => 'Bagaimana cara terbaik meracik produk ini agar stabil dan nyaman di kulit?',
        'color' => '#b84d7a',
    ],
    [
        'emoji' => '📋',
        'role'  => 'Regulator',
        'q'     => 'Apakah kadar dan klaim produk ini aman serta memenuhi aturan hukum?',
        'color' => '#6b4a9e',
    ],
    [
        'emoji' => '☪️',
        'role'  => 'Halal',
        'q'     => 'Apakah seluruh bahan baku dan prosesnya memenuhi standar halal?',
        'color' => '#1f8a70',
    ],
    [
        'emoji' => '🔬',
        'role'  => 'Scientist',
        'q'     => 'Manakah bukti riset ilmiah yang mendukung efektivitas produk ini?',
        'color' => '#2563a8',
    ],
];

$chatUser = 'Bagaimana cara terbaik meracik produk ini agar stabil dan nyaman di kulit?';
$chatAi   = 'Untuk menghasilkan formula yang stabil dan nyaman di kulit, mulailah dengan memetakan '
    . 'profil kulit target, lalu pilih sistem emulsi dan bahan aktif yang sesuai. Kompatibilitas bahan, '
    . 'pH, dan kondisi penyimpanan harus diuji bersamaan, karena menstabilkan satu komponen dapat '
    . 'memengaruhi keseluruhan formula.';
$chatNote = 'Insight yang dihasilkan AI sebaiknya ditinjau dan diverifikasi oleh profesional yang '
    . 'kompeten sebelum digunakan dalam pengembangan produk maupun keputusan regulasi.';

/* ================================================================
   06 — TARGET AUDIENCE
   ================================================================ */
$audienceIntro = 'Didesain khusus untuk mendukung para profesional di seluruh rantai nilai industri '
    . 'kecantikan dan perawatan diri:';

$audiences = [
    ['🧪', 'R&amp;D / Formulator', 'Mempercepat riset bahan dan penyusunan formula yang stabil.'],
    ['🛡️', 'QA / QC', 'Memastikan standar kualitas, pengujian produk, dan konsistensi mutu.'],
    ['📋', 'Regulatory Affairs', 'Mempermudah pemeriksaan regulasi, dokumen keamanan, dan pendaftaran produk.'],
    ['📦', 'Product Development (PD)', 'Mengubah tren dan ideasi menjadi konsep produk yang siap diproduksi.'],
    ['✦', 'Brand Owner', 'Mengambil keputusan investasi produk dan strategi bahan berbasis data akurat.'],
    ['🏭', 'Manufacturing / Toll Manufacturer (Maklon)', 'Mendukung efisiensi teknis produksi dan kesiapan sertifikasi pabrik.'],
];

/* ================================================================
   07 — FAQ (spesifik industri kosmetik, bukan FAQ generik)
   ================================================================ */
$faqIntro = 'Kami memahami kekhawatiran Anda mengenai keamanan data, akurasi regulasi, hingga '
    . 'kesiapan tim internal dalam mengadopsi teknologi AI:';

$faqs = [
    [
        'q' => 'Apakah AI ini mengambil keputusan bisnis secara otomatis tanpa kendali kita?',
        'a' => 'Tidak. Cantik.AI berfungsi sebagai expert assistant yang memberikan rekomendasi, analisis data, dan rujukan ilmiah. Seluruh keputusan akhir tetap berada penuh di tangan para ahli dan tim manajemen Anda.',
    ],
    [
        'q' => 'Bagaimana dengan keamanan data rahasia riset dan formula produk kami?',
        'a' => 'Keamanan data Anda adalah prioritas tertinggi kami. Seluruh data formulasi, dokumen internal, dan riwayat konsultasi dilindungi dengan enkripsi standar industri dan tidak akan pernah digunakan untuk melatih model umum publik.',
    ],
    [
        'q' => 'Apakah tim internal kami memerlukan pelatihan khusus untuk menggunakannya?',
        'a' => 'Tidak perlu. Sistem kami menggunakan antarmuka percakapan intuitif (conversational UI). Tim Anda cukup mengajukan pertanyaan dalam bahasa sehari-hari, dan sistem akan memberikan jawaban terstruktur secara instan.',
    ],
    [
        'q' => 'Bisakah AI disesuaikan dengan kebutuhan spesifik dan basis data brand kami?',
        'a' => 'Sangat bisa. Kami menyediakan opsi integrasi enterprise di mana AI dapat disesuaikan (custom-fit) dengan basis data bahan baku internal, aturan standar operasional pabrik (SOP), dan brand guideline Anda.',
    ],
    [
        'q' => 'Bagaimana cara memulai implementasi Cantik.AI di bisnis kosmetik saya?',
        'a' => 'Anda dapat memulai dengan mendaftar untuk sesi demonstrasi live, memilih modul spesialis yang dibutuhkan, dan langsung menguji sistem dengan kasus nyata pengembangan produk Anda.',
    ],
    [
        'q' => 'Apakah keberadaan AI akan menggantikan peran ahli dan tim R&D kami?',
        'a' => 'Tidak sama sekali. Cantik.AI hadir untuk memberdayakan (augment) tim Anda — memangkas waktu pencarian data manual hingga 80% agar tim ahli Anda dapat berfokus pada inovasi dan eksekusi strategis.',
    ],
];

/* ================================================================
   HEAD — JSON-LD + CSS
   ================================================================ */
$ld = [
    '@context'    => 'https://schema.org',
    '@type'       => 'CollectionPage',
    'name'        => 'AI for Cosmetic Industry',
    'description' => $pageDesc,
    'url'         => $canonical,
    'inLanguage'  => 'id',
    'about'       => array_map(fn($e) => [
        '@type' => 'Thing',
        'name'  => 'Cantik.AI ' . html_entity_decode($e['name']) . ' AI',
    ], $experts),
];

$faqLd = [
    '@context'   => 'https://schema.org',
    '@type'      => 'FAQPage',
    'mainEntity' => array_map(fn($f) => [
        '@type'          => 'Question',
        'name'           => $f['q'],
        'acceptedAnswer' => ['@type' => 'Answer', 'text' => $f['a']],
    ], $faqs),
];

$extraHead = '<script type="application/ld+json">'
    . json_encode($ld, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
    . '</script>' . "\n"
    . '<script type="application/ld+json">'
    . json_encode($faqLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
    . '</script>' . "\n" . <<<'CSS'
<style>
/* ══ BRAND TYPOGRAPHY — disamakan dengan homepage (Cantik.AI) ══
   Token diambil dari assets/css/vendor/sintraweb.shared.min.css:
   body  → GT Walsheim Pro 1.0625rem/400/1.5
   h1    → text-size-7xl: letter-spacing -.03em, weight 500
   Satu-satunya font yang dipakai di halaman ini: GT Walsheim Pro.
   layouts/header.php:44 memaksa body ke 'Segoe UI', jadi kita override
   langsung di .afc-page (kelas, bukan body) supaya rules itu kalah. */
.afc-page {
  font-family:'GT Walsheim Pro',Arial,sans-serif;
  font-size:1.0625rem; font-weight:400; line-height:1.5;
  -webkit-font-smoothing:antialiased; -moz-osx-font-smoothing:grayscale;
  color:#2d1a24;
}
/* Kunci stack ini ke heading + semua label supaya tidak ada elemen
   yang jatuh ke font lain,fzat mana pun gaya global yang masuk. */
.afc-page h1, .afc-page h2, .afc-page h3,
.afc-eyebrow, .afc-pillar__label, .afc-jnode__step,
.afc-hero__over, .afc-hero__display, .afc-hero__sign {
  font-family:'GT Walsheim Pro',Arial,sans-serif;
}
.afc-page h1, .afc-page h2, .afc-page h3 {
  letter-spacing:-.03em; font-weight:500;
}

.afc-wrap { max-width:1180px; margin:0 auto; }
.afc-sec  { padding:82px 2.5rem; }
.afc-sec--tint { background:#fdf8fb; }
.afc-sec--edge { border-top:1px solid rgba(184,77,122,.1); }

/* ── Shared section header ─────────────────────────────────────── */
.afc-head { margin-bottom:46px; }
.afc-eyebrow {
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
.afc-eyebrow::before {
  content:''; width:6px; height:6px; flex-shrink:0;
  border-radius:50%; background:#e8a0bf;
}
.afc-h2 {
  margin:0 auto 14px; max-width:800px; text-align:center;
  font-size:clamp(26px,3.6vw,40px); line-height:1.14; font-weight:500;
  letter-spacing:-.03em; color:#1a0a12 !important;
}
.afc-h2 em, .afc-final__title em {
  font-style:normal;
  background:linear-gradient(135deg,#e8a0bf,#b84d7a);
  -webkit-background-clip:text; background-clip:text; -webkit-text-fill-color:transparent;
}
.afc-sub { margin:0 auto; max-width:660px; text-align:center; font-size:16.5px; line-height:1.72; color:#6b3a52; }
.afc-label {
  margin:0 0 20px; text-align:center;
  font-size:12px; font-weight:800; letter-spacing:.14em; text-transform:uppercase; color:#9d5a76;
}

/* ── Buttons ───────────────────────────────────────────────────── */
.afc-btn {
  display:inline-flex; align-items:center; gap:9px;
  padding:14px 30px; border-radius:12px;
  font-size:15px; font-weight:700; text-decoration:none; line-height:1;
  transition:opacity .18s, transform .18s, box-shadow .18s, background .18s, border-color .18s;
}
.afc-btn span { transition:transform .18s; }
.afc-btn:hover span { transform:translateX(3px); }
.afc-btn--primary {
  background:linear-gradient(135deg,#e8a0bf,#b84d7a); color:#fff;
  box-shadow:0 8px 24px rgba(184,77,122,.34);
}
.afc-btn--primary:hover { opacity:.92; transform:translateY(-1px); box-shadow:0 12px 30px rgba(184,77,122,.4); }
.afc-btn--ghost { background:rgba(255,255,255,.05); border:1px solid rgba(255,255,255,.22); color:#fff; }
.afc-btn--ghost:hover { background:rgba(232,160,191,.16); border-color:rgba(232,160,191,.5); transform:translateY(-1px); }
.afc-btn--line { background:#fff; border:1px solid rgba(184,77,122,.35); color:#b84d7a; }
.afc-btn--line:hover { background:#fdf0f5; border-color:#b84d7a; transform:translateY(-1px); }

/* ══ 01 — HERO ══════════════════════════════════════════════════ */
.afc-hero {
  position:relative; overflow:hidden; text-align:left;
  padding:88px 2.5rem 84px;
  background:linear-gradient(155deg,#2d1a24 0%,#1a0a12 56%,#25112c 100%);
}
.afc-hero__glow { position:absolute; inset:0; pointer-events:none;
  background:
    radial-gradient(ellipse 62% 54% at 50% -10%, rgba(184,77,122,.46) 0%, transparent 68%),
    radial-gradient(ellipse 46% 46% at 88% 106%, rgba(107,74,158,.34) 0%, transparent 70%);
}
.afc-hero__grid {
  position:absolute; inset:0; pointer-events:none;
  background-image:
    linear-gradient(rgba(255,255,255,.05) 1px, transparent 1px),
    linear-gradient(90deg, rgba(255,255,255,.05) 1px, transparent 1px);
  background-size:64px 64px;
  -webkit-mask-image:radial-gradient(ellipse 72% 62% at 50% 28%, #000 0%, transparent 76%);
          mask-image:radial-gradient(ellipse 72% 62% at 50% 28%, #000 0%, transparent 76%);
}
.afc-hero__inner {
  position:relative; z-index:1; max-width:1160px; margin:0 auto;
  display:grid; grid-template-columns:minmax(0,1.04fr) minmax(0,.96fr);
  gap:60px; align-items:center;
}
.afc-hero__copy { min-width:0; text-align:left; }
.afc-hero__title { margin:0 0 24px; }
/* Paksa semua elemen kolom kiri rata kiri, apa pun style global yang masuk. */
.afc-hero__over,
.afc-hero__display,
.afc-hero__id,
.afc-hero__sign { text-align:left; margin-left:0; margin-right:0; }
.afc-hero__over {
  display:inline-flex; align-items:center; gap:9px;
  margin:0 0 24px; padding:8px 17px 8px 14px;
  border-radius:999px;
  background:rgba(232,160,191,.12);
  border:1px solid rgba(232,160,191,.32);
  font-family:'GT Walsheim Pro',Arial,sans-serif;
  font-size:12px; font-weight:500; letter-spacing:.15em;
  line-height:1; text-transform:uppercase;
  color:#e8a0bf !important;
  -webkit-backdrop-filter:blur(4px); backdrop-filter:blur(4px);
}
.afc-hero__over::before {
  content:''; width:6px; height:6px; flex-shrink:0;
  border-radius:50%; background:#e8a0bf;
  box-shadow:0 0 0 3px rgba(232,160,191,.22);
}
.afc-hero__display {
  display:block; font-size:clamp(29px,4.6vw,52px); line-height:1.14;
  font-weight:500; letter-spacing:-.03em; color:#fff !important;
}
.afc-hero__display em {
  font-style:normal;
  background:linear-gradient(120deg,#fbe3ec 0%,#e8a0bf 48%,#cf5f90 100%);
  -webkit-background-clip:text; background-clip:text; -webkit-text-fill-color:transparent;
}
.afc-hero__id {
  margin:0; max-width:600px;
  font-size:clamp(15.5px,1.6vw,18px); line-height:1.78; color:rgba(255,255,255,.8);
}
.afc-hero__actions { margin-top:36px; display:flex; flex-wrap:wrap; gap:13px; justify-content:flex-start; }
.afc-hero__actions .afc-btn { margin:0; flex:0 0 auto; }
.afc-hero__sign {
  margin-top:46px; padding-top:26px; border-top:1px solid rgba(255,255,255,.1);
  font-size:11px; font-weight:700; letter-spacing:.34em; text-transform:uppercase;
  color:rgba(255,255,255,.4);
}

/* ══ 02 — VALUE PROPOSITION ═════════════════════════════════════ */
.afc-pillars { display:grid; grid-template-columns:repeat(5,1fr); gap:16px; }
.afc-pillar {
  position:relative; overflow:hidden;
  padding:26px 20px 24px; background:#fff;
  border:1px solid rgba(184,77,122,.12); border-radius:18px;
  transition:transform .22s, box-shadow .22s, border-color .22s;
}
.afc-pillar::before { content:''; position:absolute; left:0; right:0; top:0; height:3px; background:var(--accent); }
.afc-pillar::after {
  content:''; position:absolute; inset:0; pointer-events:none;
  background:radial-gradient(110% 70% at 0% 0%, var(--tint) 0%, transparent 60%);
}
.afc-pillar:hover { transform:translateY(-4px); box-shadow:0 16px 34px rgba(184,77,122,.12); border-color:var(--accent); }
.afc-pillar > * { position:relative; z-index:1; }
.afc-pillar__label { margin:0 0 10px; font-size:11.5px; font-weight:800; letter-spacing:.14em; text-transform:uppercase; color:var(--accent); }
.afc-pillar__d { margin:0; font-size:13.5px; line-height:1.68; color:#6b3a52; }
.afc-closing {
  margin:40px auto 0; max-width:760px; text-align:center;
  font-size:clamp(19px,2.4vw,27px); font-weight:800; letter-spacing:-.02em; line-height:1.4;
  color:#1a0a12;
}
.afc-closing em {
  font-style:normal;
  background:linear-gradient(135deg,#e8a0bf,#b84d7a);
  -webkit-background-clip:text; background-clip:text; -webkit-text-fill-color:transparent;
}

/* ══ 03 — JOURNEY ═══════════════════════════════════════════════ */
.afc-journey { position:relative; display:grid; grid-template-columns:repeat(5,1fr); gap:20px; }
.afc-journey::before {
  content:''; position:absolute; top:23px; left:10%; right:10%; height:2px;
  background:repeating-linear-gradient(90deg, rgba(184,77,122,.34) 0 8px, transparent 8px 15px);
}
.afc-jnode { position:relative; z-index:1; text-align:center; }
.afc-jnode__dot {
  position:relative; width:46px; height:46px; margin:0 auto 20px;
  display:flex; align-items:center; justify-content:center;
  background:#fff; border:2px solid var(--accent); border-radius:50%;
  font-size:13px; font-weight:800; color:var(--accent); box-shadow:0 0 0 6px #fdf8fb;
}
.afc-jnode__step { margin:0 0 6px; font-size:10.5px; font-weight:800; letter-spacing:.16em; text-transform:uppercase; color:var(--accent); }
.afc-jnode__title { margin:0 0 10px; font-size:17.5px; font-weight:500; letter-spacing:-.02em; line-height:1.32; color:#1a0a12 !important; }
.afc-jnode__d { margin:0; font-size:13.5px; line-height:1.7; color:#6b3a52; }

.afc-qbar {
  margin-top:46px; padding:26px 30px; border-radius:18px;
  background:linear-gradient(120deg,#2d1a24,#1a0a12);
  display:grid; grid-template-columns:repeat(4,1fr);
}
.afc-qbar__item { text-align:center; padding:6px 12px; }
.afc-qbar__item + .afc-qbar__item { border-left:1px solid rgba(255,255,255,.12); }
.afc-qbar__k { display:block; font-size:11px; font-weight:800; letter-spacing:.16em; text-transform:uppercase; color:#e8a0bf; }
.afc-qbar__q { display:block; margin-top:7px; font-size:14.5px; font-weight:600; line-height:1.4; color:rgba(255,255,255,.86); }

/* ══ 04 — EXPERT TEAM ═══════════════════════════════════════════ */
.afc-team { display:grid; grid-template-columns:repeat(2,1fr); gap:22px; }
.afc-xcard {
  position:relative; overflow:hidden; display:flex; flex-direction:column;
  padding:32px 30px; background:#fff;
  border:1px solid rgba(184,77,122,.14); border-radius:22px;
  transition:transform .25s, box-shadow .25s, border-color .25s;
}
.afc-xcard::before {
  content:''; position:absolute; inset:0; opacity:.6; pointer-events:none;
  background:radial-gradient(120% 90% at 0% 0%, var(--tint) 0%, transparent 62%);
}
.afc-xcard::after { content:''; position:absolute; left:0; right:0; top:0; height:3px; background:var(--accent); }
.afc-xcard:hover { transform:translateY(-4px); box-shadow:0 18px 44px rgba(184,77,122,.14); border-color:var(--accent); }
.afc-xcard > * { position:relative; z-index:1; }
/* specificity 0,2,0 — HARUS menang dari `.afc-xcard > *` di atas,
   kalau tidak nomor ini jadi elemen flow (position:relative) dan dorong card. */
.afc-xcard > .afc-xcard__num {
  position:absolute; top:20px; right:26px; z-index:0;
  font-size:40px; font-weight:800; line-height:1; letter-spacing:-.04em; color:var(--tint);
}
.afc-xcard__head { display:flex; align-items:flex-start; gap:16px; margin-bottom:20px; }
.afc-xcard__icon {
  width:56px; height:56px; flex-shrink:0;
  display:flex; align-items:center; justify-content:center;
  font-size:25px; line-height:1; background:var(--tint-2);
  border:1px solid var(--border-c); border-radius:16px;
}
.afc-xcard__name { margin:0 0 4px; font-size:12.5px; font-weight:800; letter-spacing:.16em; text-transform:uppercase; color:var(--accent); }
.afc-xcard__role { margin:0; font-size:18.5px; font-weight:500; letter-spacing:-.02em; line-height:1.28; color:#2d1a24; }
/* Tanpa blok background — pakai warna accent kartu + garis pemisah tipis.
   Class-nya sengaja bukan "…__tag": vendor CSS punya [class*="tag"]
   { background:#2E9CBE !important } yang akan ikut kena. */
.afc-xcard__meta {
  margin:7px 0 0; padding-top:7px;
  border-top:1px solid var(--tint-2);
  font-size:12px; font-weight:500; line-height:1.5;
  letter-spacing:.01em; color:var(--accent);
}
.afc-xcard__lead { margin:0 0 8px; font-size:15.5px; font-weight:600; line-height:1.55; color:#4a2d3a; }
.afc-xcard__desc { margin:0 0 22px; font-size:14.5px; line-height:1.75; color:#6b3a52; }
.afc-xcard__capslabel {
  margin:0 0 10px; font-size:10.5px; font-weight:800;
  letter-spacing:.13em; text-transform:uppercase; color:#a98699;
}
.afc-xcard__explore {
  list-style:none; margin:0 0 24px; padding:0;
  display:grid; grid-template-columns:1fr 1fr; gap:8px;
}
.afc-xcard__explore li {
  display:flex; align-items:flex-start; gap:8px;
  padding:9px 11px; font-size:13px; line-height:1.45; color:#4a2d3a;
  background:#fdf7fa; border:1px solid rgba(184,77,122,.1); border-radius:10px;
}
.afc-xcard__explore li::before { content:'\2713'; color:var(--accent); font-weight:800; flex-shrink:0; }
.afc-xcard__cta {
  margin-top:auto; display:inline-flex; align-items:center; justify-content:center; gap:9px;
  padding:13px 24px; border-radius:12px; text-decoration:none;
  font-size:15px; font-weight:700; color:#fff; background:var(--accent);
  transition:opacity .18s, transform .18s, box-shadow .18s;
}
.afc-xcard__cta:hover { opacity:.9; transform:translateY(-1px); box-shadow:0 8px 20px var(--tint-2); }
.afc-xcard__cta span { transition:transform .18s; }
.afc-xcard__cta:hover span { transform:translateX(3px); }

/* ══ 05 — FOUR ANGLES ═══════════════════════════════════════════ */
.afc-qcards { display:grid; grid-template-columns:repeat(2,1fr); gap:18px; }
.afc-qcard {
  padding:26px 26px 24px 28px; background:#fff;
  border:1px solid rgba(184,77,122,.12); border-left:3px solid var(--accent); border-radius:18px;
  transition:transform .22s, box-shadow .22s, border-color .22s;
}
.afc-qcard:hover { transform:translateY(-4px); box-shadow:0 16px 34px rgba(184,77,122,.12); }
.afc-qcard__role {
  display:flex; align-items:center; gap:9px; margin:0 0 13px;
  font-size:11.5px; font-weight:800; letter-spacing:.15em; text-transform:uppercase; color:var(--accent);
}
.afc-qcard__role span[aria-hidden] { font-size:16px; line-height:1; }
.afc-qcard__q { margin:0; font-size:16.5px; font-weight:600; line-height:1.62; color:#3d2231; }
.afc-qcard__q::before { content:'\201C'; color:var(--accent); font-size:26px; line-height:0; vertical-align:-.18em; margin-right:2px; }

/* ══ 05 — CHAT DEMO ═════════════════════════════════════════════ */
.afc-chat {
  margin:38px auto 0; max-width:780px; padding:0 26px 24px;
  background:#fff; border:1px solid rgba(184,77,122,.14); border-radius:20px;
  box-shadow:0 18px 44px rgba(184,77,122,.1);
  /* fade whole card between loop cycles so the reset never hard-flashes */
  transition:opacity .5s ease, transform .5s ease;
}
.afc-chat.is-out { opacity:0; transform:translateY(14px) scale(.985); }

/* Di dalam hero kolomnya lebih sempit — rapatkan sedikit */
/* Kanan hero: kartu chat menempel ke tepi kanan container */
.afc-hero .afc-chat { margin:0 0 0 auto; max-width:520px; }
.afc-hero .afc-chat__note { text-align:left; }

.afc-chat [hidden] { display:none !important; }
.afc-chat__bar {
  display:flex; align-items:center; gap:10px;
  margin:0 -26px 22px; padding:14px 20px;
  background:#fdf0f5; border-bottom:1px solid rgba(184,77,122,.16);
  border-radius:19px 19px 0 0;
}
.afc-chat__dot {
  width:8px; height:8px; flex-shrink:0; border-radius:50%; background:#1f8a70;
  animation:afcPulse 2s ease-in-out infinite;
}
@keyframes afcPulse {
  0%,100% { box-shadow:0 0 0 3px rgba(31,138,112,.2); }
  50%     { box-shadow:0 0 0 6px rgba(31,138,112,.05); }
}
.afc-chat__bartitle { font-size:13.5px; font-weight:800; color:#2d1a24; }
.afc-chat__barstate {
  margin-left:auto; padding:3px 10px; border-radius:999px;
  background:rgba(31,138,112,.1); border:1px solid rgba(31,138,112,.24);
  font-size:10.5px; font-weight:800; letter-spacing:.14em; text-transform:uppercase; color:#1f8a70;
}
.afc-chat__row {
  display:flex; margin-bottom:16px;
  opacity:0; transform:translateY(10px);
  transition:opacity .45s ease, transform .45s ease;
}
.afc-chat__row:last-of-type { margin-bottom:0; }
.afc-chat__row--user { justify-content:flex-end; transform:translateY(10px) translateX(16px); }
.afc-chat__row--ai   { transform:translateY(10px) translateX(-16px); }
.afc-chat__row.is-in,
.afc-chat__row--user.is-in,
.afc-chat__row--ai.is-in { opacity:1; transform:none; }

.afc-bubble { max-width:80%; padding:13px 17px; border-radius:16px; font-size:14.5px; line-height:1.65; }
.afc-bubble--user {
  background:linear-gradient(135deg,#e8a0bf,#b84d7a); color:#fff;
  border-bottom-right-radius:5px;
}
.afc-bubble--ai {
  background:#fdf0f5; border:1px solid rgba(184,77,122,.16); color:#4a2d3a;
  border-bottom-left-radius:5px;
}
.afc-chat__who { margin:0 0 6px; font-size:10.5px; font-weight:800; letter-spacing:.15em; text-transform:uppercase; }
.afc-chat__row--user .afc-chat__who { text-align:right; color:#b84d7a; }
.afc-chat__row--ai .afc-chat__who { color:#9d5a76; }
.afc-chat__text { margin:0; }

.afc-chat__typing { display:flex; align-items:center; gap:5px; margin:0; padding:5px 0; }
.afc-chat__typing i {
  width:7px; height:7px; border-radius:50%; background:#c78fa8;
  animation:afcBounce 1.15s ease-in-out infinite;
}
.afc-chat__typing i:nth-child(2) { animation-delay:.16s; }
.afc-chat__typing i:nth-child(3) { animation-delay:.32s; }
@keyframes afcBounce {
  0%,60%,100% { transform:translateY(0); opacity:.4; }
  30%         { transform:translateY(-5px); opacity:1; }
}

/* Caret — hanya saat .is-typing aktif di root, jadi mati total tanpa JS */
.afc-chat.is-typing .afc-chat__row--ai .afc-chat__text::after {
  content:''; display:inline-block; width:2px; height:1.05em;
  margin-left:3px; vertical-align:-.16em; border-radius:1px;
  background:#b84d7a; animation:afcBlink .85s steps(1) infinite;
}
@keyframes afcBlink { 0%,50% { opacity:1; } 51%,100% { opacity:0; } }

.afc-chat__replay {
  display:inline-flex; align-items:center; gap:7px; margin-top:16px;
  padding:7px 16px; border-radius:999px; cursor:pointer;
  background:transparent; border:1px solid rgba(184,77,122,.28);
  font-family:inherit; font-size:12px; font-weight:700; letter-spacing:.06em; color:#b84d7a;
  transition:background .18s, border-color .18s;
}
.afc-chat__replay:hover { background:#fdf0f5; border-color:#b84d7a; }
.afc-chat__replay span { transition:transform .3s; }
.afc-chat__replay:hover span { transform:rotate(-180deg); }

.afc-chat__note {
  margin:18px 0 0; padding-top:16px; border-top:1px solid rgba(184,77,122,.14);
  font-size:12.5px; line-height:1.7; color:#9d7a8a; text-align:center;
}

/* ══ 06 — TARGET AUDIENCE ═══════════════════════════════════════ */
.afc-aud { display:grid; grid-template-columns:repeat(3,1fr); gap:18px; }
.afc-acard {
  display:flex; gap:15px; padding:24px 22px; background:#fff;
  border:1px solid rgba(184,77,122,.12); border-radius:18px;
  transition:transform .22s, box-shadow .22s, border-color .22s;
}
.afc-acard:hover { transform:translateY(-4px); box-shadow:0 16px 34px rgba(184,77,122,.12); border-color:rgba(184,77,122,.3); }
.afc-acard__icon {
  width:44px; height:44px; flex-shrink:0;
  display:flex; align-items:center; justify-content:center;
  font-size:21px; line-height:1; background:#fdf0f5;
  border:1px solid rgba(184,77,122,.16); border-radius:13px;
}
.afc-acard__t { margin:0 0 6px; font-size:12.5px; font-weight:800; letter-spacing:.1em; text-transform:uppercase; color:#b84d7a; }
.afc-acard__d { margin:0; font-size:14px; line-height:1.65; color:#6b3a52; }

/* ══ 07 — FAQ ═══════════════════════════════════════════════════ */
.afc-faq { max-width:860px; margin:0 auto; }
.afc-faq__item {
  background:#fff; border:1px solid rgba(184,77,122,.12); border-radius:16px;
  margin-bottom:12px; overflow:hidden; transition:border-color .22s, box-shadow .22s;
}
.afc-faq__item.is-open { border-color:rgba(184,77,122,.32); box-shadow:0 12px 30px rgba(184,77,122,.1); }
.afc-faq__q {
  display:flex; align-items:flex-start; gap:16px; width:100%;
  margin:0; padding:20px 22px; cursor:pointer;
  background:none; border:0; font:inherit; text-align:left;
  font-size:16px; font-weight:600; line-height:1.5; color:#2d1a24 !important;
}
.afc-faq__q:hover { background:#fdf7fa; }
.afc-faq__qmark {
  flex-shrink:0; width:26px; height:26px; margin-top:-1px;
  display:flex; align-items:center; justify-content:center;
  background:#fdf0f5; border:1px solid rgba(184,77,122,.2); border-radius:8px;
  font-size:12px; font-weight:800; color:#b84d7a;
}
.afc-faq__qtext { flex:1; min-width:0; }
.afc-faq__icon {
  flex-shrink:0; width:20px; height:20px; margin-top:2px; color:#b84d7a;
  transition:transform .28s ease;
}
.afc-faq__item.is-open .afc-faq__icon { transform:rotate(180deg); }
.afc-faq__a { display:grid; grid-template-rows:0fr; transition:grid-template-rows .3s ease; }
.afc-faq__item.is-open .afc-faq__a { grid-template-rows:1fr; }
.afc-faq__a > div { overflow:hidden; }
.afc-faq__atext { margin:0; padding:0 22px 22px 64px; font-size:14.5px; line-height:1.75; color:#6b3a52; }

/* ══ FINAL CTA ══════════════════════════════════════════════════ */
.afc-final {
  text-align:center; padding:60px 44px; border-radius:26px;
  background:linear-gradient(140deg,#fdf0f5 0%,#fff 42%,#f4f0fb 100%);
  border:1px solid rgba(184,77,122,.18);
  box-shadow:0 24px 60px rgba(184,77,122,.12);
}
.afc-final__title {
  margin:0 auto 18px; max-width:800px;
  font-size:clamp(24px,3.4vw,38px); font-weight:500; letter-spacing:-.03em; line-height:1.18;
  color:#1a0a12 !important;
}
.afc-final__lead { margin:0 auto; max-width:660px; font-size:16.5px; line-height:1.72; color:#6b3a52; }
.afc-final__flow {
  margin:28px auto 0; display:inline-flex; flex-wrap:wrap; justify-content:center; gap:12px;
  padding:12px 24px; border-radius:999px;
  background:#fff; border:1px solid rgba(184,77,122,.18);
  font-size:12.5px; font-weight:800; letter-spacing:.14em; color:#b84d7a;
}
.afc-final__actions { margin-top:30px; display:flex; flex-wrap:wrap; gap:13px; justify-content:center; align-items:center; }

/* ══ RESPONSIVE ══════════════════════════════════════════════════ */
@media(max-width:1080px) {
  .afc-hero__inner { grid-template-columns:1fr; gap:44px; }
  .afc-hero .afc-chat { max-width:560px; margin:0 auto; }
  .afc-hero__id { max-width:680px; }
  .afc-pillars { grid-template-columns:repeat(3,1fr); }
  .afc-journey { grid-template-columns:repeat(2,1fr); gap:32px 20px; }
  .afc-journey::before { display:none; }
  .afc-qbar { grid-template-columns:1fr 1fr; gap:18px 8px; }
  .afc-qbar__item:nth-child(odd) { border-left:0; }
  .afc-aud { grid-template-columns:1fr 1fr; }
}
@media(max-width:860px) {
  .afc-team, .afc-qcards { grid-template-columns:1fr; }
}
@media(max-width:640px) {
  .afc-sec { padding:56px 1.25rem; }
  .afc-hero { padding:60px 1.25rem 56px; }
  .afc-hero__sign { letter-spacing:.22em; }
  .afc-pillars, .afc-journey, .afc-qbar, .afc-aud { grid-template-columns:1fr; }
  .afc-qbar__item + .afc-qbar__item { border-left:0; border-top:1px solid rgba(255,255,255,.12); }
  .afc-xcard { padding:24px 20px; }
  .afc-xcard__explore { grid-template-columns:1fr; }
  .afc-bubble { max-width:100%; }
  .afc-qcard { padding:22px 20px; }
  .afc-faq__q { padding:18px 18px; font-size:15px; gap:12px; }
  .afc-faq__atext { padding:0 18px 20px 56px; }
  .afc-final { padding:40px 20px; }
  .afc-final__flow { gap:8px; letter-spacing:.1em; }
}
</style>
CSS;

/* ================================================================
   SCRIPTS — animasi chat demo + accordion FAQ
   Progressive enhancement: tanpa JS semua bubble & jawaban FAQ tetap tampil utuh.
   ================================================================ */
$extraScripts = <<<'JS'
<script>
/* ── Chat demo: bubble user → bubble AI + typing dots → typewriter ── */
(function () {
  var chat = document.getElementById('afcChat');
  if (!chat) return;

  var rowUser = chat.querySelector('[data-step="user"]');
  var rowAi   = chat.querySelector('[data-step="ai"]');
  var typing  = chat.querySelector('[data-typing]');
  var answer  = chat.querySelector('[data-answer]');
  var replay  = chat.querySelector('[data-replay]');
  var text    = (answer.getAttribute('data-text') || '').trim();

  var reduce = !!(window.matchMedia
    && window.matchMedia('(prefers-reduced-motion: reduce)').matches);
  var timers = [];
  var loopTimer = null;
  var running = false;

  /* Jeda sebelum mengulang, supaya respons lengkap sempat terbaca. */
  var LOOP_GAP = 4600;
  /* Lama kartu fade-out sebelum siklus berikutnya mulai. */
  var FADE = 520;

  function clearTimers() {
    for (var i = 0; i < timers.length; i++) clearTimeout(timers[i]);
    timers = [];
  }
  function later(fn, ms) { timers.push(setTimeout(fn, ms)); }
  function stopLoop() {
    clearTimeout(loopTimer);
    loopTimer = null;
  }

  /* Tampilkan semua sekaligus — dipakai kalau reduced-motion atau browser
     tanpa IntersectionObserver. */
  function finish() {
    clearTimers();
    chat.classList.remove('is-typing');
    rowUser.classList.add('is-in');
    rowAi.hidden = false;
    rowAi.classList.add('is-in');
    typing.hidden = true;
    answer.hidden = false;
    answer.textContent = text;
    replay.hidden = false;
  }

  function reset() {
    clearTimers();
    chat.classList.remove('is-typing');
    rowUser.classList.remove('is-in');
    rowAi.hidden = true;
    rowAi.classList.remove('is-in');
    typing.hidden = true;
    answer.hidden = true;
    answer.textContent = '';
    replay.hidden = true;
  }

  function play(done) {
    reset();

    later(function () { rowUser.classList.add('is-in'); }, 240);
    later(function () {
      rowAi.hidden = false;
      rowAi.classList.add('is-in');
    }, 1250);
    later(function () { typing.hidden = false; }, 1560);

    if (reduce) { later(done, 1560); return; }

    var i = 0;

    /* Typewriter baru mulai SETELAH dot animasi sempat terlihat.
       Kalau dipanggil langsung, teks sudah mengetik sebelum bubble muncul. */
    later(function () {
      typing.hidden = true;
      chat.classList.add('is-typing');

      (function type() {
        if (i >= text.length) {
          chat.classList.remove('is-typing');
          later(function () { answer.hidden = false; replay.hidden = false; }, 300);
          later(done, 1100);
          return;
        }
        answer.hidden = false;
        i++;
        answer.textContent = text.slice(0, i);
        /* jeda sedikit di tanda baca supaya terbaca natural */
        later(type, '.,'.indexOf(text.charAt(i - 1)) > -1 ? 100 : 10);
      })();
    }, 2050);
  }

  /* Fade kartu, lalu jalankan satu siklus penuh lagi. */
  function cycle() {
    chat.classList.add('is-out');
    later(function () {
      chat.classList.remove('is-out');
      if (running) play(cycle);
    }, FADE);
  }

  function startLoop() { if (running) play(cycle); }

  /* Reduced motion: tampilkan hasil akhir sekali, jangan loop — tapi tombol
     Putar Ulang tetap berfungsi sebagai pemicu tampil ulang statis. */
  replay.addEventListener('click', function () {
    stopLoop();
    if (reduce) { finish(); return; }
    cycle();
  });

  if (reduce) { finish(); return; }

  /* Tanpa IntersectionObserver, hero ini ada di layar sejak awal — jalan terus. */
  if (!('IntersectionObserver' in window)) {
    running = true;
    startLoop();
    return;
  }

  reset();

  var io = new IntersectionObserver(function (entries) {
    var visible = false;
    for (var i = 0; i < entries.length; i++) {
      if (entries[i].isIntersecting) visible = true;
    }

    if (visible && !running) {
      running = true;
      startLoop();
    } else if (!visible && running) {
      /* Hemat CPU: berhenti total saat kartu keluar viewport. */
      running = false;
      stopLoop();
      clearTimers();
      reset();
    }
  }, { threshold: 0.25 });

  io.observe(chat);
})();

/* ── FAQ accordion ── */
(function () {
  var list = document.getElementById('afcFaqList');
  if (!list) return;

  list.addEventListener('click', function (e) {
    var btn = e.target.closest('.afc-faq__q');
    if (!btn) return;

    var item = btn.closest('.afc-faq__item');
    var wasOpen = item.classList.contains('is-open');

    var open = list.querySelectorAll('.afc-faq__item.is-open');
    for (var i = 0; i < open.length; i++) {
      if (open[i] === item) continue;
      open[i].classList.remove('is-open');
      var ob = open[i].querySelector('.afc-faq__q');
      if (ob) ob.setAttribute('aria-expanded', 'false');
    }

    item.classList.toggle('is-open', !wasOpen);
    btn.setAttribute('aria-expanded', !wasOpen ? 'true' : 'false');
  });
})();
</script>
JS;

require_once __DIR__ . '/../../layouts/header.php';
?>

<div class="afc-page">

  <!-- ══════════ 01 — HERO ══════════ -->
  <header class="afc-hero">
    <div class="afc-hero__glow" aria-hidden="true"></div>
    <div class="afc-hero__grid" aria-hidden="true"></div>

    <div class="afc-hero__inner">
      <div class="afc-hero__copy">
        <h1 class="afc-hero__title">
          <span class="afc-hero__over">AI for Cosmetic Industry</span>
          <span class="afc-hero__display">Your AI Expert Team for<br><em>Cosmetic Product Development</em></span>
        </h1>

        <p class="afc-hero__id"><?= htmlspecialchars($heroId) ?></p>

        <div class="afc-hero__actions">
          <a class="afc-btn afc-btn--primary" href="#experts">
            Explore AI Experts <span aria-hidden="true">&rarr;</span>
          </a>
          <a class="afc-btn afc-btn--ghost" href="#journey">See the Journey</a>
        </div>

        <p class="afc-hero__sign"><?= $heroSign ?></p>
      </div>

      <div class="afc-chat" id="afcChat">
        <div class="afc-chat__bar">
          <span class="afc-chat__dot" aria-hidden="true"></span>
          <span class="afc-chat__bartitle">Formulator AI</span>
          <span class="afc-chat__barstate">Online</span>
        </div>

        <div class="afc-chat__row afc-chat__row--user is-in" data-step="user">
          <div class="afc-bubble afc-bubble--user">
            <p class="afc-chat__who">Anda</p>
            <p class="afc-chat__text"><?= htmlspecialchars($chatUser) ?></p>
          </div>
        </div>

        <div class="afc-chat__row afc-chat__row--ai is-in" data-step="ai">
          <div class="afc-bubble afc-bubble--ai">
            <p class="afc-chat__who">Formulator AI</p>
            <p class="afc-chat__typing" data-typing hidden aria-hidden="true"><i></i><i></i><i></i></p>
            <p class="afc-chat__text" data-answer data-text="<?= htmlspecialchars($chatAi) ?>"><?= htmlspecialchars($chatAi) ?></p>
          </div>
        </div>

        <div style="text-align:center;">
          <button class="afc-chat__replay" type="button" data-replay hidden>
            <span aria-hidden="true">&#8635;</span> Putar Ulang
          </button>
        </div>

        <p class="afc-chat__note"><?= htmlspecialchars($chatNote) ?></p>
      </div>
    </div>
  </header>

  <!-- ══════════ 02 — VALUE PROPOSITION ══════════ -->
  <section class="afc-sec">
    <div class="afc-wrap">
      <div class="afc-head">
        <p class="afc-eyebrow">Why AI for Cosmetic Industry</p>
        <h2 class="afc-h2">Cosmetic Innovation Is More Than <em>Formulation</em></h2>
        <p class="afc-sub"><?= htmlspecialchars($valueIntro) ?></p>
      </div>

      <div class="afc-pillars">
        <?php foreach ($pillars as $p): ?>
          <article class="afc-pillar"
                   style="--accent:<?= htmlspecialchars($p['color']) ?>;--tint:<?= htmlspecialchars($p['color']) ?>1f;">
            <p class="afc-pillar__label"><?= htmlspecialchars($p['label']) ?></p>
            <p class="afc-pillar__d"><?= htmlspecialchars($p['d']) ?></p>
          </article>
        <?php endforeach; ?>
      </div>

      <p class="afc-closing">One Product. <em>Multiple Expert Perspectives.</em></p>
    </div>
  </section>

  <!-- ══════════ 03 — PRODUCT DEVELOPMENT JOURNEY ══════════ -->
  <section class="afc-sec afc-sec--tint afc-sec--edge" id="journey">
    <div class="afc-wrap">
      <div class="afc-head">
        <p class="afc-eyebrow">The Cosmetic Development Journey</p>
        <h2 class="afc-h2">From Idea to Market &mdash; <em>With Intelligence at Every Stage</em></h2>
        <p class="afc-sub"><?= htmlspecialchars($journeyIntro) ?></p>
      </div>

      <div class="afc-journey">
        <?php foreach ($journey as $j): ?>
          <article class="afc-jnode" style="--accent:<?= htmlspecialchars($j['color']) ?>;">
            <div class="afc-jnode__dot"><?= htmlspecialchars($j['num']) ?></div>
            <p class="afc-jnode__step"><?= htmlspecialchars($j['step']) ?></p>
            <h2 class="afc-jnode__title"><?= htmlspecialchars($j['title']) ?></h2>
            <p class="afc-jnode__d"><?= htmlspecialchars($j['d']) ?></p>
          </article>
        <?php endforeach; ?>
      </div>

      <div class="afc-qbar">
        <?php foreach ($questions as $q): ?>
          <div class="afc-qbar__item">
            <span class="afc-qbar__k"><?= htmlspecialchars($q[0]) ?></span>
            <span class="afc-qbar__q"><?= htmlspecialchars($q[1]) ?></span>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- ══════════ 04 — AI EXPERT TEAM ══════════ -->
  <section class="afc-sec" id="experts">
    <div class="afc-wrap">
      <div class="afc-head">
        <p class="afc-eyebrow">Meet Your AI Expert Team</p>
        <h2 class="afc-h2">Four Specialized AI Experts <em>Designed for Your Development Needs</em></h2>
        <p class="afc-sub"><?= htmlspecialchars($teamIntro) ?></p>
      </div>

      <div class="afc-team">
        <?php foreach ($experts as $e): ?>
          <article class="afc-xcard" id="<?= htmlspecialchars($e['id']) ?>"
                   style="--accent:<?= htmlspecialchars($e['color']) ?>;--tint:<?= htmlspecialchars($e['color']) ?>1f;--tint-2:<?= htmlspecialchars($e['color']) ?>14;--border-c:<?= htmlspecialchars($e['color']) ?>33;">

            <span class="afc-xcard__num" aria-hidden="true"><?= htmlspecialchars($e['num']) ?></span>

            <div class="afc-xcard__head">
              <div class="afc-xcard__icon" aria-hidden="true"><?= $e['emoji'] ?></div>
              <div>
                <p class="afc-xcard__name"><?= htmlspecialchars($e['name']) ?></p>
                <h3 class="afc-xcard__role"><?= $e['role'] ?></h3>
                <p class="afc-xcard__meta"><?= $e['tag'] ?></p>
              </div>
            </div>

            <p class="afc-xcard__lead"><?= $e['lead'] ?></p>
            <p class="afc-xcard__desc"><?= htmlspecialchars($e['desc']) ?></p>

            <p class="afc-xcard__capslabel"><?= $e['askLabel'] ?></p>
            <ul class="afc-xcard__explore">
              <?php foreach ($e['explore'] as $x): ?>
                <li><?= $x ?></li>
              <?php endforeach; ?>
            </ul>

            <a class="afc-xcard__cta" href="<?= htmlspecialchars($appUrl) ?>">
              <?= htmlspecialchars($e['cta']) ?> <span aria-hidden="true">&rarr;</span>
            </a>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- ══════════ 05 — PRACTICAL DEMO ══════════ -->
  <section class="afc-sec afc-sec--tint afc-sec--edge">
    <div class="afc-wrap">
      <div class="afc-head">
        <p class="afc-eyebrow">What Can Each AI Do?</p>
        <h2 class="afc-h2">From Questions to <em>Practical Insights</em></h2>
        <p class="afc-sub"><?= htmlspecialchars($demoIntro) ?></p>
      </div>

      <p class="afc-label">Satu Produk, Empat Sudut Pandang:</p>

      <div class="afc-qcards">
        <?php foreach ($angles as $a): ?>
          <article class="afc-qcard" style="--accent:<?= htmlspecialchars($a['color']) ?>;">
            <p class="afc-qcard__role">
              <span aria-hidden="true"><?= $a['emoji'] ?></span><?= htmlspecialchars($a['role']) ?>
            </p>
            <p class="afc-qcard__q"><?= htmlspecialchars($a['q']) ?></p>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- ══════════ 06 — TARGET AUDIENCE ══════════ -->
  <section class="afc-sec afc-sec--edge">
    <div class="afc-wrap">
      <div class="afc-head">
        <p class="afc-eyebrow">Who Is It For?</p>
        <h2 class="afc-h2">Built for <em>Cosmetic Industry Professionals</em></h2>
        <p class="afc-sub"><?= htmlspecialchars($audienceIntro) ?></p>
      </div>

      <div class="afc-aud">
        <?php foreach ($audiences as $a): ?>
          <article class="afc-acard">
            <div class="afc-acard__icon" aria-hidden="true"><?= $a[0] ?></div>
            <div>
              <p class="afc-acard__t"><?= $a[1] ?></p>
              <p class="afc-acard__d"><?= htmlspecialchars($a[2]) ?></p>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- ══════════ FINAL CTA ══════════ -->
  <section class="afc-sec" style="padding-top:0;">
    <div class="afc-wrap">
      <div class="afc-final">
        <p class="afc-eyebrow">From Formulation to Validation</p>
        <h2 class="afc-final__title">
          Your Cosmetic Development Journey, <em>Powered by Specialized AI Expertise.</em>
        </h2>
        <p class="afc-final__lead">
          Explore the right AI expert for every question, every stage, and every product decision.
        </p>

        <p class="afc-final__flow">Formulate &rarr; Comply &rarr; Certify &rarr; Validate</p>

        <div class="afc-final__actions">
          <a class="afc-btn afc-btn--primary" href="<?= htmlspecialchars($appUrl) ?>">
            Explore AI for Cosmetic Industry <span aria-hidden="true">&rarr;</span>
          </a>
          <a class="afc-btn afc-btn--line" href="<?= htmlspecialchars($appUrl) ?>">Start with Formulator</a>
        </div>
      </div>
    </div>
  </section>

  <!-- ══════════ 07 — FAQ ══════════ -->
  <section class="afc-sec afc-sec--tint afc-sec--edge" id="faq">
    <div class="afc-wrap">
      <div class="afc-head">
        <p class="afc-eyebrow">Frequently Asked Questions</p>
        <h2 class="afc-h2">Clear Answers to <em>Help You Get Started</em></h2>
        <p class="afc-sub"><?= htmlspecialchars($faqIntro) ?></p>
      </div>

      <div class="afc-faq" id="afcFaqList">
        <?php foreach ($faqs as $i => $f): ?>
          <div class="afc-faq__item">
            <button type="button" class="afc-faq__q" aria-expanded="false" aria-controls="afc-faq-a-<?= $i ?>">
              <span class="afc-faq__qmark" aria-hidden="true"><?= str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT) ?></span>
              <span class="afc-faq__qtext"><?= htmlspecialchars($f['q']) ?></span>
              <svg class="afc-faq__icon" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                <path d="M5 7.5 10 12.5 15 7.5" stroke="currentColor" stroke-width="2"
                      stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
            </button>
            <div class="afc-faq__a" id="afc-faq-a-<?= $i ?>" role="region">
              <div>
                <p class="afc-faq__atext"><?= htmlspecialchars($f['a']) ?></p>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

</div>

<?php require_once __DIR__ . '/../../layouts/footer.php'; ?>
