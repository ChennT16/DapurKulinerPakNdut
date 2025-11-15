<?php
// transaksi.php - Update dengan Filter Periode & Button Selesai/Batal
require_once 'koneksi.php';

// ===== POST HANDLER =====
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_transaksi']) && isset($_POST['action'])) {
    try {
        $id_transaksi = $_POST['id_transaksi'];
        $action = $_POST['action'];
        
        if (!in_array($action, ['selesai', 'batal'])) {
            throw new Exception('Action tidak valid!');
        }
        
        $query = "UPDATE transaksi SET status = ? WHERE id_transaksi = ? AND status = 'pending'";
        $stmt = mysqli_prepare($conn, $query);
        
        if (!$stmt) {
            throw new Exception('Prepare failed: ' . mysqli_error($conn));
        }
        
        mysqli_stmt_bind_param($stmt, "ss", $action, $id_transaksi);
        mysqli_stmt_execute($stmt);
        $affected = mysqli_stmt_affected_rows($stmt);
        mysqli_stmt_close($stmt);
        
        if ($affected > 0) {
            $message = $action === 'selesai' 
                ? "Transaksi berhasil diselesaikan!" 
                : "Transaksi berhasil dibatalkan!";
            header("Location: transaksi.php?updated=1&action=$action&message=" . urlencode($message));
        } else {
            $check = mysqli_query($conn, "SELECT status FROM transaksi WHERE id_transaksi = '$id_transaksi'");
            $current = mysqli_fetch_assoc($check);
            $current_status = $current ? $current['status'] : 'tidak ditemukan';
            $error_msg = "Transaksi tidak bisa diupdate! Status saat ini: '$current_status'";
            header("Location: transaksi.php?error=1&message=" . urlencode($error_msg));
        }
        exit;
    } catch (Exception $e) {
        header("Location: transaksi.php?error=1&message=" . urlencode($e->getMessage()));
        exit;
    }
}

// ===== FILTER PERIODE & STATUS =====
$periode = $_GET['periode'] ?? 'all';
$filter_status = $_GET['status'] ?? '';
$where_conditions = [];

switch($periode) {
    case 'today':
        $where_conditions[] = "DATE(t.waktu) = CURDATE()";
        break;
    case 'week':
        $where_conditions[] = "YEARWEEK(t.waktu, 1) = YEARWEEK(NOW(), 1)";
        break;
    case 'month':
        $where_conditions[] = "YEAR(t.waktu) = YEAR(NOW()) AND MONTH(t.waktu) = MONTH(NOW())";
        break;
    case 'year':
        $where_conditions[] = "YEAR(t.waktu) = YEAR(NOW())";
        break;
}

if ($filter_status) {
    $where_conditions[] = "t.status = '" . mysqli_real_escape_string($conn, $filter_status) . "'";
}

$where_periode = count($where_conditions) > 0 ? "WHERE " . implode(" AND ", $where_conditions) : "";

// ===== QUERY TRANSAKSI =====
$sql = "SELECT t.*, COUNT(dt.id_detail) as jumlah_item 
        FROM transaksi t
        LEFT JOIN detail_transaksi dt ON t.id_transaksi = dt.id_transaksi
        $where_periode
        GROUP BY t.id_transaksi 
        ORDER BY t.waktu DESC";

$result = mysqli_query($conn, $sql);
$transaksi = mysqli_fetch_all($result, MYSQLI_ASSOC);

// ===== STATS =====
$stats_sql = "SELECT 
    COUNT(*) as total,
    SUM(status = 'pending') as pending,
    SUM(status = 'selesai') as selesai,
    SUM(status = 'batal') as batal,
    SUM(CASE WHEN status = 'selesai' THEN total_harga ELSE 0 END) as total_pendapatan
    FROM transaksi t
    $where_periode";

$stats_result = mysqli_query($conn, $stats_sql);
$stats = mysqli_fetch_assoc($stats_result);

