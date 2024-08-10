<?php
include '../../koneksi.php';

// Mendapatkan filter dari query string
$filter = isset($_GET['filter']) ? $_GET['filter'] : '';

// Menyiapkan query untuk mengambil data transaksi
$query = "SELECT t.id_transaksi, s.nama_supplier, t.total_harga, t.tanggal 
          FROM transaksi_pembelian t
          JOIN supplier s ON t.id_supplier = s.id_supplier";

switch ($filter) {
    case 'harian':
        $tanggal = isset($_GET['tanggal']) ? $_GET['tanggal'] : date('Y-m-d');
        $query .= " WHERE DATE(t.tanggal) = '$tanggal'";
        break;
    case 'bulanan':
        $bulan = isset($_GET['bulan']) ? $_GET['bulan'] : date('m');
        $tahun = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');
        $query .= " WHERE MONTH(t.tanggal) = '$bulan' AND YEAR(t.tanggal) = '$tahun'";
        break;
    case 'tahunan':
        $tahun = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');
        $query .= " WHERE YEAR(t.tanggal) = '$tahun'";
        break;
}

$result = mysqli_query($koneksi, $query);

// Memeriksa apakah query berhasil
if (!$result) {
    die('Error: ' . mysqli_error($koneksi));
}

// Menyiapkan array untuk menyimpan transaksi berdasarkan tanggal
$transaksiByTanggal = [];
while ($item = mysqli_fetch_assoc($result)) {
    $tanggal = $item['tanggal'];

    // Query detail barang untuk setiap transaksi
    $id_transaksi = $item['id_transaksi'];
    $detailQuery = "SELECT dp.id_produk, p.nama_produk, dp.harga_satuan, dp.jumlah_beli 
                    FROM detail_transaksi_pembelian dp
                    JOIN produk p ON dp.id_produk = p.id_produk
                    WHERE dp.id_transaksi = '$id_transaksi'";
    $detailResult = mysqli_query($koneksi, $detailQuery);

    if (!$detailResult) {
        die('Error: ' . mysqli_error($koneksi));
    }

    $detailRows = [];
    while ($detailItem = mysqli_fetch_assoc($detailResult)) {
        $detailRows[] = $detailItem;
    }

    if (!isset($transaksiByTanggal[$tanggal])) {
        $transaksiByTanggal[$tanggal] = [
            'nama_supplier' => $item['nama_supplier'],
            'total_harga' => $item['total_harga'],
            'detail' => $detailRows
        ];
    } else {
        $transaksiByTanggal[$tanggal]['total_harga'] += $item['total_harga'];
        $transaksiByTanggal[$tanggal]['detail'] = array_merge($transaksiByTanggal[$tanggal]['detail'], $detailRows);
    }
}

