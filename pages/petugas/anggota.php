<?php
require_once '../../includes/auth_check.php';
require_once '../../config/database.php';
cekRole('petugas');

/* =================================================
   PROSES AKTIF / NONAKTIF ANGGOTA
   ================================================= */
if (isset($_GET['id'], $_GET['status'])) {

    $id = (int) $_GET['id'];

    if ($_GET['status'] === 'aktif') {
        $status = 'aktif';
    } elseif ($_GET['status'] === 'nonaktif') {
        $status = 'nonaktif';
    } else {
        header("Location: anggota.php");
        exit;
    }

    mysqli_query(
        $conn,
        "UPDATE users 
         SET status='$status' 
         WHERE id='$id' AND role='mahasiswa'"
    );

    header("Location: anggota.php");
    exit;
}

/* =================================================
   DATA ANGGOTA
   ================================================= */
$anggota = mysqli_query($conn, "
    SELECT id, npm, nama, status
    FROM users
    WHERE role='mahasiswa'
    ORDER BY nama ASC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Data Anggota | Petugas</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

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

    <!-- SIDEBAR -->
    <?php include 'layout/sidebar_petugas.php'; ?>
    <!-- CONTENT -->
    <div class="flex-grow-1 p-4">

        <h4 class="mb-4 text-danger"><i class="bi bi-people"></i> Data Anggota</h4>

        <div class="card shadow-sm border-0">
            <div class="card-body">

                <table class="table table-striped align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>NPM</th>
                            <th>Nama</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>

                    <?php if (mysqli_num_rows($anggota) > 0): ?>
                        <?php $no = 1; ?>
                        <?php while ($row = mysqli_fetch_assoc($anggota)): ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><?= htmlspecialchars($row['npm']); ?></td>
                                <td><?= htmlspecialchars($row['nama']); ?></td>
                                <td>
                                    <?php if ($row['status'] === 'aktif'): ?>
                                        <span class="badge bg-success">Aktif</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Nonaktif</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">

                                    <?php if ($row['status'] === 'aktif'): ?>
                                        <button
                                            class="btn btn-sm btn-warning"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalNonaktif<?= $row['id']; ?>">
                                            <i class="bi bi-person-x"></i>
                                        </button>
                                    <?php else: ?>
                                        <button
                                            class="btn btn-sm btn-success"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalAktif<?= $row['id']; ?>">
                                            <i class="bi bi-person-check"></i>
                                        </button>
                                    <?php endif; ?>

                                </td>
                            </tr>

                            <!-- MODAL NONAKTIF -->
                            <div class="modal fade" id="modalNonaktif<?= $row['id']; ?>" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Nonaktifkan Anggota</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            Apakah Anda yakin ingin <strong>menonaktifkan</strong> anggota
                                            <strong><?= htmlspecialchars($row['nama']); ?></strong>?
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <a href="anggota.php?id=<?= $row['id']; ?>&status=nonaktif"
                                               class="btn btn-warning">
                                                Ya, Nonaktifkan
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- MODAL AKTIF -->
                            <div class="modal fade" id="modalAktif<?= $row['id']; ?>" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Aktifkan Anggota</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            Aktifkan kembali anggota
                                            <strong><?= htmlspecialchars($row['nama']); ?></strong>?
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <a href="anggota.php?id=<?= $row['id']; ?>&status=aktif"
                                               class="btn btn-success">
                                                Ya, Aktifkan
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted">
                                Data anggota belum tersedia
                            </td>
                        </tr>
                    <?php endif; ?>

                    </tbody>
                </table>

            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
