<?php
require_once '../../config/database.php';

if (isset($_POST['register'])) {
    $npm      = mysqli_real_escape_string($conn, $_POST['npm']);
    $nama     = mysqli_real_escape_string($conn, $_POST['nama']);
    $password = md5($_POST['password']);
    $role     = 'mahasiswa';

    // Cek NPM sudah terdaftar atau belum
    $cek = mysqli_query($conn, "SELECT * FROM users WHERE npm='$npm'");
    if (mysqli_num_rows($cek) > 0) {
        $error = "NPM sudah terdaftar!";
    } else {
        $insert = mysqli_query($conn, "
            INSERT INTO users (npm, nama, password, role, status)
            VALUES ('$npm', '$nama', '$password', '$role', 'aktif')
        ");

        if ($insert) {
            $success = "Registrasi berhasil! Silakan login.";
        } else {
            $error = "Registrasi gagal. Silakan coba lagi.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Registrasi | Perpustakaan UKRI</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Google Font: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', system-ui, sans-serif;
            background-color: #F8F9FA;
        }
        .card {
            border-radius: 12px;
        }
        .btn-ukri {
            background-color: #FFC107;
            color: #212121;
            font-weight: 600;
        }
        .btn-ukri:hover {
            background-color: #e0a800;
            color: #212121;
        }
        .text-ukri {
            color: #4CAF50;
        }
        .input-group-text {
            background-color: #fff;
        }
    </style>
</head>

<body>

<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="card shadow border-0" style="width: 450px;">
        <div class="card-body p-4">

            <h4 class="text-center mb-1">
                <i class="bi bi-person-plus-fill text-ukri"></i>
                Registrasi Mahasiswa
            </h4>
            <p class="text-center text-muted mb-4">
                Perpustakaan UKRI
            </p>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger text-center">
                    <i class="bi bi-exclamation-triangle"></i>
                    <?= $error ?>
                </div>
            <?php endif; ?>

            <?php if (isset($success)): ?>
                <div class="alert alert-success text-center">
                    <i class="bi bi-check-circle"></i>
                    <?= $success ?>
                </div>
            <?php endif; ?>

            <form method="post">
                <div class="mb-3">
                    <label class="form-label">NPM</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-hash"></i>
                        </span>
                        <input type="text" name="npm" class="form-control" placeholder="Masukkan NPM" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nama Lengkap</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-person"></i>
                        </span>
                        <input type="text" name="nama" class="form-control" placeholder="Masukkan nama lengkap" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-lock"></i>
                        </span>
                        <input type="password" name="password" class="form-control" placeholder="Buat password" required>
                    </div>
                </div>

                <button type="submit" name="register" class="btn btn-ukri w-100 mt-2">
                    <i class="bi bi-person-check me-1"></i>
                    Daftar
                </button>
            </form>

            <hr>

            <p class="text-center small mb-0">
                Sudah punya akun?
                <a href="login.php" class="text-ukri fw-semibold">
                    <i class="bi bi-box-arrow-in-right"></i> Login
                </a>
            </p>

            <p class="text-center small text-muted mt-2 mb-0">
                © <?= date('Y') ?> UKRI Bandung
            </p>

        </div>
    </div>
</div>

</body>
</html>
