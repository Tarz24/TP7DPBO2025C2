<?php
// view/penyewaan/delete.php - Halaman untuk menghapus penyewaan

// Inisialisasi objek
$penyewaanObj = new Penyewaan($db);

// Cek parameter ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo '<div class="alert alert-danger">ID Penyewaan tidak valid</div>';
    echo '<a href="index.php?page=penyewaan" class="btn btn-primary">Kembali</a>';
    exit;
}

$id = $_GET['id'];

// Ambil data penyewaan untuk konfirmasi
$penyewaan = $penyewaanObj->getById($id);
if (!$penyewaan) {
    echo '<div class="alert alert-danger">Penyewaan tidak ditemukan</div>';
    echo '<a href="index.php?page=penyewaan" class="btn btn-primary">Kembali</a>';
    exit;
}

// Cek apakah penyewaan bisa dihapus (hanya jika status selesai atau batal)
if ($penyewaan['status'] != 'selesai' && $penyewaan['status'] != 'batal') {
    echo '<div class="alert alert-danger">Penyewaan tidak dapat dihapus karena masih aktif. Hanya penyewaan dengan status Selesai atau Batal yang dapat dihapus.</div>';
    echo '<a href="index.php?page=penyewaan" class="btn btn-primary">Kembali</a>';
    exit;
}

// Proses penghapusan jika form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete'])) {
    if ($penyewaanObj->delete($id)) {
        // Redirect ke halaman penyewaan dengan pesan sukses
        header("Location: index.php?page=penyewaan&message=delete_success");
        exit;
    } else {
        echo '<div class="alert alert-danger">Gagal menghapus penyewaan</div>';
    }
}
?>

<div class="card mb-4">
    <div class="card-header bg-danger text-white">
        <h4 class="mb-0"><i class="fas fa-trash"></i> Hapus Penyewaan</h4>
    </div>
    <div class="card-body">
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle"></i> Apakah Anda yakin ingin menghapus penyewaan ini? Tindakan ini tidak dapat dibatalkan.
        </div>
        
        <div class="row mb-4">
            <div class="col-md-6">
                <h5>Detail Penyewa</h5>
                <table class="table table-bordered">
                    <tr>
                        <th width="30%">Nama Penyewa</th>
                        <td><?php echo htmlspecialchars($penyewaan['penyewa_nama']); ?></td>
                    </tr>
                    <tr>
                        <th>Telepon</th>
                        <td><?php echo htmlspecialchars($penyewaan['telepon']); ?></td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <h5>Detail Penyewaan</h5>
                <table class="table table-bordered">
                    <tr>
                        <th width="30%">Tanggal Mulai</th>
                        <td><?php echo date('d/m/Y', strtotime($penyewaan['tanggal_mulai'])); ?></td>
                    </tr>
                    <tr>
                        <th>Tanggal Selesai</th>
                        <td><?php echo date('d/m/Y', strtotime($penyewaan['tanggal_selesai'])); ?></td>
                    </tr>
                    <tr>
                        <th>Total Harga</th>
                        <td>Rp <?php echo number_format($penyewaan['total_harga'], 0, ',', '.'); ?></td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            <?php if($penyewaan['status'] == 'selesai'): ?>
                                <span class="badge bg-success">Selesai</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Batal</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        
        <form action="" method="POST">
            <div class="d-flex justify-content-between">
                <a href="index.php?page=penyewaan" class="btn btn-secondary">Batal</a>
                <button type="submit" name="delete" class="btn btn-danger">Hapus Penyewaan</button>
            </div>
        </form>
    </div>
</div>