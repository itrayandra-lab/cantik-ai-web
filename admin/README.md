# Raymaizing Admin Panel

Dashboard admin berbasis PHP native dengan MySQL.

## Struktur Folder

```
admin/
├── api/
│   └── menu.php          # Public API endpoint untuk mengambil menu
├── config/
│   ├── auth.php          # Autentikasi & session
│   ├── database.php      # Koneksi PDO
│   ├── helpers.php       # Fungsi utilitas
│   └── setup.sql         # SQL untuk membuat database & tabel
├── includes/
│   ├── header.php        # Layout header + sidebar
│   └── footer.php        # Layout footer
├── menus/
│   ├── index.php         # Daftar menu groups
│   ├── create.php        # Buat menu group baru
│   ├── edit.php          # Edit menu group
│   ├── items.php         # Kelola menu items & submenu
│   └── save-item.php     # Handler simpan menu item
├── users/
│   └── index.php         # Manajemen pengguna admin
├── uploads/
│   ├── menu-icons/       # Upload gambar icon menu
│   └── .htaccess         # Keamanan folder upload
├── activity.php          # Log aktivitas
├── index.php             # Dashboard utama
├── install.php           # Installer (hapus setelah install!)
├── login.php             # Halaman login
├── logout.php            # Proses logout
└── settings.php          # Pengaturan sistem
```

## Cara Install

### 1. Buat Database via Installer (Rekomendasi)
Akses: `http://localhost/admin/install.php`
- Isi konfigurasi database
- Klik Install
- **Hapus `install.php` setelah berhasil!**

### 2. Manual via SQL
```bash
mysql -u root -p < admin/config/setup.sql
```

### 3. Edit Konfigurasi Database
Edit file `admin/config/database.php`:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', 'your_password');
define('DB_NAME', 'raymaizing_db');
```

## Login Default
- **URL:** `http://localhost/admin/login.php`
- **Username:** `admin`
- **Password:** `password`

> ⚠️ Ganti password setelah login pertama!

## Fitur

### Menu Management
- Buat multiple **Menu Groups** (misal: Main Nav, Footer Nav)
- Tambah **Menu Items** dengan:
  - 🖼️ Upload gambar/icon sebelum judul menu
  - Dropdown **submenu** (nested 1 level)
  - URL, target (same tab / new tab)
  - CSS class kustom
  - Urutan (sort order)
  - Toggle aktif/nonaktif
- Drag & drop reorder (visual)

### API Endpoint
Ambil menu untuk frontend:
```
GET /admin/api/menu.php?slug=main-nav
```

Response JSON:
```json
{
  "menu": { "id": 1, "name": "Main Navigation", "slug": "main-nav" },
  "items": [
    {
      "id": 1,
      "title": "Beranda",
      "url": "/",
      "icon_image": "admin/uploads/menu-icons/img_xxx.png",
      "children": []
    }
  ]
}
```

## Keamanan
- Password di-hash dengan bcrypt (cost 12)
- PDO prepared statements (anti SQL injection)
- Session-based authentication
- Upload validation (type + size)
- PHP execution blocked di folder uploads
