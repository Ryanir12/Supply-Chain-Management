<?php
session_start();

include "koneksi.php";

$username = $_POST['username'];
$password = $_POST['password'];

$login = mysqli_query($koneksi, "SELECT * FROM user WHERE username='$username' and password='$password'");

$cek = mysqli_num_rows($login);

if ($cek > 0) {
    $data = mysqli_fetch_assoc($login);


    if ($data['hak_akses'] == "supplier") {
        //buat session
        $_SESSION['username'] = $username;
        $_SESSION['hak_akses'] = "supplier";
        $_SESSION['nama'] = $data['nama'];
        $_SESSION['jabatan'] = $data['jabatan'];
        $_SESSION['id_user'] = $data['id_user'];

        //alihkan
        header("location:pages/supplier/index-supplier.php");
    } else if ($data['hak_akses'] == "admin") {
        //buat session
        $_SESSION['username'] = $username;
        $_SESSION['hak_akses'] = "admin";
        $_SESSION['nama'] = $data['nama'];
        $_SESSION['jabatan'] = $data['jabatan'];
        $_SESSION['id_user'] = $data['id_user'];

        //alihkan
        header("location:pages/admin/index-admin.php");
    } else if ($data['hak_akses'] == "pimpinan") {
        //buat session
        $_SESSION['username'] = $username;
        $_SESSION['hak_akses'] = "pimpinan";
        $_SESSION['nama'] = $data['nama'];
        $_SESSION['jabatan'] = $data['jabatan'];
        $_SESSION['id_user'] = $data['id_user'];

        //alihkan
        header("location:pages/pimpinan/index-pimpinan.php");
    } else if ($data['hak_akses'] == "gudang") {
        //buat session
        $_SESSION['username'] = $username;
        $_SESSION['hak_akses'] = "gudang";
        $_SESSION['nama'] = $data['nama'];
        $_SESSION['jabatan'] = $data['jabatan'];
        $_SESSION['id_user'] = $data['id_user'];
        //alihkan
        header("location:pages/gudang/index-gudang.php");
    } else {

        // alihkan ke halaman login kembali
        header("location:../../index.php?pesan=gagal");
    }
} else {
    header("location:../../index.php?pesan=gagal");
}
