<?php
// layout/sidebar_user.php
?>

<div class="sidebar p-3 shadow-sm">

    <h5 class="mb-4 text-danger">
        <i class="bi bi-book-half"></i> Perpustakaan
    </h5>

    <div class="mb-3 text-muted small">
        <i class="bi bi-person-circle"></i>
        <?= $_SESSION['nama']; ?> (Mahasiswa)
    </div>

    <a href="dashboard.php" class="<?= basename($_SERVER['PHP_SELF'])=='dashboard.php'?'active':'' ?>">
        <i class="bi bi-speedometer2"></i>
        Dashboard
    </a>

    <a href="katalog.php" class="<?= basename($_SERVER['PHP_SELF'])=='katalog.php'?'active':'' ?>">
        <i class="bi bi-book"></i>
        Katalog Buku
    </a>

    <a href="pinjam.php" class="<?= basename($_SERVER['PHP_SELF'])=='pinjam.php'?'active':'' ?>">
        <i class="bi bi-arrow-left-right"></i>
        Peminjaman
    </a>

    <a href="riwayat.php" class="<?= basename($_SERVER['PHP_SELF'])=='riwayat.php'?'active':'' ?>">
        <i class="bi bi-clock-history"></i>
        Riwayat
    </a>

    <a href="profil.php" class="<?= basename($_SERVER['PHP_SELF'])=='profil.php'?'active':'' ?>">
        <i class="bi bi-person-circle"></i>
        Profil Saya
    </a>

    <hr>

    <a href="../auth/logout.php" class="text-danger">
        <i class="bi bi-box-arrow-right"></i>
        Logout
    </a>

</div>
