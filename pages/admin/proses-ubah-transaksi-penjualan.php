<?php
include '../../koneksi.php';

// Mulai sesi
session_start();

// Periksa apakah user sudah login
if (!isset($_SESSION['hak_akses']) || $_SESSION['hak_akses'] == "") {
    header("location: ../../index.php?pesan=gagal");
    exit;
}

// Ambil data dari form
$id_transaksi = isset($_POST['id_transaksi']) ? $_POST['id_transaksi'] : '';
$id_customer = isset($_POST['id_customer']) ? $_POST['id_customer'] : null;
$tanggal = isset($_POST['tanggal']) ? $_POST['tanggal'] : '';
$id_produk = isset($_POST['id_produk']) ? $_POST['id_produk'] : array();
$jumlah_jual = isset($_POST['jumlah_jual']) ? $_POST['jumlah_jual'] : array();
$harga_satuan = isset($_POST['harga_satuan']) ? $_POST['harga_satuan'] : array();
$id_detail_transaksi = isset($_POST['id_detail_transaksi']) ? $_POST['id_detail_transaksi'] : array();

// Validasi input
if (empty($id_transaksi) || empty($tanggal)) {
    die('ID transaksi atau tanggal tidak ditemukan.');
}

// Mulai transaksi
mysqli_begin_transaction($koneksi);

try {
    // Update data transaksi penjualan
    $query_update_transaksi = "UPDATE transaksi_penjualan SET tanggal = ?, id_customer = ? WHERE id_transaksi = ?";
    $stmt_update_transaksi = mysqli_prepare($koneksi, $query_update_transaksi);
    if (!$stmt_update_transaksi) {
        throw new Exception('Error preparing statement for update transaksi penjualan: ' . mysqli_error($koneksi));
    }
    mysqli_stmt_bind_param($stmt_update_transaksi, 'ssi', $tanggal, $id_customer, $id_transaksi);
    mysqli_stmt_execute($stmt_update_transaksi);

    // Hapus detail transaksi lama
    $query_delete_detail = "DELETE FROM detail_transaksi_penjualan WHERE id_transaksi = ?";
    $stmt_delete_detail = mysqli_prepare($koneksi, $query_delete_detail);
    if (!$stmt_delete_detail) {
        throw new Exception('Error preparing statement for delete detail transaksi penjualan: ' . mysqli_error($koneksi));
    }
    mysqli_stmt_bind_param($stmt_delete_detail, 'i', $id_transaksi);
    mysqli_stmt_execute($stmt_delete_detail);

    $total_harga = 0; // Inisialisasi total harga

    // Tambahkan detail transaksi baru
    foreach ($id_produk as $index => $id) {
        $jumlah = $jumlah_jual[$index];
        $harga = $harga_satuan[$index];
        $subtotal = $jumlah * $harga;
        $total_harga += $subtotal; // Hitung total harga

        $query_insert_detail = "INSERT INTO detail_transaksi_penjualan (id_transaksi, id_produk, jumlah_jual, harga_satuan) VALUES (?, ?, ?, ?)";
        $stmt_insert_detail = mysqli_prepare($koneksi, $query_insert_detail);
        if (!$stmt_insert_detail) {
            throw new Exception('Error preparing statement for insert detail transaksi penjualan: ' . mysqli_error($koneksi));
        }
        mysqli_stmt_bind_param($stmt_insert_detail, 'iiid', $id_transaksi, $id, $jumlah, $harga);
        mysqli_stmt_execute($stmt_insert_detail);
    }

    // Update total harga penjualan
    $query_update_total_harga = "UPDATE transaksi_penjualan SET total_harga = ? WHERE id_transaksi = ?";
    $stmt_update_total_harga = mysqli_prepare($koneksi, $query_update_total_harga);
    if (!$stmt_update_total_harga) {
        throw new Exception('Error preparing statement for update total harga penjualan: ' . mysqli_error($koneksi));
    }
    mysqli_stmt_bind_param($stmt_update_total_harga, 'di', $total_harga, $id_transaksi);
    mysqli_stmt_execute($stmt_update_total_harga);

    // Komit transaksi
    mysqli_commit($koneksi);

    // Redirect ke halaman kelola transaksi penjualan dengan pesan sukses
    header("Location: kelola-transaksi-penjualan.php?pesan=sukses_ubah");
} catch (Exception $e) {
    // Rollback jika terjadi kesalahan
    mysqli_rollback($koneksi);
    die('Error: ' . $e->getMessage());
}

// Tutup koneksi
mysqli_close($koneksi);
