<?php
require_once '../../includes/auth_check.php';
require_once '../../config/database.php';
cekRole('petugas');

$id = $_GET['id'];
$data = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT * FROM buku WHERE id='$id'")
);

if (isset($_POST['update'])) {
    mysqli_query($conn, "
        UPDATE buku SET
            kode_buku = '$_POST[kode]',
            judul     = '$_POST[judul]',
            pengarang = '$_POST[pengarang]',
            penerbit  = '$_POST[penerbit]',
            tahun     = '$_POST[tahun]',
            stok      = '$_POST[stok]'
        WHERE id = '$id'
    ");
    header("Location: buku.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Edit Buku | Petugas</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<style>
body { background:#f8f9fa; }

/* SIDEBAR */
.sidebar {
    width: 240px;
    min-height: 100vh;
    background: #fff;
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

/* CARD */
.card {
    border-radius: 14px;
}
</style>
</head>

<body>

<div class="d-flex">

    <!-- SIDEBAR PETUGAS -->
    <div class="sidebar p-3 shadow-sm">
        <h5 class="mb-4 text-danger">
            <i class="bi bi-book-half"></i> Perpustakaan
        </h5>

        <div class="mb-3 text-muted small">
            <i class="bi bi-person-badge"></i>
            <?= $_SESSION['nama']; ?> (Petugas)
        </div>

        <a href="dashboard.php">
            <i class="bi bi-speedometer2 me-2"></i> Dashboard
        </a>

        <a class="active" href="buku.php">
            <i class="bi bi-journal-text me-2"></i> Data Buku
        </a>

        <a href="peminjaman.php">
            <i class="bi bi-arrow-left-right me-2"></i> Peminjaman
        </a>

        <a href="laporan.php">
            <i class="bi bi-file-earmark-text me-2"></i> Laporan
        </a>

        <hr>
        <a href="../auth/logout.php" class="text-danger">
            <i class="bi bi-box-arrow-right me-2"></i> Logout
        </a>
    </div>

    <!-- CONTENT -->
    <div class="flex-grow-1 p-4">

        <h4 class="mb-4">
            <i class="bi bi-pencil-square text-danger"></i>
            Edit Buku
        </h4>

        <div class="card shadow-sm border-0 col-md-6">
            <div class="card-body">

                <form method="post">

                    <label class="form-label">Kode Buku</label>
                    <input name="kode" value="<?= $data['kode_buku']; ?>" class="form-control mb-3">

                    <label class="form-label">Judul Buku</label>
                    <input name="judul" value="<?= $data['judul']; ?>" class="form-control mb-3">

                    <label class="form-label">Pengarang</label>
                    <input name="pengarang" value="<?= $data['pengarang']; ?>" class="form-control mb-3">

                    <label class="form-label">Penerbit</label>
                    <input name="penerbit" value="<?= $data['penerbit']; ?>" class="form-control mb-3">

                    <label class="form-label">Tahun</label>
                    <input name="tahun" value="<?= $data['tahun']; ?>" class="form-control mb-3">

                    <label class="form-label">Stok</label>
                    <input name="stok" value="<?= $data['stok']; ?>" class="form-control mb-4">

                    <button name="update" class="btn btn-danger">
                        <i class="bi bi-save"></i> Update
                    </button>

                    <a href="buku.php" class="btn btn-secondary ms-2">
                        Kembali
                    </a>

                </form>

            </div>
        </div>

    </div>
</div>

</body>
</html>
