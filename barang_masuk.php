<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

include 'config.php';

// Tampilkan error MySQL sebagai exception
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Menyimpan data ke database
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['simpan'])) {
    $nama_supplier = $_POST['nama_supplier'];
    $tanggal = $_POST['tanggal'];
    $petugas = $_POST['petugas'];

    try {
        // Simpan ke barang_masuk
        $stmt = $conn->prepare("INSERT INTO barang_masuk (nama_supplier, tanggal, petugas) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $nama_supplier, $tanggal, $petugas);
        $stmt->execute();
        $barang_masuk_id = $stmt->insert_id;

        if (!empty($_POST['nama_item'])) {
            foreach ($_POST['nama_item'] as $i => $nama_item) {
                $takaran = $_POST['takaran'][$i];
                $uom = $_POST['uom'][$i];
                $jumlah_tablet = $_POST['jumlah_tablet'][$i];
                $kadaluarsa = $_POST['kadaluarsa'][$i];
                $no_rak = $_POST['no_rak'][$i];

                // Cek apakah item sudah ada di tabel produk
$cek = $conn->prepare("SELECT stok FROM produk WHERE nama_item = ? AND takaran = ? AND uom = ?");
$cek->bind_param("sss", $nama_item, $takaran, $uom);
$cek->execute();
$result = $cek->get_result();

if ($result->num_rows > 0) {
    // Update stok jika sudah ada
    $update = $conn->prepare("UPDATE produk SET stok = stok + ? WHERE nama_item = ? AND takaran = ? AND uom = ?");
    $update->bind_param("isss", $jumlah_tablet, $nama_item, $takaran, $uom);
    $update->execute();
} else {
    // Insert produk baru
    $insert = $conn->prepare("INSERT INTO produk (nama_item, takaran, uom, stok) VALUES (?, ?, ?, ?)");
    $insert->bind_param("sssi", $nama_item, $takaran, $uom, $jumlah_tablet);
    $insert->execute();
}


                // Simpan detail barang masuk
                $detail = $conn->prepare("INSERT INTO detail_barang_masuk 
                    (barang_masuk_id, nama_item, takaran, uom, jumlah_tablet, kadaluarsa, no_rak) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)");
                $detail->bind_param("isssiss", $barang_masuk_id, $nama_item, $takaran, $uom, $jumlah_tablet, $kadaluarsa, $no_rak);
                $detail->execute();
            }
        }

        echo "<script>alert('Data berhasil disimpan!'); window.location='barang_masuk.php';</script>";
    } catch (Exception $e) {
        echo "<script>alert('Terjadi kesalahan: " . $e->getMessage() . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Barang Masuk - MRS</title>
    <link rel="stylesheet" href="style.css">
    <style>
    * {
        box-sizing: border-box;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    body {
        margin: 0;
        padding: 0;
        background-color: #f4f6f9;
        color: #333;
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

    h2, h3 {
        color: #2c3e50;
        margin-bottom: 20px;
    }

    .form-container {
        display: grid;
        grid-template-columns: 1fr 200px;
        gap: 20px;
        margin-bottom: 30px;
    }

    .form-left label {
        display: block;
        margin-bottom: 12px;
    }

    .form-left label span {
        display: block;
        font-weight: bold;
        margin-bottom: 5px;
    }

    input[type="text"], input[type="date"], input[type="number"] {
        width: 100%;
        padding: 8px 10px;
        border: 1px solid #ccc;
        border-radius: 6px;
    }

    .btn-simpan, .btn-tambah {
        background-color: #28a745;
        color: #fff;
        border: none;
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: bold;
        cursor: pointer;
        margin-top: 8px;
    }

    .btn-simpan:hover, .btn-tambah:hover {
        background-color: #218838;
    }

    .table-container {
        overflow-x: auto;
        margin-bottom: 40px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        background-color: #fff;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }

    th, td {
        padding: 12px;
        border: 1px solid #e1e5ea;
        text-align: center;
    }

    th {
        background-color: #f1f3f5;
        font-weight: bold;
        color: #333;
    }

    tbody tr:hover {
        background-color: #f8f9fa;
    }

    @media screen and (max-width: 768px) {
        .main-content {
            margin-left: 0;
            padding: 15px;
        }

        .form-container {
            grid-template-columns: 1fr;
        }
    }
</style>
</head>
<body>
<?php include 'header.php'; ?>

<div class="container">
<div class="left-panel">
    <h2>Barang Masuk</h2>

    <form method="POST">
        <div class="form-container">
                <label><span>Nama Supplier</span><input type="text" name="nama_supplier" required></label>
                <label><span>Tanggal</span><input type="date" name="tanggal" required></label>
                <label><span>Petugas</span><input type="text" name="petugas" value="<?= $_SESSION['username'] ?>" required></label>
                <button type="submit" name="simpan" class="btn-simpan">Simpan</button>
            </div>

        <div class="table-container">
            <table id="barang-table">
                <thead>
                    <tr>
                        <th>Nama Item</th>
                        <th>Takaran</th>
                        <th>UoM</th>
                        <th>Jumlah Tablet</th>
                        <th>Kadaluarsa</th>
                        <th>No Rak</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><input type="text" name="nama_item[]"></td>
                        <td><input type="text" name="takaran[]"></td>
                        <td><input type="text" name="uom[]"></td>
                        <td><input type="number" name="jumlah_tablet[]"></td>
                        <td><input type="date" name="kadaluarsa[]"></td>
                        <td><input type="text" name="no_rak[]"></td>
                    </tr>
                </tbody>
            </table>
            <button type="button" class="btn-tambah" onclick="tambahBaris()">Tambah Baris</button>
        </div>
        </div>
    </form>

    <!-- LIVE STOK PRODUK -->
    <div class="right-panel">
    <h3>📦 Daftar Stok Produk (Live)</h3>
    <table>
        <thead>
            <tr>
                <th>Nama Item</th>
                <th>Takaran</th>
                <th>UoM</th>
                <th>Stok</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $stok_produk = $conn->query("SELECT * FROM produk ORDER BY nama_item ASC");
            if ($stok_produk->num_rows > 0):
                while ($row = $stok_produk->fetch_assoc()):
            ?>
            <tr>
                <td><?= htmlspecialchars($row['nama_item']) ?></td>
                <td><?= htmlspecialchars($row['takaran']) ?></td>
                <td><?= htmlspecialchars($row['uom']) ?></td>
                <td><?= htmlspecialchars($row['stok']) ?></td>
            </tr>
            <?php
                endwhile;
            else:
            ?>
            <tr><td colspan="4" style="text-align:center; color:gray;">Belum ada data stok.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
</div>
</div>

<script>
function tambahBaris() {
    const table = document.getElementById('barang-table').getElementsByTagName('tbody')[0];
    const row = table.insertRow();

    const kolom = ["nama_item", "takaran", "uom", "jumlah_tablet", "kadaluarsa", "no_rak"];
    for (let i = 0; i < kolom.length; i++) {
        const cell = row.insertCell(i);
        const input = document.createElement("input");
        input.name = kolom[i] + "[]";
        input.type = (kolom[i] === "jumlah_tablet") ? "number" : (kolom[i] === "kadaluarsa") ? "date" : "text";
        cell.appendChild(input);
    }
}
</script>

</body>
</html>
