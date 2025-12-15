<?php
// models/memberModels.php

function countAllMembers($conn, $search = null) {
    $sql = "SELECT COUNT(*) as total FROM members";
    $searchTerm = "%" . $search . "%";

    if ($search) {
        // Ganti nuptk dengan kode_guru
        $sql .= " WHERE (name LIKE ? OR kode_member LIKE ? OR username LIKE ? OR nisn LIKE ? OR kode_guru LIKE ?)";
    }
    
    $stmt = $conn->prepare($sql);
    if ($search) {
        $stmt->bind_param("sssss", $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc()['total'];
}

function getAllMembers($conn, $search = null, $limit, $offset) {
    // HAPUS kolom lama, ganti nuptk jadi kode_guru
    $sql = "SELECT id, kode_member, name, username, type, status, nisn, kode_guru 
            FROM members";
    
    $searchTerm = "%" . $search . "%";

    if ($search) {
        $sql .= " WHERE (name LIKE ? OR kode_member LIKE ? OR username LIKE ? OR nisn LIKE ? OR kode_guru LIKE ?)";
    }
    
    $sql .= " ORDER BY name ASC LIMIT ? OFFSET ?";
    $stmt = $conn->prepare($sql);

    if ($search) {
        $stmt->bind_param("sssssii", $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm, $limit, $offset);
    } else {
        $stmt->bind_param("ii", $limit, $offset);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

function getMemberById($conn, $id) {
    $sql = "SELECT * FROM members WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

function insertMember($conn, $data) {
    // Sesuaikan kolom insert dengan database baru
    $sql = "INSERT INTO members 
                (kode_member, username, password, name, type, nisn, kode_guru, status, registrasi) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, CURDATE())";
    $stmt = $conn->prepare($sql);
    
    $hashed_password = password_hash($data['password'], PASSWORD_DEFAULT);
    
    // Generate Kode Member
    $prefix = ($data['type'] == 'guru') ? 'TP-' : 'S-';
    $kode_member = $prefix . strtoupper(uniqid());

    // Siapkan parameter (nisn null jika guru, kode_guru null jika siswa)
    $nisn = ($data['type'] == 'siswa') ? $data['nisn'] : null;
    $kode_guru = ($data['type'] == 'guru') ? $data['kode_guru'] : null;

    $stmt->bind_param("ssssssss", 
        $kode_member,
        $data['username'],
        $hashed_password,
        $data['name'],
        $data['type'],
        $nisn,
        $kode_guru,
        $data['status']
    );
    return $stmt->execute();
}

function updateMember($conn, $data) {
    // Siapkan parameter
    $nisn = ($data['type'] == 'siswa') ? $data['nisn'] : null;
    $kode_guru = ($data['type'] == 'guru') ? $data['kode_guru'] : null;

    if (!empty($data['password'])) {
        $sql = "UPDATE members SET 
                    username = ?, password = ?, name = ?, type = ?, 
                    nisn = ?, kode_guru = ?, status = ?
                WHERE id = ?";
        $stmt = $conn->prepare($sql);
        
        $hashed_password = password_hash($data['password'], PASSWORD_DEFAULT);
        
        $stmt->bind_param("sssssssi", 
            $data['username'], $hashed_password, $data['name'], $data['type'],
            $nisn, $kode_guru, $data['status'],
            $data['id']
        );
    } else {
        $sql = "UPDATE members SET 
                    username = ?, name = ?, type = ?, 
                    nisn = ?, kode_guru = ?, status = ?
                WHERE id = ?";
        $stmt = $conn->prepare($sql);
        
        $stmt->bind_param("ssssssi", 
            $data['username'], $data['name'], $data['type'],
            $nisn, $kode_guru, $data['status'],
            $data['id']
        );
    }
    return $stmt->execute();
}

function deleteMember($conn, $id) {
    $sql = "DELETE FROM members WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}
?>