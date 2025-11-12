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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --orange: #FF8C00;
            --light-orange: #FFA726;
            --text-dark: #333;
            --shadow: 0 8px 25px rgba(0,0,0,0.1);
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #FFF7E6, #FFD9A3);
            min-height: 100vh;
            display: flex;
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
            background: linear-gradient(180deg, var(--orange), #FF8C00);
            color: white;
            box-shadow: var(--shadow);
            position: fixed;
            height: 100%;
            padding-top: 30px;
        }

        .sidebar .logo {
            text-align: center;
            margin-bottom: 30px;
        }

        .sidebar .logo i {
            font-size: 3rem;
        }

        .sidebar .logo h4 {
            margin-top: 10px;
            font-weight: bold;
            font-size: 1.1rem;
            line-height: 1.4;
        }

        .sidebar a {
            display: block;
            color: #fff;
            padding: 12px 25px;
            margin: 5px 15px;
            border-radius: 10px;
            text-decoration: none;
            transition: 0.3s;
        }

        .sidebar a:hover, .sidebar a.active {
            background: rgba(255,255,255,0.2);
        }

        .sidebar a i {
            margin-right: 10px;
        }

        /* Main Content */
        .main-content {
            margin-left: 250px;
            padding: 30px;
            width: calc(100% - 250px);
        }

        /* Header */
        .page-header {
            background: white;
            border-radius: 15px;
            padding: 20px 25px;
            box-shadow: var(--shadow);
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .page-header h2 {
            color: var(--orange);
            font-weight: 700;
            font-size: 1.8rem;
            margin: 0;
        }

        /* Data Card */
        .data-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: var(--shadow);
        }

        /* Table */
        .table-responsive {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table thead {
            background: var(--orange);
            color: white;
        }

        table th, table td {
            padding: 12px 15px;
            text-align: center;
            border: 1px solid #ddd;
        }

        table tbody tr {
            transition: 0.2s;
        }

        table tbody tr:nth-child(even) {
            background-color: #fff5ea;
        }

        table tbody tr:hover {
            background-color: #ffe3c2;
        }

        /* Rating */
        .rating {
            color: #FFD700;
            font-size: 1.2rem;
            letter-spacing: 2px;
        }

        .text-center {
            text-align: center;
        }

        .text-muted {
            color: #6c757d;
        }

        .py-4 {
            padding: 20px 0;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                width: 70px;
            }
            
            .sidebar .logo h4,
            .sidebar a span {
                display: none;
            }
            
            .main-content {
                margin-left: 70px;
                width: calc(100% - 70px);
            }

            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }

            .page-header h2 {
                font-size: 1.5rem;
            }

            table th, table td {
                padding: 8px 10px;
                font-size: 0.9rem;
            }
        }

        @media (max-width: 480px) {
            .main-content {
                padding: 15px;
            }

            .page-header h2 {
                font-size: 1.3rem;
            }

            table {
                font-size: 0.85rem;
            }

            .rating {
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="logo">
            <i class="fas fa-utensils"></i>
            <h4>Dapur Kuliner<br>Pak Ndut</h4>
        </div>
        <a href="admin.php"><i class="fas fa-user-shield"></i> <span>Data Admin</span></a>
        <a href="pendataan_menu.php"><i class="fas fa-book"></i> <span>Data Menu</span></a>
        <a href="transaksi.php"><i class="fas fa-shopping-cart"></i> <span>Transaksi</span></a>
        <a href="generate_laporan.php"><i class="fas fa-file-alt"></i> <span>Laporan</span></a>
        <a href="ulasan.php" class="active"><i class="fas fa-comment-dots"></i> <span>Ulasan</span></a>
        <a href="login.php"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a>
    </div>

    <!-- Konten Utama -->
    <div class="main-content">
        <div class="page-header">
            <h2><i class="fas fa-comment-dots"></i> Ulasan Pelanggan</h2>
        </div>

        <div class="data-card">
            <div class="table-responsive">
                <table>
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
                            <tr><td colspan="4" class="text-center text-muted py-4">Belum ada ulasan pelanggan</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>