<?php
session_start();
require_once '../../config/database.php';

if (isset($_POST['login'])) {
    $npm      = mysqli_real_escape_string($conn, $_POST['npm']);
    $password = md5($_POST['password']);

    $query = mysqli_query($conn, "
        SELECT * FROM users 
        WHERE npm='$npm' 
        AND password='$password' 
        AND status='aktif'
    ");

    if (mysqli_num_rows($query) === 1) {
        $user = mysqli_fetch_assoc($query);

        $_SESSION['login'] = true;
        $_SESSION['id']    = $user['id'];
        $_SESSION['nama']  = $user['nama'];
        $_SESSION['role']  = $user['role'];

        if ($user['role'] === 'petugas') {
            header("Location: ../petugas/dashboard.php");
        } else {
            header("Location: ../mahasiswa/dashboard.php");
        }
        exit;
    } else {
        $error = "NPM atau password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login | Perpustakaan UKRI</title>
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
    <div class="card shadow border-0" style="width: 420px;">
        <div class="card-body p-4">

            <h4 class="text-center mb-1">
                <i class="bi bi-book-half text-ukri"></i>
                Perpustakaan UKRI
            </h4>
            <p class="text-center text-muted mb-4">
                Universitas Kebangsaan Republik Indonesia
            </p>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger text-center">
                    <i class="bi bi-exclamation-triangle"></i>
                    <?= $error ?>
                </div>
            <?php endif; ?>

            <form method="post">
                <div class="mb-3">
                    <label class="form-label">NPM / Username</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-person"></i>
                        </span>
                        <input type="text" name="npm" class="form-control" placeholder="Masukkan NPM" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-lock"></i>
                        </span>
                        <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
                    </div>
                </div>

                <button type="submit" name="login" class="btn btn-ukri w-100 mt-2">
                    <i class="bi bi-box-arrow-in-right me-1"></i>
                    Login
                </button>
            </form>

            <hr>

            <p class="text-center small mb-0">
                Belum punya akun?
                <a href="register.php" class="text-ukri fw-semibold">
                    <i class="bi bi-person-plus"></i> Daftar di sini
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
