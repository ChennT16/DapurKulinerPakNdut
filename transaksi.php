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

// Proses Update Status (via AJAX)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_status') {
    header('Content-Type: application/json');
    
    $id_transaksi = $_POST['id_transaksi'];
    $new_status = $_POST['status'];
    
    try {
        $sql = "UPDATE transaksi SET status = :status WHERE id_transaksi = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':status' => $new_status, ':id' => $id_transaksi]);
        
        echo json_encode(['success' => true, 'message' => 'Status berhasil diupdate']);
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
    exit;
}

// Filter status
$filter_status = isset($_GET['status']) ? $_GET['status'] : '';

// Query transaksi - GANTI 'tanggal' jadi 'waktu'
$sql = "SELECT * FROM transaksi";

if ($filter_status !== '') {
    $sql .= " WHERE status = :status";
}

$sql .= " ORDER BY waktu DESC";

$stmt = $pdo->prepare($sql);
if ($filter_status !== '') {
    $stmt->execute([':status' => $filter_status]);
} else {
    $stmt->execute();
}
$transaksi = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Hitung statistik
$sqlStats = "SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
                SUM(CASE WHEN status = 'selesai' THEN 1 ELSE 0 END) as selesai,
                SUM(total_harga) as total_pendapatan
                FROM transaksi";
