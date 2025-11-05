<?php
include 'koneksi.php';

// Proses form jika di-submit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_menu = mysqli_real_escape_string($conn, $_POST['nama_menu']);
    $harga_menu = mysqli_real_escape_string($conn, $_POST['harga_menu']);
    $jenis_menu = mysqli_real_escape_string($conn, $_POST['jenis_menu']);
    $stock_menu = mysqli_real_escape_string($conn, $_POST['stock_menu']);
    $id_produksi = isset($_POST['id_produksi']) ? mysqli_real_escape_string($conn, $_POST['id_produksi']) : '';
    
    // Upload gambar
    $gambar_menu = '';
    if (isset($_FILES['gambar_menu']) && $_FILES['gambar_menu']['error'] == 0) {
        $target_dir = "images/menu/";
        
        // Cek apakah folder ada, jika tidak buat folder
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        
        $file_extension = strtolower(pathinfo($_FILES["gambar_menu"]["name"], PATHINFO_EXTENSION));
        $new_filename = 'menu_' . time() . '_' . uniqid() . '.' . $file_extension;
        $target_file = $target_dir . $new_filename;
        
        // Validasi file
        $allowed_types = array('jpg', 'jpeg', 'png', 'gif');
        $max_size = 2 * 1024 * 1024; // 2MB
        
        if (in_array($file_extension, $allowed_types) && $_FILES["gambar_menu"]["size"] <= $max_size) {
            if (move_uploaded_file($_FILES["gambar_menu"]["tmp_name"], $target_file)) {
                $gambar_menu = $new_filename;
            } else {
                $error_msg = "Gagal mengupload gambar!";
            }
        } else {
            $error_msg = "File harus berformat JPG/JPEG/PNG dan maksimal 2MB!";
        }
    }
    
    if (!isset($error_msg)) {
        // Insert ke database - TANPA id_menu (AUTO_INCREMENT akan generate otomatis)
        $sql = "INSERT INTO menu (nama_menu, harga_menu, jenis_menu, stock_menu, gambar_menu, id_produksi) 
                VALUES ('$nama_menu', '$harga_menu', '$jenis_menu', '$stock_menu', '$gambar_menu', '$id_produksi')";
        
        if (mysqli_query($conn, $sql)) {
            echo "<script>
                    alert('✅ Menu berhasil ditambahkan!');
                    window.location='pendataan_menu.php?pesan=sukses';
                  </script>";
            exit();
        } else {
            $error_msg = "Gagal menyimpan data: " . mysqli_error($conn);
        }
    }
}

