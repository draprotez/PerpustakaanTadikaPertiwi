<?php
class Buku {
    private $conn;
    private $table_name = "buku";    public $id;
    public $judul_buku;
    public $penulis;
    public $isbn;
    public $penerbit;
    public $tahun_terbit;
    public $total_copy;
    public $salinan_tersedia;
    public $lokasi_rak;
    public $kode_buku;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function readAll() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY judul_buku ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                  (kode_buku, judul_buku, penulis, isbn, penerbit, tahun_terbit, total_copy, salinan_tersedia, lokasi_rak) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->conn->prepare($query);

        $this->kode_buku = htmlspecialchars(strip_tags($this->kode_buku));
        $this->judul_buku = htmlspecialchars(strip_tags($this->judul_buku));
        $this->penulis = htmlspecialchars(strip_tags($this->penulis));
        $this->isbn = htmlspecialchars(strip_tags($this->isbn));
        $this->penerbit = htmlspecialchars(strip_tags($this->penerbit));
        $this->tahun_terbit = htmlspecialchars(strip_tags($this->tahun_terbit));
        $this->total_copy = htmlspecialchars(strip_tags($this->total_copy));
        $this->salinan_tersedia = htmlspecialchars(strip_tags($this->salinan_tersedia));
        $this->lokasi_rak = htmlspecialchars(strip_tags($this->lokasi_rak));

        $stmt->bind_param(
            "ssssssiis",
            $this->kode_buku,
            $this->judul_buku,
            $this->penulis,
            $this->isbn,
            $this->penerbit,
            $this->tahun_terbit,
            $this->total_copy,
            $this->salinan_tersedia,
            $this->lokasi_rak
        );
        
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>