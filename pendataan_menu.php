<?php 
include 'koneksi.php';
$query = mysqli_query($conn, "SELECT * FROM menu ORDER BY id_menu ASC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Data Menu - Dapur Kuliner Pak Ndut</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

:root {
  --orange: #FF8C00;
  --light-orange: #FFA726;
  --text-dark: #333;
  --shadow: 0 8px 25px rgba(0,0,0,0.1);
}

body {
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  background: linear-gradient(135deg, #FFF7E6, #FFD9A3);
  min-height: 100vh;
  display: flex;
}

/* =======================
   SIDEBAR
======================= */
.sidebar {
  width: 250px;
  background: linear-gradient(180deg, var(--orange), #FF8C00);
  color: white;
  box-shadow: var(--shadow);
  position: fixed;
  height: 100%;
  padding-top: 30px;
}

.sidebar .logo {
  text-align: center;
  margin-bottom: 30px;
}

.sidebar .logo i {
  font-size: 3rem;
}

.sidebar .logo h4 {
  margin-top: 10px;
  font-weight: bold;
  font-size: 1.1rem;
  line-height: 1.4;
}

.sidebar a {
  display: block;
  color: #fff;
  padding: 12px 25px;
  margin: 5px 15px;
  border-radius: 10px;
  text-decoration: none;
  transition: 0.3s;
}

.sidebar a:hover, .sidebar a.active {
  background: rgba(255,255,255,0.2);
}

.sidebar a i {
  margin-right: 10px;
}

/* =======================
   MAIN CONTENT
======================= */
.main-content {
  margin-left: 250px;
  padding: 30px;
  width: calc(100% - 250px);
}

.container {
  background: #fff;
  border-radius: 20px;
  padding: 30px;
  box-shadow: var(--shadow);
}

/* =======================
   HEADER
======================= */
.header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  border-bottom: 3px solid #FF9800;
  padding-bottom: 15px;
  margin-bottom: 20px;
  flex-wrap: wrap;
  gap: 15px;
}

.header-left {
  display: flex;
  align-items: center;
  gap: 15px;
}

.header-icon {
  font-size: 2em;
  background: #FF9800;
  color: #fff;
  padding: 10px;
  border-radius: 10px;
}

.header-title h1 {
  color: #FF9800;
  margin: 0;
  font-size: 1.8rem;
}

.breadcrumb {
  color: #888;
  font-size: 0.9em;
  margin-top: 5px;
}

.breadcrumb a {
  color: #FF9800;
  text-decoration: none;
}

/* =======================
   BUTTONS
======================= */
.btn {
  border: none;
  cursor: pointer;
  font-weight: 600;
  border-radius: 10px;
  padding: 12px 20px;
  transition: 0.3s;
  display: inline-flex;
  align-items: center;
  gap: 8px;
  font-size: 15px;
  text-decoration: none;
}

.btn-add {
  background: linear-gradient(90deg, #ff9800, #f57c00);
  color: white;
  box-shadow: 0 4px 10px rgba(255, 152, 0, 0.3);
}

.btn-add:hover {
  background: linear-gradient(90deg, #ffa726, #fb8c00);
  transform: translateY(-3px);
  box-shadow: 0 6px 14px rgba(255, 152, 0, 0.4);
}

/* Tombol Edit & Hapus */
.btn-edit, .btn-hapus {
  text-decoration: none;
  padding: 8px 14px;
  border-radius: 8px;
  font-weight: 600;
  transition: all 0.3s;
  display: inline-block;
  font-size: 13px;
}

.btn-edit {
  background: #7C4DFF;
  color: white;
}

.btn-edit:hover {
  background: #6b3de8;
  box-shadow: 0 3px 10px rgba(124, 77, 255, 0.4);
  transform: scale(1.05);
}

.btn-hapus {
  background: #F44336;
  color: white;
}

.btn-hapus:hover {
  background: #d32f2f;
  box-shadow: 0 3px 10px rgba(244, 67, 54, 0.4);
  transform: scale(1.05);
}

/* =======================
   CONTROLS
======================= */
.controls {
  margin: 20px 0;
  display: flex;
  justify-content: space-between;
  flex-wrap: wrap;
  gap: 10px;
}

.controls input,
.controls select {
  padding: 10px;
  border: 2px solid #ddd;
  border-radius: 8px;
  font-size: 14px;
  transition: 0.3s;
}

.controls input {
  flex: 1;
  min-width: 250px;
}

.controls input:focus,
.controls select:focus {
  outline: none;
  border-color: var(--orange);
}

/* =======================
   TABLE
======================= */
.table-container {
  overflow-x: auto;
}

table {
  width: 100%;
  border-collapse: collapse;
  font-size: 15px;
  border-radius: 10px;
  overflow: hidden;
}

thead {
  background: #FF9800;
  color: white;
}

th, td {
  text-align: center;
  padding: 12px 10px;
  border-bottom: 1px solid #ddd;
}

tbody tr:nth-child(even) {
  background: #fff8f0;
}

tbody tr:hover {
  background: #ffe3c8;
}

.menu-image {
  width: 60px;
  height: 60px;
  border-radius: 10px;
  object-fit: cover;
  border: 1px solid #ddd;
}

/* =======================
   CATEGORY & PRICE
======================= */
.menu-category {
  background: #ffcc80;
  color: #e65100;
  padding: 5px 10px;
  border-radius: 8px;
  font-size: 13px;
  text-transform: capitalize;
  font-weight: 500;
}

.menu-price {
  color: #FF9800;
  font-weight: bold;
}

.menu-stock {
  color: #4CAF50;
  font-weight: bold;
}

.action-buttons {
  display: flex;
  justify-content: center;
  gap: 8px;
  flex-wrap: wrap;
}

/* =======================
   RESPONSIVE DESIGN
======================= */
@media screen and (max-width: 768px) {
  .sidebar {
    width: 70px;
  }
  
  .sidebar .logo h4,
  .sidebar a span {
    display: none;
  }
  
  .main-content {
    margin-left: 70px;
    width: calc(100% - 70px);
    padding: 15px;
  }

  .header {
    flex-direction: column;
    align-items: flex-start;
  }

  .header-title h1 {
    font-size: 1.5rem;
  }

  .controls {
    flex-direction: column;
  }

  .controls input {
    min-width: 100%;
  }

  .btn-add {
    width: 100%;
    justify-content: center;
  }

  table, th, td {
    font-size: 13px;
  }

  .menu-image {
    width: 50px;
    height: 50px;
  }

  .action-buttons {
    flex-direction: column;
    gap: 5px;
  }

  .btn-edit, .btn-hapus {
    font-size: 12px;
    padding: 6px 10px;
  }
}

@media screen and (max-width: 480px) {
  .container {
    padding: 15px;
  }

  .header-icon {
    font-size: 1.5em;
    padding: 8px;
  }

  table, th, td {
    font-size: 12px;
    padding: 8px 5px;
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
  <a href="admin.php"><i class="fas fa-user-shield"></i> <span>Data Admin</span></a>
  <a href="pendataan_menu.php" class="active"><i class="fas fa-book"></i> <span>Data Menu</span></a>
  <a href="transaksi.php"><i class="fas fa-shopping-cart"></i> <span>Transaksi</span></a>
  <a href="generate_laporan.php"><i class="fas fa-file-alt"></i> <span>Laporan</span></a>
  <a href="ulasan.php"><i class="fas fa-comment-dots"></i> <span>Ulasan</span></a>
  <a href="login.php"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a>
</div>

<!-- Main Content -->
<div class="main-content">
  <div class="container">
    <div class="header">
      <div class="header-left">
        <div class="header-icon">🍽️</div>
        <div class="header-title">
          <h1>Data Menu</h1>
          <div class="breadcrumb"><a href="admin.php">Dashboard</a> / Data Menu</div>
        </div>
      </div>
      <div class="header-buttons">
        <a href="tambah_menu.php" class="btn btn-add">
          <i class="fas fa-plus"></i> Tambah Menu
        </a>
      </div>
    </div>

    <div class="controls">
      <input type="text" id="searchInput" placeholder="🔍 Cari nama menu..." onkeyup="filterTable()">
      <select id="categoryFilter" onchange="filterTable()">
        <option value="">Semua Kategori</option>
        <option value="makanan">Makanan</option>
        <option value="minuman">Minuman</option>
      </select>
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
            <th>STOK</th>
            <th>AKSI</th>
          </tr>
        </thead>
        <tbody>
          <?php 
          $no = 1;
          while($row = mysqli_fetch_assoc($query)) { ?>
            <tr>
              <td><?= $no++; ?></td>
              <td>
                <?php if (!empty($row['gambar_menu'])): ?>
                  <img src="img/<?= htmlspecialchars($row['gambar_menu']); ?>" 
                        alt="<?= htmlspecialchars($row['nama_menu']); ?>" 
                        class="menu-image">
                <?php else: ?>
                  <img src="img/default.jpg" alt="No Image" class="menu-image">
                <?php endif; ?>
              </td>
              <td class="menu-name"><?= htmlspecialchars($row['nama_menu']); ?></td>
              <td><span class="menu-category"><?= ucfirst($row['jenis_menu']); ?></span></td>
              <td class="menu-price">Rp <?= number_format($row['harga_menu'], 0, ',', '.'); ?></td>
              <td class="menu-stock"><?= htmlspecialchars($row['stock_menu']); ?></td>
              <td class="action-buttons">
                <a href="edit_menu.php?id=<?= $row['id_menu']; ?>" class="btn-edit">
                  <i class="fas fa-edit"></i> Edit
                </a>
                <a href="hapus_menu.php?id=<?= $row['id_menu']; ?>" 
                    class="btn-hapus"
                    onclick="return confirm('⚠️ Yakin ingin menghapus <?= addslashes($row['nama_menu']); ?>?')">
                    <i class="fas fa-trash"></i> Hapus
                </a>
              </td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<script>
function filterTable() {
  const input = document.getElementById("searchInput").value.toLowerCase();
  const category = document.getElementById("categoryFilter").value;
  const rows = document.querySelectorAll("#menuTable tbody tr");

  rows.forEach(row => {
    const name = row.cells[2].textContent.toLowerCase();
    const cat = row.cells[3].textContent.toLowerCase();
    if (name.includes(input) && (category === "" || cat === category)) {
      row.style.display = "";
    } else {
      row.style.display = "none";
    }
  });
}
</script>

</body>
</html>