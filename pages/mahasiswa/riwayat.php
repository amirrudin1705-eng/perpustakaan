<?php
require_once '../../includes/auth_check.php';
require_once '../../config/database.php';
require_once '../../includes/denda_helpers.php';
cekRole('mahasiswa');

$user_id = $_SESSION['user_id'];

/* =========================
   AMBIL DATA RIWAYAT
   ========================= */
$data = mysqli_query($conn, "
    SELECT 
        p.id,
        p.tanggal_pinjam,
        p.tanggal_jatuh_tempo,
        p.tanggal_kembali,
        p.tanggal_jadwal,
        p.jam_jadwal,
        p.status,
        b.kode_buku,
        b.judul,
        b.pengarang
    FROM peminjaman p
    JOIN buku b ON p.buku_id = b.id
    WHERE p.user_id = '$user_id'
    ORDER BY p.id DESC
");

/* =========================
   HELPER FORMAT TANGGAL (INDO)
   ========================= */
function formatTanggalIndo($tanggal) {
    if (!$tanggal || $tanggal == '0000-00-00') return '-';

    $bulan = [
        'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];

    $tgl = date('j', strtotime($tanggal));
    $bln = $bulan[(int)date('m', strtotime($tanggal)) - 1];
    $thn = date('Y', strtotime($tanggal));

    return "$tgl $bln $thn";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Riwayat Peminjaman | Mahasiswa</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

<style>
body { background:#f8f9fa; }
.sidebar { width:240px; min-height:100vh; background:#fff; flex-shrink:0; }
.sidebar a { padding:12px 16px; display:block; text-decoration:none; color:#2b2b2b; border-radius:8px; transition:.2s; }
.sidebar a:hover,.sidebar .active { background:#8B0000; color:#fff; }
.card { border-radius:14px; }
</style>
</head>

<body>

<div class="d-flex">

    <!-- SIDEBAR -->
    <?php include 'layout/sidebar_user.php'; ?>

    <!-- CONTENT -->
    <div class="flex-grow-1 p-4">

        <h4 class="mb-4">
            <i class="bi bi-clock-history text-danger"></i>
            Riwayat Peminjaman
        </h4>

        <div class="card shadow-sm border-0">
            <div class="card-body">

                <table class="table table-striped align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Kode</th>
                            <th>Judul</th>
                            <th>Pengarang</th>
                            <th>Tgl Pinjam</th>
                            <th>Jatuh Tempo</th>
                            <th>Tgl Kembali</th>
                            <th>Telat</th>
                            <th>Denda</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>

                    <?php if (mysqli_num_rows($data) > 0): ?>
                        <?php $no = 1; ?>
                        <?php while ($row = mysqli_fetch_assoc($data)): ?>

                        <?php
                        /* =========================
                           STATUS OTOMATIS TERLAMBAT
                           ========================= */
                        $status_tampil = $row['status'];

                        if (
                            $row['status'] === 'dipinjam' &&
                            !empty($row['tanggal_jatuh_tempo']) &&
                            strtotime(date('Y-m-d')) > strtotime($row['tanggal_jatuh_tempo'])
                        ) {
                            $status_tampil = 'terlambat';
                        }

                        /* =========================
                           HITUNG DENDA
                           ========================= */
                        $hasil_denda = hitungDenda($row['tanggal_jatuh_tempo']);
                        ?>

                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= htmlspecialchars($row['kode_buku']); ?></td>
                            <td><?= htmlspecialchars($row['judul']); ?></td>
                            <td><?= htmlspecialchars($row['pengarang']); ?></td>
                            <td><?= formatTanggalIndo($row['tanggal_pinjam']); ?></td>
                            <td><?= formatTanggalIndo($row['tanggal_jatuh_tempo']); ?></td>
                            <td><?= formatTanggalIndo($row['tanggal_kembali']); ?></td>
                            <td><?= $hasil_denda['hari_telat']; ?> hari</td>
                            <td>Rp <?= number_format($hasil_denda['total_denda'],0,',','.'); ?></td>
                            <td>
                                <?php
                                switch ($status_tampil) {
                                    case 'menunggu':
                                        echo '<span class="badge bg-warning text-dark">Menunggu</span>';
                                        break;
                                    case 'dijadwalkan':
                                        echo '<span class="badge bg-info text-dark">Dijadwalkan</span>';
                                        break;
                                    case 'dipinjam':
                                        echo '<span class="badge bg-primary">Dipinjam</span>';
                                        break;
                                    case 'terlambat':
                                        echo '<span class="badge bg-danger">Terlambat</span>';
                                        break;
                                    case 'dikembalikan':
                                        echo '<span class="badge bg-success">Dikembalikan</span>';
                                        break;
                                    case 'ditolak':
                                        echo '<span class="badge bg-secondary">Ditolak</span>';
                                        break;
                                }
                                ?>
                            </td>
                        </tr>

                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="10" class="text-center text-muted">
                                Belum ada riwayat peminjaman
                            </td>
                        </tr>
                    <?php endif; ?>

                    </tbody>
                </table>

            </div>
        </div>

    </div>
</div>

</body>
</html>
