<?php
// models/laporanModels.php

function countLaporan($conn, $search = null, $timeframe = 'all') {
    $sql = "SELECT COUNT(*) as total 
            FROM peminjaman p
            JOIN members m ON p.member_id = m.id
            JOIN buku b ON p.buku_id = b.id
            WHERE p.status = 'returned'";

    $params = [];
    $types = '';

    if ($timeframe == 'harian') {
        $sql .= " AND p.tanggal_kembali = CURDATE()";
    } elseif ($timeframe == 'mingguan') {
        $sql .= " AND p.tanggal_kembali >= CURDATE() - INTERVAL 6 DAY";
    } elseif ($timeframe == 'bulanan') {
        $sql .= " AND p.tanggal_kembali >= CURDATE() - INTERVAL 1 MONTH";
    }

    if ($search) {
        $searchTerm = "%" . $search . "%";
        $sql .= " AND (m.name LIKE ? OR m.nisn LIKE ? OR b.judul_buku LIKE ?)";
        $params = [$searchTerm, $searchTerm, $searchTerm];
        $types = 'sss';
    }
    
    $stmt = $conn->prepare($sql);
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc()['total'];
}

function getLaporan($conn, $search = null, $timeframe = 'all', $limit, $offset) {
    $sql = "SELECT p.tanggal_pinjam, p.tanggal_kembali, m.nisn, m.name, b.judul_buku
            FROM peminjaman p
            JOIN members m ON p.member_id = m.id
            JOIN buku b ON p.buku_id = b.id
            WHERE p.status = 'returned'";

    $params = [];
    $types = '';

    if ($timeframe == 'harian') {
        $sql .= " AND p.tanggal_kembali = CURDATE()";
    } elseif ($timeframe == 'mingguan') {
        $sql .= " AND p.tanggal_kembali >= CURDATE() - INTERVAL 6 DAY";
    } elseif ($timeframe == 'bulanan') {
        $sql .= " AND p.tanggal_kembali >= CURDATE() - INTERVAL 1 MONTH";
    }

    if ($search) {
        $searchTerm = "%" . $search . "%";
        $sql .= " AND (m.name LIKE ? OR m.nisn LIKE ? OR b.judul_buku LIKE ?)";
        $params = [$searchTerm, $searchTerm, $searchTerm];
        $types = 'sss';
    }
    
    $sql .= " ORDER BY p.tanggal_kembali DESC LIMIT ? OFFSET ?";
    
    $params[] = $limit;
    $params[] = $offset;
    $types .= 'ii';
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);
    
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}
?>