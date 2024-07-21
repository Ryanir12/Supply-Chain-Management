<!DOCTYPE html>
<html lang="en">

<head>
    <?php include '../../layout/header.php' ?>
</head>

<body id="page-top">

    <?php
    session_start();

    //cek apakah yang mengakses halaman ini sudah login
    if ($_SESSION['hak_akses'] == "") {
        header("location:../../index.php?pesan=gagal");
    }
    ?>
    <!-- Page Wrapper -->
    <div id="wrapper">

        <?php include '../../layout/sidebar-gudang.php' ?>

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <?php include '../../layout/topbar.php' ?>

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Kelola Produk</h1>
                    </div>

                    <!-- Content Row -->
                    <div class="container row-12">
                        <div class="col">
                            <div class="card mb-5">
                                <div class="card-header">
                                    <div class="nav-item">
                                        <a href="tambah-produk.php" class="btn btn-sm btn-primary">Tambah Data</a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <table class="table table-responsive-lg table-hover table-bordered" id="Table">
                                        <thead class="thead-light text-center">
                                            <tr>
                                                <th width=6%>No</th>
                                                <th>Nama Produk</th>
                                                <th>Satuan</th>
                                                <th>Harga</th>
                                                <th>Stok</th> <!-- Tambahkan kolom Stok -->

                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            include "../../koneksi.php";

                                            $no = 1;
                                            $query = "SELECT id_produk, nama_produk, satuan_produk, harga, stok FROM produk";
                                            $data = mysqli_query($koneksi, $query);

                                            // Periksa apakah query berhasil dieksekusi
                                            if (!$data) {
                                                die("Query error: " . mysqli_error($koneksi));
                                            }

                                            if (mysqli_num_rows($data) == 0) {
                                            ?>
                                                <tr>
                                                    <td colspan="6" class="text-center font-weight-bold">Data Kosong</td>
                                                </tr>
                                                <?php
                                            } else {
                                                while ($item = mysqli_fetch_array($data)) {
                                                ?>
                                                    <tr>
                                                        <td class="text-center align-middle"><?php echo $no++; ?></td>
                                                        <td class="align-middle"><?php echo $item['nama_produk']; ?></td>
                                                        <td class="align-middle"><?php echo $item['satuan_produk']; ?></td>
                                                        <td class="align-middle"><?php echo $item['harga']; ?></td>
                                                        <td class="align-middle"><?php echo $item['stok']; ?></td> <!-- Menampilkan stok -->

                                                    </tr>
                                            <?php
                                                }
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <?php include '../../layout/footer.php' ?>
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
    <?php include '../../layout/popup-logout.php' ?>

    <?php include '../../layout/js.php' ?>
    <script>
        $(document).ready(function() {
            $('#Table').DataTable();
        });
    </script>

</body>

</html>