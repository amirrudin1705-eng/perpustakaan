<?php
require_once '../../includes/auth_check.php';
require_once '../../config/database.php';
cekRole('petugas');

/* =========================
   HELPER STATUS (FIX)
   ========================= */
function statusLabel($status, $mode = 'text') {
    $map = [
        'menunggu'     => ['Menunggu', 'bg-warning text-dark'],
        'dijadwalkan'  => ['Dijadwalkan', 'bg-info'],
        'dipinjam'     => ['Dipinjam', 'bg-primary'],
        'dikembalikan' => ['Dikembalikan', 'bg-success'],
        'ditolak'      => ['Ditolak', 'bg-danger'],
    ];

    if (!isset($map[$status])) {
        return $mode === 'badge'
            ? '<span class="badge bg-secondary">'.htmlspecialchars($status).'</span>'
            : ucfirst($status);
    }

    return $mode === 'badge'
        ? '<span class="badge '.$map[$status][1].'">'.$map[$status][0].'</span>'
        : $map[$status][0];
}

/* =========================
   DOMPDF
   ========================= */
require_once __DIR__ . '/../../libs/dompdf/autoload.inc.php';

/* =========================
   CETAK LAPORAN (PDF)
   ========================= */
if (isset($_GET['cetak'])) {

    $laporan = mysqli_query($conn, "
        SELECT p.*, 
               u.nama AS mahasiswa,
               b.kode_buku, b.judul
        FROM peminjaman p
        JOIN users u ON p.user_id = u.id
        JOIN buku b ON p.buku_id = b.id
        ORDER BY p.id DESC
    ");

    $html = '
    <!DOCTYPE html>
    <html>
    <head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        h2 { text-align: center; margin-bottom: 4px; }
        p { text-align: center; margin-top: 0; font-size: 11px; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        table, th, td { border: 1px solid #000; }
        th, td { padding: 6px; text-align: left; }
        th { background: #f0f0f0; }
    </style>
    </head>
    <body>

    <h2>Laporan Transaksi Peminjaman</h2>
    <p>Perpustakaan UKRI</p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Mahasiswa</th>
                <th>Kode Buku</th>
                <th>Judul Buku</th>
                <th>Tgl Pinjam</th>
                <th>Tgl Kembali</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
    ';

    $no = 1;
    while ($row = mysqli_fetch_assoc($laporan)) {
        $html .= '
        <tr>
            <td>'.$no++.'</td>
            <td>'.htmlspecialchars($row['mahasiswa']).'</td>
            <td>'.htmlspecialchars($row['kode_buku']).'</td>
            <td>'.htmlspecialchars($row['judul']).'</td>
            <td>'.$row['tanggal_pinjam'].'</td>
            <td>'.($row['tanggal_kembali'] ?? '-').'</td>
            <td>'.statusLabel($row['status']).'</td>
        </tr>
        ';
    }

    $html .= '
        </tbody>
    </table>

    </body>
    </html>
    ';

    $dompdf = new \Dompdf\Dompdf();
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'landscape');
    $dompdf->render();
    $dompdf->stream("laporan_peminjaman.pdf", ["Attachment" => false]);
    exit;
}

/* =========================
   DATA LAPORAN (HALAMAN)
   ========================= */
$laporan = mysqli_query($conn, "
    SELECT p.*, 
           u.nama AS mahasiswa,
           b.kode_buku, b.judul
    FROM peminjaman p
    JOIN users u ON p.user_id = u.id
    JOIN buku b ON p.buku_id = b.id
    ORDER BY p.id DESC
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Laporan Peminjaman | Petugas</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

<style>
body {
    font-family: 'Inter', system-ui, sans-serif;
    background:#f8f9fa;
}
.sidebar {
    width: 240px;
    min-height: 100vh;
    background: #ffffff;
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
.card {
    border-radius: 14px;
}
</style>
</head>

<body>

<div class="d-flex">
    <?php include 'layout/sidebar_petugas.php'; ?>

    <div class="flex-grow-1 p-4">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4>
                <i class="bi bi-file-earmark-text text-danger"></i>
                Laporan Transaksi Peminjaman
            </h4>

            <a href="laporan.php?cetak=1" target="_blank" class="btn btn-danger">
                <i class="bi bi-printer"></i> Cetak Laporan
            </a>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body">

                <table class="table table-striped align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Mahasiswa</th>
                            <th>Kode Buku</th>
                            <th>Judul Buku</th>
                            <th>Tgl Pinjam</th>
                            <th>Tgl Kembali</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>

                    <?php if (mysqli_num_rows($laporan) > 0): ?>
                        <?php $no = 1; ?>
                        <?php while ($row = mysqli_fetch_assoc($laporan)): ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><?= htmlspecialchars($row['mahasiswa']); ?></td>
                                <td><?= htmlspecialchars($row['kode_buku']); ?></td>
                                <td><?= htmlspecialchars($row['judul']); ?></td>
                                <td><?= $row['tanggal_pinjam']; ?></td>
                                <td><?= $row['tanggal_kembali'] ?? '-'; ?></td>
                                <td><?= statusLabel($row['status'], 'badge'); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted">
                                Belum ada data laporan
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
