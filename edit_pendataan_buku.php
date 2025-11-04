<?php
include 'koneksi.php';

// Ambil id menu dari URL
$id_menu = $_GET['id_menu'];

// Ambil data menu berdasarkan id
$query = mysqli_query($koneksi, "SELECT * FROM menu WHERE id_menu='$id_menu'");
$data = mysqli_fetch_assoc($query);

// Jika tombol update ditekan
if (isset($_POST['update'])) {
    $nama_menu = $_POST['nama_menu'];
    $kategori = $_POST['kategori'];
    $harga = $_POST['harga'];

    // Jika ada gambar baru
    if ($_FILES['gambar']['name'] != '') {
        $gambar = $_FILES['gambar']['name'];
        $tmp = $_FILES['gambar']['tmp_name'];
        move_uploaded_file($tmp, "uploads/" . $gambar);
        $queryUpdate = mysqli_query($koneksi, "UPDATE menu SET nama_menu='$nama_menu', kategori='$kategori', harga='$harga', gambar='$gambar' WHERE id_menu='$id_menu'");
    } else {
        $queryUpdate = mysqli_query($koneksi, "UPDATE menu SET nama_menu='$nama_menu', kategori='$kategori', harga='$harga' WHERE id_menu='$id_menu'");
    }

    if ($queryUpdate) {
        echo "<script>alert('Data berhasil diperbarui!');window.location='pendataan_menu.php';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui data!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Menu - Dapur Kuliner Pak Ndut</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #FF9800 0%, #F57C00 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .edit-container {
            background: #fff;
            border-radius: 16px;
            padding: 40px;
            width: 480px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        }

        h2 {
            color: #FF9800;
            margin-bottom: 20px;
            text-align: center;
        }

        .form-group {
            margin-bottom: 18px;
        }

        label {
            font-weight: 600;
            color: #333;
            display: block;
            margin-bottom: 6px;
        }

        input, select {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 1em;
            transition: all 0.3s;
        }

        input:focus, select:focus {
            border-color: #FF9800;
            outline: none;
        }

        img {
            display: block;
            width: 100px;
            border-radius: 8px;
            margin-top: 10px;
        }

        .buttons {
            display: flex;
            gap: 10px;
            margin-top: 25px;
        }

        button, a {
            flex: 1;
            text-align: center;
            padding: 12px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            cursor: pointer;
            font-size: 1em;
            transition: all 0.3s;
        }

        .btn-save {
            background: #FF9800;
            color: white;
            border: none;
        }

        .btn-save:hover {
            background: #F57C00;
        }

        .btn-cancel {
            background: #e0e0e0;
            color: #555;
        }

        .btn-cancel:hover {
            background: #d0d0d0;
        }
    </style>
</head>
<body>
    <div class="edit-container">
        <h2>Edit Data Menu</h2>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Nama Menu</label>
                <input type="text" name="nama_menu" value="<?= $data['nama_menu']; ?>" required>
            </div>

            <div class="form-group">
                <label>Kategori</label>
                <select name="kategori" required>
                    <option value="">-- Pilih Kategori --</option>
                    <option value="Pentol" <?= ($data['kategori']=='Pentol')?'selected':''; ?>>Pentol & Gorengan</option>
                    <option value="Nasi Bento" <?= ($data['kategori']=='Nasi Bento')?'selected':''; ?>>Nasi Bento</option>
                    <option value="Minuman" <?= ($data['kategori']=='Minuman')?'selected':''; ?>>Minuman</option>
                </select>
            </div>

            <div class="form-group">
                <label>Harga (Rp)</label>
                <input type="number" name="harga" value="<?= $data['harga']; ?>" required>
            </div>

            <div class="form-group">
                <label>Gambar</label>
                <img src="uploads/<?= $data['gambar']; ?>" alt="Gambar menu">
                <input type="file" name="gambar" accept=".jpg,.jpeg,.png">
            </div>

            <div class="buttons">
                <button type="submit" name="update" class="btn-save">💾 Simpan</button>
                <a href="pendataan_menu.php" class="btn-cancel">← Batal</a>
            </div>
        </form>
    </div>
</body>
</html>
