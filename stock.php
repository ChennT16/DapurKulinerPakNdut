<?ph
session_start();

// Data menu
if (!isset($_SESSION['menu'])) {
  $_SESSION['menu'] = [
    ["id"=>1, "nama"=>"Pentol Tahu", "stok"=>10, "gambar"=>"PENTOL TAHU.jpg"],
    ["id"=>2, "nama"=>"Pentol ", "stok"=>12, "gambar"=>"PENTOL.jpg"],
    ["id"=>3, "nama"=>"Tahu Goreng", "stok"=>8, "gambar"=>"images/tahu_goreng.jpg"],
    ["id"=>4, "nama"=>"Siomay", "stok"=>6, "gambar"=>"images/siomay.jpg"],
    ["id"=>5, "nama"=>"Lontong", "stok"=>9, "gambar"=>"images/lontong.jpg"],
    ["id"=>6, "nama"=>"Sate Usus", "stok"=>11, "gambar"=>"images/sate_usus.jpg"],
    ["id"=>7, "nama"=>"Sate Telur", "stok"=>10, "gambar"=>"images/sate_telur.jpg"],
    ["id"=>8, "nama"=>"Sate Kulit", "stok"=>13, "gambar"=>"images/sate_kulit.jpg"],
    ["id"=>9, "nama"=>"Tahu Bakso", "stok"=>7, "gambar"=>"images/tahu_bakso.jpg"],
    ["id"=>10,"nama"=>"Ceker", "stok"=>5, "gambar"=>"images/ceker.jpg"],
    ["id"=>11,"nama"=>"Bakso Goreng", "stok"=>8, "gambar"=>"images/bakso_goreng.jpg"],
    ["id"=>12,"nama"=>"Sosis", "stok"=>6, "gambar"=>"images/sosis.jpg"],
    ["id"=>13,"nama"=>"Nugget", "stok"=>9, "gambar"=>"images/nugget.jpg"],
    ["id"=>14,"nama"=>"Tempe Goreng", "stok"=>10, "gambar"=>"images/tempe_goreng.jpg"],
    ["id"=>15,"nama"=>"Tahu Isi", "stok"=>7, "gambar"=>"images/tahu_isi.jpg"],
    ["id"=>16,"nama"=>"Bakwan", "stok"=>12, "gambar"=>"images/bakwan.jpg"],
    ["id"=>17,"nama"=>"Kentang", "stok"=>8, "gambar"=>"images/kentang.jpg"],
  ];
}

// Update stok
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $id = $_POST['id'];
  $action = $_POST['action'];
  foreach ($_SESSION['menu'] as &$m) {
    if ($m['id'] == $id) {
      if ($action === 'plus') $m['stok']++;
      if ($action === 'minus' && $m['stok'] > 0) $m['stok']--;
    }
  }
  header("Location: ".$_SERVER['PHP_SELF']);
  exit;
}

$menuList = $_SESSION['menu'];
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
      width: 45px;
      height: 45px;
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

    footer {
      text-align: center;
      margin-top: 50px;
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
      Dapur Pak Ndut
    </div>
    <nav>
      <a href="#" style="color:white; margin-right:15px; text-decoration:none;">Beranda</a>
      <a href="#" style="color:white; margin-right:15px; text-decoration:none;">Menu</a>
      <a href="#" style="color:white; text-decoration:none;">Kontak</a>
    </nav>
  </header>

  <main>
    <h1>Stok Menu</h1>
    <div class="menu-grid">
      <?php foreach ($menuList as $m): ?>
        <div class="card">
          <img src="<?= $m['gambar'] ?>" alt="<?= $m['nama'] ?>">
          <h3><?= $m['nama'] ?></h3>
          <div class="stok">Stok: <?= $m['stok'] ?></div>
          <form method="POST">
            <input type="hidden" name="id" value="<?= $m['id'] ?>">
            <button type="submit" name="action" value="plus">+</button>
            <button type="submit" name="action" value="minus">−</button>
          </form>
        </div>
      <?php endforeach; ?>
    </div>
  </main>

  <footer>
    © 2025 Dapur Pak Ndut | 
  </footer>
</body>
</html>
