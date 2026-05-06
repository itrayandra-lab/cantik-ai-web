<?php
/**
 * layouts/footer.php
 * Footer untuk semua halaman publik
 */
?>
<footer class="site-footer">
  <div class="site-footer__top">
    <div class="site-footer__inner">

      <!-- Brand -->
      <div class="site-footer__brand">
        <img src="/assets/img/logo-colour-pink.png" alt="Cantik.AI">
        <p>Platform AI untuk Industri Kecantikan Indonesia. Dari regulasi BPOM, formulasi kosmetik, hingga strategi bisnis.</p>
      </div>

      <!-- Links -->
      <div class="site-footer__nav">
        <div class="site-footer__col">
          <h4>Produk</h4>
          <a href="/#features-section">Fitur</a>
          <a href="/#pricing">Harga</a>
          <a href="/blog/">Blog</a>
        </div>
        <div class="site-footer__col">
          <h4>Perusahaan</h4>
          <a href="/#automations">Tentang</a>
          <a href="/#faq">FAQ</a>
        </div>
        <div class="site-footer__col">
          <h4>Akun</h4>
          <a href="https://app.cantik.ai/">Login</a>
          <a href="https://app.cantik.ai/register">Daftar</a>
        </div>
      </div>

    </div>
  </div>

  <div class="site-footer__bottom">
    <span>© <?= date('Y') ?> <a href="/">Cantik.AI</a> · All rights reserved.</span>
    <div class="site-footer__bottom-links">
      <a href="#">Privacy Policy</a>
      <a href="#">Terms of Service</a>
    </div>
  </div>
</footer>

<style>
.site-footer {
  background: rgba(232, 160, 191, 0.15);
  border-top: 1px solid rgba(184, 77, 122, 0.15);
  margin-top: 60px;
}
.site-footer__top {
  padding: 56px 0 40px;
}
.site-footer__inner {
  max-width: 1280px;
  margin: 0 auto;
  padding: 0 2.5rem;
  display: grid;
  grid-template-columns: 280px 1fr;
  gap: 64px;
  align-items: start;
}
.site-footer__brand img {
  height: 36px;
  width: auto;
  margin-bottom: 14px;
  display: block;
}
.site-footer__brand p {
  font-size: 13.5px;
  color: #6b3a52;
  line-height: 1.7;
  max-width: 240px;
  margin-bottom: 18px;
}
.site-footer__cta {
  display: inline-block;
  padding: 8px 18px;
  background: linear-gradient(135deg, #e8a0bf, #b84d7a);
  color: #fff;
  border-radius: 8px;
  font-size: 13px;
  font-weight: 600;
  text-decoration: none;
  transition: opacity .15s;
}
.site-footer__cta:hover { opacity: .85; }

.site-footer__nav {
  display: flex;
  gap: 48px;
  flex-wrap: wrap;
}
.site-footer__col h4 {
  font-size: 11px;
  font-weight: 800;
  text-transform: uppercase;
  letter-spacing: .08em;
  color: #b84d7a;
  margin-bottom: 14px;
}
.site-footer__col a {
  display: block;
  font-size: 14px;
  color: #4a2d3a;
  text-decoration: none;
  margin-bottom: 10px;
  transition: color .15s;
  font-weight: 500;
}
.site-footer__col a:hover { color: #b84d7a; }

.site-footer__bottom {
  border-top: 1px solid rgba(184, 77, 122, 0.12);
  padding: 18px 2.5rem;
  max-width: 1280px;
  margin: 0 auto;
  display: flex;
  align-items: center;
  justify-content: space-between;
  font-size: 13px;
  color: #6b3a52;
  flex-wrap: wrap;
  gap: 12px;
}
.site-footer__bottom a {
  color: #b84d7a;
  text-decoration: none;
  font-weight: 500;
}
.site-footer__bottom a:hover { text-decoration: underline; }
.site-footer__bottom-links {
  display: flex;
  gap: 20px;
}
.site-footer__bottom-links a {
  color: #6b3a52;
  font-weight: 400;
}
.site-footer__bottom-links a:hover { color: #b84d7a; }

@media(max-width: 768px) {
  .site-footer__inner { grid-template-columns: 1fr; gap: 32px; }
  .site-footer__nav { gap: 28px; }
  .site-footer__bottom { flex-direction: column; align-items: flex-start; }
}
</style>

<?php if (isset($extraScripts)) echo $extraScripts; ?>
</body>
</html>
