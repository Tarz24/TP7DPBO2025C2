<?php
// view/home.php - Halaman utama (beranda)

// Inisialisasi objek
$peralatanObj = new Peralatan($db);
$kategoriObj = new Kategori($db);

// Ambil data
$peralatan_terbaru = $peralatanObj->getAll();
$peralatan_terbaru = array_slice($peralatan_terbaru, 0, 6); // Ambil 6 peralatan terbaru
$kategori_list = $kategoriObj->getAll();
?>

<section class="hero bg-light p-4 rounded mb-4">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h2>Selamat Datang di CampRent</h2>
            <p class="lead">Sistem penyewaan alat kemah terlengkap untuk kebutuhan camping dan outdoor activity Anda.</p>
            <p>Kami menyediakan berbagai peralatan kemah dengan kualitas terbaik dan harga terjangkau. Mulai dari tenda, sleeping bag, matras, kompor portable, dan perlengkapan kemah lainnya.</p>
            <a href="index.php?page=peralatan" class="btn btn-primary">Lihat Peralatan <i class="fas fa-arrow-right"></i></a>
        </div>
        <div class="col-md-4 text-center">
            <i class="fas fa-campground fa-5x text-primary"></i>
        </div>
    </div>
</section>

<section class="mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Peralatan Terbaru</h3>
        <a href="index.php?page=peralatan" class="btn btn-outline-primary btn-sm">Lihat Semua</a>
    </div>
    
    <div class="row">
        <?php foreach($peralatan_terbaru as $item): ?>
        <div class="col-md-4 mb-3">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title"><?php echo htmlspecialchars($item['nama']); ?></h5>
                    <h6 class="card-subtitle mb-2 text-muted"><?php echo htmlspecialchars($item['kategori_nama']); ?></h6>
                    <p class="card-text">
                        <?php echo htmlspecialchars(substr($item['deskripsi'], 0, 100) . (strlen($item['deskripsi']) > 100 ? '...' : '')); ?>
                    </p>
                    <p>
                        <strong>Harga Sewa:</strong> Rp <?php echo number_format($item['harga_sewa'], 0, ',', '.'); ?> /hari<br>
                        <strong>Stok:</strong> <?php echo $item['stok']; ?> unit<br>
                        <strong>Status:</strong> 
                        <?php if($item['status'] == 'tersedia'): ?>
                            <span class="badge bg-success">Tersedia</span>
                        <?php elseif($item['status'] == 'dipinjam'): ?>
                            <span class="badge bg-warning">Sedang Dipinjam</span>
                        <?php else: ?>
                            <span class="badge bg-secondary">Pemeliharaan</span>
                        <?php endif; ?>
                    </p>
                </div>
                <div class="card-footer">
                    <a href="index.php?page=peralatan" class="btn btn-sm btn-primary">Lihat Detail</a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<section class="mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Kategori Peralatan</h3>
        <a href="index.php?page=kategori" class="btn btn-outline-primary btn-sm">Lihat Semua</a>
    </div>
    
    <div class="row">
        <?php foreach($kategori_list as $kategori): ?>
        <div class="col-md-3 mb-3">
            <div class="card h-100">
                <div class="card-body text-center">
                    <h5 class="card-title"><?php echo htmlspecialchars($kategori['nama']); ?></h5>
                    <p class="card-text">
                        <?php echo htmlspecialchars(substr($kategori['deskripsi'], 0, 80) . (strlen($kategori['deskripsi']) > 80 ? '...' : '')); ?>
                    </p>
                </div>
                <div class="card-footer text-center">
                    <a href="index.php?page=peralatan&kategori_id=<?php echo $kategori['id']; ?>" class="btn btn-sm btn-primary">Lihat Peralatan</a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<section class="how-to-rent bg-light p-4 rounded">
    <h3 class="mb-3">Cara Penyewaan</h3>
    <div class="row">
        <div class="col-md-3 mb-3 text-center">
            <div class="step-box p-3 rounded bg-white shadow-sm h-100">
                <i class="fas fa-search fa-2x text-primary mb-2"></i>
                <h5>1. Pilih Peralatan</h5>
                <p>Pilih peralatan kemah yang Anda butuhkan dari daftar</p>
            </div>
        </div>
        <div class="col-md-3 mb-3 text-center">
            <div class="step-box p-3 rounded bg-white shadow-sm h-100">
                <i class="fas fa-calendar-alt fa-2x text-primary mb-2"></i>
                <h5>2. Isi Data</h5>
                <p>Isi data diri dan tentukan tanggal penyewaan</p>
            </div>
        </div>
        <div class="col-md-3 mb-3 text-center">
            <div class="step-box p-3 rounded bg-white shadow-sm h-100">
                <i class="fas fa-file-invoice fa-2x text-primary mb-2"></i>
                <h5>3. Konfirmasi</h5>
                <p>Konfirmasi pesanan dan metode pembayaran</p>
            </div>
        </div>
        <div class="col-md-3 mb-3 text-center">
            <div class="step-box p-3 rounded bg-white shadow-sm h-100">
                <i class="fas fa-campground fa-2x text-primary mb-2"></i>
                <h5>4. Ambil Peralatan</h5>
                <p>Ambil peralatan dan nikmati camping Anda</p>
            </div>
        </div>
    </div>
</section>