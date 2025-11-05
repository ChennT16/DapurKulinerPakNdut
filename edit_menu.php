<?php
include 'koneksi.php';

// Ambil ID menu dari URL
$id_menu = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id_menu == 0) {
    echo "<script>alert('❌ ID Menu tidak valid!'); window.location='pendataan_menu.php';</script>";
    exit();
}

// Proses form jika di-submit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_menu = mysqli_real_escape_string($conn, $_POST['nama_menu']);
    $harga_menu = mysqli_real_escape_string($conn, $_POST['harga_menu']);
    $jenis_menu = mysqli_real_escape_string($conn, $_POST['jenis_menu']);
    $stock_menu = mysqli_real_escape_string($conn, $_POST['stock_menu']);
    $id_produksi = isset($_POST['id_produksi']) ? mysqli_real_escape_string($conn, $_POST['id_produksi']) : '';
    
    // Ambil gambar lama
    $query_old = "SELECT gambar_menu FROM menu WHERE id_menu = $id_menu";
    $result_old = mysqli_query($conn, $query_old);
    $old_data = mysqli_fetch_assoc($result_old);
    $gambar_menu = $old_data['gambar_menu'];
    
    // Upload gambar baru jika ada
    if (isset($_FILES['gambar_menu']) && $_FILES['gambar_menu']['error'] == 0) {
        $target_dir = "images/menu/";
        
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        
        $file_extension = strtolower(pathinfo($_FILES["gambar_menu"]["name"], PATHINFO_EXTENSION));
        $new_filename = 'menu_' . time() . '_' . uniqid() . '.' . $file_extension;
        $target_file = $target_dir . $new_filename;
        
        $allowed_types = array('jpg', 'jpeg', 'png', 'gif');
        $max_size = 2 * 1024 * 1024; // 2MB
        
        if (in_array($file_extension, $allowed_types) && $_FILES["gambar_menu"]["size"] <= $max_size) {
            if (move_uploaded_file($_FILES["gambar_menu"]["tmp_name"], $target_file)) {
                // Hapus gambar lama jika ada
                if (!empty($old_data['gambar_menu']) && file_exists($target_dir . $old_data['gambar_menu'])) {
                    unlink($target_dir . $old_data['gambar_menu']);
                }
                $gambar_menu = $new_filename;
            } else {
                $error_msg = "Gagal mengupload gambar!";
            }
        } else {
            $error_msg = "File harus berformat JPG/JPEG/PNG dan maksimal 2MB!";
        }
    }
    
    if (!isset($error_msg)) {
        // Update database menggunakan prepared statement
        $stmt = $conn->prepare("UPDATE menu SET nama_menu=?, harga_menu=?, jenis_menu=?, stock_menu=?, gambar_menu=?, id_produksi=? WHERE id_menu=?");
        $stmt->bind_param("sdssssi", $nama_menu, $harga_menu, $jenis_menu, $stock_menu, $gambar_menu, $id_produksi, $id_menu);
        
        if ($stmt->execute()) {
            echo "<script>
                    alert('✅ Menu berhasil diupdate!');
                    window.location='pendataan_menu.php?pesan=update';
                  </script>";
            exit();
        } else {
            $error_msg = "Gagal update data: " . mysqli_error($conn);
        }
    }
}

// Ambil data menu untuk ditampilkan di form
$query = "SELECT * FROM menu WHERE id_menu = $id_menu";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
    echo "<script>alert('❌ Data menu tidak ditemukan!'); window.location='pendataan_menu.php';</script>";
    exit();
}

$menu = mysqli_fetch_assoc($result);

