# Improvement Checklist

## 🟢 High Impact (Cepat & Berguna)

- [x] **#1 — Gambar Produk** — Tambah kolom `image` + upload file pada produk
- [x] **#2 — Pagination di semua list** — Produk, kategori, customer transactions pakai `paginate()`
- [x] **#3 — Search di halaman Home** — Customer bisa mencari produk dari halaman depan
- [x] **#4 — Update quantity di Cart** — Cart bisa ubah jumlah langsung dengan +/- buttons
- [x] **#5 — Halaman detail produk (`/product/{id}`)** — Halaman publik dengan gambar, info, add-to-cart

## 🟡 Medium Impact

- [ ] **#6 — Form Request Validation** — Pisah validasi ke `app/Http/Requests/` biar reusable
- [ ] **#7 — Seeder & Factory** — Data dummy untuk testing (users, categories, products, transactions)
- [x] **#8 — Slug update otomatis** — Saat edit kategori, slug ikut berubah
- [ ] **#9 — Notifikasi stok menipis** — Alert/notifikasi kalau stok produk di bawah threshold
- [ ] **#10 — Export Laporan (PDF/Excel)** — Export transaksi per periode

## 🔵 Low Impact / Nice-to-have

- [ ] **#11 — Unit & Feature Tests** — Tambah test untuk workflow utama
- [ ] **#12 — Frontend framework (Alpine.js)** — Ganti semua JS inline agar terstruktur
- [ ] **#13 — Role & Permission granular** — Policy/Gate untuk tiap aksi
- [x] **#14 — Soft Deletes** — Produk & kategori tidak hilang permanen
- [x] **#15 — Localization** — Konsistenkan Bahasa Indonesia di auth, layout, home, welcome
