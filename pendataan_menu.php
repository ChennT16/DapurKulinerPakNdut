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
<style>
/* =======================
   GLOBAL STYLE
======================= */
body {
  font-family: 'Poppins', sans-serif;
  background: linear-gradient(135deg, #FF9800, #F57C00);
  margin: 0;
  padding: 20px;
  color: #333;
}

.container {
  max-width: 1200px;
  margin: auto;
  background: #fff;
  border-radius: 20px;
  padding: 30px;
  box-shadow: 0 10px 25px rgba(0,0,0,0.2);
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
}

.breadcrumb {
  color: #888;
  font-size: 0.9em;
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
  border-radius: 8px;
  padding: 10px 18px;
  transition: 0.3s;
}

.btn-add {
  background: #FF9800;
  color: white;
}

.btn-add:hover {
  background: #F57C00;
}

.btn-edit {
  background: #7C4DFF;
  color: white;
  text-decoration: none;
  padding: 6px 14px;
  border-radius: 6px;
  margin-right: 5px;
  display: inline-block;
}

.btn-edit:hover {
  background: #6b3de8;
}

.btn-delete {
  background: #F44336;
  color: white;
  text-decoration: none;
  padding: 6px 14px;
  border-radius: 6px;
  display: inline-block;
}

.btn-delete:hover {
  background: #d32f2f;
}

/* =======================
   CONTROLS (Search & Filter)
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
  border: 1px solid #ddd;
  border-radius: 8px;
  font-size: 14px;
}

.controls input {
  flex: 1;
  min-width: 250px;
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
   MENU CATEGORY TAG
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
  gap: 10px;
}

/* =======================
   RESPONSIVE DESIGN
======================= */
@media screen and (max-width: 768px) {
  .controls {
    flex-direction: column;
  }

  .btn-add {
    width: 100%;
  }

  table, th, td {
    font-size: 14px;
  }

  .menu-image {
    width: 50px;
    height: 50px;
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
        <div class="breadcrumb"><a href="#">Dashboard</a> / Data Menu</div>
      </div>
    </div>
    <div class="header-buttons">
      <button class="btn btn-add" onclick="window.location.href='tambah_menu.php'">+ Tambah Menu</button>
    </div>
  </div>

  <div class="controls">
    <input type="text" id="searchInput" placeholder="Cari nama menu..." onkeyup="filterTable()">
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
                <img src="img/<?= htmlspecialchars($row['gambar_menu']); ?>" alt="<?= htmlspecialchars($row['nama_menu']); ?>" class="menu-image">
              <?php else: ?>
                <img src="img/default.jpg" alt="No Image" class="menu-image">
              <?php endif; ?>
            </td>
            <td class="menu-name"><?= htmlspecialchars($row['nama_menu']); ?></td>
            <td><span class="menu-category"><?= ucfirst($row['jenis_menu']); ?></span></td>
            <td class="menu-price">Rp <?= number_format($row['harga_menu'], 0, ',', '.'); ?></td>
            <td class="menu-stock"><?= htmlspecialchars($row['stock_menu']); ?></td>
            <td>
                <!-- Tombol Edit -->
                    <a href="edit_menu.php?id=<?php echo $row['id_menu']; ?>" class="btn-edit">
                        🔧 Edit
                    </a>

                <!-- Tombol Hapus dengan konfirmasi JavaScript -->
                    <a href="hapus_menu.php?id=<?php echo $row['id_menu']; ?>" 
                    class="btn-hapus"
                    onclick="return confirm('⚠️ Apakah Anda yakin ingin menghapus menu <?php echo addslashes($row['nama_menu']); ?>?')">
                        🗑️ Hapus
                    </a>
            </td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
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
