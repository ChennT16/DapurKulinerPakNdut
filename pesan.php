<?php
// pesan.php - Menggunakan MySQLi (sesuai koneksi.php)
require_once 'koneksi.php';

// Proses Form POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $nama_pembeli = trim($_POST['nama_pembeli']);
        $total_harga = (int)$_POST['total_harga'];
        $items = json_decode($_POST['items'], true);
        
        // Validasi input
        if (empty($nama_pembeli) || empty($items)) {
            throw new Exception('Data tidak lengkap!');
        }
        
        // Mulai transaksi
        mysqli_begin_transaction($conn);
        
        // Generate ID Transaksi
        $id_transaksi = 'TRX' . time();
        
        // 1. INSERT ke tabel transaksi
        $stmt = mysqli_prepare($conn, "INSERT INTO transaksi (id_transaksi, nama_pembeli, waktu, total_harga, status) 
                                       VALUES (?, ?, NOW(), ?, 'pending')");
        mysqli_stmt_bind_param($stmt, "ssi", $id_transaksi, $nama_pembeli, $total_harga);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        
        // 2. INSERT ke detail_transaksi
        // TRIGGER 'kurangi_stok_after_insert' otomatis jalan di sini!
        $stmt = mysqli_prepare($conn, "INSERT INTO detail_transaksi (id_transaksi, id_menu, nama_menu, harga_satuan, jumlah, subtotal) 
                                       VALUES (?, ?, ?, ?, ?, ?)");
        
        foreach ($items as $item) {
            mysqli_stmt_bind_param($stmt, "sissii", 
                $id_transaksi,
                $item['id'],
                $item['nama'],
                $item['harga'],
                $item['jumlah'],
                $item['subtotal']
            );
            mysqli_stmt_execute($stmt);
            // Trigger otomatis UPDATE stock_menu setelah INSERT ini!
        }
        mysqli_stmt_close($stmt);
        
        // Commit transaksi
        mysqli_commit($conn);
        
        // Redirect dengan pesan sukses
        header("Location: pesan.php?success=1&id=" . $id_transaksi);
        exit;
        
    } catch (Exception $e) {
        mysqli_rollback($conn);
        $error_message = $e->getMessage();
    }
}

