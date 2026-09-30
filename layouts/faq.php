<?php
/**
 * layouts/faq.php
 * FAQ section — shared untuk semua halaman publik.
 *
 * Markup & class PERSIS sama dengan homepage (pages/home.html), supaya
 * typography, spacing, dan grid dari sintraweb.shared.min.css ikut sama.
 *
 * Yang berbeda hanya accordion-nya: homepage pakai interaksi Webflow
 * (data-w-id + sintraweb.main.js). Halaman PHP tidak memuat runtime itu,
 * jadi dipakai accordion JS sendiri (grid-template-rows 0fr -> 1fr).
 *
 * light-theme.css mengoverride .background-color-alternate/.home-faq_*
 * dengan !important menjadi versi terang, jadi palet gelap homepage
 * dipaksa kembali lewat .faq-homepage di bawah.
 *
 * Set $showFaq = false sebelum require untuk menyembunyikan.
 */

if (!isset($showFaq)) { $showFaq = true; }

$faqItems = [
    [
        'q' => 'Apakah AI Employee mengambil keputusan bisnis sendiri?',
        'a' => 'Tidak. Cantik.AI berfungsi sebagai asisten cerdas yang memberikan rekomendasi strategis berdasarkan data industri. Keputusan akhir, kreativitas, dan visi brand tetap sepenuhnya berada di tangan Anda sebagai pemilik atau profesional.',
    ],
    [
        'q' => 'Bagaimana dengan keamanan data riset dan formulasi?',
        'a' => 'Kami menjamin kerahasiaan data riset, strategi pemasaran, dan data internal perusahaan Anda. Setiap workspace bersifat terisolasi untuk memastikan informasi berharga tidak bocor ke pihak luar.',
    ],
    [
        'q' => 'Apakah tim kami perlu pelatihan khusus untuk menggunakannya?',
        'a' => 'Cantik.AI didesain untuk kemudahan penggunaan dengan bahasa sehari-hari. Namun, kami juga menyediakan pendampingan mulai dari pelatihan tim hingga kustomisasi spesialis AI agar sesuai dengan alur kerja unik instansi atau perusahaan Anda.',
    ],
    [
        'q' => 'Bisakah AI disesuaikan dengan kebutuhan spesifik brand kami?',
        'a' => 'Tentu. Anda bisa membuat workspace terpisah yang dipersonalisasi untuk unit kerja berbeda, seperti tim R&amp;D, Pemasaran, atau Regulasi, lengkap dengan dokumen dan bot spesialis masing-masing.',
    ],
    [
        'q' => 'Bagaimana cara memulai implementasi di bisnis kosmetik saya?',
        'a' => 'Anda bisa memulai secara bertahap dengan satu unit kerja, melihat dampaknya, lalu mengembangkannya ke seluruh organisasi dengan pendekatan praktis dan terukur yang kami sediakan.',
    ],
    [
        'q' => 'Apakah AI akan menggantikan peran ahli dan tim kami?',
        'a' => 'Sama sekali tidak. Cantik.AI hadir untuk mempermudah pekerjaan harian, mengotomatiskan rutinitas, dan mempertajam analisis strategis agar tim Anda bisa fokus pada inovasi yang lebih berdampak besar.',
    ],
];

if (!$showFaq) { return; }

$faqChevron = '<svg width="100%" height="100%" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">'
    . '<path fill-rule="evenodd" clip-rule="evenodd" d="M16.5303 20.8839C16.2374 21.1768 15.7626 21.1768 15.4697 20.8839L7.82318 13.2374C7.53029 12.9445 7.53029 12.4697 7.82318 12.1768L8.17674 11.8232C8.46963 11.5303 8.9445 11.5303 9.2374 11.8232L16 18.5858L22.7626 11.8232C23.0555 11.5303 23.5303 11.5303 23.8232 11.8232L24.1768 12.1768C24.4697 12.4697 24.4697 12.9445 24.1768 13.2374L16.5303 20.8839Z" fill="currentColor"></path>'
    . '</svg>';
?>

