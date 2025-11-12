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

// Ambil data transaksi
$sql = "SELECT * FROM transaksi ORDER BY id DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$transaksi = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaksi - Dapur Kuliner Pak Ndut</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #FFF7E6, #FFD9A3);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-x: hidden;
        }

        /* Sidebar */
        .sidebar {
            background: linear-gradient(180deg, #FF8C00, #FF8C00);
            min-height: 100vh;
            width: 240px;
            position: fixed;
            top: 0;
            left: 0;
            padding-top: 30px;
            box-shadow: 3px 0 15px rgba(0,0,0,0.2);
            z-index: 1000;
        }

        .sidebar .logo {
            text-align: center;
            color: white;
            font-size: 1.3rem;
            font-weight: bold;
            margin-bottom: 30px;
            padding: 0 15px;
        }

        .sidebar .logo i {
            font-size: 2rem;
            margin-bottom: 10px;
            display: block;
        }

        .sidebar .nav-link {
            color: white;
            padding: 15px 20px;
            margin: 5px 10px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            text-decoration: none;
            transition: all 0.3s;
        }

        .sidebar .nav-link i {
            margin-right: 12px;
            width: 20px;
        }

        .sidebar .nav-link:hover {
            background: rgba(255,255,255,0.2);
        }

        .sidebar .nav-link.active {
            background: rgba(255,255,255,0.3);
            font-weight: 600;
        }

        /* Main Content */
        .main-content {
            margin-left: 240px;
            padding: 30px;
            min-height: 100vh;
        }

        /* Page Header */
        .page-header {
            background: linear-gradient(135deg, #FFA500, #FF8C00);
            color: white;
            padding: 30px 40px;
            border-radius: 20px;
            margin-bottom: 30px;
            box-shadow: 0 8px 25px rgba(255,140,0,0.3);
        }

        .page-header h2 {
            font-weight: 700;
            margin: 0;
            font-size: 2rem;
        }

        .page-header i {
            font-size: 2.2rem;
            margin-right: 15px;
        }

        /* Data Card */
        .data-card {
            background: white;
            border-radius: 20px;
            padding: 0;
            box-shadow: 0 5px 30px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        /* Table */
        .table-container {
            padding: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background: linear-gradient(135deg, #FFA500, #FF8C00);
        }

        thead th {
            color: white;
            font-weight: 600;
            padding: 18px 15px;
            text-align: left;
            font-size: 1rem;
            letter-spacing: 0.5px;
        }

        tbody td {
            padding: 18px 15px;
            border-bottom: 1px solid #f0f0f0;
            color: #333;
            font-size: 0.95rem;
        }

        tbody tr {
            transition: all 0.3s;
        }

        tbody tr:hover {
            background-color: #FFF8E7;
            transform: scale(1.01);
        }

        /* Cetak Struk Button */
        .btn-cetak {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: white;
            border: none;
            padding: 15px 20px;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.15);
            cursor: pointer;
            font-weight: 600;
            color: #333;
            transition: all 0.3s;
            z-index: 999;
            font-size: 0.95rem;
        }

        .btn-cetak:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.2);
            background: #FFA500;
            color: white;
        }

        /* Modal Struk */
        .modal-struk {
            display: none;
            position: fixed;
            z-index: 2000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.6);
            animation: fadeIn 0.3s;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .modal-content-struk {
            background-color: white;
            margin: 5% auto;
            padding: 0;
            width: 400px;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.3);
            animation: slideDown 0.3s;
        }

        @keyframes slideDown {
            from { 
                opacity: 0;
                transform: translateY(-50px);
            }
            to { 
                opacity: 1;
                transform: translateY(0);
            }
        }

        .struk-header {
            background: linear-gradient(135deg, #FFA500, #FF8C00);
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 15px 15px 0 0;
        }

        .struk-body {
            padding: 25px;
        }

        .struk-item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px dashed #ddd;
        }

        .struk-total {
            display: flex;
            justify-content: space-between;
            padding: 15px 0;
            font-weight: bold;
            font-size: 1.2rem;
            color: #FF8C00;
            border-top: 2px solid #FF8C00;
            margin-top: 10px;
        }

        .struk-footer {
            text-align: center;
            padding: 15px;
            border-top: 1px solid #eee;
            color: #666;
            font-size: 0.9rem;
        }

        .btn-close-modal {
            position: absolute;
            top: 15px;
            right: 20px;
            background: rgba(255,255,255,0.3);
            border: none;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
            width: 35px;
            height: 35px;
            border-radius: 50%;
            transition: all 0.3s;
        }

        .btn-close-modal:hover {
            background: rgba(255,255,255,0.5);
            transform: rotate(90deg);
        }

        .btn-print {
            background: #FF8C00;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 8px;
            cursor: pointer;
            width: 100%;
            font-weight: 600;
            font-size: 1rem;
            margin-top: 15px;
            transition: all 0.3s;
        }

        .btn-print:hover {
            background: #FFA500;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255,140,0,0.3);
        }

        .empty-state {
            text-align: center;
            padding: 50px 20px;
            color: #999;
        }

        .empty-state i {
            font-size: 4rem;
            margin-bottom: 20px;
            color: #ddd;
        }

        @media print {
            body * {
                visibility: hidden;
            }
            .modal-content-struk, .modal-content-struk * {
                visibility: visible;
            }
            .modal-content-struk {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                box-shadow: none;
            }
            .btn-print, .btn-close-modal {
                display: none;
            }
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .main-content {
                margin-left: 0;
            }
            .modal-content-struk {
                width: 90%;
                margin: 10% auto;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="logo">
            <i class="fas fa-utensils"></i>
            Dapur Kuliner<br>Pak Ndut
        </div>
        <nav class="nav flex-column">
            <a class="nav-link" href="admin.php">
                <i class="fas fa-home"></i>Dashboard
            </a>
            <a class="nav-link" href="data_admin.php">
                <i class="fas fa-user-shield"></i>Data Admin
            </a>
            <a class="nav-link" href="pendataan_menu.php">
                <i class="fas fa-book"></i>Data Menu
            </a>
            <a class="nav-link active" href="transaksi.php">
                <i class="fas fa-shopping-cart"></i>Transaksi
            </a>
            <a class="nav-link" href="generate_laporan.php">
                <i class="fas fa-file-alt"></i>Laporan
            </a>
            <a class="nav-link" href="ulasan.php">
                <i class="fas fa-comment-dots"></i>Ulasan
            </a>
            <a class="nav-link" href="login.php">
                <i class="fas fa-sign-out-alt"></i>Logout
            </a>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="page-header">
            <h2><i class="fas fa-shopping-cart"></i>Transaksi</h2>
        </div>

        <div class="data-card">
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Nama Pembeli</th>
                            <th>Nama menu</th>
                            <th>Tanggal</th>
                            <th>Total harga</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($transaksi) > 0): ?>
                            <?php foreach ($transaksi as $t): ?>
                                <tr>
                                    <td><?= htmlspecialchars($t['nama_pembeli']) ?></td>
                                    <td><?= htmlspecialchars($t['nama_menu']) ?></td>
                                    <td><?= date('d-m-Y', strtotime($t['tanggal'])) ?></td>
                                    <td><?= number_format($t['total_harga'], 0, ',', '.') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="empty-state">
                                    <i class="fas fa-inbox"></i>
                                    <p>Belum ada transaksi</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Button Cetak Struk -->
    <button class="btn-cetak" onclick="openStrukModal()">
        Cetak struk pembelian
    </button>

    <!-- Modal Struk -->
    <div id="strukModal" class="modal-struk">
        <div class="modal-content-struk">
            <button class="btn-close-modal" onclick="closeStrukModal()">&times;</button>
            <div class="struk-header">
                <h3 style="margin: 0;"><i class="fas fa-utensils"></i> Dapur Kuliner Pak Ndut</h3>
                <p style="margin: 5px 0 0 0; font-size: 0.9rem;">Jl. Kuliner No. 123, Jakarta</p>
                <p style="margin: 3px 0 0 0; font-size: 0.85rem;">Telp: 0812-3456-7890</p>
            </div>
            <div class="struk-body" id="strukContent">
                <div style="text-align: center; margin-bottom: 15px; padding-bottom: 15px; border-bottom: 2px dashed #ddd;">
                    <p style="margin: 5px 0; font-size: 0.9rem;">Tanggal: <span id="strukTanggal"></span></p>
                    <p style="margin: 5px 0; font-size: 0.9rem;">No. Struk: <span id="strukNo"></span></p>
                </div>
                
                <div id="strukItems"></div>
                
                <div class="struk-total">
                    <span>TOTAL</span>
                    <span id="strukTotal">Rp 0</span>
                </div>
            </div>
            <div class="struk-footer">
                <p style="margin: 5px 0;">Terima kasih atas kunjungan Anda!</p>
                <p style="margin: 5px 0; font-size: 0.85rem;">Selamat menikmati hidangan</p>
            </div>
            <div style="padding: 0 25px 25px 25px;">
                <button class="btn-print" onclick="printStruk()">
                    <i class="fas fa-print"></i> Cetak Struk
                </button>
            </div>
        </div>
    </div>

    <script>
        function openStrukModal() {
            // Generate data struk dari transaksi terbaru
            const transaksiData = <?= json_encode($transaksi) ?>;
            
            if (transaksiData.length === 0) {
                alert('Belum ada transaksi untuk dicetak');
                return;
            }

            // Ambil transaksi terakhir
            const latestTransaksi = transaksiData[0];
            
            // Set tanggal dan nomor struk
            const today = new Date();
            document.getElementById('strukTanggal').textContent = today.toLocaleDateString('id-ID');
            document.getElementById('strukNo').textContent = 'TRX-' + Date.now().toString().slice(-8);
            
            // Set items
            let itemsHTML = `
                <div class="struk-item">
                    <div>
                        <strong>${latestTransaksi.nama_menu}</strong><br>
                        <small style="color: #666;">Pembeli: ${latestTransaksi.nama_pembeli}</small>
                    </div>
                    <strong>${parseInt(latestTransaksi.total_harga).toLocaleString('id-ID')}</strong>
                </div>
            `;
            
            document.getElementById('strukItems').innerHTML = itemsHTML;
            document.getElementById('strukTotal').textContent = parseInt(latestTransaksi.total_harga).toLocaleString('id-ID');
            
            // Show modal
            document.getElementById('strukModal').style.display = 'block';
        }

        function closeStrukModal() {
            document.getElementById('strukModal').style.display = 'none';
        }

        function printStruk() {
            window.print();
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('strukModal');
            if (event.target == modal) {
                closeStrukModal();
            }
        }
    </script>
</body>
</html>