<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendataan Menu - Dapur Kuliner Pak Ndut</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #FF9800 0%, #F57C00 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #FF9800;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .header-icon {
            width: 50px;
            height: 50px;
            background: #FF9800;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            color: white;
        }

        .header-title h1 {
            color: #FF9800;
            font-size: 2em;
            margin-bottom: 5px;
        }

        .breadcrumb {
            color: #999;
            font-size: 0.9em;
        }

        .breadcrumb a {
            color: #FF9800;
            text-decoration: none;
        }

        .breadcrumb a:hover {
            text-decoration: underline;
        }

        .header-buttons {
            display: flex;
            gap: 10px;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            font-size: 0.95em;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-back {
            background: #e0e0e0;
            color: #666;
        }

        .btn-back:hover {
            background: #d0d0d0;
        }

        .btn-add {
            background: #FF9800;
            color: white;
        }

        .btn-add:hover {
            background: #F57C00;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 152, 0, 0.3);
        }

        .controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            gap: 20px;
            flex-wrap: wrap;
        }

        .search-box {
            flex: 1;
            min-width: 250px;
            position: relative;
        }

        .search-box input {
            width: 100%;
            padding: 12px 45px 12px 20px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 0.95em;
            transition: all 0.3s;
        }

        .search-box input:focus {
            outline: none;
            border-color: #FF9800;
        }

        .search-icon {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
            font-size: 1.2em;
        }

        .filter-group {
            display: flex;
            gap: 10px;
        }

        .filter-select {
            padding: 12px 20px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 0.95em;
            background: white;
            cursor: pointer;
            transition: all 0.3s;
            min-width: 150px;
        }

        .filter-select:focus {
            outline: none;
            border-color: #FF9800;
        }

        .table-container {
            overflow-x: auto;
            border-radius: 12px;
            border: 2px solid #f0f0f0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        thead {
            background: #FF9800;
            color: white;
        }

        thead th {
            padding: 18px;
            text-align: left;
            font-weight: 600;
            font-size: 0.95em;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        tbody tr {
            border-bottom: 1px solid #f0f0f0;
            transition: all 0.2s;
        }

        tbody tr:hover {
            background: #fff8f0;
        }

        tbody td {
            padding: 18px;
            color: #333;
        }

        .menu-name {
            font-weight: 600;
            color: #222;
        }

        .menu-category {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.85em;
            font-weight: 600;
        }

        .category-pentol {
            background: #FFE0B2;
            color: #E65100;
        }

        .category-bento {
            background: #C8E6C9;
            color: #2E7D32;
        }

        .category-minuman {
            background: #BBDEFB;
            color: #1565C0;
        }

        .menu-price {
            font-weight: 700;
            color: #FF9800;
            font-size: 1.05em;
        }

        .menu-image {
            width: 60px;
            height: 60px;
            border-radius: 8px;
            object-fit: cover;
            border: 2px solid #f0f0f0;
        }

        .action-buttons {
            display: flex;
            gap: 8px;
        }

        .btn-edit {
            padding: 8px 16px;
            background: #7C4DFF;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.85em;
            font-weight: 600;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .btn-edit:hover {
            background: #651FFF;
            transform: translateY(-2px);
        }

        .btn-delete {
            padding: 8px 16px;
            background: #F44336;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.85em;
            font-weight: 600;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .btn-delete:hover {
            background: #D32F2F;
            transform: translateY(-2px);
        }

        .no-data {
            text-align: center;
            padding: 60px 20px;
            color: #999;
            font-size: 1.1em;
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.6);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }

        .modal.active {
            display: flex;
        }

        .modal-content {
            background: white;
            padding: 30px;
            border-radius: 15px;
            max-width: 500px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
        }

        .modal-header {
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f0f0f0;
        }

        .modal-header h2 {
            color: #FF9800;
            font-size: 1.5em;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 600;
            font-size: 0.95em;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 0.95em;
            transition: all 0.3s;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #FF9800;
        }

        .modal-buttons {
            display: flex;
            gap: 10px;
            margin-top: 25px;
        }

        .btn-submit {
            flex: 1;
            padding: 14px;
            background: #FF9800;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            font-size: 1em;
            transition: all 0.3s;
        }

        .btn-submit:hover {
            background: #F57C00;
        }

        .btn-cancel {
            flex: 1;
            padding: 14px;
            background: #e0e0e0;
            color: #666;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            font-size: 1em;
            transition: all 0.3s;
        }

        .btn-cancel:hover {
            background: #d0d0d0;
        }

        .image-preview {
            width: 100%;
            max-width: 200px;
            height: 200px;
            border-radius: 8px;
            object-fit: cover;
            margin-top: 10px;
            border: 2px solid #e0e0e0;
            display: none;
        }

        @media (max-width: 768px) {
            .container {
                padding: 20px;
            }

            .header {
                flex-direction: column;
                gap: 20px;
            }

            .controls {
                flex-direction: column;
            }

            .search-box {
                width: 100%;
            }

            .filter-group {
                width: 100%;
                flex-direction: column;
            }

            .filter-select {
                width: 100%;
            }

            table {
                font-size: 0.85em;
            }

            thead th,
            tbody td {
                padding: 12px 8px;
            }

            .action-buttons {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="header-left">
                <div class="header-icon">🍽️</div>
                <div class="header-title">
                    <h1>Data Menu</h1>
                    <div class="breadcrumb">
                        <a href="#">Dashboard</a> / Data Menu
                    </div>
                </div>
            </div>
            <div class="header-buttons">
                <button class="btn btn-back" onclick="goBack()">
                    ← Kembali
                </button>
                <button class="btn btn-add" onclick="openModal()">
                    + Tambah Menu
                </button>
            </div>
        </div>

        <div class="controls">
            <div class="search-box">
                <input type="text" id="searchInput" placeholder="Cari nama menu..." oninput="filterMenu()">
                <span class="search-icon">🔍</span>
            </div>
            <div class="filter-group">
                <select class="filter-select" id="categoryFilter" onchange="filterMenu()">
                    <option value="">Semua Kategori</option>
                    <option value="Pentol">Pentol & Gorengan</option>
                    <option value="Nasi Bento">Nasi Bento</option>
                    <option value="Minuman">Minuman</option>
                </select>
            </div>
        </div>

        <div class="table-container">
            <table id="menuTable">
                <thead>
                    <tr>
                        <th>NO</th>
                        <th>GAMBAR</th>
                        <th>NAMA MENU</th>
                        <th>KATEGORI</th>
                        <th>HARGA</th>
                        <th>AKSI</th>
                    </tr>
                </thead>
                <tbody id="menuTableBody">
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Tambah/Edit -->
    <div class="modal" id="menuModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="modalTitle">Tambah Menu Baru</h2>
            </div>
            <form id="menuForm" onsubmit="saveMenu(event)">
                <input type="hidden" id="menuId">
                <input type="hidden" id="existingImage">
                
                <div class="form-group">
                    <label>Nama Menu *</label>
                    <input type="text" id="menuName" required placeholder="Contoh: Pentol Bakar">
                </div>

                <div class="form-group">
                    <label>Kategori *</label>
                    <select id="menuCategory" required>
                        <option value="">Pilih Kategori</option>
                        <option value="Pentol">Pentol & Gorengan</option>
                        <option value="Nasi Bento">Nasi Bento</option>
                        <option value="Minuman">Minuman</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Harga (Rp) *</label>
                    <input type="number" id="menuPrice" required placeholder="Contoh: 5000" min="0">
                </div>

                <div class="form-group">
                    <label>Upload Gambar <span id="imageRequired">(Wajib)</span></label>
                    <input type="file" id="menuImage" accept="image/*" onchange="previewImage(event)">
                    <img id="imagePreview" class="image-preview" alt="Preview">
                </div>

                <div class="modal-buttons">
                    <button type="button" class="btn-cancel" onclick="closeModal()">Batal</button>
                    <button type="submit" class="btn-submit">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let menuData = [];
        let editingId = null;

        // Inisialisasi data default
        const defaultMenuData = [
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

        // Load data dari storage
        async function loadData() {
            try {
                const result = await window.storage.get('menuData');
                if (result && result.value) {
                    menuData = JSON.parse(result.value);
                } else {
                    menuData = defaultMenuData;
                    await saveData();
                }
            } catch (error) {
                console.log('Storage tidak tersedia, menggunakan data default');
                menuData = defaultMenuData;
            }
            displayMenu();
        }

        // Simpan data ke storage
        async function saveData() {
            try {
                await window.storage.set('menuData', JSON.stringify(menuData));
            } catch (error) {
                console.error('Gagal menyimpan data:', error);
            }
        }

        function formatRupiah(amount) {
            return 'Rp ' + amount.toLocaleString('id-ID');
        }

        function getCategoryClass(category) {
            if (category === 'Pentol') return 'category-pentol';
            if (category === 'Nasi Bento') return 'category-bento';
            if (category === 'Minuman') return 'category-minuman';
            return '';
        }

        function displayMenu(data = menuData) {
            const tbody = document.getElementById('menuTableBody');
            
            if (data.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="6" class="no-data">Tidak ada data menu yang ditemukan</td>
                    </tr>
                `;
                return;
            }

            tbody.innerHTML = '';
            
            data.forEach((item, index) => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${index + 1}</td>
                    <td><img src="${item.image}" alt="${item.name}" class="menu-image" onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%2260%22 height=%2260%22%3E%3Crect fill=%22%23f0f0f0%22 width=%2260%22 height=%2260%22/%3E%3Ctext x=%2250%25%22 y=%2250%25%22 text-anchor=%22middle%22 dy=%22.3em%22 font-size=%2224%22%3E🍽️%3C/text%3E%3C/svg%3E'"></td>
                    <td class="menu-name">${item.name}</td>
                    <td><span class="menu-category ${getCategoryClass(item.category)}">${item.category}</span></td>
                    <td class="menu-price">${formatRupiah(item.price)}</td>
                    <td>
                        <div class="action-buttons">
                            <button class="btn-edit" onclick="editMenu(${item.id})">
                                ✏️ Edit
                            </button>
                            <button class="btn-delete" onclick="deleteMenu(${item.id})">
                                🗑️ Hapus
                            </button>
                        </div>
                    </td>
                `;
                tbody.appendChild(row);
            });
        }

        function filterMenu() {
            const searchValue = document.getElementById('searchInput').value.toLowerCase();
            const categoryValue = document.getElementById('categoryFilter').value;

            let filtered = menuData;

            if (searchValue) {
                filtered = filtered.filter(item => 
                    item.name.toLowerCase().includes(searchValue)
                );
            }

            if (categoryValue) {
                filtered = filtered.filter(item => item.category === categoryValue);
            }

            displayMenu(filtered);
        }

        function previewImage(event) {
            const file = event.target.files[0];
            const preview = document.getElementById('imagePreview');
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        }

        function openModal(id = null) {
            const modal = document.getElementById('menuModal');
            const modalTitle = document.getElementById('modalTitle');
            const imageInput = document.getElementById('menuImage');
            const imageRequired = document.getElementById('imageRequired');
            const preview = document.getElementById('imagePreview');
            
            if (id) {
                editingId = id;
                const menu = menuData.find(m => m.id === id);
                modalTitle.textContent = 'Edit Menu';
                document.getElementById('menuId').value = menu.id;
                document.getElementById('menuName').value = menu.name;
                document.getElementById('menuCategory').value = menu.category;
                document.getElementById('menuPrice').value = menu.price;
                document.getElementById('existingImage').value = menu.image;
                
                // Preview gambar yang ada
                preview.src = menu.image;
                preview.style.display = 'block';
                
                // Tidak wajib upload gambar saat edit
                imageInput.removeAttribute('required');
                imageRequired.textContent = '(Opsional)';
            } else {
                editingId = null;
                modalTitle.textContent = 'Tambah Menu Baru';
                document.getElementById('menuForm').reset();
                preview.style.display = 'none';
                
                // Wajib upload gambar saat tambah baru
                imageInput.setAttribute('required', 'required');
                imageRequired.textContent = '(Wajib)';
            }
            
            modal.classList.add('active');
        }

        function closeModal() {
            const modal = document.getElementById('menuModal');
            modal.classList.remove('active');
            document.getElementById('menuForm').reset();
            document.getElementById('imagePreview').style.display = 'none';
            editingId = null;
        }

        async function saveMenu(event) {
            event.preventDefault();

            const name = document.getElementById('menuName').value;
            const category = document.getElementById('menuCategory').value;
            const price = parseInt(document.getElementById('menuPrice').value);
            const fileInput = document.getElementById('menuImage');
            
            let image = document.getElementById('existingImage').value || 'img/default.jpg';

            // Jika ada file baru yang diupload
            if (fileInput.files.length > 0) {
                const file = fileInput.files[0];
                const reader = new FileReader();
                
                reader.onload = async function(e) {
                    image = e.target.result;
                    await saveMenuData(name, category, price, image);
                };
                
                reader.readAsDataURL(file);
            } else {
                await saveMenuData(name, category, price, image);
            }
        }

        async function saveMenuData(name, category, price, image) {
            if (editingId) {
                const index = menuData.findIndex(m => m.id === editingId);
                menuData[index] = { ...menuData[index], name, category, price, image };
                alert('Menu berhasil diupdate!');
            } else {
                const newId = menuData.length > 0 ? Math.max(...menuData.map(m => m.id)) + 1 : 1;
                menuData.push({ id: newId, name, category, price, image });
                alert('Menu berhasil ditambahkan!');
            }

            await saveData();
            closeModal();
            displayMenu();
            filterMenu();
        }

        function editMenu(id) {
            openModal(id);
        }

        async function deleteMenu(id) {
            if (confirm('Apakah Anda yakin ingin menghapus menu ini?')) {
                menuData = menuData.filter(item => item.id !== id);
                await saveData();
                displayMenu();
                filterMenu();
                alert('Menu berhasil dihapus!');
            }
        }

        function goBack() {
            window.history.back();
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('menuModal');
            if (event.target === modal) {
                closeModal();
            }
        }

        // Initialize
        loadData();
    </script>
</body>
</html>