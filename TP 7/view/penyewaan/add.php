<?php
// view/penyewaan/add.php - Halaman tambah penyewaan baru

// Inisialisasi objek
$peralatanObj = new Peralatan($db);
$kategoriObj = new Kategori($db);

// Ambil data untuk dropdown
$kategori_list = $kategoriObj->getAll();
$peralatan_list = $peralatanObj->getAll();

// Proses form jika disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $penyewaanObj = new Penyewaan($db);
        
        // Data penyewa
        $penyewa_data = [
            'nama' => $_POST['nama'],
            'email' => $_POST['email'],
            'telepon' => $_POST['telepon'],
            'alamat' => $_POST['alamat'],
            'ktp' => $_POST['ktp']
        ];
        
        // Simpan data penyewa dan dapatkan ID-nya
        $penyewa_id = $penyewaanObj->createPenyewa($penyewa_data);
        
        // Hitung total harga
        $total_harga = 0;
        $items = [];
        
        foreach ($_POST['peralatan_id'] as $key => $id) {
            if (!isset($_POST['jumlah'][$key]) || $_POST['jumlah'][$key] <= 0) {
                continue; // Skip jika jumlah tidak valid
            }
            
            $peralatan = $peralatanObj->getById($id);
            $jumlah = $_POST['jumlah'][$key];
            $subtotal = $peralatan['harga_sewa'] * $jumlah;
            $total_harga += $subtotal;
            
            $items[] = [
                'peralatan_id' => $id,
                'jumlah' => $jumlah,
                'harga_sewa' => $peralatan['harga_sewa'],
                'subtotal' => $subtotal
            ];
        }
        
        // Hitung durasi penyewaan
        $tanggal_mulai = $_POST['tanggal_mulai'];
        $tanggal_selesai = $_POST['tanggal_selesai'];
        $durasi = (strtotime($tanggal_selesai) - strtotime($tanggal_mulai)) / (60 * 60 * 24);
        $total_harga = $total_harga * max(1, $durasi); // Minimal 1 hari
        
        // Data penyewaan
        $penyewaan_data = [
            'penyewa_id' => $penyewa_id,
            'tanggal_mulai' => $tanggal_mulai,
            'tanggal_selesai' => $tanggal_selesai,
            'total_harga' => $total_harga,
            'status' => 'booking',
            'catatan' => $_POST['catatan'],
            'items' => $items
        ];
        
        // Simpan data penyewaan
        $penyewaan_id = $penyewaanObj->createPenyewaan($penyewaan_data);
        
        if ($penyewaan_id) {
            echo '<div class="alert alert-success">Penyewaan berhasil ditambahkan!</div>';
            echo '<meta http-equiv="refresh" content="2;url=index.php?page=penyewaan">';
        } else {
            echo '<div class="alert alert-danger">Gagal menambahkan penyewaan!</div>';
        }
    } catch (Exception $e) {
        echo '<div class="alert alert-danger">Error: ' . $e->getMessage() . '</div>';
    }
}
?>

