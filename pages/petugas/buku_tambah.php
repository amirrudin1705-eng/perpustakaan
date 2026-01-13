<?php
require_once '../../includes/auth_check.php';
require_once '../../config/database.php';
cekRole('petugas');

if (isset($_POST['simpan'])) {

    // ===============================
    // VALIDASI & UPLOAD FOTO
    // ===============================
    $namaFile = $_FILES['foto']['name'];
    $tmpFile  = $_FILES['foto']['tmp_name'];
    $ukuran   = $_FILES['foto']['size'];

    $extValid = ['jpg','jpeg','png'];
    $ext      = strtolower(pathinfo($namaFile, PATHINFO_EXTENSION));

    // validasi ekstensi
    if (!in_array($ext, $extValid)) {
        echo "<script>
            alert('Format foto harus JPG, JPEG, atau PNG');
            window.history.back();
        </script>";
        exit;
    }

    // validasi ukuran max 2MB
    if ($ukuran > 2 * 1024 * 1024) {
        echo "<script>
            alert('Ukuran foto maksimal 2 MB');
            window.history.back();
        </script>";
        exit;
    }

    // nama file baru (aman & unik)
    $namaBaru = uniqid('buku_') . '.' . $ext;
    $folderTujuan = '../../assets/img/' . $namaBaru;

    move_uploaded_file($tmpFile, $folderTujuan);

    // ===============================
    // SIMPAN DATA BUKU
    // ===============================

    $kategori_id = $_POST['kategori_id']; // (BARU)

    mysqli_query($conn, "
        INSERT INTO buku (
            kode_buku, 
            judul, 
            pengarang, 
            penerbit, 
            tahun, 
            kategori_id,  -- (BARU)
            stok, 
            foto
        )
        VALUES (
            '$_POST[kode]',
            '$_POST[judul]',
            '$_POST[pengarang]',
            '$_POST[penerbit]',
            '$_POST[tahun]',
            '$kategori_id', -- (BARU)
            '$_POST[stok]',
            '$namaBaru'
        )
    ");

    header("Location: buku.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Tambah Buku | Petugas</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<style>
body { background:#f8f9fa; }
.sidebar {
    width:240px; min-height:100vh; background:#fff;
}
.sidebar a {
    padding:12px 16px; display:block;
    text-decoration:none; color:#212529;
    border-radius:8px;
}
.sidebar a:hover, .sidebar .active {
    background:#8B0000; color:#fff;
}
.card { border-radius:14px; }
</style>
</head>

<body>
<div class="d-flex">

<!-- SIDEBAR -->
<?php include 'layout/sidebar_petugas.php'; ?>

<!-- CONTENT -->
<div class="flex-grow-1 p-4">

<h4 class="mb-4 text-danger">
    <i class="bi bi-plus-circle"></i> Tambah Buku
</h4>

<div class="card shadow-sm border-0 col-md-6">
<div class="card-body">

<form method="post" enctype="multipart/form-data">

    <input name="kode" class="form-control mb-3" placeholder="Kode Buku" required>
    <input name="judul" class="form-control mb-3" placeholder="Judul Buku" required>
    <input name="pengarang" class="form-control mb-3" placeholder="Pengarang" required>
    <input name="penerbit" class="form-control mb-3" placeholder="Penerbit" required>
    <input name="tahun" type="number" class="form-control mb-3" placeholder="Tahun" required>

    <!-- KATEGORI BUKU (DROPDOWN) -->
    <div class="mb-3">
        <label class="form-label">Kategori Buku</label>
        <select name="kategori_id" class="form-select" required>
            <option value="">-- Pilih Kategori --</option>

            <?php
            $kategori = mysqli_query($conn, "SELECT * FROM kategori ORDER BY id ASC");
            while ($k = mysqli_fetch_assoc($kategori)):
            ?>
                <option value="<?= $k['id']; ?>">
                    <?= htmlspecialchars($k['nama_kategori']); ?>
                </option>
            <?php endwhile; ?>

        </select>
    </div>

    <input name="stok" type="number" class="form-control mb-3" placeholder="Stok" required>

    <!-- INPUT FOTO BUKU -->
    <input type="file" name="foto" class="form-control mb-4" accept="image/*" required>

    <button name="simpan" class="btn btn-danger">
        <i class="bi bi-save"></i> Simpan
    </button>
    <a href="buku.php" class="btn btn-secondary ms-2">Kembali</a>

</form>

</div>
</div>

</div>
</div>
</body>
</html>
