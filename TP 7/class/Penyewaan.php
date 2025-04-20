<?php
// class/Penyewaan.php - Class untuk mengelola penyewaan peralatan

class Penyewaan {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    // Mendapatkan semua penyewaan
    public function getAll() {
        $query = "SELECT p.*, py.nama AS penyewa_nama, py.telepon 
                  FROM penyewaan p 
                  JOIN penyewa py ON p.penyewa_id = py.id 
                  ORDER BY p.tanggal_mulai DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    // Mendapatkan penyewaan berdasarkan ID
    public function getById($id) {
        $query = "SELECT p.*, py.nama AS penyewa_nama, py.telepon, py.email, py.alamat 
                  FROM penyewaan p 
                  JOIN penyewa py ON p.penyewa_id = py.id 
                  WHERE p.id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }
    
    // Mendapatkan detail penyewaan
    public function getDetail($penyewaan_id) {
        $query = "SELECT d.*, p.nama AS peralatan_nama 
                  FROM detail_penyewaan d 
                  JOIN peralatan p ON d.peralatan_id = p.id 
                  WHERE d.penyewaan_id = :penyewaan_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':penyewaan_id', $penyewaan_id);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    // Mencari penyewaan
    public function search($keyword) {
        $query = "SELECT p.*, py.nama AS penyewa_nama, py.telepon 
                  FROM penyewaan p 
                  JOIN penyewa py ON p.penyewa_id = py.id 
                  WHERE py.nama LIKE :keyword OR py.telepon LIKE :keyword OR p.status LIKE :keyword 
                  ORDER BY p.tanggal_mulai DESC";
        $keyword = "%$keyword%";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':keyword', $keyword);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    // Tambah penyewa baru
    public function createPenyewa($data) {
        $query = "INSERT INTO penyewa (nama, email, telepon, alamat, ktp) 
                  VALUES (:nama, :email, :telepon, :alamat, :ktp)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':nama', $data['nama']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':telepon', $data['telepon']);
        $stmt->bindParam(':alamat', $data['alamat']);
        $stmt->bindParam(':ktp', $data['ktp']);
        $stmt->execute();
        return $this->db->lastInsertId();
    }
    
    // Tambah penyewaan baru
    public function createPenyewaan($data) {
        $this->db->beginTransaction();
        
        try {
            // Insert ke tabel penyewaan
            $query = "INSERT INTO penyewaan (penyewa_id, tanggal_mulai, tanggal_selesai, total_harga, status, catatan) 
                      VALUES (:penyewa_id, :tanggal_mulai, :tanggal_selesai, :total_harga, :status, :catatan)";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':penyewa_id', $data['penyewa_id']);
            $stmt->bindParam(':tanggal_mulai', $data['tanggal_mulai']);
            $stmt->bindParam(':tanggal_selesai', $data['tanggal_selesai']);
            $stmt->bindParam(':total_harga', $data['total_harga']);
            $stmt->bindParam(':status', $data['status']);
            $stmt->bindParam(':catatan', $data['catatan']);
            $stmt->execute();
            
            $penyewaan_id = $this->db->lastInsertId();
            
            // Insert detail penyewaan dan update stok
            foreach ($data['items'] as $item) {
                $query = "INSERT INTO detail_penyewaan (penyewaan_id, peralatan_id, jumlah, harga_sewa, subtotal) 
                          VALUES (:penyewaan_id, :peralatan_id, :jumlah, :harga_sewa, :subtotal)";
                $stmt = $this->db->prepare($query);
                $stmt->bindParam(':penyewaan_id', $penyewaan_id);
                $stmt->bindParam(':peralatan_id', $item['peralatan_id']);
                $stmt->bindParam(':jumlah', $item['jumlah']);
                $stmt->bindParam(':harga_sewa', $item['harga_sewa']);
                $stmt->bindParam(':subtotal', $item['subtotal']);
                $stmt->execute();
                
                // Update stok peralatan
                $query = "UPDATE peralatan SET stok = stok - :jumlah WHERE id = :peralatan_id";
                $stmt = $this->db->prepare($query);
                $stmt->bindParam(':jumlah', $item['jumlah']);
                $stmt->bindParam(':peralatan_id', $item['peralatan_id']);
                $stmt->execute();
            }
            
            $this->db->commit();
            return $penyewaan_id;
            
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }
    
    // Update status penyewaan
    public function updateStatus($id, $status) {
        $query = "UPDATE penyewaan SET status = :status WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
    
    // Mengembalikan penyewaan (update status dan stok)
    public function returnItems($penyewaan_id, $items) {
        $this->db->beginTransaction();
        
        try {
            foreach ($items as $item_id => $status) {
                // Update status detail penyewaan
                $query = "UPDATE detail_penyewaan SET status = :status WHERE id = :id";
                $stmt = $this->db->prepare($query);
                $stmt->bindParam(':status', $status);
                $stmt->bindParam(':id', $item_id);
                $stmt->execute();
                
                // Ambil data detail penyewaan
                $query = "SELECT peralatan_id, jumlah FROM detail_penyewaan WHERE id = :id";
                $stmt = $this->db->prepare($query);
                $stmt->bindParam(':id', $item_id);
                $stmt->execute();
                $detail = $stmt->fetch();
                
                // Update stok jika status "dikembalikan"
                if ($status == 'dikembalikan') {
                    $query = "UPDATE peralatan SET stok = stok + :jumlah WHERE id = :peralatan_id";
                    $stmt = $this->db->prepare($query);
                    $stmt->bindParam(':jumlah', $detail['jumlah']);
                    $stmt->bindParam(':peralatan_id', $detail['peralatan_id']);
                    $stmt->execute();
                }
            }
            
            // Cek apakah semua item telah dikembalikan
            $query = "SELECT COUNT(*) as total FROM detail_penyewaan WHERE penyewaan_id = :penyewaan_id AND status != 'dikembalikan'";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':penyewaan_id', $penyewaan_id);
            $stmt->execute();
            $result = $stmt->fetch();
            
            // Jika semua sudah dikembalikan, update status penyewaan
            if ($result['total'] == 0) {
                $this->updateStatus($penyewaan_id, 'selesai');
            }
            
            $this->db->commit();
            return true;
            
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }
    
    // Batalkan penyewaan
    public function cancel($id) {
        $this->db->beginTransaction();
        
        try {
            // Update status penyewaan
            $this->updateStatus($id, 'batal');
            
            // Kembalikan stok peralatan
            $query = "SELECT peralatan_id, jumlah FROM detail_penyewaan WHERE penyewaan_id = :penyewaan_id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':penyewaan_id', $id);
            $stmt->execute();
            $items = $stmt->fetchAll();
            
            foreach ($items as $item) {
                $query = "UPDATE peralatan SET stok = stok + :jumlah WHERE id = :peralatan_id";
                $stmt = $this->db->prepare($query);
                $stmt->bindParam(':jumlah', $item['jumlah']);
                $stmt->bindParam(':peralatan_id', $item['peralatan_id']);
                $stmt->execute();
            }
            
            $this->db->commit();
            return true;
            
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }
    
    // Hapus penyewaan (hanya untuk admin dan hanya penyewaan yang sudah batal/selesai)
    public function delete($id) {
        $query = "DELETE FROM penyewaan WHERE id = :id AND (status = 'batal' OR status = 'selesai')";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}