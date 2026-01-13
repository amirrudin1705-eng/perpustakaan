<?php
require_once '../../includes/auth_check.php';
require_once '../../config/database.php';

cekRole('petugas');

/* =========================
   VALIDASI PARAMETER ID
   ========================= */
if (!isset($_GET['id'])) {
    header("Location: peminjaman.php");
    exit;
}

$id_peminjaman = (int) $_GET['id'];

/* =========================
   AMBIL DATA PEMINJAMAN
   ========================= */
$peminjaman = mysqli_fetch_assoc(
    mysqli_query($conn, "
        SELECT 
            p.id,
            p.status,
            p.user_id,
            p.buku_id,
            u.nama AS mahasiswa,
            b.judul AS buku
        FROM peminjaman p
        JOIN users u ON p.user_id = u.id
        JOIN buku b ON p.buku_id = b.id
        WHERE p.id = '$id_peminjaman'
        LIMIT 1
    ")
);

if (!$peminjaman) {
    header("Location: peminjaman.php");
    exit;
}

/* =========================
   CEK STATUS (HARUS MENUNGGU)
   ========================= */
if ($peminjaman['status'] !== 'menunggu') {
    header("Location: peminjaman.php");
    exit;
}

/* =========================
   SIMPAN JADWAL (POST)
   ========================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $tanggal_jadwal = $_POST['tanggal_jadwal'] ?? '';
    $jam_jadwal     = $_POST['jam_jadwal'] ?? '';

    // validasi sederhana
    if (empty($tanggal_jadwal) || empty($jam_jadwal)) {
        echo "<script>
            alert('Tanggal dan jam wajib diisi');
            window.history.back();
        </script>";
        exit;
    }

    // simpan jadwal + ubah status
    mysqli_query($conn, "
        UPDATE peminjaman
        SET
            status = 'dijadwalkan',
            tanggal_jadwal = '$tanggal_jadwal',
            jam_jadwal = '$jam_jadwal',
            petugas_id = '{$_SESSION['user_id']}'
        WHERE id = '$id_peminjaman'
    ");

    header("Location: peminjaman.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Jadwalkan Peminjaman | Petugas</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

<style>
body {
    font-family: 'Inter', system-ui, sans-serif;
    background:#f8f9fa;
}

/* CARD */
.card {
    border-radius: 14px;
}

/* SIDEBAR (SAMA DENGAN PEMINJAMAN) */
.sidebar {
    width: 240px;
    min-height: 100vh;
    background: #ffffff;
}
.sidebar a {
    padding: 12px 16px;
    display: flex;
    align-items: center;
    gap: 8px;
    text-decoration: none;
    color: #212529;
    border-radius: 8px;
}
.sidebar a:hover,
.sidebar .active {
    background: #8B0000;
    color: #fff;
}
</style>
</head>

<body>

<div class="d-flex">

    <!-- SIDEBAR -->
    <?php include 'layout/sidebar_petugas.php'; ?>

    <!-- CONTENT -->
    <div class="flex-grow-1 p-4">

        <h4 class="mb-4">
            <i class="bi bi-calendar-check text-danger"></i>
            Jadwalkan Peminjaman
        </h4>

        <div class="card shadow-sm border-0 col-md-6">
            <div class="card-body">

                <!-- INFO PEMINJAMAN -->
                <div class="mb-4">
                    <h6 class="fw-semibold mb-1">Informasi Peminjaman</h6>
                    <small class="text-muted d-block">
                        Mahasiswa : <strong><?= htmlspecialchars($peminjaman['mahasiswa']); ?></strong>
                    </small>
                    <small class="text-muted d-block">
                        Buku : <strong><?= htmlspecialchars($peminjaman['buku']); ?></strong>
                    </small>

                </div>

                <!-- FORM JADWAL -->
                <form method="post">

                    <div class="mb-3">
                        <label class="form-label">Tanggal Pertemuan</label>
                        <input type="date" name="tanggal_jadwal" class="form-control" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Jam Pertemuan</label>
                        <input type="time" name="jam_jadwal" class="form-control" required>
                    </div>

                    <button class="btn btn-success">
                        <i class="bi bi-save"></i> Simpan Jadwal
                    </button>

                    <a href="peminjaman.php" class="btn btn-secondary ms-2">
                        Kembali
                    </a>

                </form>

            </div>
        </div>

    </div>
</div>

</body>
</html>
