<?php
session_start();

// Cek apakah yang mengakses halaman ini sudah login
if (!isset($_SESSION['hak_akses']) || $_SESSION['hak_akses'] == "") {
    header("location: ../../index.php?pesan=gagal");
    exit;
}

// Include koneksi database
include "../../koneksi.php";

// Ambil data dari form
$nama_customer = isset($_POST['nama_customer']) ? $_POST['nama_customer'] : '';
$tanggal = $_POST['tanggal'];
$id_produk = $_POST['id_produk'];
$jumlah_beli = $_POST['jumlah_beli'];
$harga_satuan = $_POST['harga_satuan'];

// Inisialisasi ID customer
$id_customer = null;

// Jika nama customer diisi, masukkan ke database customer
if (!empty($nama_customer)) {
    $query_customer = "INSERT INTO customer (nama_customer) VALUES ('$nama_customer')";
    if (!mysqli_query($koneksi, $query_customer)) {
        die('Error: ' . mysqli_error($koneksi));
    }
    $id_customer = mysqli_insert_id($koneksi);
} else {
    // Jika nama customer kosong, buat ID customer otomatis
    $query_customer = "INSERT INTO customer (nama_customer) VALUES (NULL)";
    if (!mysqli_query($koneksi, $query_customer)) {
        die('Error: ' . mysqli_error($koneksi));
    }
    $id_customer = mysqli_insert_id($koneksi);
}

// Insert transaksi penjualan
$query_transaksi = "INSERT INTO transaksi_penjualan (tanggal, id_customer) VALUES ('$tanggal', '$id_customer')";
if (!mysqli_query($koneksi, $query_transaksi)) {
    die('Error: ' . mysqli_error($koneksi));
}
$id_transaksi = mysqli_insert_id($koneksi);

// Inisialisasi total harga
$total_harga = 0;

// Insert detail transaksi penjualan dan hitung total harga
foreach ($id_produk as $index => $id) {
    $jumlah = $jumlah_beli[$index];
    $harga = $harga_satuan[$index];

    $query_detail = "INSERT INTO detail_transaksi_penjualan (id_transaksi, id_produk, jumlah_jual, harga_satuan) VALUES ('$id_transaksi', '$id', '$jumlah', '$harga')";
    if (!mysqli_query($koneksi, $query_detail)) {
        die('Error: ' . mysqli_error($koneksi));
    }

    // Hitung total harga
    $total_harga += $jumlah * $harga;
}

// Update total harga di transaksi_penjualan
$query_update_total = "UPDATE transaksi_penjualan SET total_harga = '$total_harga' WHERE id_transaksi = '$id_transaksi'";
if (!mysqli_query($koneksi, $query_update_total)) {
    die('Error: ' . mysqli_error($koneksi));
}

// Redirect atau tampilkan pesan sukses
header("location: kelola-transaksi-penjualan.php?pesan=berhasil");
exit;
