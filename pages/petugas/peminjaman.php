<?php
require_once '../../includes/auth_check.php';
require_once '../../config/database.php';
require_once '../../includes/denda_helpers.php';

cekRole('petugas');

/* =========================
   HELPER FORMAT TANGGAL (INDONESIA)
   ========================= */
function formatTanggalIndo($tanggal) {
    if (!$tanggal) return '-';

    $bulan = [
        'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];

    $tgl = date('j', strtotime($tanggal));
    $bln = $bulan[(int)date('m', strtotime($tanggal)) - 1];
    $thn = date('Y', strtotime($tanggal));

    return "$tgl $bln $thn";
}

/* =========================
   DATA PEMINJAMAN
   ========================= */
$data = mysqli_query($conn,"
    SELECT 
        p.id,
        p.user_id,
        p.buku_id,
        p.status,
        p.tanggal_pinjam,
        p.tanggal_jatuh_tempo,
        p.tanggal_kembali,
        p.tanggal_jadwal,
        p.jam_jadwal,
        u.nama AS mahasiswa,
        b.judul AS buku
    FROM peminjaman p
    JOIN users u ON p.user_id = u.id
    JOIN buku b ON p.buku_id = b.id
    ORDER BY p.id DESC
");

/* =========================
   TOLAK PEMINJAMAN (INLINE)
   ========================= */
if (isset($_GET['tolak'])) {

    $id = (int) $_GET['tolak'];

    $p = mysqli_fetch_assoc(
        mysqli_query($conn,"SELECT status FROM peminjaman WHERE id='$id'")
    );

    // hanya boleh menolak jika status menunggu
    if ($p && $p['status'] === 'menunggu') {
        mysqli_query($conn,"
            UPDATE peminjaman 
            SET status='ditolak'
            WHERE id='$id'
        ");
    }

    header("Location: peminjaman.php");
    exit;
}

/* =========================
   SERAHKAN BUKU (VALIDASI FISIK)
   ========================= */
if (isset($_GET['serahkan'])) {

    $id = (int) $_GET['serahkan'];

    $p = mysqli_fetch_assoc(
        mysqli_query($conn,"SELECT * FROM peminjaman WHERE id='$id'")
    );

    $lama_pinjam = (int) getPengaturan('lama_pinjam_hari');

    mysqli_query($conn,"
        UPDATE peminjaman 
        SET 
            status = 'dipinjam',
            tanggal_pinjam = CURDATE(),
            tanggal_jatuh_tempo = DATE_ADD(CURDATE(), INTERVAL $lama_pinjam DAY)
        WHERE id = '$id'
    ");

    mysqli_query($conn,"
        UPDATE buku 
        SET stok = stok - 1 
        WHERE id = '{$p['buku_id']}'
    ");

    header("Location: peminjaman.php");
    exit;
}

/* =========================
   KEMBALIKAN BUKU + DENDA
   ========================= */
if (isset($_GET['kembali'])) {

    $id = (int) $_GET['kembali'];
    $p  = mysqli_fetch_assoc(
        mysqli_query($conn,"SELECT * FROM peminjaman WHERE id='$id'")
    );

    if (!empty($p['tanggal_jatuh_tempo'])) {
        $hasil = hitungDenda($p['tanggal_jatuh_tempo']);
    } else {
        $hasil = [
            'hari_telat'  => 0,
            'total_denda' => 0,
            'status'      => 'normal'
        ];
    }

    mysqli_query($conn,"
        UPDATE peminjaman 
        SET 
            status = '".($hasil['status']=='hilang'?'hilang':'dikembalikan')."',
            tanggal_kembali = CURDATE()
        WHERE id='$id'
    ");

    if ($hasil['status'] !== 'hilang') {
        mysqli_query($conn,"
            UPDATE buku 
            SET stok = stok + 1 
            WHERE id='{$p['buku_id']}'
        ");
    }

    if ($hasil['hari_telat'] > 0) {
        mysqli_query($conn,"
            INSERT INTO denda (peminjaman_id, hari_telat, total_denda)
            VALUES ('$id', '{$hasil['hari_telat']}', '{$hasil['total_denda']}')
        ");
    }

    header("Location: peminjaman.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Peminjaman | Petugas</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<style>
body { background:#f8f9fa; }
.sidebar { width:240px; min-height:100vh; background:#fff; }
.sidebar a { padding:12px 16px; display:block; text-decoration:none; color:#212529; border-radius:8px; }
.sidebar a:hover,.sidebar .active { background:#8B0000; color:#fff; }
.card { border-radius:14px; }
</style>
</head>

<body>

<div class="d-flex">
<?php include 'layout/sidebar_petugas.php'; ?>

<div class="flex-grow-1 p-4">

<h4 class="mb-4">
    <i class="bi bi-arrow-left-right text-danger"></i>
    Data Peminjaman
</h4>

<div class="card shadow-sm border-0">
<div class="card-body">

<table class="table table-striped align-middle">
<thead class="table-light">
<tr>
    <th>Mahasiswa</th>
    <th>Buku</th>
    <th>Jatuh Tempo</th>
    <th>Telat</th>
    <th>Denda</th>
    <th>Status</th>
    <th class="text-center">Aksi</th>
</tr>
</thead>
<tbody>

<?php while ($row = mysqli_fetch_assoc($data)): ?>

<?php
if (!empty($row['tanggal_jatuh_tempo'])) {
    $hasil = hitungDenda($row['tanggal_jatuh_tempo']);
} else {
    $hasil = ['hari_telat'=>0,'total_denda'=>0];
}
?>

<tr>
<td><?= htmlspecialchars($row['mahasiswa']); ?></td>
<td><?= htmlspecialchars($row['buku']); ?></td>
<td><?= formatTanggalIndo($row['tanggal_jatuh_tempo']); ?></td>
<td><?= $hasil['hari_telat']; ?> hari</td>
<td>Rp <?= number_format($hasil['total_denda'],0,',','.'); ?></td>

<td>
<?php
switch ($row['status']) {
    case 'menunggu':
        echo '<span class="badge bg-warning text-dark">Menunggu</span>';
        break;

    case 'dijadwalkan':
        echo '<span class="badge bg-info text-dark">Dijadwalkan</span>';
        break;

    case 'dipinjam':
        echo '<span class="badge bg-primary">Dipinjam</span>';
        break;

    case 'dikembalikan':
        echo '<span class="badge bg-success">Dikembalikan</span>';
        break;

    case 'ditolak':
        echo '<span class="badge bg-danger">Ditolak</span>';
        break;

    case 'hilang':
        echo '<span class="badge bg-dark">Hilang</span>';
        break;

    default:
        echo '<span class="badge bg-secondary">'.$row['status'].'</span>';
}
?>
</td>


<td class="text-center">

<?php if ($row['status'] == 'menunggu'): ?>
    <!-- JADWALKAN -->
    <a href="jadwalkan.php?id=<?= $row['id']; ?>" 
       class="btn btn-primary btn-sm me-1">
        <i class="bi bi-calendar-check"></i>
    </a>

    <!-- TOLAK -->
    <a href="?tolak=<?= $row['id']; ?>" 
       class="btn btn-danger btn-sm"
       onclick="return confirm('Yakin ingin menolak peminjaman ini?')">
        <i class="bi bi-x-circle"></i>
    </a>

<?php elseif ($row['status'] == 'dijadwalkan'): ?>
    <a href="?serahkan=<?= $row['id']; ?>" 
       class="btn btn-success btn-sm"
       onclick="return confirm('Yakin buku sudah diserahkan?')">
        <i class="bi bi-box-arrow-right"></i>
    </a>

<?php elseif ($row['status'] == 'dipinjam'): ?>
    <a href="?kembali=<?= $row['id']; ?>" 
       class="btn btn-warning btn-sm">
        <i class="bi bi-arrow-return-left"></i>
    </a>

<?php else: ?>
    -
<?php endif; ?>

</td>

</tr>

<?php endwhile; ?>

</tbody>
</table>

</div>
</div>

</div>
</div>
</body>
</html>
