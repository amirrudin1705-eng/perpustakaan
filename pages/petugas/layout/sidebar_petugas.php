<?php
//layout/sidebar_petugas.php
?>

<div class="sidebar p-3 shadow-sm">

    <h5 class="mb-4 text-danger">
        <i class="bi bi-book-half"></i> Perpustakaan
    </h5>

    <div class="mb-3 text-muted small">
        <i class="bi bi-person-badge"></i>
        <?= $_SESSION['nama']; ?> (Petugas)
    </div>

    <a href="dashboard.php" class="<?= basename($_SERVER['PHP_SELF'])=='dashboard.php'?'active':'' ?>">
        <i class="bi bi-speedometer2"></i>
        Dashboard
    </a>

    <a href="buku.php" class="<?= basename($_SERVER['PHP_SELF'])=='buku.php'?'active':'' ?>">
        <i class="bi bi-journal-text"></i>
        Data Buku
    </a>

    <a href="peminjaman.php" class="<?= basename($_SERVER['PHP_SELF'])=='peminjaman.php'?'active':'' ?>">
        <i class="bi bi-arrow-left-right"></i>
        Peminjaman
    </a>

    <a href="laporan.php" class="<?= basename($_SERVER['PHP_SELF'])=='laporan.php'?'active':'' ?>">
        <i class="bi bi-file-earmark-text"></i>
        Laporan
    </a>

    <a href="anggota.php" class="<?= basename($_SERVER['PHP_SELF'])=='anggota.php'?'active':'' ?>">
        <i class="bi bi-people"></i>
        Anggota
    </a>

    <hr>

    <a href="../auth/logout.php" class="text-danger">
        <i class="bi bi-box-arrow-right"></i>
        Logout
    </a>

</div>
