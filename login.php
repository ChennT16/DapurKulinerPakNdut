<?php
session_start();
include 'koneksi.php';

// Proses Login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    
    // Query untuk cek admin di database
    $sql = "SELECT * FROM admin WHERE username = '$username' LIMIT 1";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        
        // Verifikasi password (asumsi password di database dalam bentuk plain text atau hash)
        // Jika menggunakan hash, gunakan: password_verify($password, $row['password'])
        if ($password == $row['password']) {
            // Login berhasil - simpan data ke session
            $_SESSION['admin_id'] = $row['id'];
            $_SESSION['admin_username'] = $row['username'];
            $_SESSION['admin_nama'] = $row['nama'];
            $_SESSION['admin_email'] = $row['email'];
            $_SESSION['logged_in'] = true;
            
            echo "<script>
                    alert('Login berhasil! Selamat datang " . $row['nama'] . "');
                    window.location='admin.php';
                    </script>";
            exit();
        } else {
            $error = "Password salah!";
        }
    } else {
        $error = "Username tidak ditemukan!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Dapur Pak Ndut</title>
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f9b208 0%, #f98404 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 20px;
        }

        .login-container {
            background: #ffffff;
            padding: 50px 60px;
            border-radius: 25px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 450px;
            text-align: center;
            animation: slideUp 0.5s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .logo {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #f9b208, #f98404);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
            font-size: 40px;
            box-shadow: 0 5px 15px rgba(249, 178, 8, 0.4);
        }

        .login-container h2 {
            color: #333;
            margin-bottom: 10px;
            font-size: 28px;
            font-weight: 700;
        }

        .subtitle {
            color: #777;
            margin-bottom: 35px;
            font-size: 14px;
        }

        .alert {
            background: #fee;
            color: #c33;
            padding: 12px 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 14px;
            border: 1px solid #fcc;
            display: flex;
            align-items: center;
            gap: 10px;
            animation: shake 0.3s;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-10px); }
            75% { transform: translateX(10px); }
        }

        .form-group {
            text-align: left;
            margin-bottom: 25px;
        }

        .form-group label {
            font-size: 14px;
            color: #444;
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
        }

        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 18px;
            color: #999;
        }

        .form-group input {
            width: 100%;
            padding: 14px 15px 14px 45px;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            font-size: 15px;
            font-family: 'Poppins', sans-serif;
            transition: all 0.3s ease;
        }

        .form-group input:focus {
            outline: none;
            border-color: #f9b208;
            box-shadow: 0 0 0 4px rgba(249, 178, 8, 0.1);
        }

        .form-group input::placeholder {
            color: #bbb;
        }

        .login-btn {
            width: 100%;
            background: linear-gradient(135deg, #f9b208, #f98404);
            border: none;
            color: white;
            padding: 15px;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 10px;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(249, 178, 8, 0.3);
        }

        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(249, 178, 8, 0.4);
        }

        .login-btn:active {
            transform: translateY(0);
        }

        .back-link {
            margin-top: 25px;
            display: inline-block;
            font-size: 14px;
            color: #666;
            text-decoration: none;
            transition: all 0.3s;
            font-weight: 500;
        }

        .back-link:hover {
            color: #f9b208;
            transform: translateX(-5px);
        }

        .divider {
            margin: 30px 0;
            text-align: center;
            position: relative;
        }

        .divider::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            width: 100%;
            height: 1px;
            background: #e0e0e0;
        }

        .divider span {
            background: white;
            padding: 0 15px;
            position: relative;
            color: #999;
            font-size: 13px;
        }

        /* Responsive */
        @media (max-width: 500px) {
            .login-container {
                padding: 35px 30px;
            }
            
            .login-container h2 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="logo">🍳</div>
        <h2>Login Admin</h2>
        <p class="subtitle">Dapur Kuliner Pak Ndut</p>

        <?php if (isset($error)): ?>
            <div class="alert">
                <span>⚠️</span>
                <span><?php echo $error; ?></span>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="username">Username Admin</label>
                <div class="input-wrapper">
                    <span class="input-icon">👤</span>
                    <input type="text" name="username" id="username" 
                            required placeholder="Masukkan username"
                            value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
                </div>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <div class="input-wrapper">
                    <span class="input-icon">🔒</span>
                    <input type="password" name="password" id="password" 
                            required placeholder="Masukkan password">
                </div>
            </div>

            <button type="submit" class="login-btn">🚀 Login Sekarang</button>
        </form>

        <div class="divider">
            <span>atau</span>
        </div>

        <a href="index.php" class="back-link">← Kembali ke Beranda</a>
    </div>

    <script>
        // Auto focus ke input username saat halaman dimuat
        document.getElementById('username').focus();

        // Animasi untuk input saat ada error
        <?php if (isset($error)): ?>
        const inputs = document.querySelectorAll('input');
        inputs.forEach(input => {
            input.style.borderColor = '#ff4444';
            setTimeout(() => {
                input.style.borderColor = '#e0e0e0';
            }, 1000);
        });
        <?php endif; ?>
    </script>
</body>
</html>