// Menghitung total harga keseluruhan
$totalHargaKeseluruhan = 0;
foreach ($transaksiByTanggal as $data) {
    $totalHargaKeseluruhan += $data['total_harga'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include '../../layout/header.php'; ?>
    <style>
        .table {
            width: 100%;
            margin-bottom: 1rem;
            color: #212529;
        }

        .btn-export {
            margin-bottom: 20px;
        }

        .kop-surat {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            height: 150px;
            text-align: center;
        }

        .kop-surat img {
            width: 150px;
            height: auto;
            margin-right: 20px;
            /* Jarak antara logo dan informasi toko */
        }

        .header-info {
            flex: 1;
            /* Membuat informasi toko mengambil sisa ruang yang ada */
        }

        .header-info h1,
        .header-info p {
            margin: 0;
            color: black;
            /* Mengatur warna teks menjadi hitam */
            font-weight: bold;
            /* Mengatur teks menjadi tebal */
        }

        .header-info h1 {
            font-size: 24px;
        }

        .header-info p {
            font-size: 14px;
        }

        .text-center {
            text-align: center;
        }

        .font-bold {
            font-weight: bold;
        }

        .text-black {
            color: black;
        }

        #report-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        #report-table th,
        #report-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }

        #report-table th {
            background-color: #f8f9fa;
        }

        .total-row td {
            font-weight: bold;
        }

        .nested-table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }

        .nested-table th,
        .nested-table td {
            border: 1px solid #ddd;
            padding: 4px;
            text-align: center;
        }

        .nested-table th {
            background-color: #e9ecef;
        }

        .signature-section {
            margin-top: 50px;
            text-align: right;
        }

        .signature {
            display: inline-block;
            width: 200px;
            text-align: center;
            margin-right: 50px;
        }

        .signature p {
            margin: 0;
        }

        .signature-space {
            height: 50px;
            /* Atur tinggi space sesuai kebutuhan */
            border-bottom: 1px solid #000;
            /* Garis tanda tangan */
            margin: 10px 0;
            /* Jarak antara teks dan space */
        }
    </style>
    <!-- Link to html2pdf library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>
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

        <?php include '../../layout/sidebar-pimpinan.php'; ?>

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <?php include '../../layout/topbar.php'; ?>

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Laporan Barang Masuk</h1>
                    </div>

                    <!-- Filter Form -->
                    <form method="GET" class="mb-4">
                        <div class="form-group">
                            <label for="filter">Filter Berdasarkan:</label>
                            <select name="filter" id="filter" class="form-control" onchange="filterChanged()">
                                <option value="">Pilih Filter</option>
                                <option value="harian" <?php echo ($filter == 'harian') ? 'selected' : ''; ?>>Harian</option>
                                <option value="bulanan" <?php echo ($filter == 'bulanan') ? 'selected' : ''; ?>>Bulanan</option>
                                <option value="tahunan" <?php echo ($filter == 'tahunan') ? 'selected' : ''; ?>>Tahunan</option>
                            </select>
                        </div>
                        <div id="filter-options">
                            <?php if ($filter == 'harian') : ?>
                                <div class="form-group">
                                    <label for="tanggal">Tanggal</label>
                                    <input type="date" name="tanggal" id="tanggal" class="form-control" value="<?php echo isset($_GET['tanggal']) ? $_GET['tanggal'] : date('Y-m-d'); ?>">
                                </div>
                            <?php elseif ($filter == 'bulanan') : ?>
                                <div class="form-group">
                                    <label for="bulan">Bulan</label>
                                    <select name="bulan" id="bulan" class="form-control">
                                        <?php for ($i = 1; $i <= 12; $i++) : ?>
                                            <option value="<?php echo str_pad($i, 2, '0', STR_PAD_LEFT); ?>" <?php echo (isset($_GET['bulan']) && $_GET['bulan'] == str_pad($i, 2, '0', STR_PAD_LEFT)) ? 'selected' : ''; ?>>
                                                <?php echo date('F', mktime(0, 0, 0, $i, 1)); ?>
                                            </option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="tahun">Tahun</label>
                                    <input type="number" name="tahun" id="tahun" class="form-control" value="<?php echo isset($_GET['tahun']) ? $_GET['tahun'] : date('Y'); ?>">
                                </div>
                            <?php elseif ($filter == 'tahunan') : ?>
                                <div class="form-group">
                                    <label for="tahun">Tahun</label>
                                    <input type="number" name="tahun" id="tahun" class="form-control" value="<?php echo isset($_GET['tahun']) ? $_GET['tahun'] : date('Y'); ?>">
                                </div>
                            <?php endif; ?>
                        </div>
                        <button type="submit" class="btn btn-primary">Tampilkan</button>
                    </form>

                    <!-- Report Table -->
                    <div class="btn-export">
                        <button class="btn btn-success" onclick="generatePDF()">Cetak</button>
                    </div>

                    <div id="reportContent">
                        <div class="kop-surat">
                            <img src="../../img/waber logo.png" alt="Logo Toko">
                            <div class="header-info" style="margin-left: -190px;">
                                <h1 style="color: black; font-weight: bold;">WABER SPORT</h1>
                                <p style="color: black; font-weight: bold;">Jl. Adinegoro, Kec. Koto Tangah, Padang</p>
                                <p style="color: black; font-weight: bold;">0813-6345-7987</p>
                            </div>
                        </div>
                        <hr>
                        <div style="text-align: center;">
                            <h2 style="color: black; font-weight: bold;">
                                Laporan Barang Masuk
                            </h2>
                            <!-- Menampilkan informasi tambahan laporan -->

                            <p class="text-center font-bold text-black">
                                <?php if ($filter == 'harian') : ?>
                                    Tanggal: <?php echo isset($_GET['tanggal']) ? $_GET['tanggal'] : date('d-m-Y'); ?>
                                <?php elseif ($filter == 'bulanan') : ?>
                                    Bulan: <?php echo isset($_GET['bulan']) ? $_GET['bulan'] : date('m'); ?>, Tahun: <?php echo isset($_GET['tahun']) ? $_GET['tahun'] : date('Y'); ?>
                                <?php elseif ($filter == 'tahunan') : ?>
                                    Tahun: <?php echo isset($_GET['tahun']) ? $_GET['tahun'] : date('Y'); ?>
                                <?php endif; ?>
                            </p>
                            <table id="report-table">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Supplier</th>
                                        <th>Nama Barang </th>
                                        <th>Harga Satuan</th>
                                        <th>Jumlah</th>
                                        <th>Total Harga</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($transaksiByTanggal as $tanggal => $data) : ?>
                                        <?php foreach ($data['detail'] as $index => $detail) : ?>
                                            <tr>
                                                <?php if ($index === 0) : ?>
                                                    <td rowspan="<?php echo count($data['detail']); ?>" style="font-size: 12px;"><?php echo $tanggal; ?></td>
                                                    <td rowspan="<?php echo count($data['detail']); ?>"><?php echo $data['nama_supplier']; ?></td>
                                                <?php endif; ?>
                                                <td><?php echo $detail['nama_produk']; ?></td>
                                                <td><?php echo number_format($detail['harga_satuan'], 0, ',', '.'); ?></td>
                                                <td><?php echo $detail['jumlah_beli']; ?></td>
                                                <td><?php echo number_format($detail['harga_satuan'] * $detail['jumlah_beli'], 0, ',', '.'); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endforeach; ?>
                                    <tr class="total-row">
                                        <td colspan="5">Total Harga Keseluruhan</td>
                                        <td><?php echo number_format($totalHargaKeseluruhan, 0, ',', '.'); ?></td>
                                    </tr>
                                </tbody>
                            </table>



                            <div class="signature-section">
                                <div class="signature">
                                    <p>Padang, <?php echo date('d F Y'); ?></p>
                                    <div class="signature-space"></div>
                                    <p><strong>Didi Waber</strong></p>
                                </div>
                            </div>
                        </div>



                    </div>
                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <?php include '../../layout/footer.php'; ?>

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->



    <script>
        function filterChanged() {
            var filter = document.getElementById('filter').value;
            var filterOptions = document.getElementById('filter-options');
            filterOptions.innerHTML = '';

            if (filter === 'harian') {
                filterOptions.innerHTML = `
                    <div class="form-group">
                        <label for="tanggal">Tanggal</label>
                        <input type="date" name="tanggal" id="tanggal" class="form-control" value="<?php echo date('Y-m-d'); ?>">
                    </div>
                `;
            } else if (filter === 'bulanan') {
                filterOptions.innerHTML = `
                    <div class="form-group">
                        <label for="bulan">Bulan</label>
                        <select name="bulan" id="bulan" class="form-control">
                            <?php for ($i = 1; $i <= 12; $i++) : ?>
                                <option value="<?php echo str_pad($i, 2, '0', STR_PAD_LEFT); ?>"><?php echo date('F', mktime(0, 0, 0, $i, 1)); ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="tahun">Tahun</label>
                        <input type="number" name="tahun" id="tahun" class="form-control" value="<?php echo date('Y'); ?>">
                    </div>
                `;
            } else if (filter === 'tahunan') {
                filterOptions.innerHTML = `
                    <div class="form-group">
                        <label for="tahun">Tahun</label>
                        <input type="number" name="tahun" id="tahun" class="form-control" value="<?php echo date('Y'); ?>">
                    </div>
                `;
            }
        }

        function generatePDF() {
            var element = document.getElementById('reportContent');
            var opt = {
                margin: 1,
                filename: 'laporan_transaksi_pembelian.pdf',
                image: {
                    type: 'jpeg',
                    quality: 0.98
                },
                html2canvas: {
                    scale: 2
                },
                jsPDF: {
                    unit: 'in',
                    format: 'A4',
                    orientation: 'portrait'
                }
            };

            html2pdf().from(element).set(opt).save();
        }
    </script>

    <?php include '../../layout/js.php'; ?>
</body>

</html>