<!-- header.php -->
<link rel="stylesheet" href="style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
    body {
        margin: 0;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .topbar {
        background-color: #2e7d32;
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 20px;
        height: 60px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .topbar .menu-toggle i {
        font-size: 24px;
        cursor: pointer;
    }

    .topbar .logout-button a {
        color: white;
        text-decoration: none;
        background-color: #388e3c;
        padding: 8px 16px;
        border-radius: 5px;
        font-weight: bold;
        transition: 0.3s;
    }

    .topbar .logout-button a:hover {
        background-color: #1b5e20;
    }

    .sidebar {
        position: fixed;
        top: 60px;
        left: 0;
        width: 240px;
        height: 100vh;
        background-color: #f9f9f9;
        border-right: 1px solid #ddd;
        padding-top: 20px;
        display: flex;
        flex-direction: column;
    }

    .menu-item {
        display: flex;
        align-items: center;
        padding: 12px 20px;
        gap: 12px;
        transition: background 0.3s ease;
    }

    .menu-item i {
        color: #2e7d32;
        font-size: 18px;
        width: 24px;
        text-align: center;
    }

    .menu-item button {
        background: none;
        border: none;
        font-size: 16px;
        color: #333;
        cursor: pointer;
        text-align: left;
        flex: 1;
        font-weight: 500;
    }

    .menu-item:hover {
        background-color: #e0f2f1;
    }

    .menu-item button:hover {
        color: #2e7d32;
    }

    @media (max-width: 768px) {
        .sidebar {
            width: 100%;
            height: auto;
            position: relative;
            flex-direction: row;
            justify-content: space-around;
            border-right: none;
            border-bottom: 1px solid #ddd;
        }

        .menu-item {
            flex: 1;
            justify-content: center;
        }

        .main-content {
            margin-left: 0 !important;
        }
    }
</style>

<div class="topbar">
    <div class="menu-toggle">
        <i class="fas fa-bars"></i>
    </div>
    <div class="logout-button">
        <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>
</div>

<div class="sidebar">
    <div class="menu-item">
        <i class="fas fa-cash-register"></i>
        <button onclick="location.href='barang_keluar.php'">Barang Keluar</button>
    </div>
    <div class="menu-item">
        <i class="fas fa-dolly"></i>
        <button onclick="location.href='barang_masuk.php'">Barang Masuk</button>
    </div>
    <div class="menu-item">
        <i class="fas fa-database"></i>
        <button onclick="location.href='penyimpanan.php'">Penyimpanan</button>
    </div>
</div>
