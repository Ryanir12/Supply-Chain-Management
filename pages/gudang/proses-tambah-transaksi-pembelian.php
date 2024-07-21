<?php
include "../../koneksi.php";
session_start();

// Ambil data dari form
$id_supplier = $_POST['id_supplier'];
$tanggal = $_POST['tanggal'];
$id_produk = $_POST['id_produk'];
$jumlah_beli = $_POST['jumlah_beli'];
$harga_satuan = $_POST['harga_satuan'];

// Mulai transaksi
mysqli_begin_transaction($koneksi);

try {
    // Hitung total harga pembelian
    $total_harga = 0;
    for ($i = 0; $i < count($id_produk); $i++) {
        $total_harga += $jumlah_beli[$i] * $harga_satuan[$i];
    }

    // Simpan data ke tabel transaksi_pembelian
    $query = "INSERT INTO transaksi_pembelian (id_supplier, total_harga, tanggal) VALUES ('$id_supplier', '$total_harga', '$tanggal')";
    if (!mysqli_query($koneksi, $query)) {
        throw new Exception(mysqli_error($koneksi));
    }

    // Ambil ID transaksi terakhir yang baru saja dimasukkan
    $id_transaksi = mysqli_insert_id($koneksi);

    // Simpan data ke tabel detail_transaksi_pembelian
    for ($i = 0; $i < count($id_produk); $i++) {
        $query = "INSERT INTO detail_transaksi_pembelian (id_transaksi, id_produk, jumlah_beli, harga_satuan) VALUES ('$id_transaksi', '$id_produk[$i]', '$jumlah_beli[$i]', '$harga_satuan[$i]')";
        if (!mysqli_query($koneksi, $query)) {
            throw new Exception(mysqli_error($koneksi));
        }
    }

    // Commit transaksi
    mysqli_commit($koneksi);

    header("location: kelola-transaksi-pembelian.php");
} catch (Exception $e) {
    // Rollback transaksi jika terjadi kesalahan
    mysqli_rollback($koneksi);
    die('Error: ' . $e->getMessage());
}
