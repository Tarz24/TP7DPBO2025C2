<?php
// view/peralatan/add.php - Form tambah peralatan baru

// Inisialisasi objek
$kategoriObj = new Kategori($db);
$kategori_list = $kategoriObj->getAll();

// Proses form jika disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $peralatanObj = new Peralatan($db);
    
    // Validasi input
    $errors = [];
    if (empty($_POST['nama'])) {
        $errors[] = "Nama peralatan harus diisi";
    }
    if (empty($_POST['kategori_id'])) {
        $errors[] = "Kategori harus dipilih";
    }
    if (!is_numeric($_POST['stok']) || $_POST['stok'] < 0) {
        $errors[] = "Stok harus berupa angka positif";
    }
    if (!is_numeric($_POST['harga_sewa']) || $_POST['harga_sewa'] <= 0) {
        $errors[] = "Harga sewa harus berupa angka positif";
    }
    
    // Jika tidak ada error, simpan data
    if (empty($errors)) {
        $data = [
            'nama' => $_POST['nama'],
            'kategori_id' => $_POST['kategori_id'],
            'stok' => $_POST['stok'],
            'harga_sewa' => $_POST['harga_sewa'],
            'deskripsi' => $_POST['deskripsi'],
            'status' => $_POST['status']
        ];
        
        if ($peralatanObj->create($data)) {
            // Redirect ke halaman list peralatan
            header("Location: index.php?page=peralatan&message=Peralatan berhasil ditambahkan");
            exit;
        } else {
            $errors[] = "Gagal menambahkan peralatan";
        }
    }
}
?>

<div class="card mb-4">
    <div class="card-header bg-primary text-white">
        <h4 class="mb-0"><i class="fas fa-plus"></i> Tambah Peralatan Baru</h4>
    </div>
    <div class="card-body">
        <?php if (isset($errors) && !empty($errors)): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach($errors as $error): ?>
                    <li><?php echo $error; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        
        <form action="index.php?page=peralatan_add" method="POST">
            <div class="mb-3">
                <label for="nama" class="form-label">Nama Peralatan <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="nama" name="nama" required 
                       value="<?php echo isset($_POST['nama']) ? htmlspecialchars($_POST['nama']) : ''; ?>">
            </div>
            
            <div class="mb-3">
                <label for="kategori_id" class="form-label">Kategori <span class="text-danger">*</span></label>
                <select class="form-select" id="kategori_id" name="kategori_id" required>
                    <option value="">-- Pilih Kategori --</option>
                    <?php foreach($kategori_list as $kategori): ?>
                    <option value="<?php echo $kategori['id']; ?>" <?php echo (isset($_POST['kategori_id']) && $_POST['kategori_id'] == $kategori['id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($kategori['nama']); ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="stok" class="form-label">Stok <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="stok" name="stok" min="0" required
                               value="<?php echo isset($_POST['stok']) ? htmlspecialchars($_POST['stok']) : '0'; ?>">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="harga_sewa" class="form-label">Harga Sewa (per hari) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" class="form-control" id="harga_sewa" name="harga_sewa" min="0" step="1000" required
                                   value="<?php echo isset($_POST['harga_sewa']) ? htmlspecialchars($_POST['harga_sewa']) : '0'; ?>">
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mb-3">
                <label for="deskripsi" class="form-label">Deskripsi</label>
                <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3"><?php echo isset($_POST['deskripsi']) ? htmlspecialchars($_POST['deskripsi']) : ''; ?></textarea>
            </div>
            
            <div class="mb-3">
                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                <select class="form-select" id="status" name="status" required>
                    <option value="tersedia" <?php echo (isset($_POST['status']) && $_POST['status'] == 'tersedia') ? 'selected' : ''; ?>>Tersedia</option>
                    <option value="dipinjam" <?php echo (isset($_POST['status']) && $_POST['status'] == 'dipinjam') ? 'selected' : ''; ?>>Sedang Dipinjam</option>
                    <option value="pemeliharaan" <?php echo (isset($_POST['status']) && $_POST['status'] == 'pemeliharaan') ? 'selected' : ''; ?>>Pemeliharaan</option>
                </select>
            </div>
            
            <div class="d-flex justify-content-between">
                <a href="index.php?page=peralatan" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>