# Setup Guide - Web Raymaizing (Port 8080)

## 📋 Deskripsi
Landing page untuk Raymaizing - Platform AI Assistant untuk otomatisasi bisnis. Website ini berjalan di **port 8080** dan terintegrasi dengan aplikasi React di **port 8081**.

## 🚀 Quick Start

### Prerequisites
- Node.js (v14 atau lebih tinggi)
- npm atau npx
- Git

### Instalasi

1. **Clone Repository**
```bash
git clone https://github.com/itrayandra-lab/web_raymaizing.git
cd web_raymaizing
```

2. **Install Dependencies (Optional)**
```bash
npm install
```
*Note: Project ini menggunakan `npx` sehingga tidak memerlukan instalasi dependencies lokal*

3. **Jalankan Development Server**
```bash
npx http-server -p 8080 -o index.html
```

Server akan berjalan di: `http://localhost:8080`

## 📁 Struktur Project

```
web_raymaizing/
├── assets/              # CSS, JS, images, fonts
│   ├── css/            # Stylesheet files
│   ├── js/             # JavaScript files
│   ├── images/         # Image assets
│   └── fonts/          # Font files
├── cdn-cgi/            # CDN cached files
├── index.html          # Homepage utama
├── pricing-section.html # Section pricing (included)
├── login.html          # Login page (legacy)
├── subscribe.html      # Subscribe page (legacy)
└── package.json        # NPM configuration
```

## 🔧 Konfigurasi

### Port Configuration
- **Default Port**: 8080
- **React App Port**: 8081 (untuk login & subscribe)
- **Backend API Port**: 5001

### Integrasi dengan React App (Port 8081)

Landing page ini redirect ke React app untuk:

1. **Login**: 
   - Link: `http://localhost:8081/login`
   - Location: Navbar menu "Login"

2. **Subscribe/Pricing**:
   - Link: `http://localhost:8081/subscribe?tier={tier}&files={files}&price={price}&annual={true/false}`
   - Location: Button "Dapatkan Paket" di pricing section
   - Parameters:
     - `tier`: Tier number (1-13)
     - `files`: Jumlah file/tokenize
     - `price`: Harga bulanan (harga coret)
     - `annual`: true untuk tahunan, false untuk bulanan

## 📝 Fitur Utama

### 1. Hero Section
- Video background
- CTA button "Mulai Gratis" (scroll ke pricing)

### 2. Features Section
- Showcase fitur-fitur AI
- Use cases untuk berbagai industri

### 3. Pricing Section
- 13 tier paket (50-10000 tokenize)
- Toggle bulanan/tahunan (diskon 15% untuk tahunan)
- Redirect ke port 8081 untuk checkout

### 4. Testimonials
- Customer reviews
- Case studies

### 5. FAQ Section
- Pertanyaan umum
- Accordion interface

## 🛠️ NPM Scripts

```bash
# Development server (port 8000 - default)
npm start

# Development server dengan no-cache
npm run dev

# Build CSS (minify)
npm run build:css

# Build JS (minify)
npm run build:js

# Build semua assets
npm run build

# Format code dengan Prettier
npm run format

# Lint JavaScript
npm run lint

# Run Lighthouse audit
npm run lighthouse
```

## 🔄 Workflow Development

### Menjalankan Full Stack

1. **Terminal 1 - Backend (Port 5001)**
```bash
cd express-backend
npm start
```

2. **Terminal 2 - Landing Page (Port 8080)**
```bash
cd web_raymaizing
npx http-server -p 8080 -o index.html
```

3. **Terminal 3 - React App (Port 8081)**
```bash
cd reactjs
npm run dev -- --port 8081
```

### Testing Flow
1. Buka `http://localhost:8080`
2. Klik "Login" → redirect ke `http://localhost:8081/login`
3. Klik "Dapatkan Paket" → redirect ke `http://localhost:8081/subscribe?tier=...`

## 📤 Deployment

### Push ke GitHub
```bash
git add .
git commit -m "feat: your commit message"
git push origin main
```

### Deploy ke Production
- Hosting: Static hosting (Netlify, Vercel, GitHub Pages)
- Build: Tidak perlu build process (pure HTML/CSS/JS)
- Environment: Update URL dari `localhost:8081` ke production URL

## 🎨 Customization

### Mengubah Pricing
Edit file: `pricing-section.html`
- Update harga di data attributes
- Sync dengan React app (`reactjs/src/pages/Subscribe.tsx`)

### Mengubah Navbar
Edit file: `index.html`
- Section: `<nav>` dengan class `navbar`
- Menu items di dalam `navbar__link`

### Mengubah Hero Section
Edit file: `index.html`
- Section: `#hero-video`
- Video source: `assets/videos/`

## 🐛 Troubleshooting

### Port 8080 sudah digunakan
```bash
# Gunakan port lain
npx http-server -p 8090 -o index.html
```

### CORS Error
- Pastikan backend (port 5001) sudah running
- Check CORS configuration di `express-backend/server.js`

### Redirect tidak bekerja
- Pastikan React app (port 8081) sudah running
- Check URL di browser console

## 📚 Resources

- Repository: https://github.com/itrayandra-lab/web_raymaizing
- Backend API: `express-backend/` folder
- React App: `reactjs/` folder
- Documentation: `SETUP_LOGIN_PAYMENT.md`

## 🔐 Security Notes

- Jangan commit `.env` files
- Update production URLs sebelum deploy
- Gunakan HTTPS di production
- Validate semua user inputs di backend

## 📞 Support

Untuk pertanyaan atau issues, silakan buat issue di GitHub repository.

---

**Last Updated**: February 2026
**Version**: 1.0.0
**Maintainer**: Raymaizing Team
