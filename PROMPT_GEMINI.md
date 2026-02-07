# Prompt untuk Gemini AI - Setup Web Raymaizing

## 🤖 Prompt untuk AI Assistant

Gunakan prompt berikut untuk meminta bantuan AI (Gemini, ChatGPT, Claude, dll) dalam setup atau troubleshooting:

---

## Prompt 1: Setup Awal

```
Saya memiliki project landing page bernama "Web Raymaizing" yang merupakan static website (HTML/CSS/JS) untuk platform AI Assistant. Project ini perlu di-setup untuk berjalan di port 8080 dan terintegrasi dengan React app di port 8081.

Struktur project:
- index.html (homepage)
- pricing-section.html (section pricing)
- assets/ (CSS, JS, images)
- package.json (NPM config)

Requirements:
1. Jalankan static server di port 8080
2. Redirect link "Login" ke http://localhost:8081/login
3. Redirect button "Dapatkan Paket" ke http://localhost:8081/subscribe dengan parameter: tier, files, price, annual
4. Navbar menu "Home" (bukan "Awal")
5. Button "Mulai Gratis" scroll smooth ke section pricing

Tech stack:
- Pure HTML/CSS/JavaScript (no framework)
- http-server untuk development
- Integrasi dengan React app (port 8081) dan Express backend (port 5001)

Tolong bantu saya:
1. Setup development environment
2. Jalankan server di port 8080
3. Verifikasi integrasi dengan port 8081
4. Troubleshoot jika ada error

File yang perlu diperhatikan:
- index.html (navbar, hero section, CTA buttons)
- pricing-section.html (pricing cards, redirect logic)
- package.json (scripts untuk run server)
```

---

## Prompt 2: Troubleshooting CORS

```
Saya mengalami CORS error saat landing page (port 8080) mencoba berkomunikasi dengan backend API (port 5001).

Setup saya:
- Landing page: http://localhost:8080 (static HTML)
- React app: http://localhost:8081 (Vite dev server)
- Backend API: http://localhost:5001 (Express.js)

Error yang muncul:
"Access to fetch at 'http://localhost:5001/api/...' from origin 'http://localhost:8080' has been blocked by CORS policy"

Backend menggunakan Express dengan cors middleware:
```javascript
app.use(cors({
  origin: ['http://localhost:8081', 'http://localhost:3000'],
  credentials: true
}));
```

Pertanyaan:
1. Apakah perlu menambahkan port 8080 ke CORS origin?
2. Bagaimana cara proper handling CORS untuk multi-port setup?
3. Apakah ada security concern yang perlu diperhatikan?

Tolong bantu troubleshoot dan berikan solusi terbaik.
```

---

## Prompt 3: Integrasi Pricing dengan React

```
Saya memiliki pricing section di landing page (port 8080) yang perlu redirect ke React app (port 8081) dengan membawa parameter pricing.

Current implementation di pricing-section.html:
- 13 tier paket (50-10000 tokenize)
- Toggle bulanan/tahunan (diskon 15% untuk tahunan)
- Button "Dapatkan Paket" di setiap tier

Yang perlu dilakukan:
1. Saat user klik "Dapatkan Paket", redirect ke:
   http://localhost:8081/subscribe?tier={tier}&files={files}&price={price}&annual={true/false}

2. Parameter yang dikirim:
   - tier: nomor tier (1-13)
   - files: jumlah tokenize (50, 100, 200, dst)
   - price: harga BULANAN (harga coret), bukan harga diskon
   - annual: true jika toggle tahunan aktif, false jika bulanan

3. React app akan:
   - Terima parameter via URL
   - Hitung diskon 15% jika annual=true
   - Tampilkan form subscribe dengan harga yang sudah dihitung

Contoh:
- User pilih tier 3 (100 tokenize, Rp25.000/bulan)
- Toggle tahunan aktif
- Redirect ke: http://localhost:8081/subscribe?tier=3&files=100&price=25000&annual=true
- React app hitung: 25000 * 12 * 0.85 = Rp255.000/tahun

Tolong bantu:
1. Implementasi JavaScript untuk handle button click
2. Build URL dengan parameter yang benar
3. Pastikan harga yang dikirim adalah harga bulanan (bukan diskon)
4. Handle edge cases (user belum pilih tier, dll)
```

---

## Prompt 4: Sync Pricing antara Port 8080 dan 8081

