<?php
require_once '../../includes/auth_check.php';
require_once '../../config/database.php';
cekRole('petugas');

$id = $_GET['id'];
$data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM buku WHERE id='$id'"));

if (isset($_POST['update'])) {
    mysqli_query($conn, "UPDATE buku SET
        kode_buku='$_POST[kode]',
        judul='$_POST[judul]',
        pengarang='$_POST[pengarang]',
        penerbit='$_POST[penerbit]',
        tahun='$_POST[tahun]',
        stok='$_POST[stok]'
        WHERE id='$id'
    ");
    header("Location: buku.php");
}
?>
<!DOCTYPE html>
<html>
<head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container my-5">
<h4>Edit Buku</h4>

<form method="post">
    <input name="kode" value="<?= $data['kode_buku']; ?>" class="form-control mb-2">
    <input name="judul" value="<?= $data['judul']; ?>" class="form-control mb-2">
    <input name="pengarang" value="<?= $data['pengarang']; ?>" class="form-control mb-2">
    <input name="penerbit" value="<?= $data['penerbit']; ?>" class="form-control mb-2">
    <input name="tahun" value="<?= $data['tahun']; ?>" class="form-control mb-2">
    <input name="stok" value="<?= $data['stok']; ?>" class="form-control mb-3">

    <button name="update" class="btn btn-warning">Update</button>
    <a href="buku.php" class="btn btn-secondary">Kembali</a>
</form>
</div>

</body>
</html>
