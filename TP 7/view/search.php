<?php
// view/search.php - Halaman hasil pencarian

// Cek keyword pencarian
$keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';

// Inisialisasi objek
$peralatanObj = new Peralatan($db);

// Cari peralatan
$hasil_pencarian = $peralatanObj->search($keyword);
?>

<div class="card mb-4">
    <div class="card-header bg-primary text-white">
        <h4 class="mb-0"><i class="fas fa-search"></i> Hasil Pencarian: "<?php echo htmlspecialchars($keyword); ?>"</h4>
    </div>
    <div class="card-body">
        <?php if (empty($hasil_pencarian)): ?>
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> Tidak ada peralatan yang cocok dengan kata kunci "<?php echo htmlspecialchars($keyword); ?>".
            </div>
            <a href="index.php?page=peralatan" class="btn btn-primary">Lihat Semua Peralatan</a>
        <?php else: ?>
            <p>Ditemukan <?php echo count($hasil_pencarian); ?> peralatan dengan kata kunci "<?php echo htmlspecialchars($keyword); ?>".</p>
            
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
                        <?php $no = 1; foreach($hasil_pencarian as $item): ?>
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