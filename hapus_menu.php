<?php
include 'koneksi.php';

// Ambil ID menu dari URL
$id_menu = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id_menu == 0) {
    echo "<script>
            alert('❌ ID Menu tidak valid!');
            window.location='pendataan_menu.php';
          </script>";
    exit();
}

// Ambil data menu termasuk nama gambar
$query = "SELECT * FROM menu WHERE id_menu = $id_menu";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
    echo "<script>
            alert('❌ Data menu tidak ditemukan!');
            window.location='pendataan_menu.php';
          </script>";
    exit();
}

$menu = mysqli_fetch_assoc($result);

// Hapus gambar dari folder jika ada
if (!empty($menu['gambar_menu'])) {
    $file_path = "images/menu/" . $menu['gambar_menu'];
    if (file_exists($file_path)) {
        unlink($file_path);
    }
}

// Hapus data dari database
$delete_query = "DELETE FROM menu WHERE id_menu = $id_menu";

if (mysqli_query($conn, $delete_query)) {
    echo "<script>
            alert('✅ Menu \"" . addslashes($menu['nama_menu']) . "\" berhasil dihapus!');
            window.location='pendataan_menu.php?pesan=hapus';
          </script>";
} else {
    echo "<script>
            alert('❌ Gagal menghapus menu: " . mysqli_error($conn) . "');
            window.location='pendataan_menu.php';
          </script>";
}

mysqli_close($conn);
?>