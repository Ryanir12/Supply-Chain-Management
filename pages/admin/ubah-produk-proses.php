<?php
include "../../koneksi.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_produk = $_POST['id_produk'];
    $nama_produk = $_POST['nama_produk'];
    $satuan_produk = $_POST['satuan_produk'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];

    $query = "UPDATE produk SET 
                nama_produk='$nama_produk', 
                satuan_produk='$satuan_produk', 
                harga='$harga',
                stok='$stok' 
              WHERE id_produk='$id_produk'";

    if (mysqli_query($koneksi, $query)) {
        header("location:kelola-produk.php?pesan=berhasil");
    } else {
        echo "Error: " . $query . "<br>" . mysqli_error($koneksi);
    }

    mysqli_close($koneksi);
}
