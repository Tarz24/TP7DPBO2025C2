<?php
// class/Kategori.php - Class untuk mengelola kategori peralatan

class Kategori {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    // Mendapatkan semua kategori
    public function getAll() {
        $query = "SELECT * FROM kategori ORDER BY nama ASC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    // Mendapatkan kategori berdasarkan ID
    public function getById($id) {
        $query = "SELECT * FROM kategori WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }
    
    // Mencari kategori berdasarkan nama
    public function search($keyword) {
        $query = "SELECT * FROM kategori WHERE nama LIKE :keyword OR deskripsi LIKE :keyword ORDER BY nama ASC";
        $keyword = "%$keyword%";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':keyword', $keyword);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    // Tambah kategori baru
    public function create($data) {
        $query = "INSERT INTO kategori (nama, deskripsi) VALUES (:nama, :deskripsi)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':nama', $data['nama']);
        $stmt->bindParam(':deskripsi', $data['deskripsi']);
        return $stmt->execute();
    }
    
    // Update kategori
    public function update($id, $data) {
        $query = "UPDATE kategori SET nama = :nama, deskripsi = :deskripsi WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':nama', $data['nama']);
        $stmt->bindParam(':deskripsi', $data['deskripsi']);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
    
    // Hapus kategori
    public function delete($id) {
        $query = "DELETE FROM kategori WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
    
    // Hitung jumlah peralatan dalam kategori
    public function countPeralatan($id) {
        $query = "SELECT COUNT(*) as total FROM peralatan WHERE kategori_id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result['total'];
    }
}