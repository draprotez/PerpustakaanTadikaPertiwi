<?php
// models/userModels.php

function getUserById($conn, $id) {
    $sql = "SELECT id, username, name, role FROM user WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

function updateProfile($conn, $data) {
    if (!empty($data['password'])) {
        $hashed_password = password_hash($data['password'], PASSWORD_DEFAULT);
        
        $sql = "UPDATE user SET 
                    username = ?, 
                    password = ?, 
                    name = ?
                WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", 
            $data['username'],
            $hashed_password,
            $data['name'],
            $data['id']
        );
        
    } else {
        $sql = "UPDATE user SET 
                    username = ?, 
                    name = ?
                WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", 
            $data['username'],
            $data['name'],
            $data['id']
        );
    }
    
    return $stmt->execute();
}
?>