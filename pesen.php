<?php
include 'koneksi.php';

// Proses ketika form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['simpan_pesanan'])) {
    $nama_pembeli = mysqli_real_escape_string($conn, $_POST['nama_pembeli']);
    
    // Validasi nama pembeli tidak kosong
    if (empty($nama_pembeli)) {
        echo "<script>alert('Nama pembeli harus diisi!');</script>";
    } else {
        // Simpan data pembeli
        $query_pembeli = "INSERT INTO pembeli (nama_pembeli) VALUES ('$nama_pembeli')";
        
        if (mysqli_query($conn, $query_pembeli)) {
            $id_pembeli = mysqli_insert_id($conn); // Ambil ID pembeli yang baru disimpan
            
            // Simpan detail pesanan dari keranjang (session)
            if (isset($_SESSION['keranjang']) && count($_SESSION['keranjang']) > 0) {
                foreach ($_SESSION['keranjang'] as $item) {
                    $id_menu = $item['id_menu'];
                    $nama_menu = mysqli_real_escape_string($conn, $item['nama_menu']);
                    $harga = $item['harga'];
                    $jumlah = $item['jumlah'];
                    $subtotal = $harga * $jumlah;
                    
                    $query_detail = "INSERT INTO detail_pesanan 
                                    (id_pembeli, id_menu, nama_menu, harga, jumlah, subtotal) 
                                    VALUES 
                                    ('$id_pembeli', '$id_menu', '$nama_menu', '$harga', '$jumlah', '$subtotal')";
                    
                    mysqli_query($conn, $query_detail);
                }
                // Inisialisasi keranjang jika belum ada
if (!isset($_SESSION['keranjang'])) {
    $_SESSION['keranjang'] = array();
}

// Ketika tombol + Tambah diklik
if (isset($_POST['tambah_menu'])) {
    $id_menu = $_POST['id_menu'];
    $nama_menu = $_POST['nama_menu'];
    $harga = $_POST['harga'];
    
    // Cek apakah menu sudah ada di keranjang
    $found = false;
    foreach ($_SESSION['keranjang'] as &$item) {
        if ($item['id_menu'] == $id_menu) {
            $item['jumlah']++;
            $found = true;
            break;
        }
    }
    
    // Jika belum ada, tambahkan baru
    if (!$found) {
        $_SESSION['keranjang'][] = array(
            'id_menu' => $id_menu,
            'nama_menu' => $nama_menu,
            'harga' => $harga,
            'jumlah' => 1
        );
    }
}

                // Kosongkan keranjang setelah berhasil
                unset($_SESSION['keranjang']);
                
                echo "<script>
                        alert('Pesanan berhasil disimpan!');
                        window.location.href = 'pesan.php';
                      </script>";
            }
        } else {
            echo "<script>alert('Gagal menyimpan data: " . mysqli_error($conn) . "');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <!-- Bagian head tetap sama -->
</head>
<body>
    <!-- Bagian form -->
    <form method="POST" action="">
        <div class="form-group">
            <label>Nama Pembeli:</label>
            <input type="text" 
                   name="nama_pembeli" 
                   class="form-control" 
                   placeholder="Masukkan nama Anda..." 
                   required>
        </div>
        <form method="POST" action="">
    <input type="hidden" name="id_menu" value="<?php echo $row['id_menu']; ?>">
    <input type="hidden" name="nama_menu" value="<?php echo $row['nama_menu']; ?>">
    <input type="hidden" name="harga" value="<?php echo $row['harga']; ?>">
    <button type="submit" name="tambah_menu" class="btn-tambah">
        + Tambah
    </button>
</form>
        <!-- Tampilkan keranjang -->
        <div id="keranjang">
            <!-- Isi keranjang dari session -->
        </div>
        
        <button type="submit" 
                name="simpan_pesanan" 
                class="btn btn-success">
            Simpan Pesanan
        </button>
    </form>
</body>
</html>