<style>
/* ===== FAQ: samakan palet gelap dengan homepage ===== */
.faq-homepage,
.faq-homepage.background-color-alternate {
  background-color: #000 !important;
  color: #fff !important;
  margin-top: 1.5rem;
}
.faq-homepage .text-style-muted {
  color: rgba(255, 255, 255, .6) !important;
}
.faq-homepage .home-faq_content .home-faq_answer p {
  opacity: 1;
  color: rgba(255, 255, 255, .6) !important;
}
.faq-homepage .home-faq_answer p a { color: #fff; text-decoration: underline; }
.faq-homepage h2,
.faq-homepage h3,
.faq-homepage .home-faq_question:hover h3 { color: #fff !important; }

/* Separator: hanya tiap item (sama seperti homepage) */
.faq-homepage .home-faq_accordion {
  border-bottom: 1px solid rgba(255, 255, 255, .3) !important;
}
.faq-homepage .home-faq_list { border-bottom: 0 !important; }

/* Accordion (pengganti interaksi Webflow) */
.faq-homepage .faq_question {
  width: 100%;
  background: none;
  border: 0;
  font: inherit;
  color: inherit;
  text-align: left;
}
.faq-homepage .faq_icon-slot { transition: transform .28s ease; }
.faq-homepage .faq_item.is-open .faq_icon-slot { transform: rotate(180deg); }
.faq-homepage .faq_answer {
  display: grid;
  grid-template-rows: 0fr;
  transition: grid-template-rows .3s ease;
}
.faq-homepage .faq_item.is-open .faq_answer { grid-template-rows: 1fr; }
.faq-homepage .faq_answer > .faq_answer-inner { overflow: hidden; }
</style>

<section data-scroll-section="faq" class="section_home-faq background-color-alternate faq-homepage" id="faq">
  <div class="padding-global">
    <div class="container-large padding-section-large">
      <div class="home-faq_component breathing-space">
        <div class="w-layout-grid home-faq_content">
          <div class="home-faq_content-left">
            <h2 class="text-size-5xl">Pertanyaan Yang Sering Diajukan</h2>
            <div class="spacer-small"></div>
            <p class="text-size-base text-style-muted">
              Kami memahami kekhawatiran Anda tentang implementasi AI di industri kosmetik, mulai dari keamanan data formulasi, akurasi regulasi, hingga kesiapan tim internal.
              <br /><br />
              Berikut jawaban atas pertanyaan yang paling sering kami terima dari pemilik brand dan profesional industri kecantikan. Jika masih ada yang ingin ditanyakan, tim ahli kami siap membantu Anda.
            </p>
          </div>

          <div class="home-faq_list" data-faq>
            <?php foreach ($faqItems as $item): ?>
              <div class="home-faq_accordion faq_item">
                <button type="button"
                        class="home-faq_question faq_question"
                        aria-expanded="false">
                  <h3 class="text-size-2xl text-weight-medium"><?= $item['q'] ?></h3>
                  <div class="home-faq_icon-wrapper faq_icon-slot" aria-hidden="true">
                    <div class="icon-embed-small w-embed"><?= $faqChevron ?></div>
                  </div>
                </button>

                <div class="home-faq_answer faq_answer" style="width: 100%;">
                  <div class="faq_answer-inner">
                    <p class="text-size-base text-style-muted line-height-1-5"><?= $item['a'] ?></p>
                    <div class="spacer-small-2"></div>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<script>
(function () {
  document.querySelectorAll('[data-faq]').forEach(function (list) {
    if (list.dataset.bound) return;
    list.dataset.bound = '1';

    list.addEventListener('click', function (e) {
      var btn = e.target.closest('.faq_question');
      if (!btn) return;

      var item = btn.closest('.faq_item');
      var wasOpen = item.classList.contains('is-open');

      list.querySelectorAll('.faq_item.is-open').forEach(function (other) {
        if (other === item) return;
        other.classList.remove('is-open');
        var otherBtn = other.querySelector('.faq_question');
        if (otherBtn) otherBtn.setAttribute('aria-expanded', 'false');
      });

      item.classList.toggle('is-open', !wasOpen);
      btn.setAttribute('aria-expanded', !wasOpen ? 'true' : 'false');
    });
  });
})();
</script>
