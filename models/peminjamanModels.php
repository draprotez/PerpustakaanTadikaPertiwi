<?php
//peminjamanModels.php
function countAllPeminjamanAktif($conn, $search = null) {
    $sql = "SELECT COUNT(*) as total 
            FROM peminjaman p
            JOIN members m ON p.member_id = m.id
            JOIN buku b ON p.buku_id = b.id
            WHERE p.status != 'returned'";
            
    $searchTerm = "%" . $search . "%";

    if ($search) {
        $sql .= " AND (m.name LIKE ? OR b.judul_buku LIKE ? OR m.kode_member LIKE ?)";
    }
    
    $stmt = $conn->prepare($sql);
    if ($search) {
        $stmt->bind_param("sss", $searchTerm, $searchTerm, $searchTerm);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc()['total'];
}

function getAllPeminjamanAktif($conn, $search = null, $limit, $offset) {
    $sql = "SELECT 
                p.id as peminjaman_id, 
                p.buku_id,
                p.status,
                p.tanggal_pinjam,
                p.tenggat_waktu,
                b.judul_buku,
                b.kode_buku,
                m.name as nama_member,
                m.kode_member
            FROM peminjaman p
            JOIN members m ON p.member_id = m.id
            JOIN buku b ON p.buku_id = b.id
            WHERE p.status != 'returned'";
    
    $searchTerm = "%" . $search . "%";

    if ($search) {
        $sql .= " AND (m.name LIKE ? OR b.judul_buku LIKE ? OR m.kode_member LIKE ?)";
    }
    
    $sql .= " ORDER BY p.tenggat_waktu ASC LIMIT ? OFFSET ?";
    $stmt = $conn->prepare($sql);

    if ($search) {
        $stmt->bind_param("sssii", $searchTerm, $searchTerm, $searchTerm, $limit, $offset);
    } else {
        $stmt->bind_param("ii", $limit, $offset);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

function getAllMembers($conn) {
    $sql = "SELECT id, name, kode_member FROM members WHERE status = 'active' ORDER BY name ASC";
    $result = $conn->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}

function getAllAvailableBuku($conn) {
    $sql = "SELECT id, judul_buku, kode_buku FROM buku WHERE salinan_tersedia > 0 ORDER BY judul_buku ASC";
    $result = $conn->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}

function insertPeminjaman($conn, $data) {
    $conn->begin_transaction();
    try {
        $sql_check_stock = "SELECT salinan_tersedia FROM buku WHERE id = ? FOR UPDATE";
        $stmt_check = $conn->prepare($sql_check_stock);
        $stmt_check->bind_param("i", $data['buku_id']);
        $stmt_check->execute();
        $stock = $stmt_check->get_result()->fetch_assoc();
        $stmt_check->close();

        if ($stock['salinan_tersedia'] <= 0) {
            $conn->rollback();
            return "Stok buku habis!";
        }
        
        $sql_update_buku = "UPDATE buku SET salinan_tersedia = salinan_tersedia - 1 WHERE id = ?";
        $stmt_buku = $conn->prepare($sql_update_buku);
        $stmt_buku->bind_param("i", $data['buku_id']);
        $stmt_buku->execute();
        $stmt_buku->close();
        
        $sql_pinjam = "INSERT INTO peminjaman 
                        (member_id, buku_id, tanggal_pinjam, tenggat_waktu, status, created_by) 
                       VALUES (?, ?, CURDATE(), ?, 'borrowed', ?)";
        $stmt_pinjam = $conn->prepare($sql_pinjam);
        $stmt_pinjam->bind_param("iisi",
            $data['member_id'],
            $data['buku_id'],
            $data['tenggat_waktu'],
            $data['created_by']
        );
        $stmt_pinjam->execute();
        $stmt_pinjam->close();

        $conn->commit();
        return true;
        
    } catch (Exception $e) {
        $conn->rollback();
        return $e->getMessage();
    }
}

function returnBuku($conn, $peminjaman_id, $buku_id) {
    $conn->begin_transaction();
    
    try {
        $sql_update_pinjam = "UPDATE peminjaman SET status = 'returned', tanggal_kembali = CURDATE() 
                              WHERE id = ? AND status != 'returned'";
        $stmt_pinjam = $conn->prepare($sql_update_pinjam);
        $stmt_pinjam->bind_param("i", $peminjaman_id);
        $stmt_pinjam->execute();
        $rows_affected = $stmt_pinjam->affected_rows;
        $stmt_pinjam->close();

        if ($rows_affected > 0) {
            $sql_update_buku = "UPDATE buku SET salinan_tersedia = salinan_tersedia + 1 WHERE id = ?";
            $stmt_buku = $conn->prepare($sql_update_buku);
            $stmt_buku->bind_param("i", $buku_id);
            $stmt_buku->execute();
            $stmt_buku->close();
        } else {
            $conn->rollback();
            return "Buku sudah dikembalikan sebelumnya.";
        }
        
        $conn->commit();
        return true;

    } catch (Exception $e) {
        $conn->rollback();
        return $e->getMessage();
    }
}
?>