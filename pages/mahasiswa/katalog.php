<?php
require_once '../../includes/auth_check.php';
require_once '../../config/database.php';
cekRole('mahasiswa');

// Pencarian
$keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';

$query = mysqli_query($conn, "
    SELECT * FROM buku 
    WHERE judul LIKE '%$keyword%' 
    OR pengarang LIKE '%$keyword%'
    ORDER BY judul ASC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Katalog Buku | Perpustakaan UKRI</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Font Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', system-ui, sans-serif;
            background-color: #F8F9FA;
        }
        .navbar {
            background-color: #FFC107;
        }
        .navbar-brand {
            font-weight: 600;
            color: #212121 !important;
        }
        .card {
            border-radius: 12px;
        }
    </style>
</head>

<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="dashboard.php">
            <i class="bi bi-book-half"></i> Perpustakaan UKRI
        </a>
        <div class="ms-auto">
            <a href="dashboard.php" class="btn btn-sm btn-outline-dark me-2">
                <i class="bi bi-arrow-left"></i> Dashboard
            </a>
        </div>
    </div>
</nav>

<!-- CONTENT -->
<div class="container my-5">

    <h4 class="mb-4">
        <i class="bi bi-book text-success"></i>
        Katalog Buku
    </h4>

    <!-- SEARCH -->
    <form method="get" class="mb-4">
        <div class="input-group">
            <input type="text" name="keyword" class="form-control"
                   placeholder="Cari judul atau pengarang..."
                   value="<?= htmlspecialchars($keyword); ?>">
            <button class="btn btn-success">
                <i class="bi bi-search"></i>
            </button>
        </div>
    </form>

    <!-- TABLE -->
    <div class="card shadow-sm border-0">
        <div class="card-body">

            <table class="table table-striped align-middle">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Kode</th>
                        <th>Judul</th>
                        <th>Pengarang</th>
                        <th>Tahun</th>
                        <th>Stok</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>

                <?php if (mysqli_num_rows($query) > 0): ?>
                    <?php $no = 1; ?>
                    <?php while ($buku = mysqli_fetch_assoc($query)): ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= $buku['kode_buku']; ?></td>
                            <td><?= $buku['judul']; ?></td>
                            <td><?= $buku['pengarang']; ?></td>
                            <td><?= $buku['tahun']; ?></td>
                            <td><?= $buku['stok']; ?></td>
                            <td>
                                <?php if ($buku['stok'] > 0): ?>
                                    <span class="badge bg-success">
                                        <i class="bi bi-check-circle"></i> Tersedia
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-danger">
                                        <i class="bi bi-x-circle"></i> Habis
                                    </span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center text-muted">
                            <i class="bi bi-info-circle"></i>
                            Data buku tidak ditemukan
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
