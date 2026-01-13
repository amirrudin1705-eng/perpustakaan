<?php

/**
 * Ambil nilai pengaturan dari tabel pengaturan
 */
function getPengaturan($nama) {
    global $conn;

    $q = mysqli_query($conn, "
        SELECT nilai 
        FROM pengaturan 
        WHERE nama_pengaturan = '$nama'
        LIMIT 1
    ");

    $d = mysqli_fetch_assoc($q);
    return $d ? (int)$d['nilai'] : 0;
}

/**
 * Hitung denda keterlambatan (FINAL)
 * - TANPA batas maksimal
 * - TANPA status hilang
 */
function hitungDenda($tanggal_jatuh_tempo) {

    if (empty($tanggal_jatuh_tempo)) {
        return [
            'hari_telat'  => 0,
            'total_denda' => 0,
            'status'      => 'normal'
        ];
    }

    // Hitung hari telat
    $hari_telat = floor(
        (strtotime(date('Y-m-d')) - strtotime($tanggal_jatuh_tempo)) / 86400
    );

    if ($hari_telat < 0) {
        $hari_telat = 0;
    }

    // Ambil denda per hari
    $denda_per_hari = getPengaturan('denda_per_hari');

    // Total denda TANPA BATAS
    $total_denda = $hari_telat * $denda_per_hari;

    // Status
    $status = $hari_telat > 0 ? 'terlambat' : 'normal';

    return [
        'hari_telat'  => $hari_telat,
        'total_denda' => $total_denda,
        'status'      => $status
    ];
}
