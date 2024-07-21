<?php
session_start();

// Memastikan user sudah login
if (!isset($_SESSION['hak_akses']) || $_SESSION['hak_akses'] == "") {
    header("location: ../../index.php?pesan=gagal");
    exit;
}

include '../../koneksi.php';

// Mengambil data dari form
$id_transaksi = $_POST['id_transaksi'];
$id_supplier = $_POST['id_supplier'];
$tanggal = $_POST['tanggal'];
$id_produk = $_POST['id_produk'];
$jumlah_beli = $_POST['jumlah_beli'];
$harga_satuan = $_POST['harga_satuan'];
$id_detail_transaksi = $_POST['id_detail_transaksi'];

// Menghitung total harga
$total_harga = 0;
foreach ($jumlah_beli as $index => $jumlah) {
    $total_harga += $jumlah * $harga_satuan[$index];
}

// Update data transaksi
$query_update_transaksi = "UPDATE transaksi_pembelian SET 
                            id_supplier = '$id_supplier', 
                            tanggal = '$tanggal', 
                            total_harga = '$total_harga' 
                            WHERE id_transaksi = '$id_transaksi'";
$result_update_transaksi = mysqli_query($koneksi, $query_update_transaksi);

if (!$result_update_transaksi) {
    die('Error: ' . mysqli_error($koneksi));
}

// Update detail transaksi
foreach ($id_detail_transaksi as $index => $id_detail) {
    $query_update_detail = "UPDATE detail_transaksi_pembelian SET 
                            id_produk = '{$id_produk[$index]}', 
                            jumlah_beli = '{$jumlah_beli[$index]}', 
                            harga_satuan = '{$harga_satuan[$index]}' 
                            WHERE id_detail_transaksi = '$id_detail'";
    $result_update_detail = mysqli_query($koneksi, $query_update_detail);

    if (!$result_update_detail) {
        die('Error: ' . mysqli_error($koneksi));
    }
}

// Insert new details if needed
foreach ($id_produk as $index => $id) {
    if (empty($id_detail_transaksi[$index])) {
        $query_insert_detail = "INSERT INTO detail_transaksi_pembelian (id_transaksi, id_produk, jumlah_beli, harga_satuan) 
                                VALUES ('$id_transaksi', '$id', '{$jumlah_beli[$index]}', '{$harga_satuan[$index]}')";
        $result_insert_detail = mysqli_query($koneksi, $query_insert_detail);

        if (!$result_insert_detail) {
            die('Error: ' . mysqli_error($koneksi));
        }
    }
}

// Redirect atau pesan sukses
header("location: kelola-transaksi-pembelian.php?pesan=sukses");
exit;
