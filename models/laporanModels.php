<?php
// models/laporanModels.php

function countLaporan($conn, $search = null, $timeframe = 'all', $status = 'all') {
    $sql = "SELECT COUNT(*) as total 
            FROM peminjaman p
            JOIN members m ON p.member_id = m.id
            JOIN buku b ON p.buku_id = b.id
            WHERE 1=1"; // Base condition

    $params = [];
    $types = '';

    // 1. Filter Status
    if ($status == 'returned') {
        $sql .= " AND p.status = 'returned'";
    } elseif ($status == 'borrowed') {
        $sql .= " AND p.status IN ('borrowed', 'overdue')"; // Termasuk yang telat
    }
    // Jika 'all', tidak ada filter status (ambil semua)

    // 2. Filter Waktu
    // Jika 'returned', filter berdasarkan tanggal_kembali
    // Jika 'borrowed' atau 'all', filter berdasarkan tanggal_pinjam (agar relevan)
    $dateColumn = ($status == 'returned') ? 'p.tanggal_kembali' : 'p.tanggal_pinjam';

    if ($timeframe == 'harian') {
        $sql .= " AND $dateColumn = CURDATE()";
    } elseif ($timeframe == 'mingguan') {
        $sql .= " AND $dateColumn >= CURDATE() - INTERVAL 6 DAY";
    } elseif ($timeframe == 'bulanan') {
        $sql .= " AND $dateColumn >= CURDATE() - INTERVAL 1 MONTH";
    }

    // 3. Filter Search
    if ($search) {
        $searchTerm = "%" . $search . "%";
        $sql .= " AND (m.name LIKE ? OR m.nisn LIKE ? OR b.judul_buku LIKE ?)";
        $params[] = $searchTerm;
        $params[] = $searchTerm;
        $params[] = $searchTerm;
        $types .= 'sss';
    }
    
    $stmt = $conn->prepare($sql);
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc()['total'];
}

function getLaporan($conn, $search = null, $timeframe = 'all', $status = 'all', $limit, $offset) {
    // Tambahkan p.tenggat_waktu dan p.status
    $sql = "SELECT p.tanggal_pinjam, p.tanggal_kembali, p.tenggat_waktu, p.status, 
                   m.nisn, m.name, b.judul_buku
            FROM peminjaman p
            JOIN members m ON p.member_id = m.id
            JOIN buku b ON p.buku_id = b.id
            WHERE 1=1";

    $params = [];
    $types = '';

    // 1. Filter Status
    if ($status == 'returned') {
        $sql .= " AND p.status = 'returned'";
    } elseif ($status == 'borrowed') {
        $sql .= " AND p.status IN ('borrowed', 'overdue')";
    }

    // 2. Filter Waktu
    $dateColumn = ($status == 'returned') ? 'p.tanggal_kembali' : 'p.tanggal_pinjam';

    if ($timeframe == 'harian') {
        $sql .= " AND $dateColumn = CURDATE()";
    } elseif ($timeframe == 'mingguan') {
        $sql .= " AND $dateColumn >= CURDATE() - INTERVAL 6 DAY";
    } elseif ($timeframe == 'bulanan') {
        $sql .= " AND $dateColumn >= CURDATE() - INTERVAL 1 MONTH";
    }

    // 3. Filter Search
    if ($search) {
        $searchTerm = "%" . $search . "%";
        $sql .= " AND (m.name LIKE ? OR m.nisn LIKE ? OR b.judul_buku LIKE ?)";
        $params[] = $searchTerm;
        $params[] = $searchTerm;
        $params[] = $searchTerm;
        $types .= 'sss';
    }
    
    // Urutkan: Yang belum kembali di atas, lalu berdasarkan tanggal terbaru
    $sql .= " ORDER BY FIELD(p.status, 'overdue', 'borrowed', 'returned'), p.tanggal_pinjam DESC LIMIT ? OFFSET ?";
    
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