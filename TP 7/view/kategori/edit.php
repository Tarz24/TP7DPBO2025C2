<?php
// view/kategori/edit.php - Form edit kategori

// Cek ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php?page=kategori&error=ID tidak valid");
    exit;
}

$id = $_GET['id'];

// Inisialisasi objek
$kategoriObj = new Kategori($db);

// Ambil data kategori
$kategori = $kategoriObj->getById($id);
if (!$kategori) {
    header("Location: index.php?page=kategori&error=Kategori tidak ditemukan");
    exit;
}

// Proses form jika disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validasi input
    $errors = [];
    if (empty($_POST['nama'])) {
        $errors[] = "Nama kategori harus diisi";
    }
    
    // Jika tidak ada error, update data
    if (empty($errors)) {
        $data = [
            'nama' => $_POST['nama'],
            'deskripsi' => $_POST['deskripsi']
        ];
        
        if ($kategoriObj->update($id, $data)) {
            // Redirect ke halaman list kategori
            header("Location: index.php?page=kategori&message=Kategori berhasil diperbarui");
            exit;
        } else {
            $errors[] = "Gagal memperbarui kategori";
        }
    }
}
?>

<div class="card mb-4">
    <div class="card-header bg-primary text-white">
        <h4 class="mb-0"><i class="fas fa-edit"></i> Edit Kategori</h4>
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
        
        <form action="index.php?page=kategori_edit&id=<?php echo $id; ?>" method="POST">
            <div class="mb-3">
                <label for="nama" class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="nama" name="nama" required 
                       value="<?php echo isset($_POST['nama']) ? htmlspecialchars($_POST['nama']) : htmlspecialchars($kategori['nama']); ?>">
            </div>
            
            <div class="mb-3">
                <label for="deskripsi" class="form-label">Deskripsi</label>
                <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3"><?php echo isset($_POST['deskripsi']) ? htmlspecialchars($_POST['deskripsi']) : htmlspecialchars($kategori['deskripsi']); ?></textarea>
            </div>
            
            <div class="d-flex justify-content-between">
                <a href="index.php?page=kategori" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>