<?php
require_once '../../includes/auth_check.php';
require_once '../../config/database.php';
cekRole('petugas');

$data = mysqli_query($conn,"
SELECT p.*, u.nama AS mahasiswa, b.judul AS buku
FROM peminjaman p
JOIN users u ON p.user_id=u.id
JOIN buku b ON p.buku_id=b.id
ORDER BY p.id DESC
");

if(isset($_GET['setujui'])) {
  $id=$_GET['setujui'];
  $p=mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM peminjaman WHERE id='$id'"));
  mysqli_query($conn,"UPDATE peminjaman SET status='dipinjam' WHERE id='$id'");
  mysqli_query($conn,"UPDATE buku SET stok=stok-1 WHERE id='{$p['buku_id']}'");
  header("Location: peminjaman.php");
}

if(isset($_GET['kembali'])) {
  $id=$_GET['kembali'];
  $p=mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM peminjaman WHERE id='$id'"));
  mysqli_query($conn,"
    UPDATE peminjaman 
    SET status='dikembalikan', tanggal_kembali=CURDATE()
    WHERE id='$id'
  ");
  mysqli_query($conn,"UPDATE buku SET stok=stok+1 WHERE id='{$p['buku_id']}'");
  header("Location: peminjaman.php");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Peminjaman | Petugas</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

<style>
body { font-family:'Inter',system-ui,sans-serif; background:#F8F9FA; }
.navbar { background:#FFC107; }
.card { border-radius:12px; }
</style>
</head>

<body>

<nav class="navbar shadow-sm">
<div class="container">
<a href="dashboard.php" class="navbar-brand">
<i class="bi bi-book-half"></i> Perpustakaan UKRI
</a>
<a href="dashboard.php" class="btn btn-outline-dark btn-sm">
<i class="bi bi-arrow-left"></i> Dashboard
</a>
</div>
</nav>

<div class="container my-5">

<h4 class="mb-4">
<i class="bi bi-arrow-left-right text-success"></i>
Data Peminjaman
</h4>

<div class="card shadow-sm border-0">
<div class="card-body">

<table class="table table-striped align-middle">
<thead class="table-light">
<tr>
<th>Mahasiswa</th>
<th>Buku</th>
<th>Status</th>
<th>Aksi</th>
</tr>
</thead>
<tbody>

<?php while($row=mysqli_fetch_assoc($data)): ?>
<tr>
<td><?= $row['mahasiswa'] ?></td>
<td><?= $row['buku'] ?></td>
<td>
<?php if($row['status']=='menunggu'): ?>
<span class="badge bg-warning text-dark">Menunggu</span>
<?php elseif($row['status']=='dipinjam'): ?>
<span class="badge bg-primary">Dipinjam</span>
<?php else: ?>
<span class="badge bg-success">Dikembalikan</span>
<?php endif; ?>
</td>
<td>
<?php if($row['status']=='menunggu'): ?>
<a href="?setujui=<?= $row['id'] ?>" class="btn btn-success btn-sm">
<i class="bi bi-check-circle"></i> Setujui
</a>
<?php elseif($row['status']=='dipinjam'): ?>
<a href="?kembali=<?= $row['id'] ?>" class="btn btn-warning btn-sm">
<i class="bi bi-arrow-return-left"></i> Kembalikan
</a>
<?php else: ?> -
<?php endif; ?>
</td>
</tr>
<?php endwhile; ?>

</tbody>
</table>

</div>
</div>

</div>
</body>
</html>
