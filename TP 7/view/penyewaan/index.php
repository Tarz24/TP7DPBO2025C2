<?php
// view/penyewaan/index.php - Halaman daftar penyewaan

// Inisialisasi objek
$penyewaanObj = new Penyewaan($db);

// Ambil semua data penyewaan
$penyewaan_list = $penyewaanObj->getAll();

// Tampilkan pesan jika ada
if (isset($_GET['message'])) {
    if ($_GET['message'] == 'delete_success') {
        echo '<div class="alert alert-success">Penyewaan berhasil dihapus</div>';
    }
}
?>

<div class="card mb-4">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h4 class="mb-0"><i class="fas fa-clipboard-list"></i> Daftar Penyewaan</h4>
        <a href="index.php?page=penyewaan_add" class="btn btn-light"><i class="fas fa-plus"></i> Tambah Penyewaan</a>
    </div>
    <div class="card-body">
        <?php if (empty($penyewaan_list)): ?>
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> Belum ada data penyewaan.
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Penyewa</th>
                            <th>Tanggal Mulai</th>
                            <th>Tanggal Selesai</th>
                            <th>Total Harga</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($penyewaan_list as $item): ?>
                        <tr>
                            <td><?php echo $item['id']; ?></td>
                            <td>
                                <?php echo htmlspecialchars($item['penyewa_nama']); ?><br>
                                <small class="text-muted"><?php echo htmlspecialchars($item['telepon']); ?></small>
                            </td>
                            <td><?php echo date('d/m/Y', strtotime($item['tanggal_mulai'])); ?></td>
                            <td><?php echo date('d/m/Y', strtotime($item['tanggal_selesai'])); ?></td>
                            <td>Rp <?php echo number_format($item['total_harga'], 0, ',', '.'); ?></td>
                            <td>
                                <?php if($item['status'] == 'booking'): ?>
                                    <span class="badge bg-info">Booking</span>
                                <?php elseif($item['status'] == 'berjalan'): ?>
                                    <span class="badge bg-primary">Berjalan</span>
                                <?php elseif($item['status'] == 'selesai'): ?>
                                    <span class="badge bg-success">Selesai</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Batal</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="index.php?page=penyewaan_edit&id=<?php echo $item['id']; ?>" class="btn btn-sm btn-info mb-1">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <?php if($item['status'] == 'selesai' || $item['status'] == 'batal'): ?>
                                <a href="index.php?page=penyewaan_delete&id=<?php echo $item['id']; ?>" class="btn btn-sm btn-danger mb-1"
                                   onclick="return confirm('Apakah Anda yakin ingin menghapus penyewaan ini?')">
                                    <i class="fas fa-trash"></i> Hapus
                                </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>