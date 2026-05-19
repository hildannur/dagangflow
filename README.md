# DagangFlow

**DagangFlow** adalah aplikasi web berbasis Laravel untuk membantu owner UMKM mencatat penjualan dari berbagai channel, mengelola stok produk, mencatat pengeluaran, memantau customer, dan membaca laporan bisnis dalam satu dashboard sederhana.

Aplikasi ini dibuat untuk membantu bisnis kecil yang masih mencatat transaksi secara manual di WhatsApp, marketplace, catatan kertas, atau spreadsheet terpisah.

---

## Masalah yang Diselesaikan

Banyak owner UMKM mengalami masalah seperti:

- Penjualan dari WhatsApp, marketplace, offline, dan delivery tidak tercatat rapi.
- Stok produk sering tidak sinkron dengan transaksi.
- Pengeluaran bisnis tercampur dengan uang pribadi.
- Sulit tahu produk mana yang paling laku.
- Sulit menghitung omzet, biaya, dan estimasi laba.
- Data customer tidak tersimpan dengan baik.

DagangFlow membantu menyatukan semua data tersebut dalam satu sistem sederhana.

---

## Fitur Utama

### Dashboard Bisnis

Menampilkan ringkasan kondisi bisnis seperti:

- Omzet bulan ini
- Total pengeluaran
- Estimasi laba
- Jumlah transaksi
- Penjualan beberapa hari terakhir
- Channel penjualan terbaik
- Produk dengan stok rendah

### Manajemen Produk

Owner dapat mencatat dan mengelola produk, termasuk:

- Nama produk
- Kategori produk
- Harga jual
- Modal produk
- Stok tersedia
- Batas stok rendah

Stok akan otomatis berkurang ketika terjadi penjualan.

### Pencatatan Penjualan

Transaksi penjualan dapat dicatat berdasarkan channel seperti:

- Offline
- WhatsApp
- Marketplace
- Food delivery
- Channel lainnya

Setiap transaksi akan menghitung total penjualan, biaya platform, dan total bersih.

### Pencatatan Pengeluaran

Owner dapat mencatat pengeluaran bisnis berdasarkan kategori, seperti:

- Bahan baku
- Operasional
- Platform fee
- Marketing
- Packing
- Lainnya

### Manajemen Customer

DagangFlow menyediakan halaman untuk menyimpan data customer, termasuk:

- Nama customer
- Nomor HP
- Asal channel
- Total order
- Total belanja
- Tanggal order terakhir
- Catatan customer

### Laporan Bisnis

Halaman laporan membantu owner melihat performa bisnis berdasarkan periode tertentu, seperti:

- Hari ini
- 7 hari terakhir
- Bulan ini
- Bulan lalu
- Custom date range

Data laporan meliputi omzet, pengeluaran, biaya platform, estimasi laba, performa channel, produk terlaris, dan kategori pengeluaran terbesar.

### AI Insight

DagangFlow memiliki fitur insight bisnis berbasis AI untuk membantu owner membaca kondisi bisnis dan mendapatkan rekomendasi aksi yang lebih mudah dipahami.

---

## Teknologi yang Digunakan

- Laravel
- PHP
- Blade
- Tailwind CSS
- Vite
- SQLite / MySQL
- Gemini API untuk AI Insight

---

## Tujuan Project

Project ini dibuat sebagai aplikasi dashboard bisnis sederhana untuk UMKM, dengan fokus pada pencatatan operasional harian dan analisis bisnis yang mudah dipahami oleh owner non-teknis.

DagangFlow dirancang agar dapat dikembangkan menjadi produk SaaS untuk membantu UMKM mengelola bisnis digital mereka dengan lebih rapi.

---

## Status Pengembangan

Project masih dalam tahap pengembangan aktif.

Beberapa pengembangan berikutnya yang direncanakan:

- Perhitungan laba bersih yang lebih akurat menggunakan HPP
- Relasi customer dengan transaksi penjualan
- Export laporan ke PDF atau Excel
- Filter dan pencarian data
- Dashboard analytics yang lebih visual
- Onboarding user baru
- Role admin dan user
- Deployment guide

---

## Instalasi Lokal

Clone repository:

```bash
git clone https://github.com/hildannur/dagangflow.git
cd dagangflow