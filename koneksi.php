<?php
$conn = mysqli_connect("localhost", "root", "", "umkm");

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
echo "Koneksi berhasil ke database!";
?>


