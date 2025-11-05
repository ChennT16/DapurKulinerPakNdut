<?php
// Koneksi Database
$host = 'localhost';
$dbname = 'umkm';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Koneksi gagal: " . $e->getMessage());
}

// Handle Tambah Admin
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'tambah') {
    $nama = $_POST['nama'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $telepon = $_POST['telepon'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
    $sql = "INSERT INTO admin (nama, username, email, telepon, password) VALUES (:nama, :username, :email, :telepon, :password)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':nama' => $nama,
        ':username' => $username,
        ':email' => $email,
        ':telepon' => $telepon,
        ':password' => $password
    ]);
    
    header("Location: data_admin.php?success=tambah");
    exit;
}

// Handle Edit Admin
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'edit') {
    $id = $_POST['id'];
    $nama = $_POST['nama'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $telepon = $_POST['telepon'];
    
    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $sql = "UPDATE admin SET nama=:nama, username=:username, email=:email, telepon=:telepon, password=:password WHERE id=:id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':id' => $id,
            ':nama' => $nama,
            ':username' => $username,
            ':email' => $email,
            ':telepon' => $telepon,
            ':password' => $password
        ]);
    } else {
        $sql = "UPDATE admin SET nama=:nama, username=:username, email=:email, telepon=:telepon WHERE id=:id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':id' => $id,
            ':nama' => $nama,
            ':username' => $username,
            ':email' => $email,
            ':telepon' => $telepon
        ]);
    }
    
    header("Location: data_admin.php?success=edit");
    exit;
}

// Handle Hapus Admin
if (isset($_GET['action']) && $_GET['action'] == 'hapus' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "DELETE FROM admin WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]);
    
    header("Location: data_admin.php?success=hapus");
    exit;
}

// Ambil data admin dengan filter
$search = isset($_GET['search']) ? $_GET['search'] : '';

$sql = "SELECT * FROM admin WHERE 1=1";
$params = [];

if (!empty($search)) {
    $sql .= " AND (nama LIKE :search OR username LIKE :search OR email LIKE :search)";
    $params[':search'] = "%$search%";
}

