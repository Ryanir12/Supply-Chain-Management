<?php
include "../../koneksi.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_produk = $_POST['nama_produk'];
    $satuan_produk = $_POST['satuan_produk'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok']; // Ambil nilai stok dari form
    $id_bahanbaku = $_POST['id_bahanbaku'];

    // Mencari jumlah produk yang sudah ada
    $data = mysqli_query($koneksi, "SELECT * from produk");
    $n = mysqli_num_rows($data);

    // Membuat ID Produk berdasarkan inisial dari nama_produk dan jumlah produk saat ini
    $kata = explode(" ", $nama_produk);
    $temp = "";
    foreach ($kata as $k) {
        $temp .= $k[0];
    }
    $id_produk = substr($temp, 0, 2) . strval($n + 1);

    // Insert data produk ke database
    $query = "INSERT INTO produk (id_produk, nama_produk, satuan_produk, harga, stok) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, "sssii", $id_produk, $nama_produk, $satuan_produk, $harga, $stok);

    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        mysqli_close($koneksi);
        header("location: kelola-produk.php"); // Redirect ke halaman kelola-produk.php setelah berhasil
        exit();
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }

    mysqli_stmt_close($stmt);
    mysqli_close($koneksi);
}
