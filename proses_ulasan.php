<?php
// Koneksi ke database
$host = 'localhost';
$dbname = 'umkm';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    header("Location: index.php?status=error&msg=Koneksi database gagal#kontak");
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
        exit();
    }
    
    // Validasi email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: index.php?status=error&msg=Format email tidak valid#kontak");
        exit();
    }
    
    // Validasi rating (1-5)
    $rating = intval($rating);
    if ($rating < 1 || $rating > 5) {
        $rating = 5;
    }
    
    try {
        // Insert ke database
        $sql = "INSERT INTO ulasan (nama, email, komentar, rating) VALUES (:nama, :email, :komentar, :rating)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nama', $nama);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':komentar', $komentar);
        $stmt->bindParam(':rating', $rating);
        
        if ($stmt->execute()) {
            header("Location: index.php?status=success#kontak");
        } else {
            header("Location: index.php?status=error&msg=Gagal menyimpan ulasan#kontak");
        }
    } catch(PDOException $e) {
        header("Location: index.php?status=error&msg=" . urlencode($e->getMessage()) . "#kontak");
    }
} else {
    header("Location: index.php#kontak");
}
exit();
?>