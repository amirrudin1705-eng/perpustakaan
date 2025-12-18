<?php
require_once '../../includes/auth_check.php';
cekRole('petugas');
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Petugas | Perpustakaan UKRI</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Google Font: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', system-ui, sans-serif;
            background-color: #F8F9FA;
        }
        .navbar {
            background-color: #FFC107;
        }
        .navbar-brand, .nav-link {
            color: #212121 !important;
            font-weight: 600;
        }
        .card {
            border-radius: 12px;
        }
        .icon-box {
            font-size: 2.2rem;
            color: #4CAF50;
        }
    </style>
</head>

<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="#">
            <i class="bi bi-book-half"></i> Perpustakaan UKRI
        </a>

        <div class="ms-auto">
            <span class="me-3">
                <i class="bi bi-person-badge"></i>
                <?= $_SESSION['nama']; ?> (Petugas)
            </span>
            <a href="../auth/logout.php" class="btn btn-sm btn-outline-dark">
                <i class="bi bi-box-arrow-right"></i> Logout
            </a>
        </div>
    </div>
</nav>

<!-- CONTENT -->
<div class="container my-5">

    <h4 class="mb-4">
        <i class="bi bi-speedometer2 text-success"></i>
        Dashboard Petugas
    </h4>

    <div class="row g-4">

        <!-- Kelola Buku -->
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <div class="icon-box mb-2">
                        <i class="bi bi-book"></i>
                    </div>
                    <h5>Kelola Buku</h5>
                    <p class="text-muted small">
                        Tambah, ubah, dan hapus data buku
                    </p>
                    <a href="buku.php" class="btn btn-success btn-sm">
                        Kelola Buku
                    </a>
                </div>
            </div>
        </div>

        <!-- Peminjaman -->
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <div class="icon-box mb-2">
                        <i class="bi bi-arrow-left-right"></i>
                    </div>
                    <h5>Peminjaman</h5>
                    <p class="text-muted small">
                        Proses peminjaman & pengembalian buku
                    </p>
                    <a href="peminjaman.php" class="btn btn-success btn-sm">
                        Peminjaman
                    </a>
                </div>
            </div>
        </div>

        <!-- Laporan -->
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <div class="icon-box mb-2">
                        <i class="bi bi-file-earmark-text"></i>
                    </div>
                    <h5>Laporan</h5>
                    <p class="text-muted small">
                        Laporan transaksi perpustakaan
                    </p>
                    <a href="laporan.php" class="btn btn-success btn-sm">
                        Lihat Laporan
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>

</body>
</html>