// Ambil data menu dari database
$result = mysqli_query($conn, "SELECT id_menu, nama_menu, harga_menu, jenis_menu, stock_menu, gambar_menu 
                                FROM menu 
                                ORDER BY jenis_menu, nama_menu");
$menu = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pemesanan - Dapur Pak Ndut</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #FFF3E0, #FFE0B2);
            padding: 20px;
        }
        
        .container { max-width: 1400px; margin: 0 auto; }
        
        .header {
            background: white;
            padding: 20px 30px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .header h1 { color: #FF9800; font-size: 2em; }
        
        .btn {
            padding: 12px 25px;
            background: #FF9800;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            font-size: 1em;
            font-weight: bold;
        }
        
        .btn:hover { background: #F57C00; }
        
        .content { display: grid; grid-template-columns: 2fr 1fr; gap: 30px; }
        
        .menu-section, .cart-section {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        
        .filters { display: flex; gap: 10px; margin: 20px 0; }
        
        .filter-btn {
            padding: 10px 20px;
            border: 2px solid #FF9800;
            border-radius: 25px;
            background: white;
            color: #FF9800;
            cursor: pointer;
            font-weight: 600;
        }
        
        .filter-btn.active { background: #FF9800; color: white; }
        
        .menu-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
            max-height: 600px;
            overflow-y: auto;
        }
        
        .menu-card {
            border: 2px solid #f3f4f6;
            border-radius: 12px;
            overflow: hidden;
            text-align: center;
        }
        
        .menu-card img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            background: #f3f4f6;
        }
        
        .menu-card h4 { padding: 10px; color: #333; }
        .menu-card .price { color: #FF9800; font-weight: bold; font-size: 1.2em; }
        .menu-card .stock { color: #666; font-size: 0.9em; padding: 5px; }
        
        .menu-card button {
            width: 100%;
            padding: 10px;
            background: #FF9800;
            color: white;
            border: none;
            cursor: pointer;
            font-weight: 600;
        }
        
        .menu-card button:disabled { background: #ccc; cursor: not-allowed; }
        
        .cart-section { position: sticky; top: 20px; max-height: 90vh; overflow-y: auto; }
        
        .cart-section input {
            width: 100%;
            padding: 12px;
            border: 2px solid #FF9800;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 1em;
        }
        
        .cart-items { margin: 20px 0; min-height: 100px; }
        
        .cart-item {
            background: #f9f9f9;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .qty-controls { display: flex; gap: 10px; align-items: center; }
        
        .qty-btn {
            width: 30px;
            height: 30px;
            border: none;
            background: #FF9800;
            color: white;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
        }
        
        .remove-btn {
            background: #ef4444;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
        }
        
        .cart-total {
            border-top: 2px solid #ddd;
            padding-top: 15px;
            margin-top: 15px;
        }
        
        .total-row {
            display: flex;
            justify-content: space-between;
            margin: 10px 0;
            font-size: 1.2em;
            font-weight: bold;
        }
        
        .alert {
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-weight: bold;
        }
        
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        
        @media (max-width: 1024px) {
            .content { grid-template-columns: 1fr; }
            .cart-section { position: relative; top: 0; max-height: none; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🍽️ Pemesanan Menu</h1>
            <a href="index.php" class="btn">← Kembali</a>
        </div>
        
        <!-- Alert Success -->
        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">
                ✅ Pesanan berhasil disimpan! ID: <?= htmlspecialchars($_GET['id']) ?>
                <br>Stok otomatis dikurangi oleh trigger database.
            </div>
        <?php endif; ?>
        
        <!-- Alert Error -->
        <?php if (isset($error_message)): ?>
            <div class="alert alert-error">
                ❌ Error: <?= htmlspecialchars($error_message) ?>
            </div>
        <?php endif; ?>

        <div class="content">
            <!-- Menu Section -->
            <div class="menu-section">
                <h2>📋 Pilih Menu</h2>
                
                <div class="filters">
                    <button class="filter-btn active" onclick="filterMenu('all')">Semua</button>
                    <button class="filter-btn" onclick="filterMenu('makanan')">Makanan</button>
                    <button class="filter-btn" onclick="filterMenu('minuman')">Minuman</button>
                </div>

                <div class="menu-grid" id="menuGrid"></div>
            </div>

            <!-- Cart Section -->
            <div class="cart-section">
                <h2>🛒 Keranjang</h2>
                
                <form method="POST" action="" id="formCheckout">
                    <input type="text" name="nama_pembeli" placeholder="Nama Pembeli" required>
                    <input type="hidden" name="total_harga" id="inputTotalHarga">
                    <input type="hidden" name="items" id="inputItems">
                    
                    <div id="cartItems" class="cart-items">
                        <p style="text-align:center; color:#999;">Keranjang kosong</p>
                    </div>
                    
                    <div class="cart-total">
                        <div class="total-row">
                            <span>TOTAL:</span>
                            <span id="displayTotal">Rp 0</span>
                        </div>
                        <button type="submit" class="btn" style="width:100%;" id="btnCheckout" disabled>
                            Konfirmasi Pesanan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Data menu dari PHP
        const menuData = <?= json_encode($menu) ?>;
        let cart = [];
        let currentFilter = 'all';

        // Format Rupiah
        function formatRp(num) {
            return 'Rp ' + parseInt(num).toLocaleString('id-ID');
        }

        // Render Menu
        function renderMenu() {
            const grid = document.getElementById('menuGrid');
            const filtered = currentFilter === 'all' 
                ? menuData 
                : menuData.filter(m => m.jenis_menu === currentFilter);

            grid.innerHTML = filtered.map(item => {
                const stok = parseInt(item.stock_menu);
                const habis = stok === 0;
                
                return `
                    <div class="menu-card">
                        <img src="img/${item.gambar_menu || 'default.jpg'}" alt="${item.nama_menu}">
                        <h4>${item.nama_menu}</h4>
                        <div class="price">${formatRp(item.harga_menu)}</div>
                        <div class="stock">Stok: ${stok}</div>
                        <button onclick="addToCart(${item.id_menu})" ${habis ? 'disabled' : ''}>
                            ${habis ? '❌ Habis' : '+ Tambah'}
                        </button>
                    </div>
                `;
            }).join('');
        }

        // Filter Menu
        function filterMenu(category) {
            currentFilter = category;
            document.querySelectorAll('.filter-btn').forEach(btn => btn.classList.remove('active'));
            event.target.classList.add('active');
            renderMenu();
        }

        // Add to Cart
        function addToCart(id) {
            const menu = menuData.find(m => m.id_menu == id);
            const cartItem = cart.find(c => c.id == id);

            if (cartItem) {
                if (cartItem.jumlah >= parseInt(menu.stock_menu)) {
                    alert('Stok tidak cukup!');
                    return;
                }
                cartItem.jumlah++;
            } else {
                cart.push({
                    id: menu.id_menu,
                    nama: menu.nama_menu,
                    harga: parseInt(menu.harga_menu),
                    jumlah: 1,
                    stock: parseInt(menu.stock_menu)
                });
            }
            renderCart();
        }

        // Update Quantity
        function updateQty(id, change) {
            const item = cart.find(c => c.id == id);
            item.jumlah += change;
            
            if (item.jumlah <= 0) {
                cart = cart.filter(c => c.id != id);
            } else if (item.jumlah > item.stock) {
                alert('Stok tidak cukup!');
                item.jumlah = item.stock;
            }
            
            renderCart();
        }

        // Remove Item
        function removeItem(id) {
            cart = cart.filter(c => c.id != id);
            renderCart();
        }

        // Render Cart
        function renderCart() {
            const cartDiv = document.getElementById('cartItems');
            
            if (cart.length === 0) {
                cartDiv.innerHTML = '<p style="text-align:center; color:#999;">Keranjang kosong</p>';
                document.getElementById('displayTotal').textContent = 'Rp 0';
                document.getElementById('btnCheckout').disabled = true;
                return;
            }

            cartDiv.innerHTML = cart.map(item => {
                const subtotal = item.harga * item.jumlah;
                return `
                    <div class="cart-item">
                        <div>
                            <strong>${item.nama}</strong><br>
                            <small>${formatRp(item.harga)} × ${item.jumlah}</small>
                        </div>
                        <div class="qty-controls">
                            <button type="button" class="qty-btn" onclick="updateQty(${item.id}, -1)">−</button>
                            <span>${item.jumlah}</span>
                            <button type="button" class="qty-btn" onclick="updateQty(${item.id}, 1)" 
                                    ${item.jumlah >= item.stock ? 'disabled' : ''}>+</button>
                        </div>
                        <div>
                            <strong>${formatRp(subtotal)}</strong><br>
                            <button type="button" class="remove-btn" onclick="removeItem(${item.id})">🗑️</button>
                        </div>
                    </div>
                `;
            }).join('');

            // Hitung total
            const total = cart.reduce((sum, item) => sum + (item.harga * item.jumlah), 0);
            document.getElementById('displayTotal').textContent = formatRp(total);
            document.getElementById('inputTotalHarga').value = total;
            
            // Set items untuk POST
            const itemsForPost = cart.map(item => ({
                id: item.id,
                nama: item.nama,
                harga: item.harga,
                jumlah: item.jumlah,
                subtotal: item.harga * item.jumlah
            }));
            document.getElementById('inputItems').value = JSON.stringify(itemsForPost);
            
            document.getElementById('btnCheckout').disabled = false;
        }

        // Konfirmasi sebelum submit
        document.getElementById('formCheckout').onsubmit = function(e) {
            const nama = this.nama_pembeli.value.trim();
            const total = document.getElementById('displayTotal').textContent;
            
            if (!confirm(`Konfirmasi Pesanan?\n\nNama: ${nama}\nTotal: ${total}`)) {
                e.preventDefault();
                return false;
            }
        };

        // Init
        renderMenu();
    </script>
</body>
</html>