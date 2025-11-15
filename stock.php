<?php

// ========== KONEKSI DATABASE ==========
// Ganti dengan file koneksi Anda
include 'koneksi.php'; 

// Update stok dari form
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $id = $_POST['id_menu'];
  $action = $_POST['action'];
  
  if ($action === 'plus') {
    $sql = "UPDATE menu SET stock_menu = stock_menu + 1 WHERE stock_menu = ?";
  } elseif ($action === 'minus') {
    $sql = "UPDATE menu SET stock_menu = stock_menu - 1 WHERE stock_menu = ? AND stock_menu > 0";
  }
  
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("stock_menu", $id);
  $stmt->execute();
  $stmt->close();
  
  header("Location: ".$_SERVER['PHP_SELF']);
  exit;
}

// Ambil data menu dari database
$sql = "SELECT id_menu,nama_menu,harga_menu,jenis_menu,stock_menu,id_produksi FROM menu ORDER BY stock_menu ASC";
$result = $conn->query($sql);

$menuList = [];
if ($result->num_rows > 0) {
  while($row = $result->fetch_assoc()) {
    $menuList[] = $row;
  }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Stok Menu | Dapur Pak Ndut</title>

  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Poppins', sans-serif;
    }

    body {
      background: #fffaf2;
      color: #333;
    }

    header {
      background-color: #ff8c00;
      color: white;
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 15px 40px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }

    header img {
      width: 50px;
      height: 50px;
      border-radius: 50%;
      margin-right: 10px;
    }

    .logo {
      display: flex;
      align-items: center;
      font-size: 22px;
      font-weight: bold;
    }

    main {
      padding: 40px;
      text-align: center;
    }

    h1 {
      color: #ff8c00;
      font-size: 2.2rem;
      margin-bottom: 25px;
    }

    .menu-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 25px;
      justify-items: center;
    }

    .card {
      background: white;
      border-radius: 15px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
      padding: 15px;
      width: 230px;
      transition: 0.3s ease;
    }

    .card:hover {
      transform: scale(1.05);
      box-shadow: 0 6px 12px rgba(0,0,0,0.15);
    }

    .card img {
      width: 100%;
      height: 140px;
      object-fit: cover;
      border-radius: 10px;
      margin-bottom: 10px;
    }

    .card h3 {
      color: #333;
      margin-bottom: 8px;
      font-size: 1.1rem;
    }

    .stok {
      font-size: 16px;
      margin-bottom: 10px;
      color: #444;
    }

    form {
      display: flex;
      justify-content: center;
      gap: 10px;
    }

    button {
      background-color: #ff8c00;
      border: none;
      color: white;
      font-size: 18px;
      width: 35px;
      height: 35px;
      border-radius: 8px;
      cursor: pointer;
      transition: 0.3s;
    }

    button:hover {
      background-color: #e67800;
    }

    .footer-btn {
      text-align: center;
      margin: 60px 0 40px 0;
    }

    .back-btn {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      background: linear-gradient(135deg, #ff6b35, #ff884d);
      color: white;
      padding: 14px 28px;
      border-radius: 30px;
      font-weight: 600;
      font-size: 16px;
      text-decoration: none;
      box-shadow: 0 4px 10px rgba(255, 107, 53, 0.4);
      transition: all 0.3s ease;
    }

    .back-btn:hover {
      background: linear-gradient(135deg, #ff884d, #ff6b35);
      transform: translateY(-3px);
      box-shadow: 0 6px 15px rgba(255, 107, 53, 0.5);
    }

    .arrow {
      font-size: 18px;
      transition: transform 0.3s ease;
    }

    .back-btn:hover .arrow {
      transform: translateX(-5px);
    }

    footer {
      text-align: center;
      margin-top: 20px;
      padding: 20px;
      color: #777;
      font-size: 14px;
    }

    @media (max-width: 600px) {
      header {
        flex-direction: column;
        text-align: center;
      }
      .logo {
        margin-bottom: 10px;
      }
    }
  </style>
</head>

<body>
  <header>
    <div class="logo">
      <img src="logo_kuliner_pak_dut.png" alt="Logo Dapur Pak Ndut">
      Dapur Kuliner Pak Ndut
    </div>
  </header>

  <main>
    <h1>Stok Menu</h1>
    <div class="menu-grid">
      <?php foreach ($menuList as $m): ?>
        <div class="card">
          <img src="<?= htmlspecialchars($m['gambar']) ?>" alt="<?= htmlspecialchars($m['nama']) ?>">
          <h3><?= htmlspecialchars($m['nama_menu']) ?></h3>
          <div class="stok">Stock: <?= $m['stock_menu'] ?></div>
          <form method="POST">
            <input type="hidden" name="id_menu" value="<?= $m['id_menu'] ?>">
            <button type="submit" name="action" value="plus">+</button>
            <button type="submit" name="action" value="minus">−</button>
          </form>
        </div>
      <?php endforeach; ?>
    </div>

    <div class="footer-btn">
      <a href="index.html" class="back-btn">
        <span class="arrow">⬅</span> Kembali ke Beranda
      </a>
    </div>
  </main>

  <footer>
    © 2025 Dapur Pak Ndut | Semua hak dilindungi
  </footer>

  <script>
    // Simpan posisi scroll sebelum halaman reload
    document.querySelectorAll('form').forEach(form => {
      form.addEventListener('submit', function() {
        sessionStorage.setItem('scrollPos', window.scrollY);
      });
    });

    // Kembalikan posisi scroll setelah halaman selesai dimuat
    window.addEventListener('load', function() {
      const scrollPos = sessionStorage.getItem('scrollPos');
      if (scrollPos) {
        window.scrollTo(0, parseInt(scrollPos));
        sessionStorage.removeItem('scrollPos');
      }
    });
  </script>

</body>
</html>