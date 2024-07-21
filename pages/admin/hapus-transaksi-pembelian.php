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
$query_hapus_detail = "DELETE FROM detail_transaksi_pembelian WHERE id_transaksi = '$id_transaksi'";
$result_hapus_detail = mysqli_query($koneksi, $query_hapus_detail);

if (!$result_hapus_detail) {
    die('Error: ' . mysqli_error($koneksi));
}

// Menghapus transaksi
$query_hapus_transaksi = "DELETE FROM transaksi_pembelian WHERE id_transaksi = '$id_transaksi'";
$result_hapus_transaksi = mysqli_query($koneksi, $query_hapus_transaksi);

if (!$result_hapus_transaksi) {
    die('Error: ' . mysqli_error($koneksi));
}

// Redirect atau pesan sukses
header("location: kelola-transaksi-pembelian.php?pesan=hapus_sukses");
exit;
