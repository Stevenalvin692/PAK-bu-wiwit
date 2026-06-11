<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sova Buku - <?= ucfirst($_SESSION['role']) ?> Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="d-flex" id="wrapper">
    <!-- Sidebar -->
    <div class="bg-dark text-white" id="sidebar-wrapper" style="min-width: 250px;">
        <div class="sidebar-heading text-center py-4 fs-4 fw-bold border-bottom">
            <i class="fas fa-book-open me-2"></i>Sova Buku
        </div>
        <div class="list-group list-group-flush my-3">
            <?php if($_SESSION['role'] == 'admin'): ?>
                <a href="index.php" class="list-group-item list-group-item-action bg-dark text-white">
                    <i class="fas fa-table me-2"></i>Kelola Buku
                </a>
            <?php endif; ?>
            <a href="katalog.php" class="list-group-item list-group-item-action bg-dark text-white">
                <i class="fas fa-list me-2"></i>Katalogari
            </a>
            <a href="profile.php" class="list-group-item list-group-item-action bg-dark text-white">
                <i class="fas fa-user-cog me-2"></i>PINTOVANAN
            </a>
            <a href="logout.php" class="list-group-item list-group-item-action bg-dark text-white">
                <i class="fas fa-sign-out-alt me-2"></i>Logout
            </a>
        </div>
        <div class="text-center small p-3 border-top">
            <i class="fas fa-user-shield me-1"></i> <?= ucfirst($_SESSION['role']) ?><br>
            <span class="text-warning"><?= htmlspecialchars($_SESSION['username']) ?></span>
        </div>
    </div>
    <!-- Page Content -->
    <div id="page-content-wrapper" class="w-100">
        <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
            <div class="container-fluid">
                <button class="btn btn-primary" id="menu-toggle"><i class="fas fa-bars"></i></button>
                <span class="ms-3 fw-bold">ISTANB | Darbhavand</span>
                <div class="dropdown ms-auto">
                    <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle"></i> <?= htmlspecialchars($_SESSION['username']) ?>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="profile.php">Profil</a></li>
                        <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="container-fluid px-4 py-3">

        <?php if($_SESSION['role'] == 'admin'): ?>
    <a href="admin_transaksi.php" class="list-group-item list-group-item-action bg-dark text-white">
        <i class="fas fa-exchange-alt me-2"></i>Transaksi
    </a>
<?php else: ?>
    <a href="transaksi_saya.php" class="list-group-item list-group-item-action bg-dark text-white">
        <i class="fas fa-receipt me-2"></i>Transaksi Saya
    </a>
<?php endif; ?>