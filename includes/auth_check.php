<?php
session_start();

function cekLogin() {
    if (!isset($_SESSION['login'])) {
        header("Location: ../auth/login.php");
        exit;
    }
}

function cekRole($role) {
    cekLogin();
    if ($_SESSION['role'] != $role) {
        echo "Akses ditolak!";
        exit;
    }
}
