<!DOCTYPE html>
<html lang="en">

<head>
    <?php include '../../layout/header.php' ?>
</head>

<body id="page-top">

    <?php
    session_start();

    // cek apakah yang mengakses halaman ini sudah login
    if ($_SESSION['hak_akses'] == "") {
        header("location:../../index.php?pesan=gagal");
    }

    ?>
    <!-- Page Wrapper -->
    <div id="wrapper">

        <?php include '../../layout/sidebar-admin.php' ?>

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <?php include '../../layout/topbar.php' ?>

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Ubah Barang</h1>
                    </div>

                    <!-- Content Row -->
                    <div class="container row">
                        <div class="col-12">
                            <div class="card mb-5">
                                <div class="card-header">
                                </div>
                                <div class="card-body">
                                    <!-- Content -->
                                    <?php
                                    include "../../koneksi.php";
                                    if (isset($_GET['id'])) {
                                        $id = $_GET['id'];
                                        $data = mysqli_query($koneksi, "SELECT * FROM produk WHERE id_produk='$id'");
                                        if ($d = mysqli_fetch_array($data)) {
                                    ?>
                                            <form action="ubah-produk-proses.php" method="post">
                                                <div class="form-group">
                                                    <input type="hidden" name="id_produk" id="id_produk" value="<?php echo $d['id_produk']; ?>" required />
                                                </div>
                                                <div class="form-group">
                                                    <label for="nama_produk">Nama Barang</label>
                                                    <input type="text" name="nama_produk" id="nama_produk" value="<?php echo $d['nama_produk']; ?>" class="form-control" required />
                                                </div>
                                                <div class="form-group">
                                                    <label for="satuan_produk">Satuan Barang</label>
                                                    <input type="text" name="satuan_produk" id="satuan_produk" value="<?php echo $d['satuan_produk']; ?>" class="form-control" required />
                                                </div>
                                                <div class="form-group">
                                                    <label for="harga">Harga</label>
                                                    <input type="text" name="harga" id="harga" value="<?php echo $d['harga']; ?>" class="form-control" required />
                                                </div>
                                                <div class="form-group">
                                                    <label for="stok">Stok</label>
                                                    <input type="number" name="stok" id="stok" value="<?php echo $d['stok']; ?>" class="form-control" required />
                                                </div>
                                                <hr>
                                                <div class="form-group">
                                                    <input type="submit" class="btn btn-primary" value="Ubah" />
                                                </div>
                                            </form>
                                    <?php
                                        } else {
                                            echo "<div class='alert alert-danger'>Data tidak ditemukan.</div>";
                                        }
                                    } else {
                                        echo "<div class='alert alert-danger'>ID tidak ditemukan.</div>";
                                    }
                                    ?>
                                    <!-- End Content -->
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

</body>

</html>