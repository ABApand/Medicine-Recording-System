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
    <title>Dashboard - Medicine Recording System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include 'header.php'; ?>

<div class="main-content">
    <h2>Dashboard</h2>
    <!-- Konten halaman Barang Keluar -->
</div>
</body>
</html>
