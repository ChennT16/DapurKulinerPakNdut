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

// Handle Tambah/Edit/Hapus Admin
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'] ?? '';
    if ($action === 'tambah') {
        $stmt = $pdo->prepare("INSERT INTO admin (nama, username, email, telepon, password) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $_POST['nama'], $_POST['username'], $_POST['email'], $_POST['telepon'], password_hash($_POST['password'], PASSWORD_DEFAULT)
        ]);
        header("Location: admin.php?success=tambah"); exit;
    } elseif ($action === 'edit') {
        $id = $_POST['id'];
        if (!empty($_POST['password'])) {
            $stmt = $pdo->prepare("UPDATE admin SET nama=?, username=?, email=?, telepon=?, password=? WHERE id=?");
            $stmt->execute([$_POST['nama'], $_POST['username'], $_POST['email'], $_POST['telepon'], password_hash($_POST['password'], PASSWORD_DEFAULT), $id]);
        } else {
            $stmt = $pdo->prepare("UPDATE admin SET nama=?, username=?, email=?, telepon=? WHERE id=?");
            $stmt->execute([$_POST['nama'], $_POST['username'], $_POST['email'], $_POST['telepon'], $id]);
        }
        header("Location: admin.php?success=edit"); exit;
    }
}

if (isset($_GET['action']) && $_GET['action'] == 'hapus') {
    $stmt = $pdo->prepare("DELETE FROM admin WHERE id=?");
    $stmt->execute([$_GET['id']]);
    header("Location: admin.php?success=hapus"); exit;
}

