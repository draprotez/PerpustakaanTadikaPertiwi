<?php
// models/bukuModels.php
function getAllBuku($conn, $search = null, $limit, $offset) {
    $sql = "SELECT id, judul_buku, penulis, isbn, penerbit, tahun_terbit, total_copy, salinan_tersedia, gambar, kode_buku, kelas FROM buku";
    $searchTerm = "%" . $search . "%";
    if ($search) {
        $sql .= " WHERE (judul_buku LIKE ? OR kode_buku LIKE ? OR penulis LIKE ? OR penerbit LIKE ?)";
    }
    $sql .= " ORDER BY judul_buku ASC LIMIT ? OFFSET ?";
    $stmt = $conn->prepare($sql);
    if ($search) {
        $stmt->bind_param("ssssii", $searchTerm, $searchTerm, $searchTerm, $searchTerm, $limit, $offset);
    } else {
        $stmt->bind_param("ii", $limit, $offset);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

function countAllBuku($conn, $search = null) {
    $sql = "SELECT COUNT(*) as total FROM buku";
    $searchTerm = "%" . $search . "%";
    if ($search) {
        $sql .= " WHERE (judul_buku LIKE ? OR kode_buku LIKE ? OR penulis LIKE ? OR penerbit LIKE ?)";
    }
    $stmt = $conn->prepare($sql);
    if ($search) {
        $stmt->bind_param("ssss", $searchTerm, $searchTerm, $searchTerm, $searchTerm);
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
    $sql = "INSERT INTO buku 
                (kode_buku, judul_buku, penulis, isbn, penerbit, tahun_terbit, total_copy, salinan_tersedia, gambar, kelas) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    $salinan_tersedia = $data['total_copy']; 

    $stmt->bind_param("ssssssisss", 
        $data['kode_buku'], 
        $data['judul_buku'], 
        $data['penulis'],
        $data['isbn'],
        $data['penerbit'],
        $data['tahun_terbit'],
        $data['total_copy'],
        $salinan_tersedia,
        $data['gambar'],
        $data['kelas']
    );
    return $stmt->execute();
}

function updateBuku($conn, $data) {
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
                kelas = ?
            WHERE id = ?";
    $stmt = $conn->prepare($sql);
    
    $stmt->bind_param("ssssssisssi", 
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