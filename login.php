<?php
// --- PROSES LOGIN SEDERHANA (Tanpa Database) ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $role = $_POST['role'];

    if ($role == "Admin") {
    $user = $_POST['adminUser'];
    $pass = $_POST['adminPass'];

    // Login Admin
    if ($user == "admin" && $pass == "12345") {
        echo "<script>alert('Login Admin berhasil!'); window.location='index.php';</script>";
    } else {
        echo "<script>alert('Username atau Password Admin salah!');</script>";
    }

    } elseif ($role == "Owner") {
        $email = $_POST['ownerEmail'];
        $pass = $_POST['ownerPass'];

    // Login Owner
    if ($email == "owner@mail.com" && $pass == "12345") {
        echo "<script>alert('Login Owner berhasil!'); window.location='index.php';</script>";
    } else {
        echo "<script>alert('Email atau Password Owner salah!');</script>";
    }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Dapur Pak Ndut</title>
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
        <button id="btnOwner" onclick="showForm('owner')">Owner</button>
    </div>

    <!-- Form Admin -->
    <form id="formAdmin" class="form-section active" method="POST">
        <input type="hidden" name="role" value="Admin">
        <div class="form-group">
            <label for="adminUser">Username Admin</label>
            <input type="text" name="adminUser" id="adminUser" required placeholder="Masukkan username admin">
        </div>
        <div class="form-group">
            <label for="adminPass">Password</label>
            <input type="password" name="adminPass" id="adminPass" required placeholder="Masukkan password admin">
        </div>
        <button type="submit" class="login-btn">Login Admin</button>
    </form>

    <!-- Form Owner -->
    <form id="formOwner" class="form-section" method="POST">
        <input type="hidden" name="role" value="Owner">
        <div class="form-group">
            <label for="ownerEmail">Email Owner</label>
            <input type="email" name="ownerEmail" id="ownerEmail" required placeholder="Masukkan email owner">
        </div>
        <div class="form-group">
            <label for="ownerPass">Password</label>
            <input type="password" name="ownerPass" id="ownerPass" required placeholder="Masukkan password owner">
        </div>
        <button type="submit" class="login-btn">Login Owner</button>
    </form>

    <a href="index.html" class="back-link">← Kembali ke Beranda</a>
    </div>

    <script>
    // Ganti tampilan form Admin/Owner
    function showForm(role) {
        document.getElementById('formAdmin').classList.remove('active');
        document.getElementById('formOwner').classList.remove('active');
        document.getElementById('btnAdmin').classList.remove('active');
        document.getElementById('btnOwner').classList.remove('active');

        if (role === 'admin') {
            document.getElementById('formAdmin').classList.add('active');
            document.getElementById('btnAdmin').classList.add('active');
        } else {
            document.getElementById('formOwner').classList.add('active');
            document.getElementById('btnOwner').classList.add('active');
        }
    }
    </script>

</body>
</html>