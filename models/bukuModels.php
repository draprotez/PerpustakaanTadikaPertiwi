<?php
// bukuModels.php
function getAllBuku($conn) {
    $sql = "SELECT * FROM buku ORDER BY judul_buku ASC";
    $result = $conn->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC); 
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
    //
    $sql = "INSERT INTO buku 
                (kode_buku, judul_buku, penulis, isbn, penerbit, tahun_terbit, total_copy, salinan_tersedia, lokasi_rak) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    $salinan_tersedia = $data['total_copy']; 
    
    $stmt->bind_param("ssssssiis", 
        $data['kode_buku'], 
        $data['judul_buku'], 
        $data['penulis'],
        $data['isbn'],
        $data['penerbit'],
        $data['tahun_terbit'],
        $data['total_copy'],
        $salinan_tersedia,
        $data['lokasi_rak']
    );
    return $stmt->execute();
}

function updateBuku($conn, $data) {
    //
    $sql = "UPDATE buku SET 
                kode_buku = ?, 
                judul_buku = ?, 
                penulis = ?, 
                isbn = ?, 
                penerbit = ?, 
                tahun_terbit = ?, 
                total_copy = ?, 
                salinan_tersedia = ?, 
                lokasi_rak = ?
            WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssiisi", 
        $data['kode_buku'], 
        $data['judul_buku'], 
        $data['penulis'],
        $data['isbn'],
        $data['penerbit'],
        $data['tahun_terbit'],
        $data['total_copy'],
        $data['salinan_tersedia'],
        $data['lokasi_rak'],
        $data['id']
    );
    return $stmt->execute();
}

function deleteBuku($conn, $id) {
    //
    $sql = "DELETE FROM buku WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}
?>