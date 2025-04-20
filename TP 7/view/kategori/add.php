<?php
// view/kategori/add.php - Form tambah kategori baru

// Proses form jika disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $kategoriObj = new Kategori($db);
    
    // Validasi input
    $errors = [];
    if (empty($_POST['nama'])) {
        $errors[] = "Nama kategori harus diisi";
    }
    
    // Jika tidak ada error, simpan data
    if (empty($errors)) {
        $data = [
            'nama' => $_POST['nama'],
            'deskripsi' => $_POST['deskripsi']
        ];
        
        if ($kategoriObj->create($data)) {
            // Redirect ke halaman list kategori
            header("Location: index.php?page=kategori&message=Kategori berhasil ditambahkan");
            exit;
        } else {
            $errors[] = "Gagal menambahkan kategori";
        }
    }
}
?>

<div class="card mb-4">
    <div class="card-header bg-primary text-white">
        <h4 class="mb-0"><i class="fas fa-plus"></i> Tambah Kategori Baru</h4>
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
        
        <form action="index.php?page=kategori_add" method="POST">
            <div class="mb-3">
                <label for="nama" class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="nama" name="nama" required 
                       value="<?php echo isset($_POST['nama']) ? htmlspecialchars($_POST['nama']) : ''; ?>">
            </div>
            
            <div class="mb-3">
                <label for="deskripsi" class="form-label">Deskripsi</label>
                <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3"><?php echo isset($_POST['deskripsi']) ? htmlspecialchars($_POST['deskripsi']) : ''; ?></textarea>
            </div>
            
            <div class="d-flex justify-content-between">
                <a href="index.php?page=kategori" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>