<div class="card mb-4">
    <div class="card-header bg-primary text-white">
        <h4 class="mb-0"><i class="fas fa-plus-circle"></i> Tambah Penyewaan Baru</h4>
    </div>
    <div class="card-body">
        <form action="" method="POST" id="formPenyewaan">
            <h5 class="mb-3">Data Penyewa</h5>
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nama" name="nama" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="telepon" class="form-label">Nomor Telepon <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="telepon" name="telepon" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="ktp" class="form-label">Nomor KTP <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="ktp" name="ktp" required>
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <label for="alamat" class="form-label">Alamat <span class="text-danger">*</span></label>
                <textarea class="form-control" id="alamat" name="alamat" rows="2" required></textarea>
            </div>
            
            <hr class="my-4">
            
            <h5 class="mb-3">Data Penyewaan</h5>
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="tanggal_mulai" class="form-label">Tanggal Mulai <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="tanggal_mulai" name="tanggal_mulai" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="tanggal_selesai" class="form-label">Tanggal Selesai <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="tanggal_selesai" name="tanggal_selesai" required>
                    </div>
                </div>
            </div>
            
            <div class="mb-3">
                <label for="catatan" class="form-label">Catatan</label>
                <textarea class="form-control" id="catatan" name="catatan" rows="2"></textarea>
            </div>
            
            <hr class="my-4">
            
            <h5 class="mb-3">Pilih Peralatan</h5>
            <div class="row mb-3">
                <div class="col-md-12">
                    <div class="mb-3">
                        <label class="form-label">Filter Kategori</label>
                        <select id="filterKategori" class="form-select">
                            <option value="">Semua Kategori</option>
                            <?php foreach($kategori_list as $kategori): ?>
                            <option value="<?php echo $kategori['id']; ?>"><?php echo htmlspecialchars($kategori['nama']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
            
            <div id="peralatanContainer">
                <div class="row item-peralatan mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Peralatan <span class="text-danger">*</span></label>
                        <select name="peralatan_id[]" class="form-select peralatan-select" required>
                            <option value="">Pilih Peralatan</option>
                            <?php foreach($peralatan_list as $peralatan): ?>
                                <?php if($peralatan['stok'] > 0 && $peralatan['status'] == 'tersedia'): ?>
                                <option value="<?php echo $peralatan['id']; ?>" data-harga="<?php echo $peralatan['harga_sewa']; ?>" data-stok="<?php echo $peralatan['stok']; ?>" data-kategori="<?php echo $peralatan['kategori_id']; ?>">
                                    <?php echo htmlspecialchars($peralatan['nama']); ?> - Rp <?php echo number_format($peralatan['harga_sewa'], 0, ',', '.'); ?>/hari (Stok: <?php echo $peralatan['stok']; ?>)
                                </option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Jumlah <span class="text-danger">*</span></label>
                        <input type="number" name="jumlah[]" class="form-control jumlah-input" min="1" value="1" required>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="button" class="btn btn-danger hapus-peralatan mb-2" style="display: none;">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="mb-3">
                <button type="button" id="tambahPeralatan" class="btn btn-success">
                    <i class="fas fa-plus"></i> Tambah Peralatan
                </button>
            </div>
            
            <div class="card bg-light p-3 mb-4">
                <div class="d-flex justify-content-between">
                    <h5>Total Harga:</h5>
                    <h5 id="totalHarga">Rp 0</h5>
                </div>
                <small class="text-muted">* Total harga akan dikalikan dengan durasi penyewaan</small>
            </div>
            
            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <a href="index.php?page=penyewaan" class="btn btn-secondary me-md-2">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Penyewaan</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Filter peralatan berdasarkan kategori
    document.getElementById('filterKategori').addEventListener('change', function() {
        const kategoriId = this.value;
        const peralatanSelects = document.querySelectorAll('.peralatan-select');
        
        peralatanSelects.forEach(select => {
            const options = select.querySelectorAll('option');
            
            options.forEach(option => {
                if (option.value === '') return; // Skip opsi default
                
                const kategoriPeralatan = option.getAttribute('data-kategori');
                if (!kategoriId || kategoriId === kategoriPeralatan) {
                    option.style.display = '';
                } else {
                    option.style.display = 'none';
                }
            });
        });
    });
    
    // Tambah baris peralatan
    document.getElementById('tambahPeralatan').addEventListener('click', function() {
        const container = document.getElementById('peralatanContainer');
        const newRow = container.querySelector('.item-peralatan').cloneNode(true);
        
        // Reset nilai
        newRow.querySelector('.peralatan-select').value = '';
        newRow.querySelector('.jumlah-input').value = 1;
        
        // Tampilkan tombol hapus
        newRow.querySelector('.hapus-peralatan').style.display = 'block';
        
        container.appendChild(newRow);
        
        // Tambahkan event untuk tombol hapus
        newRow.querySelector('.hapus-peralatan').addEventListener('click', function() {
            container.removeChild(newRow);
            hitungTotal();
        });
        
        // Tambahkan event untuk select dan input
        newRow.querySelector('.peralatan-select').addEventListener('change', hitungTotal);
        newRow.querySelector('.jumlah-input').addEventListener('input', hitungTotal);
    });
    
    // Menambahkan event listener untuk menghitung total
    document.querySelectorAll('.peralatan-select, .jumlah-input').forEach(el => {
        el.addEventListener('change', hitungTotal);
        el.addEventListener('input', hitungTotal);
    });
    
    document.querySelectorAll('#tanggal_mulai, #tanggal_selesai').forEach(el => {
        el.addEventListener('change', hitungTotal);
    });
    
    // Fungsi untuk menghitung total harga
    function hitungTotal() {
        let total = 0;
        const rows = document.querySelectorAll('.item-peralatan');
        
        rows.forEach(row => {
            const select = row.querySelector('.peralatan-select');
            const jumlah = parseInt(row.querySelector('.jumlah-input').value) || 0;
            
            if (select.value) {
                const option = select.options[select.selectedIndex];
                const harga = parseFloat(option.getAttribute('data-harga'));
                const stok = parseInt(option.getAttribute('data-stok'));
                
                // Batasi jumlah maksimal sesuai stok
                if (jumlah > stok) {
                    row.querySelector('.jumlah-input').value = stok;
                }
                
                total += harga * jumlah;
            }
        });
        
        // Hitung durasi
        const tanggalMulai = document.getElementById('tanggal_mulai').value;
        const tanggalSelesai = document.getElementById('tanggal_selesai').value;
        
        if (tanggalMulai && tanggalSelesai) {
            const start = new Date(tanggalMulai);
            const end = new Date(tanggalSelesai);
            const durasi = Math.ceil((end - start) / (1000 * 60 * 60 * 24));
            
            if (durasi > 0) {
                total *= durasi;
            }
        }
        
        document.getElementById('totalHarga').textContent = 'Rp ' + total.toLocaleString('id-ID');
    }
    
    // Validasi form sebelum submit
    document.getElementById('formPenyewaan').addEventListener('submit', function(e) {
        const tanggalMulai = new Date(document.getElementById('tanggal_mulai').value);
        const tanggalSelesai = new Date(document.getElementById('tanggal_selesai').value);
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        
        // Validasi tanggal mulai tidak boleh kurang dari hari ini
        if (tanggalMulai < today) {
            alert('Tanggal mulai tidak boleh kurang dari hari ini!');
            e.preventDefault();
            return false;
        }
        
        // Validasi tanggal selesai harus lebih dari tanggal mulai
        if (tanggalSelesai <= tanggalMulai) {
            alert('Tanggal selesai harus lebih dari tanggal mulai!');
            e.preventDefault();
            return false;
        }
        
        // Periksa apakah ada item yang dipilih
        let itemValid = false;
        const peralatanSelects = document.querySelectorAll('.peralatan-select');
        peralatanSelects.forEach(select => {
            if (select.value) {
                itemValid = true;
            }
        });
        
        if (!itemValid) {
            alert('Pilih minimal satu peralatan!');
            e.preventDefault();
            return false;
        }
    });
});
</script>