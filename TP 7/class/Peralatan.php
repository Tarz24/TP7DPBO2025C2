<?php
// class/Peralatan.php - Class untuk mengelola peralatan kemah

class Peralatan {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    // Mendapatkan semua peralatan
    public function getAll() {
        $query = "SELECT p.*, k.nama AS kategori_nama 
                  FROM peralatan p 
                  JOIN kategori k ON p.kategori_id = k.id 
                  ORDER BY p.nama ASC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    // Mendapatkan peralatan berdasarkan ID
    public function getById($id) {
        $query = "SELECT p.*, k.nama AS kategori_nama 
                  FROM peralatan p 
                  JOIN kategori k ON p.kategori_id = k.id 
                  WHERE p.id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }
    
    // Mencari peralatan berdasarkan nama
    public function search($keyword) {
        $query = "SELECT p.*, k.nama AS kategori_nama 
                  FROM peralatan p 
                  JOIN kategori k ON p.kategori_id = k.id 
                  WHERE p.nama LIKE :keyword OR p.deskripsi LIKE :keyword 
                  ORDER BY p.nama ASC";
        $keyword = "%$keyword%";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':keyword', $keyword);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    // Tambah peralatan baru
    public function create($data) {
        $query = "INSERT INTO peralatan (nama, kategori_id, stok, harga_sewa, deskripsi, status) 
                  VALUES (:nama, :kategori_id, :stok, :harga_sewa, :deskripsi, :status)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':nama', $data['nama']);
        $stmt->bindParam(':kategori_id', $data['kategori_id']);
        $stmt->bindParam(':stok', $data['stok']);
        $stmt->bindParam(':harga_sewa', $data['harga_sewa']);
        $stmt->bindParam(':deskripsi', $data['deskripsi']);
        $stmt->bindParam(':status', $data['status']);
        return $stmt->execute();
    }
    
    // Update peralatan
    public function update($id, $data) {
        $query = "UPDATE peralatan SET 
                  nama = :nama, 
                  kategori_id = :kategori_id, 
                  stok = :stok, 
                  harga_sewa = :harga_sewa, 
                  deskripsi = :deskripsi, 
                  status = :status 
                  WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':nama', $data['nama']);
        $stmt->bindParam(':kategori_id', $data['kategori_id']);
        $stmt->bindParam(':stok', $data['stok']);
        $stmt->bindParam(':harga_sewa', $data['harga_sewa']);
        $stmt->bindParam(':deskripsi', $data['deskripsi']);
        $stmt->bindParam(':status', $data['status']);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
    
    // Hapus peralatan
    public function delete($id) {
        $query = "DELETE FROM peralatan WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
    
    // Update stok peralatan
    public function updateStok($id, $jumlah) {
        $query = "UPDATE peralatan SET stok = stok + :jumlah WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':jumlah', $jumlah);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
    
    // Mendapatkan peralatan berdasarkan kategori
    public function getByKategori($kategori_id) {
        $query = "SELECT p.*, k.nama AS kategori_nama 
                  FROM peralatan p 
                  JOIN kategori k ON p.kategori_id = k.id 
                  WHERE p.kategori_id = :kategori_id 
                  ORDER BY p.nama ASC";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':kategori_id', $kategori_id);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}