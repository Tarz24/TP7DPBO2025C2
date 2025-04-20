<?php
// view/kategori/index.php - Halaman daftar kategori

// Inisialisasi objek
$kategoriObj = new Kategori($db);

// Ambil semua kategori
$kategori_list = $kategoriObj->getAll();

// Tampilkan pesan jika ada
if (isset($_GET['message'])) {
    $message = $_GET['message'];
    $alert_class = 'alert-success';
} elseif (isset($_GET['error'])) {
    $message = $_GET['error'];
    $alert_class = 'alert-danger';
}
?>

<div class="card mb-4">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h4 class="mb-0"><i class="fas fa-tags"></i> Daftar Kategori</h4>
        <a href="index.php?page=kategori_add" class="btn btn-light">
            <i class="fas fa-plus"></i> Tambah Kategori
        </a>
    </div>
    <div class="card-body">
        <?php if (isset($message)): ?>
            <div class="alert <?php echo $alert_class; ?> alert-dismissible fade show">
                <?php echo $message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        
        <?php if (empty($kategori_list)): ?>
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> Belum ada kategori yang tersedia.
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Kategori</th>
                            <th>Deskripsi</th>
                            <th>Jumlah Peralatan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; foreach($kategori_list as $kategori): 
                            // Hitung jumlah peralatan dalam kategori
                            $jumlah_peralatan = $kategoriObj->countPeralatan($kategori['id']);
                        ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo htmlspecialchars($kategori['nama']); ?></td>
                            <td><?php echo htmlspecialchars(substr($kategori['deskripsi'], 0, 100) . (strlen($kategori['deskripsi']) > 100 ? '...' : '')); ?></td>
                            <td><?php echo $jumlah_peralatan; ?> item</td>
                            <td>
                                <a href="index.php?page=peralatan&kategori_id=<?php echo $kategori['id']; ?>" class="btn btn-sm btn-success">
                                    <i class="fas fa-list"></i> Peralatan
                                </a>
                                <a href="index.php?page=kategori_edit&id=<?php echo $kategori['id']; ?>" class="btn btn-sm btn-info">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <a href="index.php?page=kategori_delete&id=<?php echo $kategori['id']; ?>" class="btn btn-sm btn-danger" 
                                   onclick="return confirm('Apakah Anda yakin ingin menghapus kategori ini? Semua peralatan dalam kategori ini juga akan dihapus.')">
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