```
Saya memiliki 2 aplikasi yang perlu sync pricing:

1. Landing page (port 8080) - pricing-section.html
   - 13 tier paket
   - Harga dalam Rupiah
   - Toggle bulanan/tahunan

2. React app (port 8081) - Subscribe.tsx
   - Form subscribe
   - Terima parameter dari port 8080
   - Proses pembayaran via Midtrans

Problem:
Harga di kedua aplikasi tidak sync. Jika saya update harga di port 8080, harus manual update di port 8081 juga.

Tier pricing yang harus sama:
- Tier 1: 50 tokenize - Gratis
- Tier 2: 100 tokenize - Rp25.000/bulan
- Tier 3: 200 tokenize - Rp49.000/bulan
- Tier 4: 500 tokenize - Rp99.000/bulan
- Tier 5: 1000 tokenize - Rp149.000/bulan
- Tier 6: 2000 tokenize - Rp249.000/bulan
- Tier 7: 3000 tokenize - Rp349.000/bulan
- Tier 8: 4000 tokenize - Rp449.000/bulan
- Tier 9: 5000 tokenize - Rp549.000/bulan
- Tier 10: 6000 tokenize - Rp649.000/bulan
- Tier 11: 7000 tokenize - Rp749.000/bulan
- Tier 12: 8000 tokenize - Rp849.000/bulan
- Tier 13: 10000 tokenize - Rp999.000/bulan

Diskon tahunan: 15% (harga * 12 * 0.85)

Tolong bantu:
1. Update pricing di pricing-section.html (port 8080)
2. Update pricing di Subscribe.tsx (port 8081)
3. Pastikan semua tier sync dengan benar
4. Verifikasi perhitungan diskon tahunan
```

---

## Prompt 5: Dark Mode Implementation

```
Landing page saya (port 8080) perlu diubah ke dark mode untuk konsistensi dengan React app (port 8081).

Current state:
- Background: white/light colors
- Text: dark colors
- Cards: light backgrounds

Target dark mode:
- Background: dark (bg-background, bg-card)
- Text: light/white (text-foreground, text-muted-foreground)
- Cards: dark dengan border subtle
- Buttons: bright colors untuk active state
- Hover effects: subtle glow

Design system (dari React app):
- Background: hsl(222.2 84% 4.9%) - very dark blue
- Card: hsl(217.2 32.6% 17.5%) - dark blue-gray
- Foreground: hsl(210 40% 98%) - almost white
- Muted foreground: hsl(215 20.2% 65.1%) - light gray
- Primary: hsl(217.2 91.2% 59.8%) - bright blue
- Border: hsl(217.2 32.6% 17.5%) - dark border

Files yang perlu diupdate:
- index.html (inline styles atau classes)
- assets/css/main.css (global styles)
- pricing-section.html (pricing cards)

Tolong bantu:
1. Convert semua light colors ke dark mode
2. Pastikan text readable (contrast ratio minimal 4.5:1)
3. Update hover states untuk dark mode
4. Test di berbagai browser
```

---

## Prompt 6: Deploy ke Production

```
Saya ingin deploy landing page (port 8080) ke production.

Current setup (development):
- Local server: http://localhost:8080
- Redirect login: http://localhost:8081/login
- Redirect subscribe: http://localhost:8081/subscribe?...
- Backend API: http://localhost:5001

Production URLs:
- Landing page: https://raymaizing.com
- React app: https://app.raymaizing.com
- Backend API: https://api.raymaizing.com

Yang perlu dilakukan:
1. Update semua localhost URLs ke production URLs
2. Setup static hosting (Netlify/Vercel/GitHub Pages)
3. Configure custom domain
4. Setup SSL certificate
5. Update CORS di backend untuk production domain
6. Test redirect flow di production

Files yang perlu diupdate:
- index.html (login link)
- pricing-section.html (subscribe redirect)
- Backend CORS config

Tolong bantu:
1. Checklist pre-deployment
2. Step-by-step deployment process
3. Post-deployment verification
4. Rollback plan jika ada issue
```

---

## Prompt 7: Performance Optimization

```
Landing page saya (port 8080) perlu dioptimasi untuk performance.

Current metrics (Lighthouse):
- Performance: 75
- First Contentful Paint: 2.5s
- Largest Contentful Paint: 4.2s
- Total Blocking Time: 450ms

Issues:
1. Large images (tidak optimized)
2. Render-blocking CSS/JS
3. No lazy loading untuk images
4. No caching strategy

Assets:
- Hero video: 15MB
- Images: 50+ files, total 8MB
- CSS: 3 files, total 250KB
- JS: 5 files, total 180KB

Target metrics:
- Performance: 90+
- FCP: < 1.5s
- LCP: < 2.5s
- TBT: < 200ms

Tolong bantu:
1. Optimize images (WebP, compression)
2. Implement lazy loading
3. Minify CSS/JS
4. Setup caching headers
5. Defer non-critical JS
6. Optimize video loading
7. Implement CDN jika perlu
```

---

## 💡 Tips Menggunakan Prompt

1. **Copy prompt yang sesuai** dengan masalah yang dihadapi
2. **Sesuaikan detail** dengan kondisi actual project Anda
3. **Tambahkan error message** jika ada error spesifik
4. **Sertakan code snippet** yang relevan
5. **Jelaskan expected behavior** vs actual behavior

## 🎯 Best Practices

- Gunakan prompt yang spesifik dan detail
- Sertakan context lengkap (tech stack, setup, dll)
- Jelaskan apa yang sudah dicoba
- Tanyakan solusi alternatif jika ada
- Minta penjelasan untuk setiap solusi yang diberikan

---

**Note**: Prompt ini dirancang untuk AI assistant seperti Gemini, ChatGPT, Claude, atau Kiro. Sesuaikan dengan kebutuhan dan context project Anda.
