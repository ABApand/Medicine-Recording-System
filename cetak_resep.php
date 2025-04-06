<?php
include 'config.php';

$no_resep = $_GET['no_resep'] ?? '';

if (!$no_resep) {
    echo "Nomor resep tidak ditemukan.";
    exit();
}

$stmt = $conn->prepare("SELECT * FROM resep WHERE no_resep = ?");
$stmt->bind_param("s", $no_resep);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_all(MYSQLI_ASSOC);

if (empty($data)) {
    echo "Resep tidak ditemukan.";
    exit();
}

$pasien = $data[0];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Resep</title>
    <style>
        body {
            font-family: "Segoe UI", sans-serif;
            padding: 40px;
            background-color: #f4f4f4;
            color: #333;
        }

        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            max-width: 700px;
            margin: auto;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            margin-top: 0;
        }

        .info {
            margin-bottom: 20px;
        }

        .info p {
            margin: 6px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f7f7f7;
        }

        .btn-container {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 30px;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            font-weight: bold;
            text-decoration: none;
        }

        .btn-print {
            background-color: #28a745;
            color: white;
        }

        .btn-print:hover {
            background-color: #218838;
        }

        .btn-back {
            background-color: #6c757d;
            color: white;
        }

        .btn-back:hover {
            background-color: #5a6268;
        }

        @media print {
            .btn-container {
                display: none;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Resep Obat</h2>

    <div class="info">
        <p><strong>Nama Pasien:</strong> <?= htmlspecialchars($pasien['nama']) ?></p>
        <p><strong>No Rekam Medis:</strong> <?= htmlspecialchars($pasien['no_rm']) ?></p>
        <p><strong>Tanggal Lahir:</strong> <?= htmlspecialchars($pasien['tgl_lahir']) ?></p>
        <p><strong>No Resep:</strong> <?= htmlspecialchars($pasien['no_resep']) ?></p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Nama Item</th>
                <th>Takaran</th>
                <th>Jumlah</th>
                <th>Catatan</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($data as $item): ?>
            <tr>
                <td><?= htmlspecialchars($item['nama_item']) ?></td>
                <td><?= htmlspecialchars($item['takaran']) ?></td>
                <td><?= htmlspecialchars($item['jumlah']) ?></td>
                <td><?= htmlspecialchars($item['catatan']) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="btn-container">
        <button class="btn btn-print" onclick="window.print()">🖨 Cetak Resep</button>
        <a href="barang_keluar.php" class="btn btn-back">⬅ Kembali ke Halaman Resep</a>
    </div>
</div>

</body>
</html>
