<?php
// models/carouselModels.php

/**
 * 1. FUNGSI UNTUK TAMPIL DI INDEX & ADMIN LIST
 * Mengambil data buku yang SUDAH ADA di carousel.
 */
function getCarouselBooks($conn) {
    $sql = "SELECT hb.*, b.judul_buku, b.penulis, b.kode_buku, b.gambar 
            FROM homepage_books hb
            INNER JOIN buku b ON hb.buku_id = b.id
            ORDER BY hb.urutan ASC, hb.id DESC";
    $result = mysqli_query($conn, $sql);
    
    $books = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $books[] = $row;
    }
    return $books;
}

/**
 * 2. FUNGSI UNTUK TAMPIL DI INDEX (Hanya yang Aktif)
 */
function getActiveCarouselBooks($conn) {
    $sql = "SELECT b.judul_buku, b.gambar, b.penulis, b.kode_buku 
            FROM homepage_books hb
            INNER JOIN buku b ON hb.buku_id = b.id
            WHERE hb.is_active = 1
            ORDER BY hb.urutan ASC";
    $result = mysqli_query($conn, $sql);
    
    $books = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $books[] = $row;
    }
    return $books;
}

/**
 * 3. FUNGSI PENGAMBIL DATA BUKU DARI TABEL BUKU (UNTUK DROPDOWN)
 * --- DIPERBAIKI ---
 * Sekarang mengambil SEMUA buku, meskipun belum ada gambarnya.
 */
function getAllBooksOption($conn) {
    // Kita hapus syarat "WHERE gambar IS NOT NULL" agar semua buku muncul
    $sql = "SELECT id, judul_buku, penulis, kode_buku 
            FROM buku 
            WHERE id NOT IN (SELECT buku_id FROM homepage_books)
            ORDER BY judul_buku ASC";
            
    $result = mysqli_query($conn, $sql);
    
    $books = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $books[] = $row;
    }
    return $books;
}

/**
 * 4. FUNGSI UNTUK MEMASUKKAN KE TABEL CAROUSEL
 */
function addBookToCarousel($conn, $buku_id) {
    $buku_id = mysqli_real_escape_string($conn, $buku_id);
    
    // Cek duplikasi
    $check_sql = "SELECT id FROM homepage_books WHERE buku_id = '$buku_id'";
    if (mysqli_num_rows(mysqli_query($conn, $check_sql)) > 0) {
        return "Buku sudah ada di carousel!";
    }
    
    // Set urutan otomatis di akhir
    $max_query = mysqli_query($conn, "SELECT MAX(urutan) as max_urutan FROM homepage_books");
    $max_row = mysqli_fetch_assoc($max_query);
    $urutan = ($max_row['max_urutan'] ?? 0) + 1;
    
    // Insert ke tabel homepage_books
    $sql = "INSERT INTO homepage_books (buku_id, urutan, is_active) VALUES ('$buku_id', '$urutan', 1)";
    
    if (mysqli_query($conn, $sql)) {
        return true;
    } else {
        return "Gagal menambahkan: " . mysqli_error($conn);
    }
}

/**
 * 5. FUNGSI HAPUS DARI CAROUSEL
 */
function removeBookFromCarousel($conn, $id) {
    $id = mysqli_real_escape_string($conn, $id);
    $sql = "DELETE FROM homepage_books WHERE id = '$id'";
    return mysqli_query($conn, $sql) ? true : mysqli_error($conn);
}

/**
 * 6. FUNGSI TOGGLE STATUS
 */
function toggleCarouselStatus($conn, $id) {
    $id = mysqli_real_escape_string($conn, $id);
    $sql = "UPDATE homepage_books SET is_active = NOT is_active WHERE id = '$id'";
    return mysqli_query($conn, $sql) ? true : mysqli_error($conn);
}

/**
 * 7. FUNGSI UPDATE URUTAN
 */
function updateCarouselOrder($conn, $urutan_array) {
    foreach ($urutan_array as $id => $urutan) {
        $id = mysqli_real_escape_string($conn, $id);
        $urutan = mysqli_real_escape_string($conn, $urutan);
        mysqli_query($conn, "UPDATE homepage_books SET urutan = '$urutan' WHERE id = '$id'");
    }
    return true;
}
?>