<?php
// view/peralatan/index.php - Halaman daftar peralatan

// Inisialisasi objek
$peralatanObj = new Peralatan($db);
$kategoriObj = new Kategori($db);

// Filter berdasarkan kategori jika ada
$kategori_id = isset($_GET['kategori_id']) ? $_GET['kategori_id'] : null;
if ($kategori_id) {
    $peralatan_list = $peralatanObj->getByKategori($kategori_id);
    $kategori_info = $kategoriObj->getById($kategori_id);
} else {
    $peralatan_list = $peralatanObj->getAll();
}

// Ambil semua kategori untuk filter
$kategori_list = $kategoriObj->getAll();
?>

<div class="card mb-4">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h4 class="mb-0">
            <i class="fas fa-campground"></i> 
            <?php if (isset($kategori_info)): ?>
                Peralatan Kategori: <?php echo htmlspecialchars($kategori_info['nama']); ?>
            <?php else: ?>
                Daftar Peralatan
            <?php endif; ?>
        </h4>
        <a href="index.php?page=peralatan_add" class="btn btn-light"><i class="fas fa-plus"></i> Tambah Peralatan</a>
    </div>
    <div class="card-body">
        <!-- Filter Kategori -->
        <div class="row mb-3">
            <div class="col-md-6">
                <form action="index.php" method="GET">
                    <input type="hidden" name="page" value="peralatan">
                    <div class="input-group">
                        <select name="kategori_id" class="form-select">
                            <option value="">Semua Kategori</option>
                            <?php foreach($kategori_list as $kategori): ?>
                            <option value="<?php echo $kategori['id']; ?>" <?php echo (isset($kategori_id) && $kategori_id == $kategori['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($kategori['nama']); ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit" class="btn btn-primary">Filter</button>
                    </div>
                </form>
            </div>
        </div>
        
        <?php if (empty($peralatan_list)): ?>
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> Tidak ada peralatan yang tersedia.
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Peralatan</th>
                            <th>Kategori</th>
                            <th>Harga Sewa</th>
                            <th>Stok</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; foreach($peralatan_list as $item): ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo htmlspecialchars($item['nama']); ?></td>
                            <td><?php echo htmlspecialchars($item['kategori_nama']); ?></td>
                            <td>Rp <?php echo number_format($item['harga_sewa'], 0, ',', '.'); ?> /hari</td>
                            <td><?php echo $item['stok']; ?> unit</td>
                            <td>
                                <?php if($item['status'] == 'tersedia'): ?>
                                    <span class="badge bg-success">Tersedia</span>
                                <?php elseif($item['status'] == 'dipinjam'): ?>
                                    <span class="badge bg-warning">Sedang Dipinjam</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Pemeliharaan</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="index.php?page=peralatan_edit&id=<?php echo $item['id']; ?>" class="btn btn-sm btn-info">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <a href="index.php?page=peralatan_delete&id=<?php echo $item['id']; ?>" class="btn btn-sm btn-danger" 
                                   onclick="return confirm('Apakah Anda yakin ingin menghapus peralatan ini?')">
                                    <i class="fas fa-trash"></i> Hapus
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>