<?php
include 'koneksi.php';

// Query untuk mengambil semua data menu dari database
$query = "SELECT nama_menu, jenis_menu, harga_menu FROM menu ORDER BY jenis_menu, nama_menu";
$result = mysqli_query($conn, $query);

// Cek apakah query berhasil
if (!$result) {
    die("Query gagal: " . mysqli_error($conn));
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate Laporan Menu - Dapur Kuliner Pak Ndut</title>
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

        /* =======================
           SIDEBAR
        ======================= */
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

        /* =======================
           MAIN CONTENT
        ======================= */
        .main-content {
            margin-left: 250px;
            padding: 30px;
            width: calc(100% - 250px);
        }

        .container {
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: var(--shadow);
        }

        h1 {
            color: #FF9800;
            text-align: center;
            font-size: 2.2em;
            margin-bottom: 20px;
        }

        .info {
            text-align: center;
            color: #666;
            margin-bottom: 30px;
            font-size: 0.95em;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 40px;
            overflow: hidden;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .data-table thead {
            background: linear-gradient(135deg, #FF9800 0%, #F57C00 100%);
            color: white;
        }

        .data-table th {
            padding: 18px;
            text-align: left;
            font-weight: 600;
            font-size: 0.95em;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .data-table tbody tr {
            background: #fff8f0;
            border-bottom: 2px solid white;
            transition: all 0.3s;
        }

        .data-table tbody tr:nth-child(odd) {
            background: #ffe0b2;
        }

        .data-table tbody tr:hover {
            background: #ffcc80;
            transform: scale(1.01);
        }

        .data-table td {
            padding: 16px 18px;
            color: #333;
        }

        .empty-state {
            text-align: center;
            padding: 40px;
            color: #999;
            font-style: italic;
        }

        .button-container {
            display: flex;
            gap: 20px;
            justify-content: center;
        }

        .btn {
            padding: 15px 40px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 600;
            font-size: 1.05em;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #FF9800 0%, #F57C00 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(255, 152, 0, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(255, 152, 0, 0.5);
        }

        .btn-secondary {
            background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(108, 117, 125, 0.3);
        }

        .btn-secondary:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(108, 117, 125, 0.4);
        }

        /* =======================
           PRINT STYLES
        ======================= */
        @media print {
            body {
                background: white;
                display: block;
            }

            .sidebar {
                display: none;
            }

            .main-content {
                margin-left: 0;
                width: 100%;
                padding: 0;
            }

            .container {
                box-shadow: none;
                padding: 20px;
            }

            .button-container {
                display: none;
            }

            .data-table tbody tr:hover {
                transform: none;
            }

            .info {
                display: block;
            }

            h1 {
                color: #000;
            }
        }

        /* =======================
           RESPONSIVE
        ======================= */
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
                padding: 15px;
            }

            .container {
                padding: 30px 20px;
            }

            h1 {
                font-size: 1.8em;
            }

            .data-table {
                font-size: 0.85em;
            }

            .data-table th,
            .data-table td {
                padding: 12px 10px;
            }

            .button-container {
                flex-direction: column;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }
        }

        @media (max-width: 480px) {
            h1 {
                font-size: 1.5em;
            }

            .data-table {
                font-size: 0.8em;
            }

            .data-table th,
            .data-table td {
                padding: 10px 8px;
            }

            .btn {
                padding: 12px 20px;
                font-size: 0.95em;
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
        <a href="generate_laporan.php" class="active"><i class="fas fa-file-alt"></i> <span>Laporan</span></a>
        <a href="ulasan.php"><i class="fas fa-comment-dots"></i> <span>Ulasan</span></a>
        <a href="login.php"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="container">
            <h1><i class="fas fa-file-alt"></i> Laporan Data Menu</h1>
            <div class="info">
                Dicetak pada: <?php echo date('d F Y, H:i'); ?> WIB | Total Menu: <?php echo mysqli_num_rows($result); ?>
            </div>

            <table class="data-table">
                <thead>
                    <tr>
                        <th>Nama Menu</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                    </tr>
                </thead>
                <tbody id="menuTableBody">
                    <?php 
                    if (mysqli_num_rows($result) > 0) {
                        // Loop untuk menampilkan setiap baris data dari database
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['nama_menu']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['jenis_menu']) . "</td>";
                            echo "<td>Rp " . number_format($row['harga_menu'], 0, ',', '.') . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        // Jika tidak ada data
                        echo "<tr><td colspan='3' class='empty-state'>⚠️ Tidak ada data menu di database</td></tr>";
                    }
                    ?>
                </tbody>
            </table>

            <div class="button-container">
                <button class="btn btn-primary" onclick="generatePDF()">
                    <i class="fas fa-print"></i> Cetak PDF
                </button>
                <button class="btn btn-secondary" onclick="goBack()">
                    <i class="fas fa-arrow-left"></i> Kembali
                </button>
            </div>
        </div>
    </div>

    <script>
        function generatePDF() {
            window.print();
        }

        function goBack() {
            window.location.href = 'pendataan_menu.php';
        }
    </script>
</body>
</html>
<?php
// Tutup koneksi database
mysqli_close($conn);
?>