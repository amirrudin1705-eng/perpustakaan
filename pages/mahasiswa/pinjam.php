<?php
require_once '../../includes/auth_check.php';
require_once '../../config/database.php';
cekRole('mahasiswa');

$buku = mysqli_query($conn, "
    SELECT id, kode_buku, judul, pengarang, penerbit, tahun, stok
    FROM buku
    WHERE stok > 0
    ORDER BY judul ASC
");

if (isset($_POST['pinjam'])) {
    $user_id = $_SESSION['user_id'];
    $buku_id = $_POST['buku_id'];
    $tanggal = date('Y-m-d');

    mysqli_query($conn, "
        INSERT INTO peminjaman (user_id, buku_id, tanggal_pinjam, status)
        VALUES ('$user_id', '$buku_id', '$tanggal', 'menunggu')
    ");

    $success = "Pengajuan peminjaman berhasil. Menunggu verifikasi petugas.";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Pinjam Buku | Mahasiswa</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

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
    background: #8B0000;
    color: #fff;
}

/* CARD */
.card {
    border-radius: 14px;
}
</style>
</head>

<body>

<div class="d-flex">

    <!-- SIDEBAR -->
    <?php include 'layout/sidebar_user.php'; ?>
    <!-- CONTENT -->
    <div class="flex-grow-1 p-4">

        <h4 class="mb-4">
            <i class="bi bi-arrow-left-right text-danger"></i>
            Pinjam Buku
        </h4>

        <?php if (isset($success)): ?>
            <div class="alert alert-success">
                <i class="bi bi-check-circle"></i> <?= $success ?>
            </div>
        <?php endif; ?>

        <div class="card shadow-sm border-0">
            <div class="card-body">

                <table class="table table-striped align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Kode</th>
                            <th>Judul</th>
                            <th>Pengarang</th>
                            <th>Penerbit</th>
                            <th>Tahun</th>
                            <th>Stok</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                    <?php if (mysqli_num_rows($buku) > 0): ?>
                        <?php $no = 1; ?>
                        <?php while ($row = mysqli_fetch_assoc($buku)): ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><?= $row['kode_buku']; ?></td>
                                <td><?= $row['judul']; ?></td>
                                <td><?= $row['pengarang']; ?></td>
                                <td><?= $row['penerbit']; ?></td>
                                <td><?= $row['tahun']; ?></td>
                                <td><?= $row['stok']; ?></td>
                                <td>
                                    <form method="post" class="d-inline">
                                        <input type="hidden" name="buku_id" value="<?= $row['id']; ?>">
                                        <button name="pinjam" class="btn btn-danger btn-sm">
                                            <i class="bi bi-send"></i> Ajukan
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center text-muted">
                                Tidak ada buku tersedia
                            </td>
                        </tr>
                    <?php endif; ?>
                    </tbody>

                </table>

            </div>
        </div>

    </div>
</div>

</body>
</html>
