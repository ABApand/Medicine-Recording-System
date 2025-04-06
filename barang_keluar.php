<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}
include 'config.php';

// --- Pagination setup ---
$limit = 5;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

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
                $cekStok = $conn->prepare("SELECT stok FROM produk WHERE nama_item = ? AND takaran = ?");
                $cekStok->bind_param("ss", $item['nama_item'], $item['takaran']);
                $cekStok->execute();
                $cekStok->bind_result($stokSaatIni);
                $cekStok->fetch();
                $cekStok->close();

                if ($stokSaatIni < $item['jumlah']) {
                    throw new Exception("Stok tidak cukup untuk item: " . $item['nama_item']);
                }

                $stmt = $conn->prepare("INSERT INTO resep (nama, no_rm, tgl_lahir, no_resep, nama_item, takaran, jumlah, catatan) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssssssis", $nama, $no_rm, $tgl_lahir, $no_resep, $item['nama_item'], $item['takaran'], $item['jumlah'], $item['catatan']);
                $stmt->execute();
                $stmt->close();

                $kurangiStok = $conn->prepare("UPDATE produk SET stok = stok - ? WHERE nama_item = ? AND takaran = ?");
                $kurangiStok->bind_param("iss", $item['jumlah'], $item['nama_item'], $item['takaran']);
                $kurangiStok->execute();
                $kurangiStok->close();
            }

            $conn->commit();
            $_SESSION['cart'] = [];
            echo "<script>
                alert('Data berhasil disimpan');
                window.location = 'cetak_resep.php?no_resep=" . urlencode($no_resep) . "';
            </script>";
            exit();

        } catch (Exception $e) {
            $conn->rollback();
            echo "<script>alert('Gagal menyimpan data: " . $e->getMessage() . "');</script>";
        }
    } else {
        echo "<script>alert('Keranjang masih kosong!');</script>";
    }
}

$search = $_GET['search'] ?? '';
$produk = null;

// Hitung total produk untuk pagination
if (!empty($search)) {
    $stmt_total = $conn->prepare("SELECT COUNT(*) AS total FROM produk WHERE nama_item LIKE CONCAT('%', ?, '%')");
    $stmt_total->bind_param("s", $search);
    $stmt_total->execute();
    $result_total = $stmt_total->get_result();
} else {
    $result_total = $conn->query("SELECT COUNT(*) AS total FROM produk");
}
$total_row = $result_total->fetch_assoc();
$total_items = $total_row['total'];
$total_pages = ceil($total_items / $limit);

// Ambil data produk terbaru untuk ditampilkan dengan pagination
if (!empty($search)) {
    $stmt = $conn->prepare("SELECT * FROM produk WHERE nama_item LIKE CONCAT('%', ?, '%') ORDER BY id DESC LIMIT ? OFFSET ?");
    $stmt->bind_param("sii", $search, $limit, $offset);
} else {
    $stmt = $conn->prepare("SELECT * FROM produk ORDER BY id DESC LIMIT ? OFFSET ?");
    $stmt->bind_param("ii", $limit, $offset);
}
$stmt->execute();
$produk = $stmt->get_result();

// Hapus item dari keranjang
if (isset($_POST['hapus_item'])) {
    $hapus_index = $_POST['hapus_index'];
    if (isset($_SESSION['cart'][$hapus_index])) {
        unset($_SESSION['cart'][$hapus_index]);
        $_SESSION['cart'] = array_values($_SESSION['cart']);
    }
}
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

        .pagination-btn {
            display: inline-block;
            margin: 0 3px;
            padding: 6px 12px;
            border: 1px solid #28a745;
            border-radius: 6px;
            text-decoration: none;
            color: #28a745;
            font-weight: bold;
            background-color: #fff;
            transition: background-color 0.2s, color 0.2s;
        }

        .pagination-btn:hover {
            background-color: #28a745;
            color: #fff;
        }

        .pagination-btn.active {
            background-color: #28a745;
            color: #fff;
            pointer-events: none;
        }
        input.jumlah-kecil {
    width: 60px;
    padding: 5px;
    text-align: center;
}

    </style>
