<?php
// view/kategori/delete.php - Halaman untuk menghapus kategori

// Cek ID kategori
$id = isset($_GET['id']) ? $_GET['id'] : 0;

// Inisialisasi objek
$kategoriObj = new Kategori($db);

// Cek jika form dikirim
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Hapus kategori
    if ($kategoriObj->delete($id)) {
        // Redirect ke halaman daftar kategori
        echo "<script>
            alert('Kategori berhasil dihapus!');
            window.location.href = 'index.php?page=kategori';
        </script>";
        exit;
    } else {
        $error = "Gagal menghapus kategori. Pastikan tidak ada peralatan yang terkait dengan kategori ini.";
    }
}

// Ambil data kategori
$kategori = $kategoriObj->getById($id);
if (!$kategori) {
    echo "<script>
        alert('Kategori tidak ditemukan!');
        window.location.href = 'index.php?page=kategori';
    </script>";
    exit;
}

// Hitung jumlah peralatan dalam kategori
$jumlah_peralatan = $kategoriObj->countPeralatan($id);
?>

<div class="card mb-4">
    <div class="card-header bg-danger text-white">
        <h4 class="mb-0"><i class="fas fa-trash"></i> Hapus Kategori</h4>
    </div>
    <div class="card-body">
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle"></i> Anda yakin ingin menghapus kategori berikut?
            <?php if ($jumlah_peralatan > 0): ?>
                <strong>Perhatian:</strong> Terdapat <?php echo $jumlah_peralatan; ?> peralatan terkait dengan kategori ini. Menghapus kategori ini juga akan menghapus semua peralatan tersebut.
            <?php endif; ?>
        </div>
        
        <table class="table table-bordered">
            <tr>
                <th width="30%">ID</th>
                <td><?php echo $kategori['id']; ?></td>
            </tr>
            <tr>
                <th>Nama Kategori</th>
                <td><?php echo htmlspecialchars($kategori['nama']); ?></td>
            </tr>
            <tr>
                <th>Deskripsi</th>
                <td><?php echo nl2br(htmlspecialchars($kategori['deskripsi'])); ?></td>
            </tr>
            <tr>
                <th>Jumlah Peralatan</th>
                <td><?php echo $jumlah_peralatan; ?> item</td>
            </tr>
        </table>
        
        <form method="POST" action="">
            <div class="d-flex justify-content-between">
                <a href="index.php?page=kategori" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Batal
                </a>
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-trash"></i> Hapus Kategori
                </button>
            </div>
        </form>
    </div>
</div>