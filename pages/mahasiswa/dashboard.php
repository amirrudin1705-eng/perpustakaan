<?php
require_once '../../includes/auth_check.php';
require_once '../../config/database.php';
cekRole('mahasiswa');

$user_id = $_SESSION['user_id'];

// statistik
$totalBuku = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) total FROM buku")
)['total'];

$bukuDipinjam = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) total FROM peminjaman 
     WHERE user_id='$user_id' AND status='dipinjam'")
)['total'];

$riwayat = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) total FROM peminjaman 
     WHERE user_id='$user_id'")
)['total'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Dashboard Mahasiswa</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<style>
body { 
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
    color: #2b2b2b;
    border-radius: 8px;
    transition: .2s;
}

.sidebar a:hover,
.sidebar .active {
    background: #8B0000; /* merah hati */
    color: #fff;
}

/* HERO / LANDING */
.hero {
    background: url('../../assets/img/foto-ukri1.jpg') center/cover;
    height: 300px;
    border-radius: 15px;
    position: relative;
    overflow: hidden;
}

.hero::before {
    content:'';
    position:absolute;
    inset:0;
    background: rgba(58, 58, 58, 0.65); /* overlay merah hati */
}

.hero-content {
    position: relative;
    z-index:1;
}

/* CARD STATISTIK */
.card {
    border-radius: 14px;
}

.card h6 {
    color: #6c757d;
}

.card h3 {
    color: #8B0000; /* angka statistik merah hati */
    font-weight: 700;
}

/* ICON (kalau nanti ditambah) */
.text-primary,
.text-success {
    color: #8B0000 !important;
}
</style>
</head>

<body>

<div class="d-flex">

    <!-- SIDEBAR LANGSUNG -->
    <?php include 'layout/sidebar_user.php'; ?>
    <!-- CONTENT -->
    <div class="flex-grow-1 p-4">

        <!-- LANDING -->
        <div class="hero mb-4 text-white d-flex align-items-center">
            <div class="hero-content px-4">
                <h4>Selamat Datang, <?= $_SESSION['nama']; ?> 👋</h4>
                <p>Sistem Informasi Perpustakaan UKRI</p>
            </div>
        </div>

        <!-- STATISTIK -->
        <div class="row g-4">

            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h6 class="text-muted">Total Buku</h6>
                        <h3><?= $totalBuku; ?></h3>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h6 class="text-muted">Buku Dipinjam</h6>
                        <h3><?= $bukuDipinjam; ?></h3>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h6 class="text-muted">Riwayat</h6>
                        <h3><?= $riwayat; ?></h3>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>

</body>
</html>
