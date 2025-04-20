-- database/setup.sql - Untuk membuat database dan tabel

CREATE DATABASE IF NOT EXISTS camping_rental;
USE camping_rental;

-- Tabel Kategori (untuk mengelompokkan peralatan)
CREATE TABLE IF NOT EXISTS kategori (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nama VARCHAR(100) NOT NULL,
    deskripsi TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabel Peralatan (untuk menyimpan data peralatan kemah)
CREATE TABLE IF NOT EXISTS peralatan (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nama VARCHAR(100) NOT NULL,
    kategori_id INT NOT NULL,
    stok INT NOT NULL DEFAULT 0,
    harga_sewa DECIMAL(10,2) NOT NULL,
    deskripsi TEXT,
    status ENUM('tersedia', 'dipinjam', 'pemeliharaan') DEFAULT 'tersedia',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (kategori_id) REFERENCES kategori(id) ON DELETE CASCADE
);

-- Tabel Penyewa (untuk menyimpan data peminjam)
CREATE TABLE IF NOT EXISTS penyewa (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    telepon VARCHAR(15) NOT NULL,
    alamat TEXT,
    ktp VARCHAR(20) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabel Penyewaan (untuk mencatat transaksi penyewaan)
CREATE TABLE IF NOT EXISTS penyewaan (
    id INT PRIMARY KEY AUTO_INCREMENT,
    penyewa_id INT NOT NULL,
    tanggal_mulai DATE NOT NULL,
    tanggal_selesai DATE NOT NULL,
    total_harga DECIMAL(10,2) NOT NULL,
    status ENUM('booking', 'berjalan', 'selesai', 'batal') DEFAULT 'booking',
    catatan TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (penyewa_id) REFERENCES penyewa(id) ON DELETE CASCADE
);

-- Tabel Detail Penyewaan (untuk mencatat item yang disewa)
CREATE TABLE IF NOT EXISTS detail_penyewaan (
    id INT PRIMARY KEY AUTO_INCREMENT,
    penyewaan_id INT NOT NULL,
    peralatan_id INT NOT NULL,
    jumlah INT NOT NULL,
    harga_sewa DECIMAL(10,2) NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    status ENUM('disewa', 'dikembalikan', 'rusak') DEFAULT 'disewa',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (penyewaan_id) REFERENCES penyewaan(id) ON DELETE CASCADE,
    FOREIGN KEY (peralatan_id) REFERENCES peralatan(id) ON DELETE CASCADE
);

-- Contoh data awal untuk kategori
INSERT INTO kategori (nama, deskripsi) VALUES 
('Tenda', 'Berbagai ukuran tenda untuk camping'),
('Sleeping Gear', 'Sleeping bag, matras, dan peralatan tidur lainnya'),
('Peralatan Masak', 'Kompor, panci, dan peralatan masak outdoor'),
('Perlengkapan Pendukung', 'Lampu, pisau, dan perlengkapan camping lainnya');

-- Contoh data awal untuk peralatan
INSERT INTO peralatan (nama, kategori_id, stok, harga_sewa, deskripsi, status) VALUES 
('Tenda Dome 2 Orang', 1, 10, 50000, 'Tenda dome kapasitas 2 orang, waterproof', 'tersedia'),
('Tenda Dome 4 Orang', 1, 8, 75000, 'Tenda dome kapasitas 4 orang, waterproof double layer', 'tersedia'),
('Sleeping Bag', 2, 20, 20000, 'Sleeping bag untuk suhu dingin', 'tersedia'),
('Matras Camping', 2, 15, 15000, 'Matras untuk camping, nyaman dan anti air', 'tersedia'),
('Kompor Portable', 3, 12, 25000, 'Kompor portable untuk camping', 'tersedia'),
('Headlamp', 4, 25, 10000, 'Lampu kepala LED tahan air', 'tersedia');