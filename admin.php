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
            overflow-x: hidden;
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
            border-bottom: 2px solid rgba(255,255,255,0.1);
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
            display: flex;
            align-items: center;
        }

        .sidebar .nav-link:hover {
            background: rgba(255,255,255,0.1);
            color: white;
            transform: translateX(5px);
        }

        .sidebar .nav-link.active {
            background: rgba(255,255,255,0.15);
            color: white;
            border-left: 4px solid var(--light-orange);
        }

        .sidebar .nav-link i {
            margin-right: 10px;
            font-size: 1.1rem;
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

        .page-header h2 {
            margin: 0;
            color: var(--dark-orange);
            font-weight: 700;
        }

        .page-header .breadcrumb {
            background: none;
            margin: 5px 0 0 0;
            padding: 0;
            font-size: 0.9rem;
        }

        .btn-add {
            background: linear-gradient(135deg, var(--primary-orange), var(--secondary-orange));
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(255, 140, 0, 0.3);
        }

        .btn-add:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(255, 140, 0, 0.5);
            color: white;
        }

        .data-card {
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }

        .search-filter {
            display: flex;
            gap: 15px;
            margin-bottom: 25px;
            flex-wrap: wrap;
        }

        .search-box {
            flex: 1;
            min-width: 250px;
        }

        .search-box input {
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            padding: 12px 20px 12px 45px;
            width: 100%;
            transition: all 0.3s;
        }

        .search-box {
            position: relative;
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

        .filter-select {
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            padding: 12px 20px;
            transition: all 0.3s;
        }

        .filter-select:focus {
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
            letter-spacing: 0.5px;
        }

        .table tbody tr {
            transition: all 0.3s;
            border-bottom: 1px solid #f3f4f6;
        }

        .table tbody tr:hover {
            background: rgba(255, 140, 0, 0.05);
            transform: scale(1.01);
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
            box-shadow: 0 4px 10px rgba(255, 140, 0, 0.3);
        }

        .admin-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .admin-name {
            font-weight: 600;
            color: #1f2937;
            margin: 0;
        }

        .admin-username {
            font-size: 0.85rem;
            color: #6b7280;
            margin: 0;
        }

        .badge-role {
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.8rem;
        }

        .badge-super-admin {
            background: linear-gradient(135deg, #F59E0B, #FBBF24);
            color: white;
        }

        .badge-admin {
            background: linear-gradient(135deg, #3B82F6, #60A5FA);
            color: white;
        }

        .badge-status {
            padding: 6px 14px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.8rem;
        }

        .badge-aktif {
            background: #D1FAE5;
            color: #065F46;
        }

        .badge-nonaktif {
            background: #FEE2E2;
            color: #991B1B;
        }

        .btn-action {
            padding: 8px 12px;
            border: none;
            border-radius: 8px;
            transition: all 0.3s;
            margin: 0 3px;
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

        .btn-view {
            background: #FFE5CC;
            color: #CC6600;
        }

        .btn-view:hover {
            background: var(--primary-orange);
            color: white;
            transform: translateY(-2px);
        }

        .pagination {
            margin-top: 25px;
            justify-content: center;
        }

        .pagination .page-link {
            border: 2px solid #e5e7eb;
            color: var(--primary-orange);
            padding: 10px 18px;
            margin: 0 5px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .pagination .page-link:hover {
            background: var(--primary-orange);
            border-color: var(--primary-orange);
            color: white;
        }

        .pagination .page-item.active .page-link {
            background: var(--primary-orange);
            border-color: var(--primary-orange);
        }

        /* Modal Styles */
        .modal-content {
            border-radius: 20px;
            border: none;
            box-shadow: 0 20px 50px rgba(0,0,0,0.2);
        }

        .modal-header {
            background: linear-gradient(135deg, var(--primary-orange), var(--secondary-orange));
            color: white;
            border-radius: 20px 20px 0 0;
            padding: 20px 30px;
        }

        .modal-header .btn-close {
            filter: brightness(0) invert(1);
        }

        .modal-body {
            padding: 30px;
        }

        .form-label {
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
        }

        .form-control, .form-select {
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            padding: 12px 15px;
            transition: all 0.3s;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-orange);
            box-shadow: 0 0 0 3px rgba(255, 140, 0, 0.1);
        }

        .btn-save {
            background: linear-gradient(135deg, var(--primary-orange), var(--secondary-orange));
            color: white;
            padding: 12px 30px;
            border-radius: 10px;
            border: none;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-save:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 140, 0, 0.4);
            color: white;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 70px;
            }

            .sidebar .logo span {
                display: none;
            }

            .sidebar .nav-link span {
                display: none;
            }

            .main-content {
                margin-left: 70px;
                padding: 15px;
            }

            .search-filter {
                flex-direction: column;
            }

            .search-box {
                width: 100%;
            }
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
            <a class="nav-link" href="#dashboard">
                <i class="fas fa-home"></i>
                <span>Dashboard Admin</span>
            </a>
            <a class="nav-link active" href="#admin">
                <i class="fas fa-user-shield"></i>
                <span>Data Admin</span>
            </a>
            <a class="nav-link" href="#menu">
                <i class="fas fa-book"></i>
                <span>Menu</span>
            </a>
            <a class="nav-link" href="#transaksi">
                <i class="fas fa-shopping-cart"></i>
                <span>Transaksi</span>
            </a>
            <a class="nav-link" href="#laporan">
                <i class="fas fa-file-alt"></i>
                <span>Generate Laporan</span>
            </a>
            <a class="nav-link" href="#logout">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </a>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Page Header -->
        <div class="page-header">
            <div>
                <h2><i class="fas fa-user-shield me-2"></i>Data Admin</h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#" style="color: var(--primary-orange)">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Data Admin</li>
                    </ol>
                </nav>
            </div>
            <button class="btn btn-add" data-bs-toggle="modal" data-bs-target="#modalTambahAdmin">
                <i class="fas fa-plus me-2"></i>Tambah Admin
            </button>
        </div>

        <!-- Data Card -->
        <div class="data-card">
            <!-- Search & Filter -->
            <div class="search-filter">
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" id="searchInput" placeholder="Cari nama admin atau username..." onkeyup="searchTable()">
                </div>
                <select class="filter-select" id="roleFilter" onchange="filterTable()">
                    <option value="">Semua Role</option>
                    <option value="Super Admin">Super Admin</option>
                    <option value="Admin">Admin</option>
                </select>
                <select class="filter-select" id="statusFilter" onchange="filterTable()">
                    <option value="">Semua Status</option>
                    <option value="Aktif">Aktif</option>
                    <option value="Nonaktif">Nonaktif</option>
                </select>
            </div>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table" id="adminTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Admin</th>
                            <th>Email</th>
                            <th>No. Telepon</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Last Login</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>
                                <div class="admin-info">
                                    <div class="admin-avatar">A</div>
                                    <div>
                                        <p class="admin-name">Ahmad Fauzi</p>
                                        <p class="admin-username">@ahmadfauzi</p>
                                    </div>
                                </div>
                            </td>
                            <td>ahmad@dapurpakundut.com</td>
                            <td>081234567890</td>
                            <td><span class="badge-role badge-super-admin">Super Admin</span></td>
                            <td><span class="badge-status badge-aktif">Aktif</span></td>
                            <td>04 Nov 2025, 10:30</td>
                            <td>
                                <button class="btn-action btn-view" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn-action btn-edit" title="Edit" data-bs-toggle="modal" data-bs-target="#modalEditAdmin">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn-action btn-delete" title="Hapus" onclick="confirmDelete('Ahmad Fauzi')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>
                                <div class="admin-info">
                                    <div class="admin-avatar" style="background: linear-gradient(135deg, #EC4899, #F472B6);">S</div>
                                    <div>
                                        <p class="admin-name">Siti Nurhaliza</p>
                                        <p class="admin-username">@sitinur</p>
                                    </div>
                                </div>
                            </td>
                            <td>siti@dapurpakundut.com</td>
                            <td>081234567891</td>
                            <td><span class="badge-role badge-admin">Admin</span></td>
                            <td><span class="badge-status badge-aktif">Aktif</span></td>
                            <td>04 Nov 2025, 09:15</td>
                            <td>
                                <button class="btn-action btn-view" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn-action btn-edit" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn-action btn-delete" title="Hapus" onclick="confirmDelete('Siti Nurhaliza')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>
                                <div class="admin-info">
                                    <div class="admin-avatar" style="background: linear-gradient(135deg, #10B981, #34D399);">B</div>
                                    <div>
                                        <p class="admin-name">Budi Santoso</p>
                                        <p class="admin-username">@budisantoso</p>
                                    </div>
                                </div>
                            </td>
                            <td>budi@dapurpakundut.com</td>
                            <td>081234567892</td>
                            <td><span class="badge-role badge-admin">Admin</span></td>
                            <td><span class="badge-status badge-aktif">Aktif</span></td>
                            <td>03 Nov 2025, 16:45</td>
                            <td>
                                <button class="btn-action btn-view" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn-action btn-edit" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn-action btn-delete" title="Hapus" onclick="confirmDelete('Budi Santoso')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>4</td>
                            <td>
                                <div class="admin-info">
                                    <div class="admin-avatar" style="background: linear-gradient(135deg, #F59E0B, #FBBF24);">D</div>
                                    <div>
                                        <p class="admin-name">Dewi Lestari</p>
                                        <p class="admin-username">@dewilestari</p>
                                    </div>
                                </div>
                            </td>
                            <td>dewi@dapurpakundut.com</td>
                            <td>081234567893</td>
                            <td><span class="badge-role badge-admin">Admin</span></td>
                            <td><span class="badge-status badge-nonaktif">Nonaktif</span></td>
                            <td>28 Oct 2025, 14:20</td>
                            <td>
                                <button class="btn-action btn-view" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn-action btn-edit" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn-action btn-delete" title="Hapus" onclick="confirmDelete('Dewi Lestari')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>5</td>
                            <td>
                                <div class="admin-info">
                                    <div class="admin-avatar" style="background: linear-gradient(135deg, #EF4444, #F87171);">R</div>
                                    <div>
                                        <p class="admin-name">Rina Anggraeni</p>
                                        <p class="admin-username">@rinaanggraeni</p>
                                    </div>
                                </div>
                            </td>
                            <td>rina@dapurpakundut.com</td>
                            <td>081234567894</td>
                            <td><span class="badge-role badge-admin">Admin</span></td>
                            <td><span class="badge-status badge-aktif">Aktif</span></td>
                            <td>04 Nov 2025, 08:00</td>
                            <td>
                                <button class="btn-action btn-view" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn-action btn-edit" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn-action btn-delete" title="Hapus" onclick="confirmDelete('Rina Anggraeni')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <nav aria-label="Page navigation">
                <ul class="pagination">
                    <li class="page-item disabled">
                        <a class="page-link" href="#" tabindex="-1">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    </li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item">
                        <a class="page-link" href="#">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>

    <!-- Modal Tambah Admin -->
    <div class="modal fade" id="modalTambahAdmin" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-user-plus me-2"></i>Tambah Admin Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control" placeholder="Masukkan nama lengkap">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Username</label>
                                <input type="text" class="form-control" placeholder="Masukkan username">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" placeholder="admin@example.com">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">No. Telepon</label>
                                <input type="tel" class="form-control" placeholder="081234567890">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" class="form-control" placeholder="Masukkan password">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Konfirmasi Password</label>
                                <input type="password" class="form-control" placeholder="Konfirmasi password">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Role</label>
                                <select class="form-select">
                                    <option selected>Pilih Role</option>
                                    <option value="super_admin">Super Admin</option>
                                    <option value="admin">Admin</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Status</label>
                                <select class="form-select">
                                    <option value="aktif" selected>Aktif</option>
                                    <option value="nonaktif">Nonaktif</option>
                                </select>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-save">
                                <i class="fas fa-save me-2"></i>Simpan Admin
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit Admin -->
    <div class="modal fade" id="modalEditAdmin" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Edit Data Admin</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control" value="Ahmad Fauzi">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Username</label>
                                <input type="text" class="form-control" value="ahmadfauzi">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" value="ahmad@dapurpakundut.com">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">No. Telepon</label>
                                <input type="tel" class="</div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Password Baru</label>
                                <input type="password" class="form-control" placeholder="Kosongkan jika tidak ingin mengubah">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Konfirmasi Password</label>
                                <input type="password" class="form-control" placeholder="Konfirmasi password baru">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Role</label>
                                <select class="form-select">
                                    <option value="super_admin" selected>Super Admin</option>
                                    <option value="admin">Admin</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Status</label>
                                <select class="form-select">
                                    <option value="aktif" selected>Aktif</option>
                                    <option value="nonaktif">Nonaktif</option>
                                </select>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-save">
                                <i class="fas fa-save me-2"></i>Update Admin
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function searchTable() {
            const input = document.getElementById('searchInput');
            const filter = input.value.toLowerCase();
            const table = document.getElementById('adminTable');
            const tr = table.getElementsByTagName('tr');
            for (let i = 1; i < tr.length; i++) {
                const tdName = tr[i].getElementsByTagName('td')[1];
                if (tdName) {
                    const txtValue = tdName.textContent || tdName.innerText;
                    tr[i].style.display = txtValue.toLowerCase().indexOf(filter) > -1 ? '' : 'none';
                }
            }
        }

        function filterTable() {
            const roleFilter = document.getElementById('roleFilter').value;
            const statusFilter = document.getElementById('statusFilter').value;
            const table = document.getElementById('adminTable');
            const tr = table.getElementsByTagName('tr');
            for (let i = 1; i < tr.length; i++) {
                const tdRole = tr[i].getElementsByTagName('td')[4];
                const tdStatus = tr[i].getElementsByTagName('td')[5];
                let showRole = !roleFilter || (tdRole && (tdRole.textContent || tdRole.innerText).indexOf(roleFilter) > -1);
                let showStatus = !statusFilter || (tdStatus && (tdStatus.textContent || tdStatus.innerText).indexOf(statusFilter) > -1);
                tr[i].style.display = (showRole && showStatus) ? '' : 'none';
            }
        }

        function confirmDelete(name) {
            if (confirm('Apakah Anda yakin ingin menghapus admin "' + name + '"?')) {
                alert('Admin "' + name + '" berhasil dihapus!');
            }
        }
    </script>
</body>
</html>