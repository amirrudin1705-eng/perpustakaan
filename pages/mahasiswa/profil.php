<?php
require_once '../../includes/auth_check.php';
require_once '../../config/database.php';
cekRole('mahasiswa');

$id = $_SESSION['user_id'];

/* =========================
   AMBIL DATA USER
   ========================= */
$user = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT npm, nama, alamat, whatsapp FROM users WHERE id='$id'")
);

/* =========================
   UPDATE PROFIL
   ========================= */
if (isset($_POST['simpan'])) {

    $alamat   = mysqli_real_escape_string($conn, $_POST['alamat']);
    $whatsapp = mysqli_real_escape_string($conn, $_POST['whatsapp']);

    mysqli_query($conn, "
        UPDATE users SET
            alamat='$alamat',
            whatsapp='$whatsapp'
        WHERE id='$id'
    ");

    header("Location: profil.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Profil Mahasiswa</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<style>
body { background:#f8f9fa; }

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
    color: #2b2b2b;
    border-radius: 8px;
    transition: .2s;
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
    <?php include 'layout/sidebar_user.php'; ?>
    <!-- CONTENT -->
    <div class="flex-grow-1 p-4">

        <h4 class="mb-4 text-danger">
            <i class="bi bi-person-circle"></i> Profil Saya
        </h4>

        <div class="card shadow-sm border-0 col-md-6">
            <div class="card-body">

                <form method="post">

                    <div class="mb-3">
                        <label class="form-label">NPM</label>
                        <input type="text" class="form-control"
                               value="<?= htmlspecialchars($user['npm']); ?>" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nama</label>
                        <input type="text" class="form-control"
                               value="<?= htmlspecialchars($user['nama']); ?>" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Alamat</label>
                        <textarea name="alamat" class="form-control" rows="3"
                                  placeholder="Masukkan alamat"><?= htmlspecialchars($user['alamat']); ?></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">No WhatsApp</label>
                        <input type="text" name="whatsapp" class="form-control"
                               placeholder="08xxxxxxxxxx"
                               value="<?= htmlspecialchars($user['whatsapp']); ?>">
                    </div>

                    <button name="simpan" class="btn btn-danger">
                        <i class="bi bi-save"></i> Simpan Perubahan
                    </button>

                </form>

            </div>
        </div>

    </div>
</div>

</body>
</html>
