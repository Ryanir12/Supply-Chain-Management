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
        exit; // pastikan untuk menghentikan eksekusi script setelah melakukan redirect
    }

    // Include koneksi database
    include "../../koneksi.php";

    // Query untuk mengambil daftar supplier
    $supplierQuery = "SELECT * FROM supplier";
    $supplierResult = mysqli_query($koneksi, $supplierQuery);

    // Query untuk mengambil daftar produk
    $produkQuery = "SELECT * FROM produk";
    $produkResult = mysqli_query($koneksi, $produkQuery);
    ?>

    <!-- Page Wrapper -->
    <div id="wrapper">

        <?php include '../../layout/sidebar-supplier.php'; ?>

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <?php include '../../layout/topbar.php'; ?>

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Tambah Transaksi Pembelian</h1>
                    </div>

                    <!-- Content Row -->
                    <div class="row">
                        <div class="col">
                            <div class="card mb-4">
                                <div class="card-body">
                                    <form action="proses-tambah-transaksi-pembelian.php" method="post">
                                        <div class="form-group">
                                            <label for="supplier">Supplier</label>
                                            <select name="id_supplier" id="supplier" class="form-control" required>
                                                <option value="">Pilih Supplier</option>
                                                <?php while ($supplier = mysqli_fetch_assoc($supplierResult)) : ?>
                                                    <option value="<?php echo $supplier['id_supplier']; ?>"><?php echo $supplier['nama_supplier']; ?></option>
                                                <?php endwhile; ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="tanggal">Tanggal Pembelian</label>
                                            <input type="date" name="tanggal" id="tanggal" class="form-control" required>
                                        </div>
                                        <hr>
                                        <h4>Detail Pembelian</h4>
                                        <div id="detail-pembelian-container">
                                            <div class="detail-pembelian">
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
                                                    <label for="jumlah_beli">Jumlah Beli</label>
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
                        <div class="detail-pembelian">
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
                    $('#detail-pembelian-container').append(detailHTML);
                });

                $(document).on('change', '.produk-select', function() {
                    var harga = $(this).find('option:selected').data('harga');
                    $(this).closest('.detail-pembelian').find('.harga-satuan-input').val(harga);
                });

                $(document).on('click', '.btn-remove', function() {
                    $(this).closest('.detail-pembelian').remove();
                });
            });
        </script>

</body>

</html>