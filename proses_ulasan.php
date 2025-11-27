<?php
include 'koneksi.php';

if (!$conn) {
    header("Location: index.php?status=error&msg=Koneksi database gagal: " . urlencode(mysqli_connect_error()) . "#kontak");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama'] ?? '';
    $email = $_POST['email'] ?? '';
    $komentar = $_POST['pesan'] ?? '';
    $rating = $_POST['rating'] ?? 5;
    
    if (empty($nama) || empty($email) || empty($komentar)) {
        header("Location: index.php?status=error&msg=Semua field harus diisi#kontak");
        mysqli_close($conn);
        exit();
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: index.php?status=error&msg=Format email tidak valid#kontak");
        mysqli_close($conn);
        exit();
    }
    
    $rating = intval($rating);
    if ($rating < 1 || $rating > 5) {
        $rating = 5;
    }
    
    $nama = mysqli_real_escape_string($conn, $nama);
    $email = mysqli_real_escape_string($conn, $email);
    $komentar = mysqli_real_escape_string($conn, $komentar);
    
    // INSERT dengan id_admin = 1 (admin tunggal)
    $sql = "INSERT INTO ulasan (id_admin, nama, email, komentar, rating) 
            VALUES (1, '$nama', '$email', '$komentar', $rating)";
    
    if (mysqli_query($conn, $sql)) {
        header("Location: index.php?status=success#kontak");
    } else {
        header("Location: index.php?status=error&msg=" . urlencode("Gagal menyimpan ulasan: " . mysqli_error($conn)) . "#kontak");
    }
    
    mysqli_close($conn);
} else {
    mysqli_close($conn);
    header("Location: index.php#kontak");
}
exit();
?>