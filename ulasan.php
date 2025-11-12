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
    die("Koneksi gagal: " . $e->getMessage());
}

// Ambil data ulasan
$sql = "SELECT * FROM ulasan ORDER BY id DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$ulasan = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ulasan Pelanggan - Dapur Kuliner Pak Ndut</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #FFF7E6, #FFD9A3);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        /* Header */
        .page-header {
            background-color: #FFA500;
            color: white;
            padding: 25px 30px;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.15);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .page-header h2 {
            font-weight: 700;
            margin: 0;
        }

        /* Card Data */
        .data-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }

        /* Tabel */
        table {
            width: 100%;
            border-collapse: collapse;
        }
        thead {
            background-color: #FF8C00;
            color: white;
        }
        th, td {
            text-align: center;
            vertical-align: middle;
            padding: 12px;
        }
        tbody tr:nth-child(even) {
            background-color: #fff5ea;
        }
        tbody tr:hover {
            background-color: #ffe3c2;
        }

        /* Rating */
        .rating {
            color: #FFD700;
            font-size: 1.1rem;
        }

        /* Sidebar (opsional jika mau dipakai juga) */
        .sidebar {
            background: linear-gradient(180deg, #FF8C00, #FF8C00);
            min-height: 100vh;
            width: 240px;
            position: fixed;
            top: 0;
            left: 0;
            padding-top: 30px;
            box-shadow: 3px 0 15px rgba(0,0,0,0.2);
        }
        .sidebar .logo {
            text-align: center;
            color: white;
            font-size: 1.3rem;
            font-weight: bold;
            margin-bottom: 25px;
        }
        .sidebar .nav-link {
            color: white;
            padding: 12px 20px;
            border-radius: 10px;
            margin: 5px 10px;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            background: rgba(255,255,255,0.2);
        }

        .main-content {
            margin-left: 260px;
            padding: 30px;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="logo">
            <i class="fas fa-utensils me-2"></i>Dapur Kuliner<br>Pak Ndut
        </div>
        <nav class="nav flex-column">
            <a class="nav-link" href="admin.php"><i class="fas fa-home me-2"></i>Dashboard</a>
            <a class="nav-link" href="data_admin.php"><i class="fas fa-user-shield me-2"></i>Data Admin</a>
            <a class="nav-link" href="pendataan_menu.php"><i class="fas fa-book me-2"></i>Data Menu</a>
            <a class="nav-link" href="data_transaksi.php"><i class="fas fa-shopping-cart me-2"></i>Transaksi</a>
            <a class="nav-link" href="generate_laporan.php"><i class="fas fa-file-alt me-2"></i>Laporan</a>
            <a class="nav-link active" href="ulasan.php"><i class="fas fa-comment-dots me-2"></i>Ulasan</a>
            <a class="nav-link" href="login.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a>
        </nav>
    </div>

    <!-- Konten Utama -->
    <div class="main-content">
        <div class="page-header">
            <h2><i class="fas fa-comment-dots me-2"></i>Ulasan Pelanggan</h2>
        </div>

        <div class="data-card">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Pelanggan</th>
                        <th>Ulasan</th>
                        <th>Rating</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($ulasan) > 0): ?>
                        <?php $no = 1; foreach ($ulasan as $u): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= htmlspecialchars($u['nama']) ?></td>
                                <td><?= htmlspecialchars($u['komentar']) ?></td>
                                <td class="rating"><?= str_repeat('★', $u['rating']) . str_repeat('☆', 5 - $u['rating']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="5" class="text-center text-muted py-4">Belum ada ulasan pelanggan</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