// Ambil daftar kategori untuk dropdown
$kategori_query = "SELECT DISTINCT jenis_menu FROM menu WHERE jenis_menu IS NOT NULL ORDER BY jenis_menu";
$kategori_result = mysqli_query($conn, $kategori_query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Menu - Dapur Pak Ndut</title>
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
        
        .current-image {
            margin-bottom: 20px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 15px;
            text-align: center;
        }
        
        .current-image img {
            max-width: 300px;
            max-height: 300px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.15);
        }
        
        .current-image-label {
            display: block;
            margin-bottom: 15px;
            font-weight: 600;
            color: #666;
        }
        
        .file-upload-container {
            border: 3px dashed #FF8C00;
            border-radius: 15px;
            padding: 60px 40px;
            text-align: center;
            transition: all 0.3s;
            background: white;
            cursor: pointer;
        }
        
        .file-upload-container:hover {
            border-color: #FFA500;
            background: #fff8f0;
        }
        
        #gambar_menu {
            display: none;
        }
        
        .upload-icon {
            font-size: 80px;
            margin-bottom: 20px;
            color: #7a7a7a;
        }
        
        .upload-text {
            font-size: 16px;
            color: #666;
            margin-bottom: 8px;
            font-weight: 500;
        }
        
        .upload-hint {
            font-size: 14px;
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
            <h1><span>✏️</span> Edit Menu</h1>
            <div class="breadcrumb">Dashboard / Data Menu / Edit Menu</div>
        </div>
        
        <div class="form-container">
            <?php if (isset($error_msg)): ?>
                <div class="alert alert-danger">
                    <span>⚠️</span>
                    <span><?php echo $error_msg; ?></span>
                </div>
            <?php endif; ?>
            
            <form method="POST" enctype="multipart/form-data" id="formEdit">
                <div class="form-row">
                    <div class="form-group">
                        <label>Nama Menu <span class="required">*</span></label>
                        <input type="text" name="nama_menu" required value="<?php echo htmlspecialchars($menu['nama_menu']); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label>Harga <span class="required">*</span></label>
                        <input type="number" name="harga_menu" required value="<?php echo $menu['harga_menu']; ?>" min="0">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Kategori <span class="required">*</span></label>
                        <select name="jenis_menu" required>
                            <option value="">-- Pilih Kategori --</option>
                            <?php 
                            $added_categories = array();
                            mysqli_data_seek($kategori_result, 0);
                            while ($kat = mysqli_fetch_assoc($kategori_result)) {
                                if (!in_array($kat['jenis_menu'], $added_categories)) {
                                    $selected = ($kat['jenis_menu'] == $menu['jenis_menu']) ? 'selected' : '';
                                    echo '<option value="' . htmlspecialchars($kat['jenis_menu']) . '" ' . $selected . '>';
                                    echo htmlspecialchars($kat['jenis_menu']);
                                    echo '</option>';
                                    $added_categories[] = $kat['jenis_menu'];
                                }
                            }
                            ?>
                            <option value="Makanan" <?php echo ($menu['jenis_menu'] == 'Makanan') ? 'selected' : ''; ?>>Makanan</option>
                            <option value="Minuman" <?php echo ($menu['jenis_menu'] == 'Minuman') ? 'selected' : ''; ?>>Minuman</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Stok <span class="required">*</span></label>
                        <input type="number" name="stock_menu" required value="<?php echo $menu['stock_menu']; ?>" min="0">
                    </div>
                </div>
                
                <div class="form-group">
                    <label>ID Produksi (Opsional)</label>
                    <input type="text" name="id_produksi" value="<?php echo htmlspecialchars($menu['id_produksi']); ?>">
                </div>
                
                <div class="form-group">
                    <label>Gambar Menu</label>
                    
                    <?php if (!empty($menu['gambar_menu'])): ?>
                    <div class="current-image">
                        <span class="current-image-label">📸 Gambar Saat Ini:</span>
                        <img src="images/menu/<?php echo htmlspecialchars($menu['gambar_menu']); ?>" alt="Current Image">
                    </div>
                    <?php endif; ?>
                    
                    <div class="file-upload-container" onclick="document.getElementById('gambar_menu').click()">
                        <div class="upload-icon">📷</div>
                        <div class="upload-text">Klik untuk memilih gambar</div>
                        <div class="upload-hint">atau drag & drop file di sini</div>
                        <input type="file" name="gambar_menu" id="gambar_menu" accept="image/*" onchange="previewImage(this)">
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
        function previewImage(input) {
            const previewContainer = document.getElementById('preview-container');
            previewContainer.innerHTML = '';
            
            if (input.files && input.files[0]) {
                const file = input.files[0];
                
                const maxSize = 2 * 1024 * 1024;
                if (file.size > maxSize) {
                    alert('⚠️ Ukuran file terlalu besar! Maksimal 2MB.');
                    input.value = '';
                    return;
                }
                
                const filename = document.createElement('div');
                filename.className = 'filename-display';
                filename.innerHTML = '📄 ' + file.name + ' (' + (file.size / 1024).toFixed(2) + ' KB)';
                previewContainer.appendChild(filename);
                
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
        
        document.getElementById('formEdit').addEventListener('submit', function(e) {
            if (!confirm('✅ Apakah Anda yakin ingin mengupdate menu ini?')) {
                e.preventDefault();
                return false;
            }
        });
    </script>
</body>
</html>