-- 1. Tabel Spesialisasi (Master)
CREATE TABLE spesialisasi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_spesialisasi VARCHAR(50) NOT NULL
);

-- 2. Tabel Dokter (Relasi One to Many dengan Spesialisasi)
CREATE TABLE dokter (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_dokter VARCHAR(100) NOT NULL,
    id_spesialisasi INT,
    telp VARCHAR(15),
    FOREIGN KEY (id_spesialisasi) REFERENCES spesialisasi(id) ON DELETE SET NULL
);

-- 3. Tabel Pasien
CREATE TABLE pasien (
    id INT AUTO_INCREMENT PRIMARY KEY,
    no_rm VARCHAR(20) UNIQUE NOT NULL,
    nama_pasien VARCHAR(100) NOT NULL,
    tgl_lahir DATE,
    alamat TEXT,
    telp VARCHAR(15)
);

-- 4. Tabel Jadwal Dokter (Relasi One to Many dengan Dokter)
CREATE TABLE jadwal_dokter (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_dokter INT,
    hari VARCHAR(20),
    jam_mulai TIME,
    jam_selesai TIME,
    FOREIGN KEY (id_dokter) REFERENCES dokter(id) ON DELETE CASCADE
);

-- 5. Tabel Appointment (Relasi Many to Many antara Pasien & Jadwal)
CREATE TABLE appointment (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_pasien INT,
    id_jadwal INT,
    tgl_appointment DATE,
    status ENUM('Menunggu', 'Selesai', 'Batal') DEFAULT 'Menunggu',
    FOREIGN KEY (id_pasien) REFERENCES pasien(id) ON DELETE CASCADE,
    FOREIGN KEY (id_jadwal) REFERENCES jadwal_dokter(id) ON DELETE CASCADE
);

-- 6. Tabel Rekam Medis (Relasi One to One dengan Appointment)
CREATE TABLE rekam_medis (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_appointment INT UNIQUE,
    keluhan TEXT,
    diagnosa TEXT,
    tgl_periksa DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_appointment) REFERENCES appointment(id) ON DELETE CASCADE
);

-- 7. Tabel Obat (Inventory)
CREATE TABLE obat (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_obat VARCHAR(100) NOT NULL,
    stok INT NOT NULL,
    harga DECIMAL(10,2) NOT NULL
);

-- 8. Tabel Resep Obat (Relasi Many to Many antara Rekam Medis & Obat)
CREATE TABLE resep_obat (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_rekam_medis INT,
    id_obat INT,
    jumlah INT,
    dosis VARCHAR(50),
    FOREIGN KEY (id_rekam_medis) REFERENCES rekam_medis(id) ON DELETE CASCADE,
    FOREIGN KEY (id_obat) REFERENCES obat(id) ON DELETE CASCADE
);

-- 9. Tabel Pembayaran (Billing - Relasi One to One dengan Rekam Medis)
CREATE TABLE pembayaran (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_rekam_medis INT UNIQUE,
    total_biaya DECIMAL(10,2),
    status_bayar ENUM('Belum Lunas', 'Lunas') DEFAULT 'Belum Lunas',
    tgl_bayar DATETIME NULL,
    FOREIGN KEY (id_rekam_medis) REFERENCES rekam_medis(id) ON DELETE CASCADE
);

-- 10. Tabel Users (Untuk Login Pegawai/Admin)
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('Admin', 'Dokter', 'Apoteker') DEFAULT 'Admin'
);

-- ==========================================
-- IMPLEMENTASI AGGREGATE FUNCTION & VIEW
-- ==========================================

-- VIEW 1: Menampilkan Laporan Appointment Lengkap (Menerapkan JOIN banyak tabel)
CREATE VIEW view_laporan_appointment AS
SELECT 
    a.id AS id_appointment, a.tgl_appointment, a.status,
    p.nama_pasien, p.no_rm,
    d.nama_dokter, s.nama_spesialisasi,
    j.jam_mulai, j.jam_selesai
FROM appointment a
JOIN pasien p ON a.id_pasien = p.id
JOIN jadwal_dokter j ON a.id_jadwal = j.id
JOIN dokter d ON j.id_dokter = d.id
JOIN spesialisasi s ON d.id_spesialisasi = s.id;

-- VIEW 2: Laporan Pendapatan (Menerapkan Aggregate SUM)
CREATE VIEW view_pendapatan AS
SELECT 
    DATE(tgl_bayar) as tanggal,
    COUNT(id) as total_transaksi,
    SUM(total_biaya) as total_pendapatan
FROM pembayaran
WHERE status_bayar = 'Lunas'
GROUP BY DATE(tgl_bayar);

-- Insert Dummy Data Pasien untuk Testing
INSERT INTO pasien (no_rm, nama_pasien, tgl_lahir, alamat, telp) VALUES 
('RM001', 'Budi Santoso', '1990-05-12', 'Jl. Merdeka No 1', '08123456789'),
('RM002', 'Siti Aminah', '1985-11-20', 'Jl. Mawar No 5', '08198765432');