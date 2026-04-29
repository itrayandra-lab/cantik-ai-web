# 📚 Panduan Setup Admin Panel Raymaizing

Dashboard admin berbasis **PHP Native + MySQL** dengan fitur lengkap untuk mengelola header menu website, termasuk upload gambar icon dan dropdown submenu.

---

## 🚀 Cara Install

### Langkah 1: Persiapan Server
Pastikan server Anda memiliki:
- ✅ PHP 7.4+ (rekomendasi PHP 8.0+)
- ✅ MySQL 5.7+ atau MariaDB 10.3+
- ✅ Apache/Nginx dengan mod_rewrite
- ✅ Extension PHP: PDO, PDO_MySQL, GD/Imagick (untuk upload gambar)

### Langkah 2: Install Database

#### Opsi A: Via Web Installer (Mudah)
1. Buka browser: `http://localhost/admin/install.php`
2. Isi form konfigurasi database:
   - **Host:** `localhost`
   - **Username:** `root`
   - **Password:** (kosongkan jika tidak ada)
   - **Database Name:** `raymaizing_db`
3. Klik **Install Sekarang**
4. ⚠️ **PENTING:** Hapus file `admin/install.php` setelah instalasi berhasil!

#### Opsi B: Manual via MySQL Command
```bash
# Login ke MySQL
mysql -u root -p

# Import SQL
source admin/config/setup.sql

# Atau via command line langsung
mysql -u root -p < admin/config/setup.sql
```

### Langkah 3: Konfigurasi Database (Jika Manual)
Edit file `admin/config/database.php`:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', 'your_password_here');
define('DB_NAME', 'raymaizing_db');
```

### Langkah 4: Set Permission Folder Upload
```bash
chmod -R 755 admin/uploads
chmod -R 777 admin/uploads/menu-icons
```

### Langkah 5: Login ke Dashboard
- **URL:** `http://localhost/admin/login.php`
- **Username:** `admin`
- **Password:** `password`

> ⚠️ **Ganti password default setelah login pertama!**

---

## 📋 Struktur Database

Database `raymaizing_db` berisi 4 tabel utama:

### 1. `admin_users`
Menyimpan data admin yang bisa login ke dashboard.
- `id`, `username`, `email`, `password` (bcrypt)
- `full_name`, `role` (superadmin/admin/editor)
- `avatar`, `is_active`, `last_login`

### 2. `nav_menus`
Menu groups (container untuk menu items).
- `id`, `name`, `slug`, `description`
- `is_active`, `created_at`, `updated_at`

### 3. `nav_menu_items`
Menu items dengan support submenu (nested 1 level).
- `id`, `menu_id`, `parent_id` (NULL = top-level)
- `title`, `url`, `target` (_self/_blank)
- `icon_image`, `icon_alt` (path gambar icon)
- `css_class`, `sort_order`, `is_active`

### 4. `activity_log`
Log semua aktivitas admin.
- `id`, `user_id`, `action`, `description`
- `ip_address`, `created_at`

---

## 🎯 Fitur Utama

### 1. Menu Management

#### Menu Groups
Buat multiple menu groups untuk berbagai lokasi di website:
- **Main Navigation** (header utama)
- **Footer Menu** (footer links)
- **Mobile Menu** (khusus mobile)
- dll.

**Cara Buat Menu Group:**
1. Dashboard → **Menu Groups**
2. Klik **➕ Tambah Menu Group**
3. Isi:
   - **Nama:** Contoh "Main Navigation"
   - **Slug:** Auto-generate dari nama (contoh: `main-navigation`)
   - **Deskripsi:** Opsional
4. Klik **💾 Simpan**

#### Menu Items dengan Icon & Submenu

**Fitur Lengkap:**
- ✅ Upload **gambar/icon** sebelum judul menu
- ✅ Dropdown **submenu** (nested 1 level)
- ✅ URL internal (`/beranda`) atau eksternal (`https://...`)
- ✅ Target: buka di tab sama atau tab baru
- ✅ CSS class kustom untuk styling
- ✅ Drag & drop untuk reorder
- ✅ Toggle aktif/nonaktif

**Cara Tambah Menu Item:**
1. Dashboard → **Menu Items**
2. Pilih menu group dari dropdown
3. Klik **➕ Tambah Menu Item**
4. Isi form:
   - **Icon/Gambar:** Klik atau drag gambar (JPG/PNG/SVG/WEBP, maks 2MB)
   - **Alt Text:** Deskripsi gambar untuk aksesibilitas
   - **Judul Menu:** Contoh "Beranda"
   - **URL/Link:** Pilih tipe URL:
     - **🏠 Internal:** Path halaman di website (contoh: `beranda` → `/beranda`)
     - **🌐 External:** Link ke website lain (contoh: `https://google.com`)
     - **⚓ Anchor:** Scroll ke section dengan ID (contoh: `section-harga` → `#section-harga`)
     - **✏️ Custom:** URL bebas (javascript:, mailto:, tel:, dll)
   - **Buka di:** Tab sama / Tab baru (auto-set untuk external)
   - **Urutan:** Angka untuk sorting (0 = paling atas)
   - **CSS Class:** Opsional, contoh `nav-highlight`
5. Klik **💾 Simpan**

**Cara Tambah Submenu:**
1. Pada menu item yang sudah ada, klik tombol **➕** (Tambah Submenu)
2. Isi form sama seperti menu item biasa
3. Submenu akan muncul sebagai dropdown di bawah parent menu

**Contoh Struktur:**
```
📋 Main Navigation
  ├─ 🏠 Beranda (/)
  ├─ 🎯 Fitur (/fitur)
  │   ├─ AI Assistant (/fitur/ai-assistant)
  │   ├─ Otomatisasi (/fitur/otomatisasi)
  │   └─ Integrasi (/fitur/integrasi)
  ├─ 💰 Harga (/pricing)
  └─ 📞 Kontak (/kontak)
```

