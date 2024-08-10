<?php
include '../../koneksi.php';

// Memeriksa jika ada parameter id_transaksi dalam query string
$id_transaksi = isset($_GET['id_transaksi']) ? $_GET['id_transaksi'] : '';

if (empty($id_transaksi)) {
    die('ID transaksi tidak ditemukan.');
}

// Query untuk mendapatkan data transaksi
$query_transaksi = "SELECT t.*, c.nama_customer 
                    FROM transaksi_penjualan t
                    LEFT JOIN customer c ON t.id_customer = c.id_customer
                    WHERE t.id_transaksi = '$id_transaksi'";
$result_transaksi = mysqli_query($koneksi, $query_transaksi);

// Memeriksa apakah query berhasil
if (!$result_transaksi) {
    die('Error: ' . mysqli_error($koneksi));
}

$transaksi = mysqli_fetch_assoc($result_transaksi);

// Query untuk mendapatkan detail transaksi
$query_detail = "SELECT * FROM detail_transaksi_penjualan WHERE id_transaksi = '$id_transaksi'";
$result_detail = mysqli_query($koneksi, $query_detail);

// Memeriksa apakah query detail berhasil
if (!$result_detail) {
    die('Error: ' . mysqli_error($koneksi));
}

// Query untuk mendapatkan daftar produk
$produkQuery = "SELECT id_produk, nama_produk, harga FROM produk";
$produkResult = mysqli_query($koneksi, $produkQuery);

// Memeriksa apakah query produk berhasil
if (!$produkResult) {
    die('Error: ' . mysqli_error($koneksi));
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include '../../layout/header.php'; ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .remove-row {
            cursor: pointer;

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
                        <h1 class="h3 mb-0 text-gray-800">Ubah Data Barang Keluar</h1>
                    </div>

                    <!-- Content Row -->
                    <div class="row">
                        <div class="col">
                            <div class="card mb-4">
                                <div class="card-body">
                                    <form action="proses-ubah-transaksi-penjualan.php" method="POST">
                                        <input type="hidden" name="id_transaksi" value="<?php echo htmlspecialchars($id_transaksi, ENT_QUOTES, 'UTF-8'); ?>">

                                        <!-- Customer dan tanggal -->
                                        <div class="form-group">
                                            <label for="id_customer">Customer (opsional)</label>
                                            <select name="id_customer" id="id_customer" class="form-control">
                                                <option value="">Pilih Customer</option>
                                                <?php
                                                // Query untuk mendapatkan daftar customer
                                                $customerQuery = "SELECT id_customer, nama_customer FROM customer";
                                                $customerResult = mysqli_query($koneksi, $customerQuery);

                                                if (!$customerResult) {
                                                    die('Error: ' . mysqli_error($koneksi));
                                                }

                                                while ($customer = mysqli_fetch_assoc($customerResult)) {
                                                    $selected = ($customer['id_customer'] == $transaksi['id_customer']) ? 'selected' : '';
                                                    echo "<option value='{$customer['id_customer']}' $selected>{$customer['nama_customer']}</option>";
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
                                                    $jumlah_jual = $detail['jumlah_jual'];
                                                    $harga_satuan = $detail['harga_satuan'];
                                                ?>
                                                    <tr>
                                                        <td>
                                                            <select name="id_produk[]" class="form-control produk-select" onchange="updateHarga(this)" required>
                                                                <?php
                                                                // Reset produk result pointer untuk dropdown
                                                                mysqli_data_seek($produkResult, 0);

                                                                while ($produk = mysqli_fetch_assoc($produkResult)) {
                                                                    $selected = ($produk['id_produk'] == $id_produk) ? 'selected' : '';
                                                                    echo "<option value='{$produk['id_produk']}' data-harga='{$produk['harga']}' $selected>{$produk['nama_produk']}</option>";
                                                                }
                                                                ?>
                                                            </select>
                                                        </td>
                                                        <td><input type="number" name="jumlah_jual[]" class="form-control" value="<?php echo htmlspecialchars($jumlah_jual, ENT_QUOTES, 'UTF-8'); ?>" required></td>
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
                                            <button type="button" id="add-row" class="btn btn-success">Tambah Baris</button>
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
                        updateTotalHarga();
                    });
                });

                // Script untuk menambahkan baris baru
                document.getElementById('add-row').addEventListener('click', function() {
                    const tableBody = document.querySelector('#detail-table tbody');
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>
                            <select name="id_produk[]" class="form-control produk-select" onchange="updateHarga(this)" required>
                                <?php
                                // Reset produk result pointer untuk dropdown
                                mysqli_data_seek($produkResult, 0);

                                while ($produk = mysqli_fetch_assoc($produkResult)) {
                                    echo "<option value='{$produk['id_produk']}' data-harga='{$produk['harga']}'>{$produk['nama_produk']}</option>";
                                }
                                ?>
                            </select>
                        </td>
                        <td><input type="number" name="jumlah_jual[]" class="form-control" required></td>
                        <td><input type="number" name="harga_satuan[]" class="form-control" readonly></td>
                        <td><button type="button" class="btn btn-danger remove-row">Hapus</button></td>
                        <input type="hidden" name="id_detail_transaksi[]" value="">
                    `;
                    tableBody.appendChild(row);

                    // Re-attach event listener for new remove buttons
                    row.querySelector('.remove-row').addEventListener('click', function() {
                        this.closest('tr').remove();
                        updateTotalHarga();
                    });
                });

                // Function to update harga satuan berdasarkan produk
                window.updateHarga = function(selectElement) {
                    const harga = selectElement.options[selectElement.selectedIndex].dataset.harga;
                    selectElement.closest('tr').querySelector('input[name="harga_satuan[]"]').value = harga;
                    updateTotalHarga();
                }

                // Function to update total harga (if needed)
                function updateTotalHarga() {
                    // Implementasi sesuai kebutuhan
                }
            });
        </script>

</body>

</html>