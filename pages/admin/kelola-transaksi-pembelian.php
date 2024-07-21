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
        exit; // pastikan untuk menghentikan eksekusi script setelah melakukan redirect
    }

    // Include koneksi database
    include "../../koneksi.php";

    // Query untuk mengambil daftar transaksi pembelian
    $query = "SELECT t.id_transaksi, s.nama_supplier, t.total_harga, t.tanggal 
              FROM transaksi_pembelian t
              JOIN supplier s ON t.id_supplier = s.id_supplier";
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
                        <h1 class="h3 mb-0 text-gray-800">Kelola Transaksi Pembelian</h1>
                    </div>

                    <!-- Content Row -->
                    <div class="row">
                        <div class="col">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <a href="tambah-transaksi-pembelian.php" class="btn btn-sm btn-primary">Tambah Transaksi</a>
                                </div>
                                <div class="card-body">
                                    <table class="table table-responsive-lg table-hover table-bordered" id="dataTable" width="100%" cellspacing="0">
                                        <thead class="thead-light text-center">
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Supplier</th>
                                                <th>Detail Pembelian</th>
                                                <th>Total Harga Pembelian</th>
                                                <th>Tanggal Pembelian</th>
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
                                                    <td><?php echo $item['nama_supplier']; ?></td>
                                                    <td class="text-center">
                                                        <button class="btn btn-info btn-sm" onclick="openModal(<?php echo $item['id_transaksi']; ?>)">Lihat Detail</button>
                                                    </td>
                                                    <td><?php echo number_format($item['total_harga'], 0, ',', '.'); ?></td>
                                                    <td><?php echo $item['tanggal']; ?></td>
                                                    <td>
                                                        <a href="ubah-transaksi-pembelian.php?id=<?php echo $item['id_transaksi']; ?>" class="btn btn-info btn-sm">Ubah</a>
                                                        <form action="hapus-transaksi-pembelian.php?id=<?php echo $item['id_transaksi']; ?>" method="post" style="display: inline;">
                                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Anda yakin ingin menghapus transaksi ini?')">Hapus</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                                <tr id="modal-<?php echo $item['id_transaksi']; ?>" class="modal">
                                                    <td colspan="6">
                                                        <div class="modal-content">
                                                            <span class="close" onclick="closeModal(<?php echo $item['id_transaksi']; ?>)">&times;</span>
                                                            <h2>Detail Pembelian</h2>
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
                                                                    $detailQuery = "SELECT p.nama_produk, d.jumlah_beli, d.harga_satuan 
                                                                                    FROM detail_transaksi_pembelian d
                                                                                    JOIN produk p ON d.id_produk = p.id_produk
                                                                                    WHERE d.id_transaksi = " . $item['id_transaksi'];
                                                                    $detailResult = mysqli_query($koneksi, $detailQuery);

                                                                    if (!$detailResult) {
                                                                        echo mysqli_error($koneksi); // menampilkan pesan error jika query detail gagal
                                                                    } else {
                                                                        while ($detail = mysqli_fetch_assoc($detailResult)) {
                                                                    ?>
                                                                            <tr>
                                                                                <td><?php echo $detail['nama_produk']; ?></td>
                                                                                <td><?php echo $detail['jumlah_beli']; ?></td>
                                                                                <td><?php echo number_format($detail['harga_satuan'], 0, ',', '.'); ?></td>
                                                                            </tr>
                                                                    <?php
                                                                        }
                                                                    }
                                                                    ?>
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
                    <!-- /.container-fluid -->

                </div>
                <!-- End of Main Content -->

                <!-- Footer -->
                <?php include '../../layout/footer.php'; ?>
                <!-- End of Footer -->

            </div>
            <!-- End of Content Wrapper -->

        </div>
        <!-- End of Page Wrapper -->

        <!-- Scroll to Top Button-->
        <a class="scroll-to-top rounded" href="#page-top">
            <i class="fas fa-angle-up"></i>
        </a>

        <!-- Logout Modal-->
        <?php include '../../layout/popup-logout.php'; ?>

        <?php include '../../layout/js.php'; ?>
        <script>
            $(document).ready(function() {
                $('#dataTable').DataTable();
            });

            function openModal(id) {
                document.getElementById('modal-' + id).style.display = 'block';
            }

            function closeModal(id) {
                document.getElementById('modal-' + id).style.display = 'none';
            }
        </script>

</body>

</html>