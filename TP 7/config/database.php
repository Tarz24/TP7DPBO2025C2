<?php
// config/database.php - Konfigurasi database

// Konfigurasi database
define('DB_HOST', 'localhost');
define('DB_NAME', 'camping_rental');
define('DB_USER', 'root');
define('DB_PASS', '');

// Membuat koneksi menggunakan PDO
try {
    $db = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    echo "Koneksi database gagal: " . $e->getMessage();
    exit;
}