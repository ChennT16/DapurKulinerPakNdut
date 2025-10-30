<?php
session_start();
unset($_SESSION['menu']); // <-- tambahkan ini sementara untuk reset data
// Data menu
if (!isset($_SESSION['menu'])) {
  $_SESSION['menu'] = [
    ["id"=>1, "nama"=>"Pentol Tahu", "stok"=>10, "gambar"=>"PENTOL TAHU.jpg"],
    ["id"=>2, "nama"=>"Pentol Bakar", "stok"=>12, "gambar"=>"PENTOL BAKAR.jpg"],
    ["id"=>3, "nama"=>"Pentol", "stok"=>8, "gambar"=>"PENTOL.jpg"],
    ["id"=>4, "nama"=>"Rica - rica Balungan", "stok"=>6, "gambar"=>"RICA RICA BALUNGAN.jpg"],
    ["id"=>5, "nama"=>"Nasi Bento Ati Ampela", "stok"=>9, "gambar"=>"NASI BENTO AYAM CRISPY.jpg"],
    ["id"=>6, "nama"=>"Nasi Bento Ayam Crispy", "stok"=>11, "gambar"=>"NASI BENTO AYAM CRISPY.jpg"],
    ["id"=>7, "nama"=>"Nasi Bento Dagingf Dan Sosis", "stok"=>10, "gambar"=>"NASI BENTO DAGING DAN SOSIS.jpg"],
    ["id"=>8, "nama"=>"Nasi Bento Geprek", "stok"=>13, "gambar"=>"NASI BENTO GEPREK.jpg"],
    ["id"=>9, "nama"=>"Nasi Bento Katsu", "stok"=>7, "gambar"=>"NASI BENTO KATSU.jpg"],
    ["id"=>10,"nama"=>"Nasi Bento Rica - Rica balungan", "stok"=>5, "gambar"=>"NASI BENTO RICA RICA BALUNGAN.jpg"],
    ["id"=>11,"nama"=>"Tahu Bakar", "stok"=>8, "gambar"=>"TAHU BAKAR.jpg"],
    ["id"=>12,"nama"=>"Gorengan Pangsit", "stok"=>6, "gambar"=>"GORENGAN PANGSIT.jpg"],
    ["id"=>13,"nama"=>"Es Kuwut", "stok"=>9, "gambar"=>"ES KUWUT.jpg"],
    ["id"=>14,"nama"=>"Es Rasa - Rasa", "stok"=>10, "gambar"=>"ES RASA RASA.jpg"],
    ["id"=>15,"nama"=>"Tahu Isi", "stok"=>7, "gambar"=>"AIR MINERAL.jpg"],
    ["id"=>16,"nama"=>"Lemon Tea", "stok"=>12, "gambar"=>"LEMON TEA.jpg"],
    ["id"=>17,"nama"=>"SUSU KEDELAI.jpg", "stok"=>8, "gambar"=>"SUSU KEDELAI.jpg"],
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
      Dapur Kuliner Pak Ndut
    
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
    © 2025 Dapur Pak Ndut | Semua hak dilindungi
  </footer>
</body>
</html>