// Ambil daftar kategori untuk dropdown
$kategori_query = "SELECT DISTINCT jenis_menu FROM menu WHERE jenis_menu IS NOT NULL ORDER BY jenis_menu";
$kategori_result = mysqli_query($conn, $kategori_query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Menu - Dapur Pak Ndut</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            padding: 20px;
            min-height: 100vh;
        }
        
        .container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .header {
            background: linear-gradient(135deg, #FF8C00 0%, #FFA500 100%);
            padding: 30px 40px;
            color: white;
        }
        
        .header h1 {
            font-size: 28px;
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 5px;
        }
        
        .breadcrumb {
            opacity: 0.9;
            font-size: 14px;
        }
        
        .form-container {
            padding: 40px;
        }
        
        .alert {
            padding: 15px 20px;
            margin-bottom: 25px;
            border-radius: 10px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .alert-danger {
            background: #fee;
            color: #c33;
            border: 1px solid #fcc;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 25px;
            margin-bottom: 25px;
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        .form-group.full-width {
            grid-column: 1 / -1;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
            font-size: 14px;
        }
        
        label .required {
            color: #ff4444;
        }
        
        input[type="text"],
        input[type="number"],
        select {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 15px;
            transition: all 0.3s;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        input:focus,
        select:focus {
            outline: none;
            border-color: #FF8C00;
            box-shadow: 0 0 0 4px rgba(255,140,0,0.1);
        }
        
        .file-upload-container {
            border: 3px dashed #d0d0d0;
            border-radius: 15px;
            padding: 40px;
            text-align: center;
            transition: all 0.3s;
            background: #fafafa;
            cursor: pointer;
            position: relative;
        }
        
        .file-upload-container:hover {
            border-color: #FF8C00;
            background: #fff8f0;
        }
        
        .file-upload-container.dragover {
            border-color: #FF8C00;
            background: #fff8f0;
            transform: scale(1.02);
        }
        
        #gambar_menu {
            display: none;
        }
        
        .upload-icon {
            font-size: 60px;
            margin-bottom: 15px;
            color: #999;
        }
        
        .upload-text {
            font-size: 16px;
            color: #666;
            margin-bottom: 8px;
            font-weight: 500;
        }
        
        .upload-hint {
            font-size: 13px;
            color: #999;
        }
        
        #preview-container {
            margin-top: 20px;
        }
        
        .preview-image {
            max-width: 300px;
            max-height: 300px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.15);
            margin: 15px auto;
            display: block;
        }
        
        .filename-display {
            margin-top: 15px;
            padding: 12px 20px;
            background: #f0f8ff;
            border-radius: 10px;
            font-size: 14px;
            color: #0066cc;
            border: 1px solid #b3d9ff;
            display: inline-block;
        }
        
        .button-group {
            display: flex;
            gap: 15px;
            margin-top: 35px;
            padding-top: 25px;
            border-top: 2px solid #f0f0f0;
        }
        
        .btn {
            padding: 14px 30px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-size: 15px;
            font-weight: 600;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            justify-content: center;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #FF8C00 0%, #FFA500 100%);
            color: white;
            flex: 1;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(255,140,0,0.4);
        }
        
        .btn-primary:active {
            transform: translateY(0);
        }
        
        .btn-secondary {
            background: #6c757d;
            color: white;
            padding: 14px 25px;
        }
        
        .btn-secondary:hover {
            background: #5a6268;
            transform: translateY(-2px);
        }
        
        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }
            
            .container {
                margin: 10px;
            }
            
            .form-container {
                padding: 25px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><span>➕</span> Tambah Menu Baru</h1>
            <div class="breadcrumb">Dashboard / Data Menu / Tambah Menu</div>
        </div>
        
        <div class="form-container">
            <?php if (isset($error_msg)): ?>
                <div class="alert alert-danger">
                    <span>⚠️</span>
                    <span><?php echo $error_msg; ?></span>
                </div>
            <?php endif; ?>
            
            <form method="POST" enctype="multipart/form-data" id="formTambah">
                <div class="form-row">
                    <div class="form-group">
                        <label>Nama Menu <span class="required">*</span></label>
                        <input type="text" name="nama_menu" required placeholder="Contoh: Nasi Bento Ayam Katsu">
                    </div>
                    
                    <div class="form-group">
                        <label>Harga <span class="required">*</span></label>
                        <input type="number" name="harga_menu" required placeholder="Contoh: 15000" min="0">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Kategori <span class="required">*</span></label>
                        <select name="jenis_menu" required>
                            <option value="">-- Pilih Kategori --</option>
                            <?php 
                            $added_categories = array();
                            while ($kat = mysqli_fetch_assoc($kategori_result)) {
                                if (!in_array($kat['jenis_menu'], $added_categories)) {
                                    echo '<option value="' . htmlspecialchars($kat['jenis_menu']) . '">';
                                    echo htmlspecialchars($kat['jenis_menu']);
                                    echo '</option>';
                                    $added_categories[] = $kat['jenis_menu'];
                                }
                            }
                            ?>
                            <option value="Makanan">Makanan</option>
                            <option value="Minuman">Minuman</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Stok <span class="required">*</span></label>
                        <input type="number" name="stock_menu" required placeholder="Contoh: 100" min="0" value="250">
                    </div>
                </div>
                
                <div class="form-group">
                    <label>ID Produksi (Opsional)</label>
                    <input type="text" name="id_produksi" placeholder="Contoh: 112233">
                </div>
                
                <div class="form-group">
                    <label>Gambar Menu (Max 2MB, JPG/JPEG/PNG) <span class="required">*</span></label>
                    <div class="file-upload-container" id="dropZone" onclick="document.getElementById('gambar_menu').click()">
                        <div class="upload-icon">📷</div>
                        <div class="upload-text">Klik untuk memilih gambar</div>
                        <div class="upload-hint">atau drag & drop file di sini</div>
                        <input type="file" name="gambar_menu" id="gambar_menu" accept="image/*" required onchange="previewImage(this)">
                    </div>
                    <div id="preview-container"></div>
                </div>
                
                <div class="button-group">
                    <button type="submit" class="btn btn-primary">
                        <span>💾</span> Simpan Menu
                    </button>
                    <a href="pendataan_menu.php" class="btn btn-secondary">
                        <span>←</span> Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Preview gambar sebelum upload
        function previewImage(input) {
            const previewContainer = document.getElementById('preview-container');
            previewContainer.innerHTML = '';
            
            if (input.files && input.files[0]) {
                const file = input.files[0];
                
                // Validasi ukuran file
                const maxSize = 2 * 1024 * 1024; // 2MB
                if (file.size > maxSize) {
                    alert('⚠️ Ukuran file terlalu besar! Maksimal 2MB.');
                    input.value = '';
                    return;
                }
                
                // Tampilkan nama file
                const filename = document.createElement('div');
                filename.className = 'filename-display';
                filename.innerHTML = '📄 ' + file.name + ' (' + (file.size / 1024).toFixed(2) + ' KB)';
                previewContainer.appendChild(filename);
                
                // Tampilkan preview gambar
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'preview-image';
                    previewContainer.appendChild(img);
                }
                reader.readAsDataURL(file);
            }
        }
        
        // Drag & Drop functionality
        const dropZone = document.getElementById('dropZone');
        
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, preventDefaults, false);
        });
        
        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }
        
        ['dragenter', 'dragover'].forEach(eventName => {
            dropZone.addEventListener(eventName, () => {
                dropZone.classList.add('dragover');
            }, false);
        });
        
        ['dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, () => {
                dropZone.classList.remove('dragover');
            }, false);
        });
        
        dropZone.addEventListener('drop', handleDrop, false);
        
        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            
            const input = document.getElementById('gambar_menu');
            input.files = files;
            
            previewImage(input);
        }
        
        // Validasi form sebelum submit
        document.getElementById('formTambah').addEventListener('submit', function(e) {
            const fileInput = document.getElementById('gambar_menu');
            
            if (fileInput.files.length === 0) {
                e.preventDefault();
                alert('⚠️ Silakan pilih gambar menu terlebih dahulu!');
                return false;
            }
            
            // Konfirmasi sebelum submit
            if (!confirm('✅ Apakah data sudah benar dan ingin disimpan?')) {
                e.preventDefault();
                return false;
            }
        });
    </script>
</body>
</html>