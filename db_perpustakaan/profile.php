<?php require_once 'config.php'; include 'header.php'; ?>
<div class="card">
    <div class="card-header bg-info text-white"><i class="fas fa-user-shield"></i> PINTOVANAN / Profil Akun</div>
    <div class="card-body">
        <p><strong>Username:</strong> <?= htmlspecialchars($_SESSION['username']) ?></p>
        <p><strong>Role:</strong> <?= ucfirst($_SESSION['role']) ?></p>
        <p><strong>Status:</strong> <?= ($_SESSION['role'] == 'admin') ? 'Administrator' : 'Pengguna Biasa' ?></p>
        <p><strong>Lokasi:</strong> ISTANB - Darbhavand</p>
        <hr>
        <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>
</div>
<?php include 'footer.php'; ?>