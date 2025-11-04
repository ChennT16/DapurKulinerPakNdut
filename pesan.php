<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pemesanan - Dapur Pak Ndut</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #FFF3E0 0%, #FFE0B2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
        }

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

        .header h1 {
            color: #FF9800;
            display: flex;
            align-items: center;
            gap: 15px;
            font-size: 2em;
        }

        .header .logo-icon {
            width: 50px;
            height: 50px;
            background: #FF9800;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
        }

        .back-btn {
            padding: 12px 25px;
            background: #FF9800;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            transition: all 0.3s;
            cursor: pointer;
            border: none;
            font-size: 1em;
        }

        .back-btn:hover {
            background: #F57C00;
            transform: translateY(-2px);
        }

        .content-wrapper {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 30px;
        }

        .menu-section {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }

        .menu-section h2 {
            color: #333;
            margin-bottom: 20px;
            font-size: 1.8em;
        }

        .filter-buttons {
            display: flex;
            gap: 10px;
            margin-bottom: 25px;
            flex-wrap: wrap;
        }

        .filter-btn {
            padding: 10px 20px;
            border: 2px solid #FF9800;
            border-radius: 25px;
            background: white;
            color: #FF9800;
            cursor: pointer;
            font-size: 0.95em;
            font-weight: 600;
            transition: all 0.3s;
        }

        .filter-btn:hover {
            background: #FFE0B2;
        }

        .filter-btn.active {
            background: #FF9800;
            color: white;
        }

        .menu-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 20px;
            max-height: 600px;
            overflow-y: auto;
            padding-right: 10px;
        }

        .menu-grid::-webkit-scrollbar {
            width: 8px;
        }

        .menu-grid::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .menu-grid::-webkit-scrollbar-thumb {
            background: #FF9800;
            border-radius: 10px;
        }

        .menu-card {
            background: white;
            border: 2px solid #f3f4f6;
            border-radius: 12px;
            overflow: hidden;
            transition: all 0.3s;
            cursor: pointer;
        }

        .menu-card:hover {
            border-color: #FF9800;
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(255, 152, 0, 0.2);
        }

        .menu-image {
            width: 100%;
            height: 160px;
            object-fit: cover;
            background-color: #f3f4f6;
        }

        .menu-info {
            padding: 15px;
        }

        .menu-info h4 {
            color: #333;
            font-size: 1.05em;
            margin-bottom: 5px;
        }

        .menu-category {
            color: #999;
            font-size: 0.85em;
            margin-bottom: 8px;
        }

        .menu-price {
            color: #FF9800;
            font-size: 1.2em;
            font-weight: bold;
            margin-bottom: 12px;
        }

        .add-btn {
            width: 100%;
            padding: 10px;
            background: #FF9800;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s;
        }

        .add-btn:hover {
            background: #F57C00;
            transform: scale(1.02);
        }

        .cart-section {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            position: sticky;
            top: 20px;
            max-height: calc(100vh - 40px);
            display: flex;
            flex-direction: column;
        }

        .cart-section h2 {
            color: #333;
            margin-bottom: 20px;
            font-size: 1.5em;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .cart-empty {
            text-align: center;
            color: #999;
            padding: 60px 20px;
            font-size: 1.1em;
        }

        .cart-items {
            overflow-y: auto;
            margin-bottom: 20px;
            flex: 1;
            padding-right: 5px;
        }

        .cart-items::-webkit-scrollbar {
            width: 6px;
        }

        .cart-items::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .cart-items::-webkit-scrollbar-thumb {
            background: #FF9800;
            border-radius: 10px;
        }

        .cart-item {
            background: #f9fafb;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 12px;
            border: 1px solid #e5e7eb;
        }

        .cart-item-header {
            margin-bottom: 10px;
        }

        .cart-item-header h5 {
            color: #333;
            font-size: 1em;
            margin-bottom: 5px;
        }

        .cart-item-price {
            color: #666;
            font-size: 0.9em;
        }

        .cart-item-controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .quantity-controls {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .qty-btn {
            width: 32px;
            height: 32px;
            border: none;
            background: #e5e7eb;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
            font-size: 1.1em;
            transition: all 0.3s;
        }

        .qty-btn:hover {
            background: #d1d5db;
        }

        .quantity {
            font-weight: bold;
            min-width: 30px;
            text-align: center;
        }

        .remove-btn {
            background: #ef4444;
            color: white;
            border: none;
            padding: 6px 14px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.85em;
            transition: all 0.3s;
        }

        .remove-btn:hover {
            background: #dc2626;
        }

        .cart-total {
            border-top: 2px solid #e5e7eb;
            padding-top: 15px;
            margin-bottom: 15px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 0.95em;
        }

        .total-row.final {
            font-size: 1.3em;
            font-weight: bold;
            color: #FF9800;
            padding-top: 10px;
            border-top: 1px solid #e5e7eb;
        }

        .checkout-btn {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #FF9800, #F57C00);
            color: white;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-size: 1.1em;
            font-weight: bold;
            transition: all 0.3s;
        }

        .checkout-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 152, 0, 0.4);
        }

        .checkout-btn:disabled {
            background: #ccc;
            cursor: not-allowed;
            transform: none;
        }

        @media (max-width: 1024px) {
            .content-wrapper {
                grid-template-columns: 1fr;
            }

            .cart-section {
                position: relative;
                top: 0;
                max-height: none;
            }

            .menu-grid {
                max-height: none;
            }
        }

        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }

            .header h1 {
                font-size: 1.5em;
            }

            .menu-grid {
                grid-template-columns: 1fr;
            }

            .filter-buttons {
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>
                <div class="logo-icon">👨‍🍳</div>
                Pemesanan Menu
            </h1>
            <button class="back-btn" onclick="goBack()">← Kembali ke Beranda</button>
        </div>

        <div class="content-wrapper">
            <div class="menu-section">
                <h2>📋 Pilih Menu</h2>
                
                <div class="filter-buttons">
                    <button class="filter-btn active" onclick="filterCategory('Semua')">Semua</button>
                    <button class="filter-btn" onclick="filterCategory('Pentol')">Pentol & Gorengan</button>
                    <button class="filter-btn" onclick="filterCategory('Nasi Bento')">Nasi Bento</button>
                    <button class="filter-btn" onclick="filterCategory('Minuman')">Minuman</button>
                </div>

                <div class="menu-grid" id="menuGrid"></div>
            </div>

            <div class="cart-section">
                <h2>🛒 Keranjang</h2>
                <div id="cartContent">
                    <div class="cart-empty">Keranjang masih kosong<br>Silakan pilih menu</div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const menuData = [
            { id: 1, name: 'Pentol', price: 5000, category: 'Pentol', image: 'img/PENTOL.jpg' },
            { id: 2, name: 'Pentol Tahu', price: 5000, category: 'Pentol', image: 'img/PENTOL TAHU.jpg' },
            { id: 3, name: 'Pentol Bakar', price: 1000, category: 'Pentol', image: 'img/PENTOL BAKAR.jpg' },
            { id: 4, name: 'Tahu Bakar', price: 1000, category: 'Pentol', image: 'img/TAHU BAKAR.jpg' },
            { id: 11, name: 'Gorengan Pangsit', price: 1000, category: 'Pentol', image: 'img/GORENGAN PANGSIT.jpg' },
            
            { id: 5, name: 'Nasi Bento Ayam Katsu', price: 10000, category: 'Nasi Bento', image: 'img/NASI BENTO KATSU.jpg' },
            { id: 6, name: 'Nasi Bento Ayam Crispy', price: 10000, category: 'Nasi Bento', image: 'img/NASI BENTO AYAM CRISPY.jpg' },
            { id: 7, name: 'Nasi Bento Ayam Geprek', price: 10000, category: 'Nasi Bento', image: 'img/NASI BENTO GEPREK.jpg' },
            { id: 8, name: 'Nasi Bento Beff & Sosis', price: 10000, category: 'Nasi Bento', image: 'img/NASI BENTO DAGING DAN SOSIS.jpg' },
            { id: 9, name: 'Nasi Bento Rica-Rica Balungan', price: 8000, category: 'Nasi Bento', image: 'img/NASI BENTO RICA RICA BALUNGAN.jpg' },
            { id: 10, name: 'Nasi Bento Ati Ampela', price: 8000, category: 'Nasi Bento', image: 'img/NASI BENTO ATI AMPELA (1).jpg' },
            
            { id: 12, name: 'Susu Kedelai', price: 6000, category: 'Minuman', image: 'img/SUSU KEDELAI.jpg' },
            { id: 13, name: 'Es Kuwut', price: 3000, category: 'Minuman', image: 'img/ES KUWUT.jpg' },
            { id: 14, name: 'Es Rasa Rasa', price: 3000, category: 'Minuman', image: 'img/ES RASA RASA.jpg' },
            { id: 15, name: 'Es Teh', price: 3000, category: 'Minuman', image: 'img/ES TEH.jpg' },
            { id: 16, name: 'Teh Lemon', price: 3000, category: 'Minuman', image: 'img/LEMON TEA.jpg' },
            { id: 17, name: 'Air Mineral', price: 3000, category: 'Minuman', image: 'img/AIR MINERAL.jpg' }
        ];

        let cart = [];
        let currentCategory = 'Semua';

        function formatRupiah(amount) {
            return 'Rp ' + amount.toLocaleString('id-ID');
        }

        function goBack() {
            window.history.back();
        }

        function displayMenu() {
            const menuGrid = document.getElementById('menuGrid');
            const filteredMenu = currentCategory === 'Semua' 
                ? menuData 
                : menuData.filter(item => item.category === currentCategory);

            menuGrid.innerHTML = '';

            filteredMenu.forEach(item => {
                const menuCard = document.createElement('div');
                menuCard.className = 'menu-card';
                
                const imgElement = document.createElement('img');
                imgElement.src = item.image;
                imgElement.alt = item.name;
                imgElement.className = 'menu-image';
                imgElement.onerror = function() {
                    this.style.display = 'none';
                    const placeholder = document.createElement('div');
                    placeholder.className = 'menu-image';
                    placeholder.style.display = 'flex';
                    placeholder.style.alignItems = 'center';
                    placeholder.style.justifyContent = 'center';
                    placeholder.style.fontSize = '2em';
                    placeholder.style.color = '#999';
                    placeholder.textContent = '🍽️';
                    this.parentNode.insertBefore(placeholder, this);
                };
                
                const menuInfo = document.createElement('div');
                menuInfo.className = 'menu-info';
                menuInfo.innerHTML = `
                    <h4>${item.name}</h4>
                    <div class="menu-category">${item.category}</div>
                    <div class="menu-price">${formatRupiah(item.price)}</div>
                    <button class="add-btn" onclick="addToCart(${item.id})">+ Tambah</button>
                `;
                
                menuCard.appendChild(imgElement);
                menuCard.appendChild(menuInfo);
                menuGrid.appendChild(menuCard);
            });
        }

        function filterCategory(category) {
            currentCategory = category;
            
            document.querySelectorAll('.filter-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            event.target.classList.add('active');
            
            displayMenu();
        }

        function addToCart(itemId) {
            const item = menuData.find(m => m.id === itemId);
            if (!item) return;

            const cartItem = cart.find(c => c.id === itemId);
            
            if (cartItem) {
                cartItem.quantity++;
            } else {
                cart.push({
                    ...item,
                    quantity: 1
                });
            }

            updateCart();
        }

        function updateQuantity(itemId, change) {
            const cartItem = cart.find(c => c.id === itemId);
            if (!cartItem) return;

            cartItem.quantity += change;

            if (cartItem.quantity <= 0) {
                removeFromCart(itemId);
            } else {
                updateCart();
            }
        }

        function removeFromCart(itemId) {
            cart = cart.filter(item => item.id !== itemId);
            updateCart();
        }

        function updateCart() {
            const cartContent = document.getElementById('cartContent');

            if (cart.length === 0) {
                cartContent.innerHTML = '<div class="cart-empty">Keranjang masih kosong<br>Silakan pilih menu</div>';
                return;
            }

            let html = '<div class="cart-items">';
            
            cart.forEach(item => {
                html += `
                    <div class="cart-item">
                        <div class="cart-item-header">
                            <h5>${item.name}</h5>
                            <div class="cart-item-price">${formatRupiah(item.price)} x ${item.quantity}</div>
                        </div>
                        <div class="cart-item-controls">
                            <div class="quantity-controls">
                                <button class="qty-btn" onclick="updateQuantity(${item.id}, -1)">-</button>
                                <span class="quantity">${item.quantity}</span>
                                <button class="qty-btn" onclick="updateQuantity(${item.id}, 1)">+</button>
                            </div>
                            <button class="remove-btn" onclick="removeFromCart(${item.id})">Hapus</button>
                        </div>
                    </div>
                `;
            });

            html += '</div>';

            const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
            const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);

            html += `
                <div class="cart-total">
                    <div class="total-row">
                        <span>Jumlah Item:</span>
                        <span>${totalItems} item</span>
                    </div>
                    <div class="total-row">
                        <span>Subtotal:</span>
                        <span>${formatRupiah(subtotal)}</span>
                    </div>
                    <div class="total-row final">
                        <span>Total Bayar:</span>
                        <span>${formatRupiah(subtotal)}</span>
                    </div>
                </div>
                <button class="checkout-btn" onclick="checkout()">Konfirmasi Pesanan</button>
            `;

            cartContent.innerHTML = html;
        }

        function checkout() {
            if (cart.length === 0) {
                alert('Keranjang masih kosong!');
                return;
            }

            const total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            const itemCount = cart.reduce((sum, item) => sum + item.quantity, 0);

            let orderDetails = '=== PESANAN ANDA ===\n\n';
            cart.forEach(item => {
                orderDetails += `${item.name}\n`;
                orderDetails += `${item.quantity} x ${formatRupiah(item.price)} = ${formatRupiah(item.price * item.quantity)}\n\n`;
            });
            orderDetails += `Total: ${formatRupiah(total)}\n`;
            orderDetails += `Jumlah Item: ${itemCount}\n\n`;
            orderDetails += 'Terima kasih telah memesan di Dapur Kuliner Pak Ndut!';

            alert(orderDetails);

            cart = [];
            updateCart();
        }

        displayMenu();
    </script>
</body>
</html>