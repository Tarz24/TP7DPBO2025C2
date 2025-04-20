<?php
// view/navbar.php - Navigasi untuk semua halaman (lanjutan)
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link <?php echo $page == 'home' ? 'active' : ''; ?>" href="index.php">
                        <i class="fas fa-home"></i> Beranda
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $page == 'peralatan' ? 'active' : ''; ?>" href="index.php?page=peralatan">
                        <i class="fas fa-campground"></i> Peralatan
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $page == 'kategori' ? 'active' : ''; ?>" href="index.php?page=kategori">
                        <i class="fas fa-tags"></i> Kategori
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $page == 'penyewaan' ? 'active' : ''; ?>" href="index.php?page=penyewaan">
                        <i class="fas fa-clipboard-list"></i> Penyewaan
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>