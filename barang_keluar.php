<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}
include 'config.php';

// Tambah item ke sesi (keranjang)
if (isset($_POST['tambah_item'])) {
    $nama_item = $_POST['nama_item'];
    $takaran = $_POST['takaran'];
    $jumlah = $_POST['jumlah'];
    $catatan = $_POST['catatan'];

    $_SESSION['cart'][] = [
        'nama_item' => $nama_item,
        'takaran' => $takaran,
        'jumlah' => $jumlah,
        'catatan' => $catatan
    ];
}

// Simpan data ke DB saat tombol "Simpan" ditekan
if (isset($_POST['simpan'])) {
    $nama = $_POST['nama'] ?? '';
    $no_rm = $_POST['no_rm'] ?? '';
    $tgl_lahir = $_POST['tgl_lahir'] ?? '';
    $no_resep = $_POST['no_resep'] ?? '';

    if (!empty($_SESSION['cart'])) {
        $conn->begin_transaction();

        try {
            foreach ($_SESSION['cart'] as $item) {
                // Cek stok
                $cekStok = $conn->prepare("SELECT stok FROM produk WHERE nama_item = ? AND takaran = ?");
                $cekStok->bind_param("ss", $item['nama_item'], $item['takaran']);
                $cekStok->execute();
                $cekStok->bind_result($stokSaatIni);
                $cekStok->fetch();
                $cekStok->close();

                if ($stokSaatIni < $item['jumlah']) {
                    throw new Exception("Stok tidak cukup untuk item: " . $item['nama_item']);
                }

                // Simpan ke tabel resep
                $stmt = $conn->prepare("INSERT INTO resep (nama, no_rm, tgl_lahir, no_resep, nama_item, takaran, jumlah, catatan) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssssssis", $nama, $no_rm, $tgl_lahir, $no_resep, $item['nama_item'], $item['takaran'], $item['jumlah'], $item['catatan']);
                $stmt->execute();
                $stmt->close();

                // Kurangi stok
                $kurangiStok = $conn->prepare("UPDATE produk SET stok = stok - ? WHERE nama_item = ? AND takaran = ?");
                $kurangiStok->bind_param("iss", $item['jumlah'], $item['nama_item'], $item['takaran']);
                $kurangiStok->execute();
                $kurangiStok->close();
            }

            $conn->commit();
            $_SESSION['cart'] = [];
            echo "<script>alert('Data berhasil disimpan');window.location='barang_keluar.php';</script>";
            exit();
        } catch (Exception $e) {
            $conn->rollback();
            echo "<script>alert('Gagal menyimpan data: " . $e->getMessage() . "');</script>";
        }
    } else {
        echo "<script>alert('Keranjang masih kosong!');</script>";
    }
}


$produk = mysqli_query($conn, "SELECT * FROM produk");




?>

<!DOCTYPE html>
<html>
<head>
    <title>Barang Keluar</title>
    <link rel="stylesheet" href="style.css">
    <style>
        
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f8f9fa;
        }

        .container {
            padding: 20px;
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-left: 270px;
        }

        .left-panel, .right-panel {
            flex: 1 1 48%;
            background: #fff;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }

        h2 {
            margin-top: 0;
        }

        input[type="text"], input[type="number"], input[type="date"] {
            width: 100%;
            padding: 8px;
            margin: 4px 0 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            padding: 10px;
            border: 1px solid #dee2e6;
            text-align: center;
        }

        th {
            background: #f1f3f5;
        }

        .btn-green {
            background-color: #28a745;
            color: white;
            padding: 6px 12px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
        }

        .btn-green:hover {
            background-color: #218838;
        }

        .form-info {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<?php include 'header.php'; ?>
<div class="container">
    <!-- LEFT PANEL -->
    <div class="left-panel">
        <h2>Tambah Obat</h2>
        <table>
    <tr><th>Nama Item</th><th>Takaran</th><th>Jumlah</th><th>Catatan</th><th>Aksi</th></tr>
    <?php while ($row = mysqli_fetch_assoc($produk)): ?>
    <tr>
        <form method="post">
            <td>
                <input type="hidden" name="nama_item" value="<?= htmlspecialchars($row['nama_item']) ?>">
                <?= htmlspecialchars($row['nama_item']) ?>
            </td>
            <td>
                <input type="hidden" name="takaran" value="<?= htmlspecialchars($row['takaran']) ?>">
                <?= htmlspecialchars($row['takaran']) ?>
            </td>
            <td><input type="number" name="jumlah" value="1" min="1" required></td>
            <td><input type="text" name="catatan" placeholder="Pagi, Siang, Malam"></td>
            <td><button type="submit" name="tambah_item" class="btn-green">Tambah</button></td>
        </form>
    </tr>
    <?php endwhile; ?>
</table>

    </div>

    <!-- RIGHT PANEL -->
    <div class="right-panel">
        <h2>Data Pasien & Resep</h2>
        <form method="post">
            <div class="form-info">
                <input type="text" name="nama" placeholder="Nama Pasien" required>
                <input type="text" name="no_rm" placeholder="No. Rekam Medis" required>
                <input type="date" name="tgl_lahir" required>
                <input type="text" name="no_resep" placeholder="Nomor Resep" required>
            </div>

            <h3>🧺 Keranjang Obat</h3>

<table border="1" cellpadding="8" cellspacing="0" style="width: 100%; border-collapse: collapse;">
    <thead style="background-color: #f2f2f2;">
        <tr>
            <th>Nama Item</th>
            <th>Jumlah</th>
            <th>Catatan</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($_SESSION['cart'])): ?>
            <?php foreach ($_SESSION['cart'] as $item): ?>
                <tr>
                    <td>
                        <?= htmlspecialchars(($item['nama_item'] ?? 'Tidak diketahui') . ' ' . ($item['takaran'] ?? '')) ?>
                    </td>
                    <td><?= htmlspecialchars($item['jumlah'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($item['catatan'] ?? '-') ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="3" style="text-align: center; color: gray;">Keranjang kosong</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>


            <br>
            <button type="submit" name="simpan" class="btn-green">Simpan</button>
        </form>
        
    </div>
    
</div>

</body>
</html>
