<?php
require_once '../../includes/auth_check.php';
require_once '../../config/database.php';
cekRole('petugas');

/* =========================
   PARAMETER KATEGORI
   ========================= */
$kategori_id = isset($_GET['kategori']) ? $_GET['kategori'] : '';

/* =========================
   DATA KATEGORI (UNTUK DROPDOWN)
   ========================= */
$kategori = mysqli_query($conn, "SELECT * FROM kategori ORDER BY id ASC");

/* =========================
   DATA BUKU
   ========================= */
if (!empty($kategori_id)) {
    // JIKA FILTER KATEGORI DIPILIH
    $buku = mysqli_query(
        $conn,
        "SELECT * FROM buku 
         WHERE kategori_id = '$kategori_id'
         ORDER BY id DESC"
    );
} else {
    // LOGIC LAMA (DIPERTAHANKAN)
    $buku = mysqli_query(
        $conn,
        "SELECT * FROM buku 
         ORDER BY id DESC"
    );
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Kelola Buku | Petugas</title>
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
    flex-shrink: 0;
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

/* CARD BUKU */
.book-card {
    border-radius: 14px;
    transition: .2s;
}
.book-card:hover {
    transform: translateY(-4px);
}
.book-cover {
    height: 220px;
    object-fit: cover;
    border-radius: 14px 14px 0 0;
}
</style>
</head>

<body>

<div class="d-flex">

    <!-- SIDEBAR PETUGAS -->
    <?php include 'layout/sidebar_petugas.php'; ?>

    <!-- CONTENT -->
    <div class="flex-grow-1 p-4">

        <!-- HEADER -->
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h4>
                <i class="bi bi-journal-text text-danger"></i>
                Kelola Buku
            </h4>

            <a href="buku_tambah.php" class="btn btn-danger">
                <i class="bi bi-plus-circle"></i> Tambah Buku
            </a>
        </div>

        <!-- FILTER KATEGORI -->
        <div class="mb-4">
            <form method="get" class="d-flex align-items-center gap-2">
                <span class="text-muted small">Kategori:</span>

                <select name="kategori"
                class="form-select form-select-sm w-auto"
                onchange="this.form.submit()">

            <option value="">Semua Kategori</option>

            <?php while ($k = mysqli_fetch_assoc($kategori)): ?>
                <option value="<?= $k['id']; ?>"
                    <?= ($kategori_id == $k['id']) ? 'selected' : ''; ?>>
                    <?= htmlspecialchars($k['nama_kategori']); ?>
                </option>
            <?php endwhile; ?>
                </select>
            </form>
        </div>

        <!-- === END KATEGORI === -->

        <!-- GRID BUKU -->
        <div class="row g-4">

        <?php if (mysqli_num_rows($buku) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($buku)): ?>
                <div class="col-md-3 col-sm-6">
                    <div class="card book-card shadow-sm border-0 h-100">

                        <img src="../../assets/img/<?= !empty($row['foto']) ? $row['foto'] : 'default.jpg'; ?>"
                             class="book-cover"
                             alt="Cover Buku">

                        <div class="card-body">
                            <h6 class="mb-1"><?= $row['judul']; ?></h6>
                            <small class="text-muted"><?= $row['pengarang']; ?></small>

                            <div class="mt-2">
                                <span class="badge bg-secondary">
                                    Stok: <?= $row['stok']; ?>
                                </span>
                            </div>
                        </div>

                        <div class="card-footer bg-white border-0 d-flex justify-content-between">
                            <a href="buku_edit.php?id=<?= $row['id']; ?>" 
                               class="btn btn-warning btn-sm">
                                <i class="bi bi-pencil"></i>
                            </a>

                            <a href="buku_hapus.php?id=<?= $row['id']; ?>"
                               onclick="return confirm('Yakin hapus buku ini?')"
                               class="btn btn-danger btn-sm">
                                <i class="bi bi-trash"></i>
                            </a>
                        </div>

                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="text-muted">Data buku belum tersedia.</p>
        <?php endif; ?>

        </div>

    </div>
</div>

</body>
</html>
