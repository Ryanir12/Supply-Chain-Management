<?php
include '../../koneksi.php';

// Mendapatkan ID transaksi dari query string
$id_transaksi = isset($_GET['id']) ? $_GET['id'] : '';

// Query untuk mendapatkan data transaksi
$query_transaksi = "SELECT t.*, s.nama_supplier 
                    FROM transaksi_pembelian t
                    JOIN supplier s ON t.id_supplier = s.id_supplier
                    WHERE t.id_transaksi = '$id_transaksi'";
$result_transaksi = mysqli_query($koneksi, $query_transaksi);

// Memeriksa apakah query berhasil
if (!$result_transaksi) {
    die('Error: ' . mysqli_error($koneksi));
}

$transaksi = mysqli_fetch_assoc($result_transaksi);

// Query untuk mendapatkan detail transaksi
$query_detail = "SELECT * FROM detail_transaksi_pembelian WHERE id_transaksi = '$id_transaksi'";
$result_detail = mysqli_query($koneksi, $query_detail);

// Memeriksa apakah query detail berhasil
if (!$result_detail) {
    die('Error: ' . mysqli_error($koneksi));
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include '../../layout/header.php'; ?>
    <style>
        .remove-row {
            cursor: pointer;
            color: red;
        }

        .remove-row:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body id="page-top">

    <?php
    // Memastikan user sudah login
    session_start();
    if (!isset($_SESSION['hak_akses']) || $_SESSION['hak_akses'] == "") {
        header("location: ../../index.php?pesan=gagal");
        exit;
    }
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
                        <h1 class="h3 mb-0 text-gray-800">Ubah Transaksi Pembelian</h1>
                    </div>

                    <!-- Content Row -->
                    <div class="row">
                        <div class="col">
                            <div class="card mb-4">
                                <div class="card-body">
                                    <form action="proses-ubah-transaksi-pembelian.php" method="POST">
                                        <input type="hidden" name="id_transaksi" value="<?php echo htmlspecialchars($id_transaksi, ENT_QUOTES, 'UTF-8'); ?>">

                                        <!-- Supplier dan tanggal -->
                                        <div class="form-group">
                                            <label for="id_supplier">Supplier</label>
                                            <select name="id_supplier" id="id_supplier" class="form-control" required>
                                                <?php
                                                // Query untuk mendapatkan daftar supplier
                                                $supplier_query = "SELECT id_supplier, nama_supplier FROM supplier";
                                                $supplier_result = mysqli_query($koneksi, $supplier_query);

                                                if (!$supplier_result) {
                                                    die('Error: ' . mysqli_error($koneksi));
                                                }

                                                while ($supplier = mysqli_fetch_assoc($supplier_result)) {
                                                    $selected = ($supplier['id_supplier'] == $transaksi['id_supplier']) ? 'selected' : '';
                                                    echo "<option value='{$supplier['id_supplier']}' $selected>{$supplier['nama_supplier']}</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="tanggal">Tanggal</label>
                                            <input type="date" name="tanggal" id="tanggal" class="form-control" value="<?php echo htmlspecialchars($transaksi['tanggal'], ENT_QUOTES, 'UTF-8'); ?>" required>
                                        </div>

                                        <!-- Detail Transaksi -->
                                        <table id="detail-table" class="table">
                                            <thead>
                                                <tr>
                                                    <th>Nama Produk</th>
                                                    <th>Jumlah</th>
                                                    <th>Harga Satuan</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                while ($detail = mysqli_fetch_assoc($result_detail)) {
                                                    $id_produk = $detail['id_produk'];
                                                    $jumlah_beli = $detail['jumlah_beli'];
                                                    $harga_satuan = $detail['harga_satuan'];
                                                ?>
                                                    <tr>
                                                        <td>
                                                            <select name="id_produk[]" class="form-control" onchange="updateHarga(this)" required>
                                                                <?php
                                                                $produk_query = "SELECT id_produk, nama_produk FROM produk";
                                                                $produk_result = mysqli_query($koneksi, $produk_query);

                                                                if (!$produk_result) {
                                                                    die('Error: ' . mysqli_error($koneksi));
                                                                }

                                                                while ($produk = mysqli_fetch_assoc($produk_result)) {
                                                                    $selected = ($produk['id_produk'] == $id_produk) ? 'selected' : '';
                                                                    echo "<option value='{$produk['id_produk']}' $selected>{$produk['nama_produk']}</option>";
                                                                }
                                                                ?>
                                                            </select>
                                                        </td>
                                                        <td><input type="number" name="jumlah_beli[]" class="form-control" value="<?php echo htmlspecialchars($jumlah_beli, ENT_QUOTES, 'UTF-8'); ?>" required></td>
                                                        <td><input type="number" name="harga_satuan[]" class="form-control" value="<?php echo htmlspecialchars($harga_satuan, ENT_QUOTES, 'UTF-8'); ?>" readonly></td>
                                                        <td><button type="button" class="btn btn-danger remove-row">Hapus</button></td>
                                                        <input type="hidden" name="id_detail_transaksi[]" value="<?php echo htmlspecialchars($detail['id_detail_transaksi'], ENT_QUOTES, 'UTF-8'); ?>">
                                                    </tr>
                                                <?php
                                                }
                                                ?>
                                            </tbody>
                                        </table>

                                        <div class="form-group">
                                            <button type="button" id="add-row" class="btn btn-primary">Tambah Baris</button>
                                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                        </div>
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
            document.addEventListener('DOMContentLoaded', function() {
                // Script untuk menghapus baris
                document.querySelectorAll('.remove-row').forEach(button => {
                    button.addEventListener('click', function() {
                        this.closest('tr').remove();
                    });
                });

                // Script untuk menambahkan baris baru
                document.getElementById('add-row').addEventListener('click', function() {
                    const tableBody = document.querySelector('#detail-table tbody');
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>
                            <select name="id_produk[]" class="form-control" onchange="updateHarga(this)" required>
                                <?php
                                $produk_query = "SELECT id_produk, nama_produk FROM produk";
                                $produk_result = mysqli_query($koneksi, $produk_query);

                                if (!$produk_result) {
                                    die('Error: ' . mysqli_error($koneksi));
                                }

                                while ($produk = mysqli_fetch_assoc($produk_result)) {
                                    echo "<option value='{$produk['id_produk']}'>{$produk['nama_produk']}</option>";
                                }
                                ?>
                            </select>
                        </td>
                        <td><input type="number" name="jumlah_beli[]" class="form-control" required></td>
                        <td><input type="number" name="harga_satuan[]" class="form-control" readonly></td>
                        <td><button type="button" class="btn btn-danger remove-row">Hapus</button></td>
                        <input type="hidden" name="id_detail_transaksi[]" value="">
                    `;
                    tableBody.appendChild(row);

                    // Re-attach event listener for new remove buttons
                    row.querySelector('.remove-row').addEventListener('click', function() {
                        this.closest('tr').remove();
                    });
                });
            });

            function updateHarga(select) {
                const row = select.closest('tr');
                const idProduk = select.value;
                const hargaInput = row.querySelector('input[name="harga_satuan[]"]');

                fetch('get-harga-produk.php?id=' + idProduk)
                    .then(response => response.json())
                    .then(data => {
                        hargaInput.value = data.harga_satuan;
                    })
                    .catch(error => console.error('Error:', error));
            }
        </script>

</body>

</html>