### 2. User Management
Kelola admin yang bisa akses dashboard.

**Role:**
- **Superadmin:** Full access
- **Admin:** Manage content
- **Editor:** Edit only

**Cara Tambah User:**
1. Dashboard → **Pengguna**
2. Isi form di sidebar kanan
3. Klik **💾 Buat Pengguna**

### 3. Activity Log
Semua aktivitas admin tercatat otomatis:
- Login/logout
- Create/edit/delete menu
- Create/edit/delete user
- IP address & timestamp

---

## 🔌 API Endpoint

### Get Menu by Slug
Ambil menu untuk ditampilkan di frontend.

**Endpoint:**
```
GET /admin/api/menu.php?slug=main-nav
```

**Response JSON:**
```json
{
  "menu": {
    "id": 1,
    "name": "Main Navigation",
    "slug": "main-nav"
  },
  "items": [
    {
      "id": 1,
      "title": "Beranda",
      "url": "/",
      "target": "_self",
      "icon_image": "admin/uploads/menu-icons/img_abc123.png",
      "icon_alt": "Home icon",
      "css_class": "nav-home",
      "sort_order": 0,
      "children": []
    },
    {
      "id": 2,
      "title": "Fitur",
      "url": "/fitur",
      "target": "_self",
      "icon_image": "admin/uploads/menu-icons/img_def456.png",
      "icon_alt": "Features icon",
      "css_class": null,
      "sort_order": 1,
      "children": [
        {
          "id": 3,
          "title": "AI Assistant",
          "url": "/fitur/ai-assistant",
          "target": "_self",
          "icon_image": null,
          "icon_alt": null,
          "css_class": null,
          "sort_order": 0
        }
      ]
    }
  ]
}
```

**Contoh Implementasi di Frontend (JavaScript):**
```javascript
// Fetch menu dari API
fetch('/admin/api/menu.php?slug=main-nav')
  .then(res => res.json())
  .then(data => {
    const menuContainer = document.getElementById('main-menu');
    
    data.items.forEach(item => {
      // Buat menu item
      const li = document.createElement('li');
      
      // Tambahkan icon jika ada
      if (item.icon_image) {
        const icon = document.createElement('img');
        icon.src = '/' + item.icon_image;
        icon.alt = item.icon_alt || '';
        icon.className = 'menu-icon';
        li.appendChild(icon);
      }
      
      // Tambahkan link
      const link = document.createElement('a');
      link.href = item.url;
      link.target = item.target;
      link.textContent = item.title;
      if (item.css_class) link.className = item.css_class;
      li.appendChild(link);
      
      // Tambahkan submenu jika ada
      if (item.children && item.children.length > 0) {
        const submenu = document.createElement('ul');
        submenu.className = 'submenu';
        
        item.children.forEach(child => {
          const subLi = document.createElement('li');
          const subLink = document.createElement('a');
          subLink.href = child.url;
          subLink.target = child.target;
          subLink.textContent = child.title;
          subLi.appendChild(subLink);
          submenu.appendChild(subLi);
        });
        
        li.appendChild(submenu);
      }
      
      menuContainer.appendChild(li);
    });
  });
```

---

## 🔒 Keamanan

### Password
- Hash menggunakan **bcrypt** dengan cost 12
- Tidak pernah disimpan plain text

### SQL Injection
- Semua query menggunakan **PDO prepared statements**
- Input di-sanitize dengan `htmlspecialchars()`

### Upload Security
- Validasi tipe file (hanya gambar)
- Validasi ukuran (maks 2MB)
- PHP execution di-block di folder uploads via `.htaccess`
- Filename di-randomize dengan `uniqid()`

### Session
- Session-based authentication
- Auto-logout jika tidak aktif
- IP address tracking di activity log

---

## 🛠️ Troubleshooting

### Error: "Database connection failed"
**Solusi:**
1. Cek kredensial di `admin/config/database.php`
2. Pastikan MySQL service running
3. Cek user MySQL punya akses ke database

### Error: "Failed to upload image"
**Solusi:**
1. Cek permission folder: `chmod 777 admin/uploads/menu-icons`
2. Cek PHP upload settings di `php.ini`:
   ```ini
   upload_max_filesize = 10M
   post_max_size = 10M
   ```
3. Restart web server

### Menu tidak muncul di frontend
**Solusi:**
1. Pastikan menu group **is_active = 1**
2. Pastikan menu items **is_active = 1**
3. Cek API endpoint: `/admin/api/menu.php?slug=your-slug`
4. Cek console browser untuk error JavaScript

### Lupa Password Admin
**Solusi:**
```sql
-- Login ke MySQL
mysql -u root -p raymaizing_db

-- Reset password ke "newpassword123"
UPDATE admin_users 
SET password = '$2y$12$LQv3c1yqBWVHxkd0LHAkCOYz6TtxMQJqhN8/LewY5GyYIxIvT5.3u' 
WHERE username = 'admin';
```

---

## 📞 Support

Jika ada pertanyaan atau masalah:
1. Cek **Activity Log** untuk error tracking
2. Cek PHP error log: `/var/log/apache2/error.log` atau `php_error.log`
3. Pastikan semua requirement terpenuhi

---

## 📝 Changelog

### v1.0.0 (2026-04-28)
- ✅ Menu Groups management
- ✅ Menu Items dengan icon upload
- ✅ Dropdown submenu (nested 1 level)
- ✅ User management dengan role
- ✅ Activity logging
- ✅ Public API endpoint
- ✅ Responsive dashboard design
- ✅ Security hardening

---

**© 2026 Raymaizing. All rights reserved.**
