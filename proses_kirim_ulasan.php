<?php
include 'koneksi.php';

// Cek apakah form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Ambil dan sanitasi input
    $nama = mysqli_real_escape_string($conn, trim($_POST['nama']));
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $pesan = mysqli_real_escape_string($conn, trim($_POST['pesan']));
    $rating = isset($_POST['rating']) ? intval($_POST['rating']) : 5;
    
    // Validasi input
    $errors = array();
    
    if (empty($nama)) {
        $errors[] = "Nama harus diisi!";
    }
    
    if (empty($email)) {
        $errors[] = "Email harus diisi!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Format email tidak valid!";
    }
    
    if (empty($pesan)) {
        $errors[] = "Pesan ulasan harus diisi!";
    } elseif (strlen($pesan) < 10) {
        $errors[] = "Pesan minimal 10 karakter!";
    }
    
    if ($rating < 1 || $rating > 5) {
        $errors[] = "Rating harus antara 1-5!";
    }
    
    // Jika tidak ada error, simpan ke database
    if (empty($errors)) {
        $query = "INSERT INTO ulasan (nama_pengirim, email_pengirim, pesan_ulasan, rating, status_ulasan) 
                  VALUES ('$nama', '$email', '$pesan', '$rating', 'pending')";
        
        if (mysqli_query($conn, $query)) {
            echo "<script>
                    alert('✅ Terima kasih! Ulasan Anda telah dikirim dan menunggu persetujuan admin.');
                    window.location.href = 'index.html#ulasan';
                  </script>";
        } else {
            echo "<script>
                    alert('❌ Gagal mengirim ulasan: " . mysqli_error($conn) . "');
                    window.history.back();
                  </script>";
        }
    } else {
        // Tampilkan error
        $error_message = implode("\\n", $errors);
        echo "<script>
                alert('⚠️ Terjadi kesalahan:\\n\\n" . $error_message . "');
                window.history.back();
              </script>";
    }
    
} else {
    // Jika diakses langsung tanpa POST
    header("Location: index.html");
    exit();
}

mysqli_close($conn);
?>