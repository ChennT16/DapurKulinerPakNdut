<?php
// get_detail_transaksi.php - Halaman Detail Transaksi (MySQLi)
require_once 'koneksi.php';

// Cek koneksi database
if (!$conn) {
    die("ERROR: Koneksi database gagal! " . mysqli_connect_error());
}

$id = $_GET['id'] ?? '';

if (empty($id)) {
    header("Location: transaksi.php");
    exit;
}

// Ambil data transaksi
$stmt = mysqli_prepare($conn, "SELECT * FROM transaksi WHERE id_transaksi = ?");

if (!$stmt) {
    die("ERROR Prepare Statement: " . mysqli_error($conn));
}

mysqli_stmt_bind_param($stmt, "s", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$transaksi = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$transaksi) {
    header("Location: transaksi.php");
    exit;
}

// Ambil detail items
$stmt = mysqli_prepare($conn, "SELECT * FROM detail_transaksi WHERE id_transaksi = ? ORDER BY id_detail");

if (!$stmt) {
    die("ERROR Prepare Statement Items: " . mysqli_error($conn));
}

mysqli_stmt_bind_param($stmt, "s", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$items = mysqli_fetch_all($result, MYSQLI_ASSOC);
mysqli_stmt_close($stmt);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Transaksi - <?= $id ?></title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #FFF3E0, #FFE0B2);
            padding: 20px;
        }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
        }
        
        .card {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        
        h1 {
            color: #FF9800;
            margin-bottom: 20px;
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
        
        h3 {
            color: #333;
            margin: 20px 0 15px;
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
        
        .item-name {
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
        }
        
        .item-details {
            font-size: 0.9em;
            color: #666;
        }
        
        .item-subtotal {
            font-weight: bold;
            color: #FF9800;
            font-size: 1.1em;
        }
        
        .total-section {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 2px solid #FF9800;
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
            color: #FF9800;
        }
        
        .btn {
            padding: 12px 25px;
            background: #FF9800;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            display: inline-block;
            font-weight: bold;
            transition: 0.3s;
        }
        
        .btn:hover {
            background: #F57C00;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <h1>📋 Detail Transaksi</h1>
            
            <div class="detail-row">
                <span class="detail-label">🆔 ID Transaksi:</span>
                <span class="detail-value"><?= htmlspecialchars($transaksi['id_transaksi']) ?></span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">👤 Nama Pembeli:</span>
                <span class="detail-value"><?= htmlspecialchars($transaksi['nama_pembeli']) ?></span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">📅 Tanggal & Waktu:</span>
                <span class="detail-value">
                    <?= date('d F Y, H:i', strtotime($transaksi['waktu'])) ?> WIB
                </span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">📊 Status:</span>
                <span class="badge <?= $transaksi['status'] ?>">
                    <?= $transaksi['status'] === 'pending' ? '⏳ Pending' : '✅ Selesai' ?>
                </span>
            </div>
            
            <h3>🍽️ Item Pesanan:</h3>
            
            <?php foreach ($items as $item): ?>
                <div class="item-card">
                    <div>
                        <div class="item-name"><?= htmlspecialchars($item['nama_menu']) ?></div>
                        <div class="item-details">
                            <?= $item['jumlah'] ?>x @ Rp <?= number_format($item['harga_satuan'], 0, ',', '.') ?>
                        </div>
                    </div>
                    <div class="item-subtotal">
                        Rp <?= number_format($item['subtotal'], 0, ',', '.') ?>
                    </div>
                </div>
            <?php endforeach; ?>
            
            <div class="total-section">
                <span class="total-label">💰 TOTAL BAYAR:</span>
                <span class="total-value">
                    Rp <?= number_format($transaksi['total_harga'], 0, ',', '.') ?>
                </span>
            </div>
        </div>
        
        <a href="transaksi.php" class="btn">← Kembali ke Daftar Transaksi</a>
    </div>
</body>
</html>