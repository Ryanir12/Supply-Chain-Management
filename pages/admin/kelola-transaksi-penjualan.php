<!DOCTYPE html>
<html lang="en">

<head>
    <?php include '../../layout/header.php'; ?>
    <style>
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            padding-top: 100px;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
        }

        .modal-content {
            background-color: #fefefe;
            margin: auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>

<body id="page-top">

    <?php
    session_start();

    // cek apakah yang mengakses halaman ini sudah login
    if (!isset($_SESSION['hak_akses']) || $_SESSION['hak_akses'] == "") {
        header("location: ../../index.php?pesan=gagal");
        exit;
    }

    // Include koneksi database
    include "../../koneksi.php";

    // Query untuk mengambil daftar transaksi penjualan
    $query = "SELECT t.id_transaksi, c.nama_customer, t.total_harga, t.tanggal 
              FROM transaksi_penjualan t
              LEFT JOIN customer c ON t.id_customer = c.id_customer";
    $result = mysqli_query($koneksi, $query);

    // Periksa apakah query berhasil dieksekusi
    if (!$result) {
        die('Error: ' . mysqli_error($koneksi));
    }
    ?>

    <!-- Page Wrapper -->
    <div id="wrapper">

        <?php include '../../layout/sidebar-admin.php'; ?>

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <?php include '../../layout/topbar.php'; ?>

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Kelola Barang Keluar</h1>
                    </div>

                    <!-- Content Row -->
                    <div class="row">
                        <div class="col">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <a href="tambah-transaksi-penjualan.php" class="btn btn-sm btn-primary">Tambah Data</a>
                                </div>
                                <div class="card-body">
                                    <table class="table table-responsive-lg table-hover table-bordered" id="dataTable" width="100%" cellspacing="0">
                                        <thead class="thead-light text-center">
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Customer</th>
                                                <th>Detail</th>
                                                <th>Total Harga</th>
                                                <th>Tanggal Barang Keluar</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $no = 1;
                                            while ($item = mysqli_fetch_assoc($result)) :
                                            ?>
                                                <tr>
                                                    <td class="text-center"><?php echo $no++; ?></td>
                                                    <td><?php echo $item['nama_customer'] ? $item['nama_customer'] : 'Umum'; ?></td>
                                                    <td class="text-center">
                                                        <button class="btn btn-info btn-sm" onclick="openModal(<?php echo $item['id_transaksi']; ?>)">Lihat Detail</button>
                                                    </td>
                                                    <td><?php echo number_format($item['total_harga'], 0, ',', '.'); ?></td>
                                                    <td><?php echo $item['tanggal']; ?></td>
                                                    <td>
                                                        <a href="ubah-transaksi-penjualan.php?id_transaksi=<?php echo $item['id_transaksi']; ?>" class="btn btn-info btn-sm">Ubah</a>
                                                        <form action="hapus-transaksi-penjualan.php?id=<?php echo $item['id_transaksi']; ?>" method="post" style="display: inline;">
                                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Anda yakin ingin menghapus transaksi ini?')">Hapus</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                                <tr id="modal-<?php echo $item['id_transaksi']; ?>" class="modal">
                                                    <td colspan="6">
                                                        <div class="modal-content">
                                                            <span class="close" onclick="closeModal(<?php echo $item['id_transaksi']; ?>)">&times;</span>
                                                            <h2>Detail Penjualan</h2>
                                                            <table class="table table-bordered">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Nama Produk</th>
                                                                        <th>Jumlah</th>
                                                                        <th>Harga Satuan</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php
                                                                    $detailQuery = "SELECT p.nama_produk, d.jumlah_jual, d.harga_satuan 
                                                                                    FROM detail_transaksi_penjualan d
                                                                                    JOIN produk p ON d.id_produk = p.id_produk
                                                                                    WHERE d.id_transaksi = " . $item['id_transaksi'];
                                                                    $detailResult = mysqli_query($koneksi, $detailQuery);

                                                                    if (!$detailResult) {
                                                                        die('Error: ' . mysqli_error($koneksi));
                                                                    }

                                                                    while ($detail = mysqli_fetch_assoc($detailResult)) :
                                                                    ?>
                                                                        <tr>
                                                                            <td><?php echo $detail['nama_produk']; ?></td>
                                                                            <td class="text-center"><?php echo $detail['jumlah_jual']; ?></td>
                                                                            <td class="text-right"><?php echo number_format($detail['harga_satuan'], 0, ',', '.'); ?></td>
                                                                        </tr>
                                                                    <?php endwhile; ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endwhile; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Content Row -->

                </div>
                <!-- End Page Content -->

            </div>
            <!-- End Main Content -->

            <?php include '../../layout/footer.php'; ?>

        </div>
        <!-- End Content Wrapper -->

    </div>
    <!-- End Page Wrapper -->

    <script>
        function openModal(id) {
            document.getElementById('modal-' + id).style.display = 'block';
        }

        function closeModal(id) {
            document.getElementById('modal-' + id).style.display = 'none';
        }

        window.onclick = function(event) {
            if (event.target.classList.contains('modal')) {
                event.target.style.display = "none";
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.btn-hapus').forEach(function(button) {
                button.addEventListener('click', function(event) {
                    event.preventDefault();
                    var idTransaksi = this.getAttribute('data-id');
                    if (confirm('Apakah Anda yakin ingin menghapus transaksi ini?')) {
                        window.location.href = 'hapus-transaksi-penjualan.php?id=' + idTransaksi;
                    }
                });
            });
        });
    </script>

</body>

</html>