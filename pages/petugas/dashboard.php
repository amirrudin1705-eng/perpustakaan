<?php
require_once '../../includes/auth_check.php';
require_once '../../config/database.php';
cekRole('petugas');

/* =========================
   STATISTIK DASHBOARD
   ========================= */
$totalBuku = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) total FROM buku")
)['total'];

$totalMahasiswa = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) total FROM users WHERE role='mahasiswa'")
)['total'];

$peminjamanAktif = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) total FROM peminjaman WHERE status='dipinjam'")
)['total'];

$peminjamanMenunggu = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) total FROM peminjaman WHERE status='menunggu'")
)['total'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Dashboard Petugas | Perpustakaan UKRI</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

<style>
body {
    font-family: 'Inter', system-ui, sans-serif;
    background:#f8f9fa;
}

/* SIDEBAR */
.sidebar {
    width: 240px;
    min-height: 100vh;
    background: #ffffff;
}
.sidebar a {
    padding: 12px 16px;
    display: block;
    text-decoration: none;
    color: #212529;
    border-radius: 8px;
}
.sidebar a:hover,
.sidebar .active {
    background: #8B0000;
    color: #fff;
}

/* HERO / LANDING */
.hero {
    background-image: url('../../assets/img/foto-ukri1.jpg');
    background-size: cover;
    background-position: center;
    height: 300px;
    border-radius: 16px;
    position: relative;
    overflow: hidden;

    /* efek timbul */
    box-shadow: 0 20px 40px rgba(0,0,0,0.25);
}

.hero::before {
    content:'';
    position:absolute;
    inset:0;
    background: rgba(58, 58, 58, 0.65); /* overlay merah hati */
}

.hero-content {
    position: relative;
    z-index: 1;
}


/* CARD */
.stat-card {
    border-radius: 14px;
}
.stat-card h3 {
    color: #8B0000;
    font-weight: 700;
}
</style>
</head>

<body>

<div class="d-flex">

    <!-- SIDEBAR PETUGAS -->
    <?php include 'layout/sidebar_petugas.php'; ?>
    <!-- CONTENT -->
    <div class="flex-grow-1 p-4">

        <!-- HERO / LANDING -->
       <div class="hero mb-4 text-white d-flex align-items-center">
    <div class="hero-content px-4">
        <h4>Selamat Datang, <?= $_SESSION['nama']; ?> 👋</h4>
        <p class="mb-0">
            Dashboard Petugas Perpustakaan UKRI
        </p>
    </div>
</div>

        <!-- STATISTIK -->
        <div class="row g-4">

            <div class="col-md-3">
                <div class="card stat-card shadow-sm border-0">
                    <div class="card-body">
                        <h6 class="text-muted">Total Buku</h6>
                        <h3><?= $totalBuku; ?></h3>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card stat-card shadow-sm border-0">
                    <div class="card-body">
                        <h6 class="text-muted">Mahasiswa</h6>
                        <h3><?= $totalMahasiswa; ?></h3>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card stat-card shadow-sm border-0">
                    <div class="card-body">
                        <h6 class="text-muted">Dipinjam</h6>
                        <h3><?= $peminjamanAktif; ?></h3>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card stat-card shadow-sm border-0">
                    <div class="card-body">
                        <h6 class="text-muted">Menunggu</h6>
                        <h3><?= $peminjamanMenunggu; ?></h3>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>

</body>
</html>
