<?php
// models/bukuModels.php

// ▼▼▼ FUNGSI BARU: Ambil semua kategori untuk dropdown ▼▼▼
function getAllKategori($conn) {
    $sql = "SELECT * FROM kategori ORDER BY nama ASC";
    $result = $conn->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}
// ▲▲▲ AKHIR FUNGSI BARU ▲▲▲

function getAllBuku($conn, $search = null, $limit, $offset) {
    // Tambahkan kategori_id dan join ke tabel kategori untuk ambil nama kategori
    $sql = "SELECT b.*, k.nama as nama_kategori 
            FROM buku b
            LEFT JOIN kategori k ON b.kategori_id = k.id";
            
    $searchTerm = "%" . $search . "%";
    
    if ($search) {
        // Tambahkan pencarian kategori
        $sql .= " WHERE (b.judul_buku LIKE ? OR b.kode_buku LIKE ? OR b.penulis LIKE ? OR b.penerbit LIKE ? OR k.nama LIKE ?)";
    }
    
    $sql .= " ORDER BY b.judul_buku ASC LIMIT ? OFFSET ?";
    $stmt = $conn->prepare($sql);
    
    if ($search) {
        // sssssii (5 string pencarian + 2 integer limit/offset)
        $stmt->bind_param("sssssii", $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm, $limit, $offset);
    } else {
        $stmt->bind_param("ii", $limit, $offset);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

function countAllBuku($conn, $search = null) {
    $sql = "SELECT COUNT(*) as total FROM buku b LEFT JOIN kategori k ON b.kategori_id = k.id";
    $searchTerm = "%" . $search . "%";
    
    if ($search) {
        $sql .= " WHERE (b.judul_buku LIKE ? OR b.kode_buku LIKE ? OR b.penulis LIKE ? OR b.penerbit LIKE ? OR k.nama LIKE ?)";
    }
    
    $stmt = $conn->prepare($sql);
    
    if ($search) {
        $stmt->bind_param("sssss", $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc()['total'];
}

function getBukuById($conn, $id) {
    $sql = "SELECT * FROM buku WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

function insertBuku($conn, $data) {
    // Tambahkan kategori_id
    $sql = "INSERT INTO buku 
                (kode_buku, judul_buku, penulis, isbn, penerbit, tahun_terbit, total_copy, salinan_tersedia, gambar, kelas, kurikulum, kategori_id) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    $salinan_tersedia = $data['total_copy']; 

    // Tambah tipe data 'i' di akhir untuk kategori_id
    $stmt->bind_param("ssssssissssi", 
        $data['kode_buku'], 
        $data['judul_buku'], 
        $data['penulis'],
        $data['isbn'],
        $data['penerbit'],
        $data['tahun_terbit'],
        $data['total_copy'],      
        $salinan_tersedia,       
        $data['gambar'],
        $data['kelas'],
        $data['kurikulum'],
        $data['kategori_id'] // <-- Ditambahkan
    );
    
    return $stmt->execute();
}

function updateBuku($conn, $data) {
    // Tambahkan kategori_id
    $sql = "UPDATE buku SET 
                kode_buku = ?, 
                judul_buku = ?, 
                penulis = ?, 
                isbn = ?, 
                penerbit = ?, 
                tahun_terbit = ?, 
                total_copy = ?, 
                salinan_tersedia = ?, 
                gambar = ?,
                kelas = ?,
                kurikulum = ?,
                kategori_id = ?
            WHERE id = ?";
    $stmt = $conn->prepare($sql);
    
    // Tambah tipe data 'i' sebelum id
    $stmt->bind_param("ssssssissssii", 
        $data['kode_buku'], 
        $data['judul_buku'], 
        $data['penulis'],
        $data['isbn'],
        $data['penerbit'],
        $data['tahun_terbit'],
        $data['total_copy'],      
        $data['salinan_tersedia'],
        $data['gambar'],
        $data['kelas'],
        $data['kurikulum'],
        $data['kategori_id'], // <-- Ditambahkan
        $data['id']
    );
    
    return $stmt->execute();
}

function deleteBuku($conn, $id) {
    $sql = "DELETE FROM buku WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}
?>