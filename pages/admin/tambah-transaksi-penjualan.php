<!DOCTYPE html>
<html lang="en">

<head>
    <?php include '../../layout/header.php'; ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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

    // Query untuk mengambil daftar produk
    $produkQuery = "SELECT id_produk, nama_produk, harga FROM produk";
    $produkResult = mysqli_query($koneksi, $produkQuery);

    if (!$produkResult) {
        die('Query error: ' . mysqli_error($koneksi));
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
                        <h1 class="h3 mb-0 text-gray-800">Tambah Data Barang Keluar</h1>
                    </div>

                    <!-- Content Row -->
                    <div class="row">
                        <div class="col">
                            <div class="card mb-4">
                                <div class="card-body">
                                    <form action="proses-tambah-transaksi-penjualan.php" method="post">
                                        <div class="form-group">
                                            <label for="nama_customer">Nama Customer (Opsional)</label>
                                            <input type="text" name="nama_customer" id="nama_customer" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label for="tanggal">Tanggal</label>
                                            <input type="date" name="tanggal" id="tanggal" class="form-control" required>
                                        </div>
                                        <hr>
                                        <h4>Detail Barang</h4>
                                        <div id="detail-penjualan-container">
                                            <div class="detail-penjualan">
                                                <div class="form-group">
                                                    <label for="produk">Produk</label>
                                                    <select name="id_produk[]" class="form-control produk-select" required>
                                                        <option value="">Pilih Produk</option>
                                                        <?php while ($produk = mysqli_fetch_assoc($produkResult)) : ?>
                                                            <option value="<?php echo $produk['id_produk']; ?>" data-harga="<?php echo $produk['harga']; ?>"><?php echo $produk['nama_produk']; ?></option>
                                                        <?php endwhile; ?>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="jumlah_beli">Jumlah</label>
                                                    <input type="number" name="jumlah_beli[]" class="form-control jumlah-beli-input" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="harga_satuan">Harga Satuan</label>
                                                    <input type="text" name="harga_satuan[]" class="form-control harga-satuan-input" readonly required>
                                                </div>
                                                <button type="button" class="btn btn-danger btn-sm btn-remove">Hapus</button>
                                            </div>
                                        </div>
                                        <button type="button" id="btn-add-detail" class="btn btn-primary btn-sm">Tambah Produk</button>
                                        <hr>
                                        <button type="submit" class="btn btn-success">Simpan</button>
                                    </form>
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
                $('#btn-add-detail').on('click', function() {
                    var detailHTML = `
                        <div class="detail-penjualan">
                            <div class="form-group">
                                <label for="produk">Produk</label>
                                <select name="id_produk[]" class="form-control produk-select" required>
                                    <option value="">Pilih Produk</option>
                                    <?php
                                    mysqli_data_seek($produkResult, 0); // Reset produk result pointer to the beginning
                                    while ($produk = mysqli_fetch_assoc($produkResult)) : ?>
                                        <option value="<?php echo $produk['id_produk']; ?>" data-harga="<?php echo $produk['harga']; ?>"><?php echo $produk['nama_produk']; ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="jumlah_beli">Jumlah Beli</label>
                                <input type="number" name="jumlah_beli[]" class="form-control jumlah-beli-input" required>
                            </div>
                            <div class="form-group">
                                <label for="harga_satuan">Harga Satuan</label>
                                <input type="text" name="harga_satuan[]" class="form-control harga-satuan-input" readonly required>
                            </div>
                            <button type="button" class="btn btn-danger btn-sm btn-remove">Hapus</button>
                        </div>
                    `;
                    $('#detail-penjualan-container').append(detailHTML);
                });

                $(document).on('change', '.produk-select', function() {
                    var harga = $(this).find('option:selected').data('harga');
                    $(this).closest('.detail-penjualan').find('.harga-satuan-input').val(harga);
                });

                $(document).on('click', '.btn-remove', function() {
                    $(this).closest('.detail-penjualan').remove();
                });
            });
        </script>

</body>

</html>