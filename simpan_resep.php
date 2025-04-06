<?php
session_start();
require 'config.php';

$nama = $_POST['nama'] ?? '';
$rm = $_POST['rm'] ?? '';
$tanggal = $_POST['tanggal'] ?? '';
$resep = $_POST['resep'] ?? '';

$conn->query("INSERT INTO resep (nama_pasien, no_rm, tanggal_lahir, no_resep) VALUES ('$nama', '$rm', '$tanggal', '$resep')");
$resep_id = $conn->insert_id;

foreach ($_SESSION['cart'] as $item) {
    $id_produk = $item['id'];
    $qty = $item['qty'];
    $pagi = $item['pagi'];
    $siang = $item['siang'];
    $makan = $item['makan'];

    $conn->query("INSERT INTO resep_detail (resep_id, produk_id, jumlah_tablet, catatan_pagi, catatan_siang, catatan_makan)
        VALUES ('$resep_id', '$id_produk', '$qty', '$pagi', '$siang', '$makan')");
}

$_SESSION['cart'] = [];
header("Location: barang_keluar.php?success=1");
