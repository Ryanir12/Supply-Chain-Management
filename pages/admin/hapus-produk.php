<?php
include "../../koneksi.php";

$id = $_GET['id'];

// Hapus data dari tabel produk
mysqli_query($koneksi, "DELETE FROM produk WHERE id_produk='$id'");

// Periksa apakah query berhasil dieksekusi
if (mysqli_affected_rows($koneksi) > 0) {
    header("location:kelola-produk.php");
} else {
    echo "Gagal menghapus produk. Pastikan ID produk benar.";
}