$stmtStats = $pdo->query($sqlStats);
$stats = $stmtStats->fetch(PDO::FETCH_ASSOC);
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

        /* Stats Cards */
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            gap: 20px;
            transition: all 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }

        .stat-icon.total {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .stat-icon.pending {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
        }

        .stat-icon.selesai {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
        }

        .stat-icon.pendapatan {
            background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
            color: white;
        }

        .stat-info h4 {
            color: #666;
            font-size: 0.9rem;
            font-weight: 500;
            margin-bottom: 5px;
        }

        .stat-info .stat-value {
            font-size: 1.8rem;
            font-weight: bold;
            color: #333;
        }

        /* Page Header */
        .page-header {
            background: linear-gradient(135deg, #FFA500, #FF8C00);
            color: white;
            padding: 30px 40px;
            border-radius: 20px;
            margin-bottom: 30px;
            box-shadow: 0 8px 25px rgba(255,140,0,0.3);
            display: flex;
            justify-content: space-between;
            align-items: center;
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

        /* Filter Buttons */
        .filter-container {
            background: white;
            padding: 20px;
            border-radius: 15px;
            margin-bottom: 20px;
            box-shadow: 0 3px 15px rgba(0,0,0,0.1);
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            align-items: center;
        }

        .filter-label {
            font-weight: 600;
            color: #666;
            margin-right: 10px;
        }

        .filter-btn {
            padding: 10px 20px;
            border: 2px solid #ddd;
            background: white;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s;
            font-weight: 500;
            text-decoration: none;
            color: #333;
        }

        .filter-btn:hover {
            border-color: #FF8C00;
            background: #fff8f0;
        }

        .filter-btn.active {
            background: #FF8C00;
            color: white;
            border-color: #FF8C00;
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
            overflow-x: auto;
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
            font-size: 0.95rem;
            letter-spacing: 0.5px;
        }

        tbody td {
            padding: 18px 15px;
            border-bottom: 1px solid #f0f0f0;
            color: #333;
            font-size: 0.95rem;
            vertical-align: middle;
        }

        tbody tr {
            transition: all 0.3s;
        }

        tbody tr:hover {
            background-color: #FFF8E7;
        }

        /* Status Badge */
        .status-badge {
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-block;
        }

        .status-badge.pending {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }

        .status-badge.selesai {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        /* Checkbox Custom */
        .checkbox-wrapper {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .custom-checkbox {
            width: 24px;
            height: 24px;
            cursor: pointer;
            accent-color: #FF8C00;
            transform: scale(1.2);
        }

        .custom-checkbox:disabled {
            cursor: not-allowed;
            opacity: 0.5;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 8px;
        }

        .btn-detail {
            background: #667eea;
            color: white;
            padding: 8px 14px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            font-size: 0.85rem;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-detail:hover {
            background: #5568d3;
            transform: scale(1.05);
        }

        /* Modal */
        .modal {
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

        .modal-content {
            background-color: white;
            margin: 5% auto;
            padding: 0;
            width: 600px;
            max-width: 90%;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.3);
            animation: slideDown 0.3s;
            max-height: 80vh;
            overflow-y: auto;
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

        .modal-header {
            background: linear-gradient(135deg, #FFA500, #FF8C00);
            color: white;
            padding: 25px 30px;
            border-radius: 20px 20px 0 0;
            position: relative;
        }

        .modal-header h3 {
            margin: 0;
            font-size: 1.5rem;
        }

        .modal-body {
            padding: 30px;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px dashed #ddd;
        }

        .detail-label {
            font-weight: 600;
            color: #666;
        }

        .detail-value {
            color: #333;
            font-weight: 500;
        }

        .items-list {
            margin-top: 20px;
        }

        .item-card {
            background: #f9f9f9;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .item-info {
            flex: 1;
        }

        .item-name {
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
        }

        .item-details {
            font-size: 0.9rem;
            color: #666;
        }

        .item-subtotal {
            font-weight: bold;
            color: #FF8C00;
            font-size: 1.1rem;
        }

        .total-section {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 2px solid #FF8C00;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .total-label {
            font-size: 1.3rem;
            font-weight: bold;
            color: #333;
        }

        .total-value {
            font-size: 1.8rem;
            font-weight: bold;
            color: #FF8C00;
        }

        .btn-close-modal {
            position: absolute;
            top: 20px;
            right: 25px;
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

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #999;
        }

        .empty-state i {
            font-size: 4rem;
            margin-bottom: 20px;
            color: #ddd;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 70px;
            }
            
            .sidebar .logo h4,
            .sidebar .nav-link span {
                display: none;
            }
            
            .main-content {
                margin-left: 70px;
            }

            .stats-container {
                grid-template-columns: 1fr;
            }

            .page-header {
                flex-direction: column;
                text-align: center;
                gap: 15px;
            }

            .modal-content {
                width: 95%;
                margin: 10% auto;
            }

            table {
                font-size: 0.85rem;
            }

            thead th, tbody td {
                padding: 12px 8px;
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
        <nav class="nav flex-column">
            <a class="nav-link" href="admin.php">
                <i class="fas fa-user-shield"></i> <span>Data Admin</span>
            </a>
            <a class="nav-link" href="pendataan_menu.php">
                <i class="fas fa-book"></i> <span>Data Menu</span>
            </a>
            <a class="nav-link active" href="transaksi.php">
                <i class="fas fa-shopping-cart"></i> <span>Transaksi</span>
            </a>
            <a class="nav-link" href="generate_laporan.php">
                <i class="fas fa-file-alt"></i> <span>Laporan</span>
            </a>
            <a class="nav-link" href="ulasan.php">
                <i class="fas fa-comment-dots"></i> <span>Ulasan</span>
            </a>
            <a class="nav-link" href="login.php">
                <i class="fas fa-sign-out-alt"></i> <span>Logout</span>
            </a>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Stats Cards -->
        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-icon total">
                    <i class="fas fa-shopping-bag"></i>
                </div>
                <div class="stat-info">
                    <h4>Total Transaksi</h4>
                    <div class="stat-value"><?= $stats['total'] ?></div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon pending">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-info">
                    <h4>Pending</h4>
                    <div class="stat-value"><?= $stats['pending'] ?></div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon selesai">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-info">
                    <h4>Selesai</h4>
                    <div class="stat-value"><?= $stats['selesai'] ?></div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon pendapatan">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <div class="stat-info">
                    <h4>Total Pendapatan</h4>
                    <div class="stat-value">Rp <?= number_format($stats['total_pendapatan'], 0, ',', '.') ?></div>
                </div>
            </div>
        </div>

        <div class="page-header">
            <h2><i class="fas fa-shopping-cart"></i>Daftar Transaksi</h2>
        </div>

        <!-- Filter Buttons -->
        <div class="filter-container">
            <span class="filter-label"><i class="fas fa-filter"></i> Filter Status:</span>
            <a href="?status=" class="filter-btn <?= $filter_status === '' ? 'active' : '' ?>">
                <i class="fas fa-list"></i> Semua
            </a>
            <a href="?status=pending" class="filter-btn <?= $filter_status === 'pending' ? 'active' : '' ?>">
                <i class="fas fa-clock"></i> Pending
            </a>
            <a href="?status=selesai" class="filter-btn <?= $filter_status === 'selesai' ? 'active' : '' ?>">
                <i class="fas fa-check-circle"></i> Selesai
            </a>
        </div>

        <div class="data-card">
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th style="width: 50px; text-align: center;">
                                <i class="fas fa-check-square"></i>
                            </th>
                            <th>ID Transaksi</th>
                            <th>Nama Pembeli</th>
                            <th>Tanggal</th>
                            <th>Items</th>
                            <th>Total Harga</th>
                            <th>Status</th>
                            <th style="text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($transaksi) > 0): ?>
                            <?php foreach ($transaksi as $t): ?>
                                <tr>
                                    <td style="text-align: center;">
                                        <div class="checkbox-wrapper">
                                            <input 
                                                type="checkbox" 
                                                class="custom-checkbox status-checkbox" 
                                                data-id="<?= $t['id_transaksi'] ?>"
                                                <?= $t['status'] === 'selesai' ? 'checked disabled' : '' ?>
                                                onchange="updateStatus(this)"
                                                title="<?= $t['status'] === 'selesai' ? 'Sudah selesai' : 'Klik untuk tandai selesai' ?>"
                                            >
                                        </div>
                                    </td>
                                    <td><strong><?= htmlspecialchars($t['id_transaksi']) ?></strong></td>
                                    <td><?= htmlspecialchars($t['nama_pembeli']) ?></td>
                                    <td><?= date('d/m/Y H:i', strtotime($t['waktu'])) ?></td>
                                    <td>
                                        <span style="color: #666;">
                                            <i class="fas fa-shopping-basket"></i> <?= $t['jumlah_item'] ?> item
                                        </span>
                                    </td>
                                    <td><strong style="color: #FF8C00;">Rp <?= number_format($t['total_harga'], 0, ',', '.') ?></strong></td>
                                    <td>
                                        <span class="status-badge <?= $t['status'] ?>">
                                            <?= $t['status'] === 'pending' ? '⏳ Pending' : '✅ Selesai' ?>
                                        </span>
                                    </td>
                                    <td style="text-align: center;">
                                        <div class="action-buttons">
                                            <button class="btn-detail" onclick='showDetail(<?= json_encode($t) ?>)'>
                                                <i class="fas fa-eye"></i> Detail
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="empty-state">
                                    <i class="fas fa-inbox"></i>
                                    <p>Belum ada transaksi<?= $filter_status !== '' ? ' dengan status ' . $filter_status : '' ?></p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Detail -->
    <div id="detailModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fas fa-receipt"></i> Detail Transaksi</h3>
                <button class="btn-close-modal" onclick="closeDetailModal()">&times;</button>
            </div>
            <div class="modal-body" id="detailContent">
                <!-- Content will be loaded via JavaScript -->
            </div>
        </div>
    </div>

    <script>
        // Update status transaksi
        function updateStatus(checkbox) {
            const id = checkbox.dataset.id;
            const newStatus = checkbox.checked ? 'selesai' : 'pending';
            
            if (!confirm('✅ Tandai pesanan sebagai SELESAI?\n\nPesanan tidak bisa diubah kembali ke pending.')) {
                checkbox.checked = !checkbox.checked;
                return;
            }

            // Kirim request AJAX
            fetch('transaksi.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: action=update_status&id_transaksi=${id}&status=${newStatus}
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('✅ Pesanan berhasil ditandai SELESAI!');
                    if (newStatus === 'selesai') {
                        checkbox.disabled = true;
                    }
                    location.reload();
                } else {
                    alert('❌ Gagal update status: ' + data.message);
                    checkbox.checked = !checkbox.checked;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('❌ Terjadi kesalahan saat update status');
                checkbox.checked = !checkbox.checked;
            });
        }

        // Show detail transaksi
        function showDetail(data) {
            try {
                // Parse detail_items dari JSON string
                const items = JSON.parse(data.detail_items);
                
                let html = `
                    <div class="detail-row">
                        <span class="detail-label"><i class="fas fa-hashtag"></i> ID Transaksi:</span>
                        <span class="detail-value">${data.id_transaksi}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label"><i class="fas fa-user"></i> Nama Pembeli:</span>
                        <span class="detail-value">${data.nama_pembeli}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label"><i class="fas fa-calendar"></i> Tanggal:</span>
                        <span class="detail-value">${new Date(data.waktu).toLocaleString('id-ID', {
                            day: '2-digit',
                            month: 'long',
                            year: 'numeric',
                            hour: '2-digit',
                            minute: '2-digit'
                        })}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label"><i class="fas fa-info-circle"></i> Status:</span>
                        <span class="status-badge ${data.status}">${data.status === 'pending' ? '⏳ Pending' : '✅ Selesai'}</span>
                    </div>

                    <div class="items-list">
                        <h4 style="margin-bottom: 15px; color: #333;"><i class="fas fa-list"></i> Item Pesanan:</h4>
                `;

                items.forEach(item => {
                    html += `
                        <div class="item-card">
                            <div class="item-info">
                                <div class="item-name">${item.name}</div>
                                <div class="item-details">
                                    ${item.quantity}x @ Rp ${parseInt(item.price).toLocaleString('id-ID')}
                                </div>
                            </div>
                            <div class="item-subtotal">
                                Rp ${parseInt(item.subtotal).toLocaleString('id-ID')}
                            </div>
                        </div>
                    `;
                });

                html += `
                    </div>
                    <div class="total-section">
                        <span class="total-label">TOTAL BAYAR:</span>
                        <span class="total-value">Rp ${parseInt(data.total_harga).toLocaleString('id-ID')}</span>
                    </div>
                `;

                document.getElementById('detailContent').innerHTML = html;
                document.getElementById('detailModal').style.display = 'block';
            } catch (error) {
                console.error('Error parsing detail:', error);
                alert('Gagal memuat detail transaksi');
            }
        }

        function closeDetailModal() {
            document.getElementById('detailModal').style.display = 'none';
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('detailModal');
            if (event.target == modal) {
                closeDetailModal();
            }
        }
    </script>
</body>
</html>