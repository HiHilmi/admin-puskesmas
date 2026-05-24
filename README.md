# 🏥 Sistem Informasi Puskesmas (Web-Based)

Sistem Informasi terintegrasi untuk mengelola data operasional Puskesmas atau Klinik Kesehatan. Proyek ini dibangun menggunakan **PHP Native, MySQL, dan Bootstrap 5**, serta mengimplementasikan berbagai konsep *Database Management System* (RDBMS) tingkat lanjut.

## ✨ Fitur Utama

Sistem ini memiliki fitur *Full CRUD* (Create, Read, Update, Delete) pada setiap modulnya:
- **👨‍⚕️ Manajemen Data Pasien:** Pendaftaran pasien baru dan pencatatan rekam medis.
- **📅 Jadwal Dokter & Janji Temu (Appointment):** Pengelolaan jadwal praktik dokter dan pendaftaran antrian pasien.
- **🩺 Rekam Medis:** Pencatatan keluhan dan diagnosa pasien yang otomatis terhubung dengan sistem tagihan.
- **💊 Inventory Obat:** Manajemen stok dan harga obat.
- **💳 Billing & Kasir:** Sistem pembayaran dan pembuatan invoice (tagihan) pasien.

## 🛠️ Teknologi yang Digunakan

- **Front-End:** HTML5, CSS3, Bootstrap 5, FontAwesome Icons.
- **Back-End:** PHP 8.x (Native).
- **Database:** MySQL / MariaDB (via XAMPP).
- **Library Tambahan:** DataTables (untuk *Search, Sort, & Pagination* tabel interaktif).

## 🗄️ Spesifikasi Database

Database pada sistem ini (`puskesmas_db`) dirancang untuk memenuhi kriteria kompleksitas struktural, yang terdiri dari:
1. **10 Tabel Relasional:** `pasien`, `dokter`, `spesialisasi`, `jadwal_dokter`, `appointment`, `rekam_medis`, `obat`, `resep_obat`, `pembayaran`, dan `users`.
2. **Relasi Antar Tabel:** Menerapkan *One-to-One*, *One-to-Many*, dan *Many-to-Many* (menggunakan *Foreign Keys* dengan aksi `CASCADE` dan `SET NULL`).
3. **Aggregate Functions:** Menggunakan `COUNT()` dan `SUM()` untuk menampilkan statistik total antrian dan stok obat di halaman Dashboard.
4. **SQL VIEW:** Menggunakan *View* (`view_laporan_appointment` dan `view_pendapatan`) yang mengkombinasikan banyak tabel menggunakan *JOIN* untuk mempermudah pelaporan.

## 🚀 Cara Instalasi & Menjalankan Proyek

1. **Clone Repository:**
   ```bash
   git clone https://github.com/HiHilmi/admin-puskesmas.git