</head>
<body>
<?php include 'header.php'; ?>
<div class="container">
    <!-- LEFT PANEL -->
    <div class="left-panel">
        <h2>Tambah Obat</h2>
        <form method="get" style="margin-bottom: 10px;">
            <input type="text" name="search" placeholder="Cari Nama Item..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>" style="width: 60%; padding: 8px; border-radius: 6px; border: 1px solid #ccc;">
            <button type="submit" class="btn-green">Cari</button>
            <a href="barang_keluar.php" style="margin-left: 10px; text-decoration: none; color: #007bff;">Reset</a>
        </form>
        <table>
            <tr>
                <th>Nama Item</th>
                <th>Takaran</th>
                <th>Stok</th>
                <th>Jumlah</th>
                <th>Catatan</th>
                <th>Aksi</th>
            </tr>
            <?php if ($produk && $produk->num_rows > 0): ?>
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
                            <td><?= htmlspecialchars($row['stok']) ?></td>
                            <td><input type="number" name="jumlah" value="1" min="1" required class="jumlah-kecil"></td>
                            <td><input type="text" name="catatan" placeholder="Cth: Pagi, Siang, Malam"></td>
                            <td><button type="submit" name="tambah_item" class="btn-green">Tambah</button></td>
                        </form>
                    </tr>
                <?php endwhile; ?>
            <?php elseif ($search): ?>
                <tr><td colspan="6" style="text-align: center; color: gray;">Item tidak ditemukan.</td></tr>
            <?php else: ?>
                <tr><td colspan="6" style="text-align: center; color: gray;">Silakan cari item terlebih dahulu.</td></tr>
            <?php endif; ?>
        </table>

        <?php if (!$search && $total_pages > 1): ?>
            <div style="margin-top: 20px; text-align: center;">
                <?php if ($page > 1): ?>
                    <a href="?page=<?= $page - 1 ?>" class="pagination-btn">&laquo; Prev</a>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <a href="?page=<?= $i ?>" class="pagination-btn <?= ($i == $page) ? 'active' : '' ?>"><?= $i ?></a>
                <?php endfor; ?>

                <?php if ($page < $total_pages): ?>
                    <a href="?page=<?= $page + 1 ?>" class="pagination-btn">Next &raquo;</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- RIGHT PANEL -->
    <div class="right-panel">
        <h2>Data Pasien & Resep</h2>
        <form method="post">
            <div class="form-info">
                Nama:
                <input type="text" name="nama" required>
                No. RM:
                <input type="text" name="no_rm" required>
                Tanggal Lahir:
                <input type="date" name="tgl_lahir" required>
                No. Resep:
                <input type="text" name="no_resep" required>
            </div>
            <button type="submit" name="simpan" class="btn-green">Simpan</button>
        </form>

        <h3>🧺 Keranjang Obat</h3>
        <table border="1" cellpadding="8" cellspacing="0" style="width: 100%; border-collapse: collapse;">
            <thead style="background-color: #f2f2f2;">
                <tr>
                    <th>Nama Item</th>
                    <th>Jumlah</th>
                    <th>Catatan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($_SESSION['cart'])): ?>
                    <?php foreach ($_SESSION['cart'] as $index => $item): ?>
                        <tr>
                            <td><?= htmlspecialchars(($item['nama_item'] ?? 'Tidak diketahui') . ' ' . ($item['takaran'] ?? '')) ?></td>
                            <td><?= htmlspecialchars($item['jumlah'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($item['catatan'] ?? '-') ?></td>
                            <td>
                                <form method="post" style="margin: 0;">
                                    <input type="hidden" name="hapus_index" value="<?= $index ?>">
                                    <button type="submit" name="hapus_item" class="btn-green" style="background-color: #dc3545;">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" style="text-align: center; color: gray;">Keranjang kosong</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
