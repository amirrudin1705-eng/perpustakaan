<?php
require_once '../../includes/auth_check.php';
require_once '../../config/database.php';
cekRole('mahasiswa');

$user_id = $_SESSION['id'];

/* =========================
   AMBIL RIWAYAT PEMINJAMAN USER
   ========================= */
$data = mysqli_query($conn, "
    SELECT p.*, 
           b.kode_buku, b.judul, b.pengarang, b.penerbit, b.tahun
    FROM peminjaman p
    JOIN buku b ON p.buku_id = b.id
    WHERE p.user_id = '$user_id'
    ORDER BY p.id DESC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Riwayat Peminjaman | Mahasiswa</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<!-- Font -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

<style>
body {
    font-family: 'Inter', system-ui, sans-serif;
    background-color: #F8F9FA;
}
.navbar {
    background-color: #FFC107;
}
.card {
    border-radius: 12px;
}
</style>
</head>

<body>

<!-- NAVBAR -->
<nav class="navbar shadow-sm">
    <div class="container">
        <span class="navbar-brand fw-semibold">
            <i class="bi bi-book-half"></i> Perpustakaan UKRI
        </span>
        <a href="dashboard.php" class="btn btn-outline-dark btn-sm">
            <i class="bi bi-arrow-left"></i> Dashboard
        </a>
    </div>
</nav>

<div class="container my-5">

    <h4 class="mb-4">
        <i class="bi bi-clock-history text-success"></i>
        Riwayat Peminjaman
    </h4>

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
                        <th>Tgl Pinjam</th>
                        <th>Tgl Kembali</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>

                <?php if (mysqli_num_rows($data) > 0): ?>
                    <?php $no = 1; ?>
                    <?php while ($row = mysqli_fetch_assoc($data)): ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= $row['kode_buku']; ?></td>
                            <td><?= $row['judul']; ?></td>
                            <td><?= $row['pengarang']; ?></td>
                            <td><?= $row['penerbit']; ?></td>
                            <td><?= $row['tahun']; ?></td>
                            <td><?= $row['tanggal_pinjam']; ?></td>
                            <td><?= $row['tanggal_kembali'] ?? '-'; ?></td>
                            <td>
                                <?php if ($row['status'] == 'menunggu'): ?>
                                    <span class="badge bg-warning text-dark">
                                        Menunggu
                                    </span>
                                <?php elseif ($row['status'] == 'dipinjam'): ?>
                                    <span class="badge bg-primary">
                                        Dipinjam
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-success">
                                        Dikembalikan
                                    </span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9" class="text-center text-muted">
                            Belum ada riwayat peminjaman
                        </td>
                    </tr>
                <?php endif; ?>

                </tbody>
            </table>

        </div>
    </div>

</div>

</body>
</html>