$periode_label = [
    'all' => 'Semua Waktu',
    'today' => 'Hari Ini',
    'week' => 'Minggu Ini',
    'month' => 'Bulan Ini',
    'year' => 'Tahun Ini'
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaksi - Dapur Pak Ndut</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #FFF3E0, #FFE0B2);
            display: flex;
            min-height: 100vh;
        }
        .sidebar {
            background: linear-gradient(180deg, #FF9800, #F57C00);
            width: 240px;
            padding: 30px 20px;
            box-shadow: 3px 0 15px rgba(0,0,0,0.2);
            position: fixed;
            height: 100vh;
        }
        .sidebar h3 {
            color: white;
            text-align: center;
            margin-bottom: 30px;
            font-size: 1.3em;
        }
        .sidebar a {
            display: block;
            color: white;
            padding: 15px;
            margin: 5px 0;
            border-radius: 10px;
            text-decoration: none;
            transition: 0.3s;
        }
        .sidebar a:hover, .sidebar a.active {
            background: rgba(255,255,255,0.2);
        }
        .main {
            margin-left: 260px;
            padding: 20px;
            flex: 1;
        }
        .header-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            background: white;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        .periode-selector {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .periode-selector label {
            font-weight: 600;
            color: #333;
        }
        .periode-selector select {
            padding: 10px 20px;
            border: 2px solid #FF9800;
            border-radius: 10px;
            background: white;
            color: #FF9800;
            font-weight: 600;
            cursor: pointer;
            font-size: 1em;
            outline: none;
            transition: 0.3s;
        }
        .periode-selector select:hover {
            background: #FFF3E0;
        }
        .periode-badge {
            background: linear-gradient(135deg, #FF9800, #F57C00);
            color: white;
            padding: 8px 20px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.95em;
        }
        .stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            text-align: center;
            transition: 0.3s;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
        .stat-card h4 {
            color: #666;
            font-size: 0.9em;
            margin-bottom: 10px;
        }
        .stat-card p {
            color: #FF9800;
            font-size: 2em;
            font-weight: bold;
        }
        .stat-card.pending p { color: #f59e0b; }
        .stat-card.selesai p { color: #10b981; }
        .stat-card.batal p { color: #ef4444; }
        h2 {
            color: #333;
            margin-bottom: 20px;
            font-size: 2em;
        }
        .filters {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }
        .filters a {
            padding: 10px 20px;
            border: 2px solid #FF9800;
            border-radius: 25px;
            background: white;
            color: #FF9800;
            text-decoration: none;
            font-weight: 600;
            transition: 0.3s;
        }
        .filters a:hover {
            background: #FFF3E0;
        }
        .filters a.active {
            background: #FF9800;
            color: white;
        }
        table {
            width: 100%;
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        thead {
            background: linear-gradient(135deg, #FF9800, #F57C00);
        }
        thead th {
            color: white;
            padding: 18px 15px;
            text-align: left;
        }
        tbody td {
            padding: 18px 15px;
            border-bottom: 1px solid #f0f0f0;
        }
        tbody tr:hover {
            background: #FFF8E7;
        }
        .badge {
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 0.85em;
            font-weight: 600;
        }
        .badge.pending {
            background: #fff3cd;
            color: #856404;
        }
        .badge.selesai {
            background: #d4edda;
            color: #155724;
        }
        .badge.batal {
            background: #f8d7da;
            color: #721c24;
        }
        .btn {
            padding: 8px 16px;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            font-weight: 600;
            margin: 0 3px;
            transition: 0.3s;
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        .btn-detail { background: #667eea; }
        .btn-detail:hover { background: #5568d3; }
        .btn-selesai { background: #4CAF50; }
        .btn-selesai:hover { background: #45a049; }
        .btn-batal { background: #ef4444; }
        .btn-batal:hover { background: #dc2626; }
        .alert {
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-weight: 600;
            animation: slideDown 0.3s ease;
        }
        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .action-btns {
            display: flex;
            gap: 5px;
            flex-wrap: wrap;
        }
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #999;
        }
        @media (max-width: 1024px) {
            .stats { grid-template-columns: repeat(2, 1fr); }
            .header-section { flex-direction: column; gap: 15px; }
        }
        @media (max-width: 768px) {
            .sidebar { width: 70px; }
            .main { margin-left: 90px; }
            .stats { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <aside class="sidebar">
        <h3>🍽️ Dapur Pak Ndut</h3>
        <nav>
            <a href="admin.php">👤 Admin</a>
            <a href="pendataan_menu.php">📖 Menu</a>
            <a href="transaksi.php" class="active">🛒 Transaksi</a>
            <a href="generate_laporan.php">📊 Laporan</a>
            <a href="ulasan.php">💬 Ulasan</a>
            <a href="login.php">🚪 Logout</a>
        </nav>
    </aside>
    <main class="main">
        <div class="header-section">
            <div class="periode-selector">
                <label>📅 Periode:</label>
                <select id="periodeSelect" onchange="changePeriode()">
                    <option value="all" <?= $periode === 'all' ? 'selected' : '' ?>>Semua Waktu</option>
                    <option value="today" <?= $periode === 'today' ? 'selected' : '' ?>>Hari Ini</option>
                    <option value="week" <?= $periode === 'week' ? 'selected' : '' ?>>Minggu Ini</option>
                    <option value="month" <?= $periode === 'month' ? 'selected' : '' ?>>Bulan Ini</option>
                    <option value="year" <?= $periode === 'year' ? 'selected' : '' ?>>Tahun Ini</option>
                </select>
            </div>
            <div class="periode-badge">
                📊 Menampilkan: <?= $periode_label[$periode] ?>
            </div>
        </div>
        
        <?php if (isset($_GET['updated']) && isset($_GET['message'])): ?>
            <div class="alert alert-success">✅ <?= htmlspecialchars($_GET['message']) ?></div>
        <?php endif; ?>
        
        <?php if (isset($_GET['error']) && isset($_GET['message'])): ?>
            <div class="alert alert-error">❌ <?= htmlspecialchars($_GET['message']) ?></div>
        <?php endif; ?>
        
        <div class="stats">
            <div class="stat-card">
                <h4>📦 Total Transaksi</h4>
                <p><?= $stats['total'] ?? 0 ?></p>
            </div>
            <div class="stat-card pending">
                <h4>⏳ Pending</h4>
                <p><?= $stats['pending'] ?? 0 ?></p>
            </div>
            <div class="stat-card selesai">
                <h4>✅ Selesai</h4>
                <p><?= $stats['selesai'] ?? 0 ?></p>
            </div>
            <div class="stat-card">
                <h4>💰 Pendapatan</h4>
                <p style="font-size: 1.5em;">Rp <?= number_format($stats['total_pendapatan'] ?? 0, 0, ',', '.') ?></p>
            </div>
        </div>
        <h2>🛒 Daftar Transaksi</h2>
        <div class="filters">
            <a href="?periode=<?= $periode ?>" class="<?= !$filter_status ? 'active' : '' ?>">Semua</a>
            <a href="?periode=<?= $periode ?>&status=pending" class="<?= $filter_status === 'pending' ? 'active' : '' ?>">Pending</a>
            <a href="?periode=<?= $periode ?>&status=selesai" class="<?= $filter_status === 'selesai' ? 'active' : '' ?>">Selesai</a>
            <a href="?periode=<?= $periode ?>&status=batal" class="<?= $filter_status === 'batal' ? 'active' : '' ?>">Batal</a>
        </div>
        <table>
            <thead>
                <tr>
                    <th>ID Transaksi</th>
                    <th>Nama Pembeli</th>
                    <th>Tanggal</th>
                    <th>Items</th>
                    <th>Total Harga</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($transaksi) > 0): ?>
                    <?php foreach ($transaksi as $t): ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($t['id_transaksi']) ?></strong></td>
                            <td><?= htmlspecialchars($t['nama_pembeli']) ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($t['waktu'])) ?></td>
                            <td><?= $t['jumlah_item'] ?> item</td>
                            <td><strong>Rp <?= number_format($t['total_harga'], 0, ',', '.') ?></strong></td>
                            <td>
                                <?php if ($t['status']): ?>
                                    <span class="badge <?= $t['status'] ?>"><?= ucfirst($t['status']) ?></span>
                                <?php else: ?>
                                    <span class="badge batal">Batal</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="action-btns">
                                    <a href="get_detail_transaksi.php?id=<?= $t['id_transaksi'] ?>" class="btn btn-detail">👁️ Detail</a>
                                    <?php if ($t['status'] === 'pending'): ?>
                                        <form method="POST" style="display:inline;" 
                                              onsubmit="return confirm('✅ Selesaikan transaksi ini?')">
                                            <input type="hidden" name="id_transaksi" value="<?= $t['id_transaksi'] ?>">
                                            <input type="hidden" name="action" value="selesai">
                                            <button type="submit" class="btn btn-selesai">✅ Selesai</button>
                                        </form>
                                        <form method="POST" style="display:inline;" 
                                              onsubmit="return confirm('❌ Batalkan transaksi ini?')">
                                            <input type="hidden" name="id_transaksi" value="<?= $t['id_transaksi'] ?>">
                                            <input type="hidden" name="action" value="batal">
                                            <button type="submit" class="btn btn-batal">❌ Batal</button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="empty-state">
                            <p style="font-size: 1.2em; margin-bottom: 10px;">Tidak ada transaksi</p>
                            <p style="font-size: 0.9em;">untuk periode <strong><?= $periode_label[$periode] ?></strong></p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </main>
    <script>
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            });
        }, 5000);
        function changePeriode() {
            const periode = document.getElementById('periodeSelect').value;
            const currentStatus = new URLSearchParams(window.location.search).get('status') || '';
            if (currentStatus) {
                window.location.href = `?periode=${periode}&status=${currentStatus}`;
            } else {
                window.location.href = `?periode=${periode}`;
            }
        }
    </script>
</body>
</html>