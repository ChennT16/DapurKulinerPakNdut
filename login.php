<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Dapur Pak Ndut</title>
  <link rel="stylesheet" href="stile.css">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(135deg, #f9b208, #f98404);
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
      margin: 0;
    }

    .login-container {
      background: #fff;
      padding: 40px 50px;
      border-radius: 20px;
      box-shadow: 0 8px 16px rgba(0,0,0,0.2);
      width: 100%;
      max-width: 420px;
      text-align: center;
    }

    .login-container h2 {
      color: #333;
      margin-bottom: 25px;
    }

    .tab-buttons {
      display: flex;
      justify-content: space-between;
      margin-bottom: 25px;
    }

    .tab-buttons button {
      flex: 1;
      padding: 10px;
      border: none;
      cursor: pointer;
      background: #eee;
      color: #555;
      font-weight: bold;
      transition: 0.3s;
      border-radius: 10px;
      margin: 0 5px;
    }

    .tab-buttons button.active {
      background: #f9b208;
      color: white;
    }

    .form-group {
      text-align: left;
      margin-bottom: 15px;
    }

    .form-group label {
      font-size: 14px;
      color: #444;
      display: block;
      margin-bottom: 5px;
    }

    .form-group input {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 8px;
      font-size: 15px;
    }

    .login-btn {
      width: 100%;
      background-color: #f9b208;
      border: none;
      color: white;
      padding: 12px;
      border-radius: 10px;
      font-size: 16px;
      cursor: pointer;
      margin-top: 10px;
      transition: 0.3s;
    }

    .login-btn:hover {
      background-color: #f98404;
    }

    .back-link {
      margin-top: 20px;
      display: block;
      font-size: 14px;
      color: #444;
      text-decoration: none;
    }

    .back-link:hover {
      text-decoration: underline;
    }

    .form-section {
      display: none;
    }

    .form-section.active {
      display: block;
    }
  </style>
</head>
<body>

  <div class="login-container">
    <h2>Login Dapur Pak Ndut</h2>

    <div class="tab-buttons">
      <button id="btnAdmin" class="active" onclick="showForm('admin')">Admin</button>
      <button id="btnCustomer" onclick="showForm('customer')">Customer</button>
    </div>

    <!-- Form Admin -->
    <form id="formAdmin" class="form-section active" onsubmit="return login('Admin')">
      <div class="form-group">
        <label for="adminUser">Username Admin</label>
        <input type="text" id="adminUser" required placeholder="Masukkan username admin">
      </div>
      <div class="form-group">
        <label for="adminPass">Password</label>
        <input type="password" id="adminPass" required placeholder="Masukkan password admin">
      </div>
      <button type="submit" class="login-btn">Login Admin</button>
    </form>

    <!-- Form Customer -->
    <form id="formCustomer" class="form-section" onsubmit="return login('Customer')">
      <div class="form-group">
        <label for="custEmail">Email</label>
        <input type="email" id="custEmail" required placeholder="Masukkan email Anda">
      </div>
      <div class="form-group">
        <label for="custPass">Password</label>
        <input type="password" id="custPass" required placeholder="Masukkan password">
      </div>
      <button type="submit" class="login-btn">Login Customer</button>
    </form>

    <a href="index.html" class="back-link">← Kembali ke Beranda</a>
  </div>

  <script>
    // Ganti tampilan form Admin/Customer
    function showForm(role) {
      document.getElementById('formAdmin').classList.remove('active');
      document.getElementById('formCustomer').classList.remove('active');
      document.getElementById('btnAdmin').classList.remove('active');
      document.getElementById('btnCustomer').classList.remove('active');

      if (role === 'admin') {
        document.getElementById('formAdmin').classList.add('active');
        document.getElementById('btnAdmin').classList.add('active');
      } else {
        document.getElementById('formCustomer').classList.add('active');
        document.getElementById('btnCustomer').classList.add('active');
      }
    }

    // Fungsi login (dummy)
    function login(role) {
      alert(role + " berhasil login!");
      window.location.href = "index.html";
      return false; // mencegah reload halaman
    }
  </script>

</body>
</html>
