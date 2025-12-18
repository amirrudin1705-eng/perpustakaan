<?php
require_once '../../includes/auth_check.php';
require_once '../../config/database.php';
cekRole('petugas');

if (isset($_POST['simpan'])) {
    $kode = $_POST['kode'];
    $judul = $_POST['judul'];
    $pengarang = $_POST['pengarang'];
    $penerbit = $_POST['penerbit'];
    $tahun = $_POST['tahun'];
    $stok = $_POST['stok'];

    mysqli_query($conn, "INSERT INTO buku VALUES (
        NULL,'$kode','$judul','$pengarang','$penerbit','$tahun','$stok'
    )");

    header("Location: buku.php");
}
?>
<!-- HTML FORM -->
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Tambah Buku</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container my-5">
<h4>Tambah Buku</h4>

<form method="post">
    <input name="kode" class="form-control mb-2" placeholder="Kode Buku" required>
    <input name="judul" class="form-control mb-2" placeholder="Judul" required>
    <input name="pengarang" class="form-control mb-2" placeholder="Pengarang" required>
    <input name="penerbit" class="form-control mb-2" placeholder="Penerbit" required>
    <input name="tahun" type="number" class="form-control mb-2" placeholder="Tahun" required>
    <input name="stok" type="number" class="form-control mb-3" placeholder="Stok" required>

    <button name="simpan" class="btn btn-success">Simpan</button>
    <a href="buku.php" class="btn btn-secondary">Kembali</a>
</form>
</div>

</body>
</html>
