# Marketplace With Laravel 13

Aplikasi marketplace berbasis **Laravel 13** dengan sistem role-based (Admin & Customer), manajemen produk, kategori, transaksi, dan keranjang belanja.

---

## Fitur

### Role Admin
- Dashboard statistik (total produk, terjual, stok, kategori)
- CRUD **Produk**
- CRUD **Kategori**
- CRUD **Transaksi** (pembelian & penjualan)
- Melihat data customer pada setiap transaksi

### Role Customer
- **Marketplace** — lihat semua produk + filter kategori
- **Keranjang** — tambah/hapus item, checkout bayar
- **Riwayat Transaksi** — lihat pembelian yang sudah dibayar

### Guest
- Halaman utama dengan statistik real & grid produk

### Umum
- Auth dengan **Laravel Sanctum**
- Role-based middleware (`admin` / customer)
- Data real-time: stok & sold terupdate otomatis saat transaksi

---

## Persyaratan

- Docker & Docker Compose
- PHP 8.5+ (via container)
- Composer (via container)
- Node.js & NPM (via container)

---

## Cara Menjalankan

### 1. Clone & masuk direktori

```bash
git clone <repo-url> marketplace-laravel13
cd marketplace-laravel13
```

### 2. Copy环境 file

```bash
cp .env.example .env
```

### 3. Start Docker containers

```bash
docker compose up -d
```

### 4. Install dependencies

```bash
docker compose exec laravel.test composer install
docker compose exec laravel.test npm install
```

### 5. Generate app key

```bash
docker compose exec laravel.test php artisan key:generate
```

### 6. Migrate & seed database

```bash
docker compose exec laravel.test php artisan migrate:fresh --seed
```

### 7. Build frontend assets

```bash
docker compose exec laravel.test npm run build
```

### 8. Akses aplikasi

Buka **http://localhost** di browser.

---

## Akun Default (Seeder)

| Role | Email | Password |
|------|-------|----------|
| Admin | `admin@example.com` | `password` |
| Customer | `customer@example.com` | `password` |

---

## Struktur Routes

### Web (`/`)
| URL | Method | Auth | Role | Keterangan |
|-----|--------|------|------|------------|
| `/` | GET | - | Guest | Halaman utama |
| `/login` | GET/POST | - | Guest | Login |
| `/register` | GET/POST | - | Guest | Register (role: customer) |

### Admin (`/admin/*`)
| URL | Method | Keterangan |
|-----|--------|------------|
| `/admin/dashboard` | GET | Statistik |
| `/admin/products` | GET/POST/PUT/DELETE | CRUD produk |
| `/admin/categories` | GET/POST/PUT/DELETE | CRUD kategori |
| `/admin/transactions` | GET/POST/DELETE | CRUD transaksi |

### Customer (`/customer/*`)
| URL | Method | Keterangan |
|-----|--------|------------|
| `/customer/dashboard` | GET | Marketplace + statistik pribadi |
| `/customer/cart` | GET/POST/DELETE | Keranjang belanja |
| `/customer/cart/checkout` | POST | Bayar semua item |
| `/customer/transactions` | GET | Riwayat transaksi |

---

## Tech Stack

- **Backend**: Laravel 13, PHP 8.5
- **Frontend**: Tailwind CSS v4, Vite
- **Database**: MySQL 8.4
- **Cache**: Redis
- **Auth**: Laravel Sanctum
- **Container**: Docker (Laravel Sail)

---

## Catatan

- Port MySQL host: **3307** (bukan 3306)
- Port Redis host: **6380**
- File hasil `artisan make:*` yang dibuat di dalam container mungkin perlu `chown` jika diedit dari host
- CSS di-build via Vite (`npm run build`), tidak menggunakan dev server
