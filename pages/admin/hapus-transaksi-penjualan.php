<?php
session_start();

// Memastikan user sudah login
if (!isset($_SESSION['hak_akses']) || $_SESSION['hak_akses'] == "") {
    header("location: ../../index.php?pesan=gagal");
    exit;
}

include '../../koneksi.php';

// Mendapatkan ID transaksi dari query string
$id_transaksi = isset($_GET['id']) ? $_GET['id'] : '';

// Memastikan ID transaksi valid
if (empty($id_transaksi)) {
    header("location: halaman_error.php?pesan=transaksi_tidak_ditemukan");
    exit;
}

// Menghapus detail transaksi
$query_hapus_detail = "DELETE FROM detail_transaksi_penjualan WHERE id_transaksi = ?";
$stmt_hapus_detail = mysqli_prepare($koneksi, $query_hapus_detail);
if ($stmt_hapus_detail) {
    mysqli_stmt_bind_param($stmt_hapus_detail, 's', $id_transaksi);
    mysqli_stmt_execute($stmt_hapus_detail);
    mysqli_stmt_close($stmt_hapus_detail);
} else {
    die('Error preparing statement for deleting detail transaksi: ' . mysqli_error($koneksi));
}

// Menghapus transaksi
$query_hapus_transaksi = "DELETE FROM transaksi_penjualan WHERE id_transaksi = ?";
$stmt_hapus_transaksi = mysqli_prepare($koneksi, $query_hapus_transaksi);
if ($stmt_hapus_transaksi) {
    mysqli_stmt_bind_param($stmt_hapus_transaksi, 's', $id_transaksi);
    mysqli_stmt_execute($stmt_hapus_transaksi);
    mysqli_stmt_close($stmt_hapus_transaksi);
} else {
    die('Error preparing statement for deleting transaksi: ' . mysqli_error($koneksi));
}

// Redirect atau pesan sukses
header("location: kelola-transaksi-penjualan.php?pesan=hapus_sukses");
exit;
