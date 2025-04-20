<?php
// view/peralatan/delete.php - Proses hapus peralatan

// Cek ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php?page=peralatan&error=ID tidak valid");
    exit;
}

$id = $_GET['id'];

// Inisialisasi objek
$peralatanObj = new Peralatan($db);

// Ambil data peralatan untuk konfirmasi
$peralatan = $peralatanObj->getById($id);
if (!$peralatan) {
    header("Location: index.php?page=peralatan&error=Peralatan tidak ditemukan");
    exit;
}

// Proses hapus
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['konfirmasi']) && $_POST['konfirmasi'] == 'ya') {
    if ($peralatanObj->delete($id)) {
        header("Location: index.php?page=peralatan&message=Peralatan berhasil dihapus");
        exit;
    } else {
        $error = "Gagal menghapus peralatan. Peralatan mungkin sedang digunakan dalam transaksi penyewaan.";
    }
}
?>

<div class="card mb-4">
    <div class="card-header bg-danger text-white">
        <h4 class="mb-0"><i class="fas fa-trash"></i> Hapus Peralatan</h4>
    </div>
    <div class="card-body">
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle"></i> Apakah Anda yakin ingin menghapus peralatan berikut?
        </div>
        
        <table class="table table-bordered">
            <tr>
                <th style="width: 30%">Nama Peralatan</th>
                <td><?php echo htmlspecialchars($peralatan['nama']); ?></td>
            </tr>
            <tr>
                <th>Kategori</th>
                <td><?php echo htmlspecialchars($peralatan['kategori_nama']); ?></td>
            </tr>
            <tr>
                <th>Stok</th>
                <td><?php echo $peralatan['stok']; ?> unit</td>
            </tr>
            <tr>
                <th>Harga Sewa</th>
                <td>Rp <?php echo number_format($peralatan['harga_sewa'], 0, ',', '.'); ?> /hari</td>
            </tr>
            <tr>
                <th>Status</th>
                <td>
                    <?php if($peralatan['status'] == 'tersedia'): ?>
                        <span class="badge bg-success">Tersedia</span>
                    <?php elseif($peralatan['status'] == 'dipinjam'): ?>
                        <span class="badge bg-warning">Sedang Dipinjam</span>
                    <?php else: ?>
                        <span class="badge bg-secondary">Pemeliharaan</span>
                    <?php endif; ?>
                </td>
            </tr>
        </table>
        
        <div class="alert alert-danger">
            <strong>Perhatian!</strong> Tindakan ini tidak dapat dibatalkan. Data peralatan akan dihapus permanen dari sistem.
        </div>
        
        <form action="index.php?page=peralatan_delete&id=<?php echo $id; ?>" method="POST" class="d-flex justify-content-between">
            <a href="index.php?page=peralatan" class="btn btn-secondary">
                <i class="fas fa-times"></i> Batal
            </a>
            <input type="hidden" name="konfirmasi" value="ya">
            <button type="submit" class="btn btn-danger">
                <i class="fas fa-trash"></i> Ya, Hapus Peralatan
            </button>
        </form>
    </div>
</div>