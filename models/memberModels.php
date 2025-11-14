<?php
// models/memberModels.php

function countAllMembers($conn, $search = null) {
    $sql = "SELECT COUNT(*) as total FROM members";
    $searchTerm = "%" . $search . "%";

    if ($search) {
        $sql .= " WHERE (name LIKE ? OR kode_member LIKE ? OR username LIKE ? OR nisn LIKE ? OR nuptk LIKE ?)";
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
    $sql = "SELECT id, kode_member, name, username, type, kelas, keterangan, status 
            FROM members";
    
    $searchTerm = "%" . $search . "%";

    if ($search) {
        $sql .= " WHERE (name LIKE ? OR kode_member LIKE ? OR username LIKE ? OR nisn LIKE ? OR nuptk LIKE ?)";
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
    $sql = "INSERT INTO members 
                (kode_member, username, password, name, type, nisn, nis, nuptk, nip, kelas, keterangan, status, registrasi) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, CURDATE())";
    $stmt = $conn->prepare($sql);
    
    $hashed_password = password_hash($data['password'], PASSWORD_DEFAULT);
    
    $prefix = ($data['type'] == 'siswa') ? 'S-' : 'G-';
    $kode_member = $prefix . strtoupper(uniqid());

    $keterangan = ($data['type'] == 'guru') ? $data['keterangan'] : null;

    $stmt->bind_param("ssssssssssss", 
        $kode_member,
        $data['username'],
        $hashed_password,
        $data['name'],
        $data['type'],
        $data['nisn'],
        $data['nis'],
        $data['nuptk'],
        $data['nip'],
        $data['kelas'],
        $keterangan,
        $data['status']
    );
    return $stmt->execute();
}

function updateMember($conn, $data) {
    if (!empty($data['password'])) {
        $sql = "UPDATE members SET 
                    username = ?, password = ?, name = ?, type = ?, 
                    nisn = ?, nis = ?, nuptk = ?, nip = ?, 
                    kelas = ?, keterangan = ?, status = ?
                WHERE id = ?";
        $stmt = $conn->prepare($sql);
        
        $hashed_password = password_hash($data['password'], PASSWORD_DEFAULT);
        
        $keterangan = ($data['type'] == 'guru') ? $data['keterangan'] : null;
        
        $stmt->bind_param("sssssssssssi", 
            $data['username'], $hashed_password, $data['name'], $data['type'],
            $data['nisn'], $data['nis'], $data['nuptk'], $data['nip'],
            $data['kelas'], $keterangan, $data['status'],
            $data['id']
        );
    } else {
        $sql = "UPDATE members SET 
                    username = ?, name = ?, type = ?, 
                    nisn = ?, nis = ?, nuptk = ?, nip = ?, 
                    kelas = ?, keterangan = ?, status = ?
                WHERE id = ?";
        $stmt = $conn->prepare($sql);
        
        $keterangan = ($data['type'] == 'guru') ? $data['keterangan'] : null;
        
        $stmt->bind_param("ssssssssssi", 
            $data['username'], $data['name'], $data['type'],
            $data['nisn'], $data['nis'], $data['nuptk'], $data['nip'],
            $data['kelas'], $keterangan, $data['status'],
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