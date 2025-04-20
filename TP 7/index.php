<?php
// index.php - File utama aplikasi penyewaan alat kemah

// Memulai session
session_start();

// Load konfigurasi database
require_once 'config/database.php';

// Load class yang dibutuhkan
require_once 'class/Peralatan.php';
require_once 'class/Kategori.php';
require_once 'class/Penyewaan.php';

// Default halaman
$page = isset($_GET['page']) ? $_GET['page'] : 'home';

// Header
include 'view/header.php';

// Navigasi
include 'view/navbar.php';

// Konten utama
switch ($page) {
    case 'home':
        include 'view/home.php';
        break;
    
    // Peralatan
    case 'peralatan':
        include 'view/peralatan/index.php';
        break;
    case 'peralatan_add':
        include 'view/peralatan/add.php';
        break;
    case 'peralatan_edit':
        include 'view/peralatan/edit.php';
        break;
    case 'peralatan_delete':
        include 'view/peralatan/delete.php';
        break;
    
    // Kategori
    case 'kategori':
        include 'view/kategori/index.php';
        break;
    case 'kategori_add':
        include 'view/kategori/add.php';
        break;
    case 'kategori_edit':
        include 'view/kategori/edit.php';
        break;
    case 'kategori_delete':
        include 'view/kategori/delete.php';
        break;
    
    // Penyewaan
    case 'penyewaan':
        include 'view/penyewaan/index.php';
        break;
    case 'penyewaan_add':
        include 'view/penyewaan/add.php';
        break;
    case 'penyewaan_edit':
        include 'view/penyewaan/edit.php';
        break;
    case 'penyewaan_delete':
        include 'view/penyewaan/delete.php';
        break;
    
    // Search
    case 'search':
        include 'view/search.php';
        break;
    
    default:
        include 'view/home.php';
        break;
}

// Footer
include 'view/footer.php';
?>