$search = $_GET['search'] ?? '';
$query = "SELECT * FROM admin WHERE nama LIKE :s OR username LIKE :s OR email LIKE :s ORDER BY id DESC";
$stmt = $pdo->prepare($query);
$stmt->execute([':s' => "%$search%"]);
$admins = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Data Admin - Dapur Kuliner Pak Ndut</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<style>
:root {
    --orange: #FF8C00;
    --light-orange: #FF8C00;
    --white: #FF8C00;
    --text-dark: #333;
    --shadow: 0 8px 25px rgba(0,0,0,0.1);
}
body {
    font-family: 'Segoe UI', sans-serif;
    background: linear-gradient(135deg, #FFF7E6, #FFD9A3);
    min-height: 100vh;
    display: flex;
    margin: 0;
}
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
.main {
    margin-left: 250px;
    padding: 30px;
    width: calc(100% - 250px);
}
.header {
    background: white;
    border-radius: 15px;
    padding: 20px 25px;
    box-shadow: var(--shadow);
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.header h2 {
    color: var(--orange);
    font-weight: 700;
}
.btn-orange {
    background: var(--orange);
    color: white;
    border: none;
    border-radius: 10px;
    padding: 10px 20px;
    transition: 0.3s;
}
.btn-orange:hover {
    background: #FF7000;
    transform: translateY(-2px);
    box-shadow: var(--shadow);
}
.card {
    margin-top: 25px;
    background: white;
    border-radius: 15px;
    padding: 25px;
    box-shadow: var(--shadow);
}
.search-box {
    position: relative;
    margin-bottom: 20px;
}
.search-box input {
    border-radius: 10px;
    border: 2px solid #eee;
    padding: 10px 15px 10px 40px;
    width: 100%;
}
.search-box i {
    position: absolute;
    left: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: #aaa;
}
.table thead {
    background: var(--orange);
    color: white;
}
.table tbody tr:hover {
    background: #FFF3E0;
}
.btn-edit, .btn-delete {
    border: none;
    border-radius: 8px;
    padding: 8px 12px;
    transition: 0.2s;
}
.btn-edit { background: #DBEAFE; color: #1E40AF; }
.btn-edit:hover { background: #3B82F6; color: white; }
.btn-delete { background: #FEE2E2; color: #991B1B; }
.btn-delete:hover { background: #EF4444; color: white; }
.modal-header {
    background: var(--orange);
    color: white;
    border-radius: 15px 15px 0 0;
}
.form-control:focus {
    border-color: var(--orange);
    box-shadow: 0 0 0 3px rgba(255,140,0,0.1);
}
</style>
</head>
<body>
<div class="sidebar">
    <div class="logo">
        <i class="fas fa-utensils"></i>
        <h4>Dapur Kuliner<br>Pak Ndut</h4>
    </div>
    <a href="dashboard.php"><i class="fas fa-home me-2"></i> Dashboard</a>
    <a href="admin.php" class="active"><i class="fas fa-user-shield me-2"></i> Data Admin</a>
    <a href="pendataan_menu.php"><i class="fas fa-book me-2"></i> Menu</a>
    <a href="data_transaksi.php"><i class="fas fa-shopping-cart me-2"></i> Transaksi</a>
    <a href="generate_laporan.php"><i class="fas fa-file-alt me-2"></i> Laporan</a>
    <a href="ulasan.php"><i class="fas fa-comment-dots me-2"></i> Ulasan</a>
    <a href="login.php"><i class="fas fa-sign-out-alt me-2"></i> Logout</a>
</div>

<div class="main">
    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?php 
                if ($_GET['success'] == 'tambah') echo '✅ Data admin berhasil ditambahkan!';
                if ($_GET['success'] == 'edit') echo '✅ Data admin berhasil diubah!';
                if ($_GET['success'] == 'hapus') echo '✅ Data admin berhasil dihapus!';
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="header">
        <h2><i class="fas fa-user-shield me-2"></i> Data Admin</h2>
        <button class="btn-orange" data-bs-toggle="modal" data-bs-target="#modalTambah">
            <i class="fas fa-plus me-2"></i>Tambah Admin
        </button>
    </div>

    <div class="card">
        <form method="GET" class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Cari nama admin...">
        </form>
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead><tr><th>No</th><th>Nama</th><th>Email</th><th>Telepon</th><th>Aksi</th></tr></thead>
                <tbody>
                    <?php if ($admins): $no=1; foreach ($admins as $a): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars($a['nama']) ?><br><small>@<?= htmlspecialchars($a['username']) ?></small></td>
                        <td><?= htmlspecialchars($a['email']) ?></td>
                        <td><?= htmlspecialchars($a['telepon']) ?></td>
                        <td>
                            <button class="btn-edit" onclick='editAdmin(<?= json_encode($a) ?>)'><i class="fas fa-edit"></i></button>
                            <button class="btn-delete" onclick="hapusAdmin(<?= $a['id'] ?>, '<?= htmlspecialchars($a['nama']) ?>')"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                    <?php endforeach; else: ?>
                    <tr><td colspan="5" class="text-center text-muted">Tidak ada data admin</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="modalTambah" tabindex="-1">
<div class="modal-dialog modal-lg">
<div class="modal-content">
<div class="modal-header"><h5><i class="fas fa-user-plus me-2"></i>Tambah Admin</h5></div>
<form method="POST">
<input type="hidden" name="action" value="tambah">
<div class="modal-body">
<div class="row">
    <div class="col-md-6 mb-3"><label class="form-label">Nama</label><input type="text" name="nama" class="form-control" required></div>
    <div class="col-md-6 mb-3"><label class="form-label">Username</label><input type="text" name="username" class="form-control" required></div>
</div>
<div class="row">
    <div class="col-md-6 mb-3"><label class="form-label">Email</label><input type="email" name="email" class="form-control" required></div>
    <div class="col-md-6 mb-3"><label class="form-label">Telepon</label><input type="tel" name="telepon" class="form-control" required></div>
</div>
<div class="row">
    <div class="col-md-6 mb-3"><label class="form-label">Password</label><input type="password" name="password" class="form-control" required></div>
    <div class="col-md-6 mb-3"><label class="form-label">Konfirmasi Password</label><input type="password" id="confirmTambah" class="form-control" required></div>
</div>
</div>
<div class="modal-footer">
<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
<button type="submit" class="btn-orange">Simpan</button>
</div>
</form>
</div></div></div>

<!-- Modal Edit -->
<div class="modal fade" id="modalEdit" tabindex="-1">
<div class="modal-dialog modal-lg">
<div class="modal-content">
<div class="modal-header"><h5><i class="fas fa-edit me-2"></i>Edit Admin</h5></div>
<form method="POST">
<input type="hidden" name="action" value="edit">
<input type="hidden" name="id" id="edit_id">
<div class="modal-body">
<div class="row">
    <div class="col-md-6 mb-3"><label class="form-label">Nama</label><input type="text" name="nama" id="edit_nama" class="form-control" required></div>
    <div class="col-md-6 mb-3"><label class="form-label">Username</label><input type="text" name="username" id="edit_username" class="form-control" required></div>
</div>
<div class="row">
    <div class="col-md-6 mb-3"><label class="form-label">Email</label><input type="email" name="email" id="edit_email" class="form-control" required></div>
    <div class="col-md-6 mb-3"><label class="form-label">Telepon</label><input type="tel" name="telepon" id="edit_telepon" class="form-control" required></div>
</div>
<div class="row">
    <div class="col-md-6 mb-3"><label class="form-label">Password Baru</label><input type="password" name="password" id="edit_password" class="form-control"></div>
    <div class="col-md-6 mb-3"><label class="form-label">Konfirmasi Password</label><input type="password" id="edit_confirm" class="form-control"></div>
</div>
</div>
<div class="modal-footer">
<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
<button type="submit" class="btn-orange">Update</button>
</div>
</form>
</div></div></div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function editAdmin(a){
    document.getElementById('edit_id').value=a.id;
    document.getElementById('edit_nama').value=a.nama;
    document.getElementById('edit_username').value=a.username;
    document.getElementById('edit_email').value=a.email;
    document.getElementById('edit_telepon').value=a.telepon;
    new bootstrap.Modal(document.getElementById('modalEdit')).show();
}
function hapusAdmin(id,nama){
    Swal.fire({
        title:'Hapus Admin?',
        text:`Yakin ingin menghapus "${nama}"?`,
        icon:'warning', showCancelButton:true,
        confirmButtonText:'Ya, hapus!', cancelButtonText:'Batal',
        confirmButtonColor:'#FF8C00'
    }).then(r=>{ if(r.isConfirmed){ window.location=`admin.php?action=hapus&id=${id}`; } });
}
</script>
</body>
</html>
