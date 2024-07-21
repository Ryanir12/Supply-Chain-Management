<?php
include '../../koneksi.php';

$id_produk = isset($_GET['id']) ? $_GET['id'] : '';

// Query untuk mendapatkan harga produk
$query = "SELECT harga FROM produk WHERE id_produk = '$id_produk'";
$result = mysqli_query($koneksi, $query);

if (!$result) {
    die('Error: ' . mysqli_error($koneksi));
}

$produk = mysqli_fetch_assoc($result);

// Mengembalikan data dalam format JSON
echo json_encode(['harga_satuan' => $produk['harga']]);
