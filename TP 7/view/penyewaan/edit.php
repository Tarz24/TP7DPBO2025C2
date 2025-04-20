<?php
// view/penyewaan/edit.php - Halaman edit penyewaan

// Inisialisasi objek
$penyewaanObj = new Penyewaan($db);
$peralatanObj = new Peralatan($db);

// Cek parameter ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo '<div class="alert alert-danger">ID Penyewaan tidak valid</div>';
    echo '<a href="index.php?page=penyewaan" class="btn btn-primary">Kembali</a>';
    exit;
}

$id = $_GET['id'];

// Ambil data penyewaan
$penyewaan = $penyewaanObj->getById($id);
if (!$penyewaan) {
    echo '<div class="alert alert-danger">Penyewaan tidak ditemukan</div>';
    echo '<a href="index.php?page=penyewaan" class="btn btn-primary">Kembali</a>';
    exit;
}

// Ambil detail penyewaan
$detail_penyewaan = $penyewaanObj->getDetail($id);

// Proses form update status
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update_status'])) {
        $new_status = $_POST['status'];
        
        if ($penyewaanObj->updateStatus($id, $new_status)) {
            // Jika status diubah menjadi batal, kembalikan stok peralatan
            if ($new_status == 'batal') {
                $penyewaanObj->cancel($id);
            }
            
            echo '<div class="alert alert-success">Status penyewaan berhasil diperbarui</div>';
            // Refresh data penyewaan
            $penyewaan = $penyewaanObj->getById($id);
        } else {
            echo '<div class="alert alert-danger">Gagal memperbarui status penyewaan</div>';
        }
    }
    
    // Proses pengembalian peralatan
    if (isset($_POST['return_items'])) {
        $return_items = [];
        foreach ($_POST['item_status'] as $item_id => $status) {
            $return_items[$item_id] = $status;
        }
        
        if ($penyewaanObj->returnItems($id, $return_items)) {
            echo '<div class="alert alert-success">Status pengembalian peralatan berhasil diperbarui</div>';
            // Refresh data detail
            $detail_penyewaan = $penyewaanObj->getDetail($id);
            // Refresh data penyewaan untuk status yang mungkin berubah
            $penyewaan = $penyewaanObj->getById($id);
        } else {
            echo '<div class="alert alert-danger">Gagal memperbarui status pengembalian peralatan</div>';
        }
    }
}
?>

<div class="card mb-4">
    <div class="card-header bg-primary text-white">
        <h4 class="mb-0"><i class="fas fa-edit"></i> Edit Penyewaan #<?php echo $id; ?></h4>
    </div>
    <div class="card-body">
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
                    <tr>
                        <th>Email</th>
                        <td><?php echo htmlspecialchars($penyewaan['email']); ?></td>
                    </tr>
                    <tr>
                        <th>Alamat</th>
                        <td><?php echo htmlspecialchars($penyewaan['alamat']); ?></td>
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
                            <?php if($penyewaan['status'] == 'booking'): ?>
                                <span class="badge bg-info">Booking</span>
                            <?php elseif($penyewaan['status'] == 'berjalan'): ?>
                                <span class="badge bg-primary">Berjalan</span>
                            <?php elseif($penyewaan['status'] == 'selesai'): ?>
                                <span class="badge bg-success">Selesai</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Batal</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        
        <h5>Peralatan yang Disewa</h5>
        <div class="table-responsive mb-4">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Peralatan</th>
                        <th>Jumlah</th>
                        <th>Harga Sewa</th>
                        <th>Subtotal</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach($detail_penyewaan as $item): ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo htmlspecialchars($item['peralatan_nama']); ?></td>
                        <td><?php echo $item['jumlah']; ?> unit</td>
                        <td>Rp <?php echo number_format($item['harga_sewa'], 0, ',', '.'); ?> /hari</td>
                        <td>Rp <?php echo number_format($item['subtotal'], 0, ',', '.'); ?></td>
                        <td>
                            <?php if($item['status'] == 'disewa'): ?>
                                <span class="badge bg-warning">Disewa</span>
                            <?php elseif($item['status'] == 'dikembalikan'): ?>
                                <span class="badge bg-success">Dikembalikan</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Rusak</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="4" class="text-end">Total:</th>
                        <th colspan="2">Rp <?php echo number_format($penyewaan['total_harga'], 0, ',', '.'); ?></th>
                    </tr>
                </tfoot>
            </table>
        </div>
        
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Update Status Penyewaan</h5>
                    </div>
                    <div class="card-body">
                        <form action="" method="POST">
                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select name="status" id="status" class="form-select" required>
                                    <option value="booking" <?php echo $penyewaan['status'] == 'booking' ? 'selected' : ''; ?>>Booking</option>
                                    <option value="berjalan" <?php echo $penyewaan['status'] == 'berjalan' ? 'selected' : ''; ?>>Berjalan</option>
                                    <option value="selesai" <?php echo $penyewaan['status'] == 'selesai' ? 'selected' : ''; ?>>Selesai</option>
                                    <option value="batal" <?php echo $penyewaan['status'] == 'batal' ? 'selected' : ''; ?>>Batal</option>
                                </select>
                                <div class="form-text">Perhatian: Mengubah status menjadi "Batal" akan otomatis mengembalikan stok peralatan.</div>
                            </div>
                            <button type="submit" name="update_status" class="btn btn-primary">Update Status</button>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Update Status Pengembalian</h5>
                    </div>
                    <div class="card-body">
                        <?php if ($penyewaan['status'] == 'batal' || $penyewaan['status'] == 'selesai'): ?>
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i> Penyewaan sudah selesai atau dibatalkan.
                            </div>
                        <?php else: ?>
                            <form action="" method="POST">
                                <?php foreach($detail_penyewaan as $item): ?>
                                <div class="mb-3">
                                    <label class="form-label"><?php echo htmlspecialchars($item['peralatan_nama']); ?> (<?php echo $item['jumlah']; ?> unit)</label>
                                    <select name="item_status[<?php echo $item['id']; ?>]" class="form-select">
                                        <option value="disewa" <?php echo $item['status'] == 'disewa' ? 'selected' : ''; ?>>Masih Disewa</option>
                                        <option value="dikembalikan" <?php echo $item['status'] == 'dikembalikan' ? 'selected' : ''; ?>>Dikembalikan</option>
                                        <option value="rusak" <?php echo $item['status'] == 'rusak' ? 'selected' : ''; ?>>Rusak</option>
                                    </select>
                                </div>
                                <?php endforeach; ?>
                                <button type="submit" name="return_items" class="btn btn-primary">Update Pengembalian</button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="mt-3">
            <a href="index.php?page=penyewaan" class="btn btn-secondary">Kembali</a>
            <?php if ($penyewaan['status'] == 'selesai' || $penyewaan['status'] == 'batal'): ?>
            <a href="index.php?page=penyewaan_delete&id=<?php echo $id; ?>" class="btn btn-danger" 
               onclick="return confirm('Apakah Anda yakin ingin menghapus penyewaan ini?')">
                <i class="fas fa-trash"></i> Hapus Penyewaan
            </a>
            <?php endif; ?>
        </div>
    </div>
</div>