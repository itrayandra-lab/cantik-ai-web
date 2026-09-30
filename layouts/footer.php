<?php
/**
 * layouts/footer.php
 * Footer untuk semua halaman publik.
 *
 * Struktur & nilai visual sama dengan homepage (pages/home.html, <section class="footer">):
 *   padding-global > container-large > padding 32px 0 > flex row
 *   copyright | links (gap 32px) | social (gap 16px)
 *
 * Class .footer dari homepage sengaja TIDAK dipakai, karena light-theme.css
 * punya `.footer .container-large a { color:#6b4c5e !important }` yang akan
 * membuat teks putih di atas latar hitam jadi tidak terbaca.
 */

$siteUrl  = isset($siteUrl)  ? $siteUrl  : 'https://cantik.ai';

// Tautan FAQ: ke section di halaman ini kalau ada, kalau tidak ke homepage.
$faqHref = isset($showFaq) && $showFaq ? '#faq' : '/#faq';
?>

<footer class="site-footer">
  <div class="padding-global">
    <div class="container-large">
      <div class="site-footer__bar">
        <div class="site-footer__copy">&copy; <?= date('Y') ?> Cantik.AI. All rights reserved.</div>

        <nav class="site-footer__links" aria-label="Footer">
          <a href="<?= htmlspecialchars($siteUrl) ?>/privacy">Privacy Policy</a>
          <a href="<?= htmlspecialchars($siteUrl) ?>/terms">Terms of Service</a>
          <a href="<?= htmlspecialchars($faqHref) ?>">FAQ</a>
          <a href="https://app.cantik.ai/">Contact Us</a>
        </nav>

        <div class="site-footer__social">
          <a href="#" aria-label="Facebook">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
              <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
            </svg>
          </a>
          <a href="#" aria-label="X (Twitter)">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
              <path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm5.894 8.221l-1.97 9.28c-.145.658-.537.818-1.084.508l-3-2.21-1.446 1.394c-.14.18-.357.295-.6.295-.002 0-.003 0-.005 0l.213-3.054 5.56-5.022c.24-.213-.054-.334-.373-.121l-6.869 4.326-2.96-.924c-.64-.203-.658-.64.135-.954l11.566-4.458c.538-.196 1.006.128.832.941z"/>
            </svg>
          </a>
          <a href="#" aria-label="LinkedIn">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
              <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
            </svg>
          </a>
        </div>
      </div>
    </div>
  </div>
</footer>

<style>
/* ===== FOOTER (identik dengan section.footer di homepage) ===== */
/* light-theme.css punya `[class*="footer"] { background:#f0e8f0 !important }`.
   Selector itu kena ke .site-footer DAN semua elemennya (site-footer__bar,
   __copy, __links, __social), jadi latar hitam di sini kalah important.
   Dipaksa balik ke #000 — sama dengan section FAQ — pakai selector
   ber-specificity lebih tinggi (0,2,0) + !important. */
.site-footer.site-footer { background: #000 !important; }
.site-footer [class*="footer"] { background: transparent !important; }

.site-footer { position: relative; overflow: hidden; }
.site-footer__bar {
  padding: 32px 0;
  display: flex;
  justify-content: space-between;
  align-items: center;
  flex-wrap: wrap;
  gap: 20px;
}
.site-footer__copy { color: rgba(255, 255, 255, .7); font-size: 15px; font-weight: 500; }

.site-footer__links { display: flex; gap: 32px; flex-wrap: wrap; align-items: center; }
.site-footer__links a {
  color: rgba(255, 255, 255, .7);
  font-size: 15px;
  font-weight: 500;
  text-decoration: none;
  transition: color .3s;
}
.site-footer__links a:hover { color: rgba(255, 255, 255, 1); }

.site-footer__social { display: flex; gap: 16px; align-items: center; }
.site-footer__social a {
  color: rgba(255, 255, 255, .7);
  display: flex;
  align-items: center;
  justify-content: center;
  transition: color .3s, transform .3s;
}
.site-footer__social a:hover { color: rgba(255, 255, 255, 1); transform: scale(1.1); }
</style>

<?php if (isset($extraScripts)) echo $extraScripts; ?>
</body>
</html>
