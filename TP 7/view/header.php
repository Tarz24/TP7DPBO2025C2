<?php
// view/header.php - Header untuk semua halaman
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CampRent - Sistem Penyewaan Alat Kemah</title>
    <link rel="stylesheet" href="style.css">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome untuk ikon -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <header class="py-4">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1 class="site-title">CampRent</h1>
                    <p class="site-tagline">Sistem Penyewaan Alat Kemah</p>
                </div>
                <div class="col-md-6 text-end">
                    <form action="index.php?page=search" method="GET" class="search-form">
                        <input type="hidden" name="page" value="search">
                        <div class="input-group">
                            <input type="text" name="keyword" class="form-control" placeholder="Cari peralatan kemah..." required>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i> Cari
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </header>