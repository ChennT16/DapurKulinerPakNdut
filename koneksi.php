<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "umkm"; // pastikan nama database kamu benar
$port =8111 ;
// Membuat koneksi
$conn = mysqli_connect($servername, $username, $password, $database,$port);

// Mengecek koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
} else {
    echo "Koneksi berhasil ke database '$database'";
}
?>
