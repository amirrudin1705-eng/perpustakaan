<?php
require_once '../../includes/auth_check.php';
require_once '../../config/database.php';
cekRole('petugas');

$buku = mysqli_query($conn, "SELECT * FROM buku ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Buku | Petugas</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Inter', system-ui, sans-serif; background:#F8F9FA; }
        .navbar { background:#FFC107; }
        .navbar-brand { font-weight:600; }
    </style>
</head>

<body>

<nav class="navbar shadow-sm">
    <div class="container">
        <a href="dashboard.php" class="navbar-brand">
            <i class="bi bi-arrow-left-circle"></i> Dashboard Petugas
        </a>

        <div>
            <a href="dashboard.php" class="btn btn-sm btn-outline-dark me-2">
                <i class="bi bi-arrow-left"></i> Dashboard
            </a>
        </div>
    </div>
</nav>


<div class="container my-5">

    <a href="buku_tambah.php" class="btn btn-success mb-3">
        <i class="bi bi-plus-circle"></i> Tambah Buku
    </a>

    <div class="card shadow-sm border-0">
        <div class="card-body">

            <table class="table table-bordered align-middle">
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
                            <a href="buku_edit.php?id=<?= $row['id']; ?>" class="btn btn-warning btn-sm">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="buku_hapus.php?id=<?= $row['id']; ?>"
                               onclick="return confirm('Yakin hapus buku ini?')"
                               class="btn btn-danger btn-sm">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center text-muted">
                            Data buku belum ada
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