$sql .= " ORDER BY id DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$admins = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Admin - Dapur Kuliner Pak Ndut</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary-orange: #FF8C00;
            --secondary-orange: #FFA500;
            --light-orange: #FFE5CC;
            --dark-orange: #CC6600;
        }

        body {
            background: linear-gradient(135deg, #FFA500 0%, #FF8C00 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
        }

        .sidebar {
            background: var(--dark-orange);
            min-height: 100vh;
            padding: 0;
            position: fixed;
            width: 250px;
            box-shadow: 4px 0 15px rgba(0,0,0,0.2);
            z-index: 1000;
        }

        .sidebar .logo {
            text-align: center;
            padding: 30px 20px;
            color: white;
            font-size: 1.3rem;
            font-weight: bold;
            background: rgba(0,0,0,0.2);
        }

        .sidebar .logo i {
            font-size: 2.5rem;
            margin-bottom: 10px;
            display: block;
        }

        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 15px 25px;
            margin: 5px 15px;
            border-radius: 10px;
            transition: all 0.3s;
        }

        .sidebar .nav-link:hover {
            background: rgba(255,255,255,0.1);
            color: white;
        }

        .sidebar .nav-link.active {
            background: rgba(255,255,255,0.15);
            color: white;
            border-left: 4px solid var(--light-orange);
        }

        .sidebar .nav-link i {
            margin-right: 10px;
        }

        .main-content {
            margin-left: 250px;
            padding: 30px;
        }

        .page-header {
            background: white;
            padding: 25px 30px;
            border-radius: 20px;
            margin-bottom: 30px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .btn-add {
            background: linear-gradient(135deg, var(--primary-orange), var(--secondary-orange));
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-add:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 140, 0, 0.4);
            color: white;
        }

        .data-card {
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }

        .search-box {
            position: relative;
            margin-bottom: 25px;
        }

        .search-box input {
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            padding: 12px 20px 12px 45px;
            width: 100%;
            transition: all 0.3s;
        }

        .search-box i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
        }

        .search-box input:focus {
            outline: none;
            border-color: var(--primary-orange);
            box-shadow: 0 0 0 3px rgba(255, 140, 0, 0.1);
        }

        .table-responsive {
            border-radius: 15px;
            overflow: hidden;
        }

        .table {
            margin: 0;
        }

        .table thead {
            background: linear-gradient(135deg, var(--primary-orange), var(--secondary-orange));
            color: white;
        }

        .table thead th {
            padding: 18px 20px;
            border: none;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
        }

        .table tbody tr {
            transition: all 0.3s;
            border-bottom: 1px solid #f3f4f6;
        }

        .table tbody tr:hover {
            background: rgba(255, 140, 0, 0.05);
        }

        .table tbody td {
            padding: 20px;
            vertical-align: middle;
        }

        .admin-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-orange), var(--secondary-orange));
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.2rem;
        }

        .admin-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .btn-action {
            padding: 8px 12px;
            border: none;
            border-radius: 8px;
            transition: all 0.3s;
            margin: 0 3px;
            cursor: pointer;
        }

        .btn-edit {
            background: #DBEAFE;
            color: #1E40AF;
        }

        .btn-edit:hover {
            background: #3B82F6;
            color: white;
            transform: translateY(-2px);
        }

        .btn-delete {
            background: #FEE2E2;
            color: #991B1B;
        }

        .btn-delete:hover {
            background: #EF4444;
            color: white;
            transform: translateY(-2px);
        }

        .modal-header {
            background: linear-gradient(135deg, var(--primary-orange), var(--secondary-orange));
            color: white;
            border-radius: 20px 20px 0 0;
        }

        .modal-content {
            border-radius: 20px;
            border: none;
        }

        .form-label {
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
        }

        .form-control {
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            padding: 12px 15px;
            transition: all 0.3s;
        }

        .form-control:focus {
            border-color: var(--primary-orange);
            box-shadow: 0 0 0 3px rgba(255, 140, 0, 0.1);
        }

        .alert {
            border-radius: 12px;
            margin-bottom: 20px;
        }

        .avatar-colors {
            display: inline-block;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="logo">
            <i class="fas fa-utensils"></i>
            <span>Dapur Kuliner<br>Pak Ndut</span>
        </div>
        <nav class="nav flex-column mt-3">
            <a class="nav-link" href="admin.php">
                <i class="fas fa-home"></i>Dashboard Admin
            </a>
            <a class="nav-link active" href="data_admin.php">
                <i class="fas fa-user-shield"></i>Data Admin
            </a>
            <a class="nav-link" href="pendataan_menu.php">
                <i class="fas fa-book"></i>Menu
            </a>
            <a class="nav-link" href="data_transaksi.php">
                <i class="fas fa-shopping-cart"></i>Transaksi
            </a>
            <a class="nav-link" href="generate_laporan.php">
                <i class="fas fa-file-alt"></i>Generate Laporan
            </a>
            <a class="nav-link" href="login.php">
                <i class="fas fa-sign-out-alt"></i>Logout
            </a>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <?php 
                    if ($_GET['success'] == 'tambah') echo '✓ Data admin berhasil ditambahkan!';
                    if ($_GET['success'] == 'edit') echo '✓ Data admin berhasil diupdate!';
                    if ($_GET['success'] == 'hapus') echo '✓ Data admin berhasil dihapus!';
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="page-header">
            <div>
                <h2><i class="fas fa-user-shield me-2"></i>Data Admin</h2>
            </div>
            <button class="btn btn-add" data-bs-toggle="modal" data-bs-target="#modalTambahAdmin">
                <i class="fas fa-plus me-2"></i>Tambah Admin
            </button>
        </div>

        <div class="data-card">
            <form method="GET" class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" name="search" placeholder="Cari nama admin atau username..." value="<?= htmlspecialchars($search) ?>" onchange="this.form.submit()">
            </form>

            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Admin</th>
                            <th>Email</th>
                            <th>No. Telepon</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($admins) > 0): ?>
                            <?php 
                                $no = 1; 
                                $colors = [
                                    'linear-gradient(135deg, #FF8C00, #FFA500)',
                                    'linear-gradient(135deg, #EC4899, #F472B6)',
                                    'linear-gradient(135deg, #10B981, #34D399)',
                                    'linear-gradient(135deg, #F59E0B, #FBBF24)',
                                    'linear-gradient(135deg, #EF4444, #F87171)',
                                    'linear-gradient(135deg, #8B5CF6, #A78BFA)',
                                    'linear-gradient(135deg, #06B6D4, #22D3EE)',
                                ];
                                foreach ($admins as $index => $admin): 
                                    $colorIndex = $index % count($colors);
                            ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td>
                                        <div class="admin-info">
                                            <div class="admin-avatar" style="background: <?= $colors[$colorIndex] ?>">
                                                <?= strtoupper(substr($admin['nama'], 0, 1)) ?>
                                            </div>
                                            <div>
                                                <p class="mb-0 fw-bold"><?= htmlspecialchars($admin['nama']) ?></p>
                                                <small class="text-muted">@<?= htmlspecialchars($admin['username']) ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td><?= htmlspecialchars($admin['email']) ?></td>
                                    <td><?= htmlspecialchars($admin['telepon']) ?></td>
                                    <td>
                                        <button class="btn-action btn-edit" onclick='editAdmin(<?= json_encode($admin) ?>)' title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn-action btn-delete" onclick="hapusAdmin(<?= $admin['id'] ?>, '<?= htmlspecialchars($admin['nama']) ?>')" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">Tidak ada data admin</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Admin -->
    <div class="modal fade" id="modalTambahAdmin" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-user-plus me-2"></i>Tambah Admin Baru</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" onsubmit="return validateForm('tambah')">
                    <input type="hidden" name="action" value="tambah">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control" name="nama" id="tambah_nama" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Username</label>
                                <input type="text" class="form-control" name="username" id="tambah_username" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" id="tambah_email" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">No. Telepon</label>
                                <input type="tel" class="form-control" name="telepon" id="tambah_telepon" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" class="form-control" name="password" id="tambah_password" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Konfirmasi Password</label>
                                <input type="password" class="form-control" id="tambah_confirm_password" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-add">
                            <i class="fas fa-save me-2"></i>Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit Admin -->
    <div class="modal fade" id="modalEditAdmin" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Edit Data Admin</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" onsubmit="return validateForm('edit')">
                    <input type="hidden" name="action" value="edit">
                    <input type="hidden" name="id" id="edit_id">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control" name="nama" id="edit_nama" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Username</label>
                                <input type="text" class="form-control" name="username" id="edit_username" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" id="edit_email" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">No. Telepon</label>
                                <input type="tel" class="form-control" name="telepon" id="edit_telepon" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Password Baru</label>
                                <input type="password" class="form-control" name="password" id="edit_password" placeholder="Kosongkan jika tidak diubah">
                                <small class="text-muted">Kosongkan jika tidak ingin mengubah password</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Konfirmasi Password</label>
                                <input type="password" class="form-control" id="edit_confirm_password" placeholder="Konfirmasi password baru">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-add">
                            <i class="fas fa-save me-2"></i>Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function validateForm(type) {
            const password = document.getElementById(type + '_password').value;
            const confirmPassword = document.getElementById(type + '_confirm_password').value;
            
            if (type === 'tambah' && password !== confirmPassword) {
                Swal.fire({
                    icon: 'error',
                    title: 'Password Tidak Cocok',
                    text: 'Password dan konfirmasi password harus sama!',
                    confirmButtonColor: '#FF8C00'
                });
                return false;
            }
            
            if (type === 'edit' && password !== '' && password !== confirmPassword) {
                Swal.fire({
                    icon: 'error',
                    title: 'Password Tidak Cocok',
                    text: 'Password dan konfirmasi password harus sama!',
                    confirmButtonColor: '#FF8C00'
                });
                return false;
            }
            
            return true;
        }

        function editAdmin(admin) {
            document.getElementById('edit_id').value = admin.id;
            document.getElementById('edit_nama').value = admin.nama;
            document.getElementById('edit_username').value = admin.username;
            document.getElementById('edit_email').value = admin.email;
            document.getElementById('edit_telepon').value = admin.telepon;
            document.getElementById('edit_password').value = '';
            document.getElementById('edit_confirm_password').value = '';
            
            const modal = new bootstrap.Modal(document.getElementById('modalEditAdmin'));
            modal.show();
        }

        function hapusAdmin(id, nama) {
            Swal.fire({
                title: 'Hapus Admin?',
                text: `Yakin ingin menghapus admin "${nama}"? Data yang sudah dihapus tidak dapat dikembalikan!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#EF4444',
                cancelButtonColor: '#6B7280',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = `data_admin.php?action=hapus&id=${id}`;
                }
            });
        }
    </script>
</body>
</html>