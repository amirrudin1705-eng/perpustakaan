<?php
require_once '../../includes/auth_check.php';
require_once '../../config/database.php';
cekRole('petugas');

$id = $_GET['id'];
mysqli_query($conn, "DELETE FROM buku WHERE id='$id'");
header("Location: buku.php");
