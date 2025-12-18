<?php
require_once '../../includes/auth_check.php';
cekRole('mahasiswa');
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Mahasiswa | Perpustakaan UKRI</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 -->
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
            font-size: 2rem;
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
                <i class="bi bi-person-circle"></i>
                <?= $_SESSION['nama']; ?>
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
        Dashboard Mahasiswa
    </h4>

    <div class="row g-4">

        <!-- Katalog Buku -->
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <div class="icon-box mb-2">
                        <i class="bi bi-book"></i>
                    </div>
                    <h5>Katalog Buku</h5>
                    <p class="text-muted small">
                        Lihat dan cari buku yang tersedia di perpustakaan
                    </p>
                    <a href="katalog.php" class="btn btn-success btn-sm">
                        Lihat Buku
                    </a>
                </div>
            </div>
        </div>

        <!-- Pinjam Buku -->
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <div class="icon-box mb-2">
                        <i class="bi bi-arrow-left-right"></i>
                    </div>
                    <h5>Pinjam Buku</h5>
                    <p class="text-muted small">
                        Ajukan peminjaman buku secara online
                    </p>
                    <a href="pinjam.php" class="btn btn-success btn-sm">
                        Pinjam
                    </a>
                </div>
            </div>
        </div>

        <!-- Riwayat -->
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <div class="icon-box mb-2">
                        <i class="bi bi-clock-history"></i>
                    </div>
                    <h5>Riwayat Peminjaman</h5>
                    <p class="text-muted small">
                        Lihat status dan riwayat peminjaman buku
                    </p>
                    <a href="riwayat.php" class="btn btn-success btn-sm">
                        Riwayat
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>

</body>
</html>
