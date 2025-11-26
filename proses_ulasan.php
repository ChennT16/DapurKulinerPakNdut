<?php
// Include file koneksi
include 'koneksi.php'; // sesuaikan dengan nama file koneksi kamu

// Cek koneksi
if (!$conn) {
    header("Location: index.php?status=error&msg=Koneksi database gagal: " . urlencode(mysqli_connect_error()) . "#kontak");
    exit();
}

// Proses form jika ada POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama'] ?? '';
    $email = $_POST['email'] ?? '';
    $komentar = $_POST['pesan'] ?? '';
    $rating = $_POST['rating'] ?? 5;
    
    // Validasi input
    if (empty($nama) || empty($email) || empty($komentar)) {
        header("Location: index.php?status=error&msg=Semua field harus diisi#kontak");
        mysqli_close($conn);
        exit();
    }
    
    // Validasi email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: index.php?status=error&msg=Format email tidak valid#kontak");
        mysqli_close($conn);
        exit();
    }
    
    // Validasi rating (1-5)
    $rating = intval($rating);
    if ($rating < 1 || $rating > 5) {
        $rating = 5;
    }
    
    // Escape string untuk keamanan (mencegah SQL injection)
    $nama = mysqli_real_escape_string($conn, $nama);
    $email = mysqli_real_escape_string($conn, $email);
    $komentar = mysqli_real_escape_string($conn, $komentar);
    
    // Insert ke database
    $sql = "INSERT INTO ulasan (nama, email, komentar, rating) VALUES ('$nama', '$email', '$komentar', $rating)";
    
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