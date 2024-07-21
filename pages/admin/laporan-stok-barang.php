<?php
include '../../koneksi.php';

// Menyiapkan query untuk mengambil data produk
$query = "SELECT * FROM produk";

// Menjalankan query dan memeriksa hasil
$result = mysqli_query($koneksi, $query);
if (!$result) {
    die('Error: ' . mysqli_error($koneksi));
}

// Menyiapkan array untuk menyimpan data produk
$produkList = [];
while ($item = mysqli_fetch_assoc($result)) {
    $produkList[] = $item;
}

mysqli_free_result($result);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include '../../layout/header.php'; ?>
    <style>
        /* Styles */
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
        }

        .header-info {
            flex: 1;
        }

        .header-info h1,
        .header-info p {
            margin: 0;
            color: black;
            font-weight: bold;
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
            border-bottom: 1px solid #000;
            margin: 10px 0;
        }
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>
</head>

<body id="page-top">

    <?php
    session_start();
    if (!isset($_SESSION['hak_akses']) || $_SESSION['hak_akses'] == "") {
        header("location: ../../index.php?pesan=gagal");
        exit;
    }
    ?>

    <div id="wrapper">

        <?php include '../../layout/sidebar-admin.php'; ?>

        <div id="content-wrapper" class="d-flex flex-column">

            <div id="content">

                <?php include '../../layout/topbar.php'; ?>

                <div class="container-fluid">

                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Laporan Stok Barang</h1>
                    </div>

                    <div class="btn-export">
                        <button id="export-btn" class="btn btn-primary btn-export">Cetak</button>
                    </div>

                    <div id="report-section">
                        <!-- Export Button -->

                        <div class="kop-surat">
                            <img src="../../img/waber logo.png" alt="Logo Toko">
                            <div class="header-info" style="margin-left: -190px;">
                                <h1 style="color: black; font-weight: bold;">WABER SPORT</h1>
                                <p style="color: black; font-weight: bold;">Jl. Adinegoro, Kec. Koto Tangah, Padang</p>
                                <p style="color: black; font-weight: bold;">0813-6345-7987</p>
                            </div>
                        </div>
                        <h2 class="text-center font-bold text-black">Laporan Stok Barang</h2>

                        <table id="report-table">
                            <thead>
                                <tr>
                                    <th>ID Produk</th>
                                    <th>Nama Produk</th>
                                    <th>Satuan Produk</th>
                                    <th>Harga</th>
                                    <th>Stok</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($produkList as $produk) : ?>
                                    <tr>
                                        <td><?php echo $produk['id_produk']; ?></td>
                                        <td><?php echo $produk['nama_produk']; ?></td>
                                        <td><?php echo $produk['satuan_produk']; ?></td>
                                        <td><?php echo number_format($produk['harga'], 0, ',', '.'); ?></td>
                                        <td><?php echo $produk['stok']; ?></td>
                                    </tr>
                                <?php endforeach; ?>
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

            <?php include '../../layout/footer.php'; ?>

        </div>
    </div>

    <script>
        document.getElementById('export-btn').addEventListener('click', function() {
            var element = document.getElementById('report-section');
            var opt = {
                margin: [10, 10, 20, 10],
                filename: 'Laporan_Stok_Barang.pdf',
                image: {
                    type: 'jpeg',
                    quality: 0.98
                },
                html2canvas: {
                    scale: 2
                },
                jsPDF: {
                    unit: 'mm',
                    format: 'A4',
                    orientation: 'portrait'
                }
            };
            html2pdf().from(element).set(opt).save();
        });
    </script>

</body>

</html>