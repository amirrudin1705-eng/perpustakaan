<?php
require_once '../../includes/auth_check.php';
require_once '../../config/database.php';

cekRole('mahasiswa');

/* =========================
   AMBIL PARAMETER
   ========================= */
$keyword     = isset($_GET['keyword']) ? $_GET['keyword'] : '';
$kategori_id = isset($_GET['kategori']) ? $_GET['kategori'] : '';

/* =========================
   DATA KATEGORI (URUT ID)
   ========================= */
$kategori = mysqli_query(
    $conn,
    "SELECT * FROM kategori ORDER BY id ASC"
);

/* =========================
   DATA BUKU (KEYWORD + KATEGORI)
   ========================= */
$sql = "
    SELECT * FROM buku
    WHERE (judul LIKE '%$keyword%'
           OR pengarang LIKE '%$keyword%')
";

if (!empty($kategori_id)) {
    $sql .= " AND kategori_id = '$kategori_id'";
}

$sql .= " ORDER BY judul ASC";

$query = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Katalog Buku</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<style>
body { 
    background:#f8f9fa; 
}

/* SIDEBAR */
.sidebar {
    width: 240px;
    min-height: 100vh;
    background: #ffffff;
    flex-shrink: 0;
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

/* BOOK CARD */
.book-card {
    border-radius: 14px;
    transition: .2s;
}
.book-card:hover {
    transform: translateY(-4px);
}

.book-cover {
    height: 220px;
    object-fit: cover;
    border-radius: 12px 12px 0 0;
}

/* ======================
   KATEGORI
   ====================== */
.kategori-wrapper {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-bottom: 24px;
}

.kategori-btn {
    border: 1px solid #dee2e6;
    background: #ffffff;
    color: #333;
    border-radius: 20px;
    padding: 6px 14px;
    font-size: 14px;
    text-decoration: none;
    transition: .2s;
    white-space: nowrap;
}

.kategori-btn:hover {
    background: #f1f1f1;
}

.kategori-btn.active {
    background: #8B0000;
    color: #fff;
    border-color: #8B0000;
}
</style>
</head>

<body>

<div class="d-flex">

    <!-- SIDEBAR -->
    <?php include 'layout/sidebar_user.php'; ?>

    <!-- CONTENT -->
    <div class="flex-grow-1 p-4">

        <h4 class="mb-4">
            <i class="bi bi-book text-danger"></i> Katalog Buku
        </h4>

        <!-- SEARCH -->
        <form class="mb-3">
            <div class="input-group">
                <input type="text" name="keyword" class="form-control"
                       placeholder="Cari judul atau pengarang..."
                       value="<?= htmlspecialchars($keyword); ?>">
                <button class="btn btn-danger">
                    <i class="bi bi-search"></i>
                </button>
            </div>
        </form>

        <!-- KATEGORI -->
        <div class="kategori-wrapper">
            <a href="katalog.php?keyword=<?= urlencode($keyword); ?>"
               class="kategori-btn <?= empty($kategori_id) ? 'active' : ''; ?>">
                Semua
            </a>

            <?php while ($k = mysqli_fetch_assoc($kategori)): ?>
                <a href="katalog.php?kategori=<?= $k['id']; ?>&keyword=<?= urlencode($keyword); ?>"
                   class="kategori-btn <?= ($kategori_id == $k['id']) ? 'active' : ''; ?>">
                    <?= htmlspecialchars($k['nama_kategori']); ?>
                </a>
            <?php endwhile; ?>
        </div>

        <!-- GRID BUKU -->
        <div class="row g-4">

        <?php if (mysqli_num_rows($query) > 0): ?>
            <?php while ($buku = mysqli_fetch_assoc($query)): ?>
                <div class="col-md-3 col-sm-6">
                    <div class="card book-card shadow-sm border-0">

                        <img src="../../assets/img/<?= !empty($buku['foto']) ? $buku['foto'] : 'default.jpg'; ?>"
                             class="book-cover"
                             alt="Cover Buku">

                        <div class="card-body">
                            <h6 class="mb-1"><?= htmlspecialchars($buku['judul']); ?></h6>
                            <small class="text-muted"><?= htmlspecialchars($buku['pengarang']); ?></small>

                            <div class="mt-2">
                                <?php if ($buku['stok'] > 0): ?>
                                    <span class="badge bg-success">Tersedia</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Habis</span>
                                <?php endif; ?>
                            </div>
                        </div>

                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="text-muted">Buku tidak ditemukan.</p>
        <?php endif; ?>

        </div>

    </div>
</div>

</body>
</html>
