<?php
include 'config.php';

$search = $_GET['search'] ?? '';

// Ambil semua no_resep unik berdasarkan pencarian
if (!empty($search)) {
    $stmt = $conn->prepare("SELECT DISTINCT no_resep FROM resep WHERE no_resep LIKE CONCAT('%', ?, '%') ORDER BY created_at DESC");
    $stmt->bind_param("s", $search);
} else {
    $stmt = $conn->prepare("SELECT DISTINCT no_resep FROM resep ORDER BY created_at DESC");
}
$stmt->execute();
$result = $stmt->get_result();

$allResep = [];

while ($row = $result->fetch_assoc()) {
    $noResep = $row['no_resep'];
    $stmtDetail = $conn->prepare("SELECT * FROM resep WHERE no_resep = ?");
    $stmtDetail->bind_param("s", $noResep);
    $stmtDetail->execute();
    $detail = $stmtDetail->get_result()->fetch_all(MYSQLI_ASSOC);
    $allResep[] = [
        'no_resep' => $noResep,
        'data' => $detail
    ];
    $stmtDetail->close();
}
$stmt->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Data Resep</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #f8f9fa; }
        .resep-box {
            background: #fff;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 20px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }
        .resep-box h3 {
            margin-top: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            padding: 8px;
            border: 1px solid #ccc;
        }
        th {
            background: #e9ecef;
        }
        form {
            margin-bottom: 20px;
        }
        input[type="text"] {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 6px;
            width: 250px;
        }
        .btn-green {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 6px;
            cursor: pointer;
            margin-left: 5px;
        }
        .btn-green:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>

<h2>Data Resep</h2>

<form method="get">
    <input type="text" name="search" placeholder="Cari No. Resep..." value="<?= htmlspecialchars($search) ?>">
    <button type="submit" class="btn-green">Cari</button>
    <a href="data_resep.php" class="btn-green" style="background-color: #6c757d;">Reset</a>
</form>

<?php if (!empty($allResep)): ?>
    <?php foreach ($allResep as $resep): ?>
        <div class="resep-box">
            <h3>No. Resep: <?= htmlspecialchars($resep['no_resep']) ?></h3>
            <table>
                <thead>
                    <tr>
                        <th>Nama Pasien</th>
                        <th>No. RM</th>
                        <th>Tgl Lahir</th>
                        <th>Nama Item</th>
                        <th>Takaran</th>
                        <th>Jumlah</th>
                        <th>Catatan</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($resep['data'] as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['nama']) ?></td>
                            <td><?= htmlspecialchars($item['no_rm']) ?></td>
                            <td><?= htmlspecialchars($item['tgl_lahir']) ?></td>
                            <td><?= htmlspecialchars($item['nama_item']) ?></td>
                            <td><?= htmlspecialchars($item['takaran']) ?></td>
                            <td><?= htmlspecialchars($item['jumlah']) ?></td>
                            <td><?= htmlspecialchars($item['catatan']) ?></td>
                            <td><?= htmlspecialchars($item['created_at']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <p>Tidak ada data resep ditemukan.</p>
<?php endif; ?>

<a href="barang_keluar.php" class="btn-green" style="background-color:#007bff;">← Kembali ke Resep</a>

</body>
</html>
