<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Penyimpanan - MRS</title>
    <link rel="stylesheet" href="style.css">
    <style>
        * {
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            margin: 0;
            padding: 0;
            background: #f8f9fa;
        }

        .main-content {
            margin-left: 270px;
            padding: 20px;
        }

        .search-box {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .search-box input {
            padding: 10px;
            font-size: 16px;
            width: 300px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }

        .search-box i {
            font-size: 22px;
            margin-left: 10px;
            cursor: pointer;
        }

        .container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .left-panel, .right-panel {
            flex: 1 1 48%;
            background: #fff;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 10px;
        }

        th, td {
            border: 1px solid #dee2e6;
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #f1f3f5;
        }

        input[type="text"] {
            width: 100%;
            padding: 6px;
            margin: 2px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .qty-control {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
        }

        .qty-btn {
            font-size: 18px;
            font-weight: bold;
            padding: 5px 12px;
            background-color: #e9ecef;
            border: 1px solid #ccc;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn-tambah, .btn-simpan, .btn-cetak {
            background-color: #28a745;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .btn-tambah:hover,
        .btn-simpan:hover,
        .btn-cetak:hover {
            background-color: #218838;
        }

        .form-info input {
            margin-bottom: 8px;
        }

        @media (max-width: 768px) {
            .container {
                flex-direction: column;
            }

            .left-panel, .right-panel {
                flex: 1 1 100%;
            }

            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
<?php include 'header.php'; ?>

<div class="main-content">
    <h2>Penyimpanan</h2>
    <!-- Konten halaman Barang Keluar -->
</div>
</body>
</html>
