<?php require_once 'config.php'; 
if($_SESSION['role'] != 'admin') header('Location: katalog.php');
include 'header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h2><i class="fas fa-book"></i> Kelola Buku</h2>
    <a href="tambah_buku.php" class="btn btn-success"><i class="fas fa-plus"></i> Tambah Buku</a>
</div>

<!-- Pencarian -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-8">
                <input type="text" name="search" class="form-control" placeholder="Cari judul atau penulis..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search"></i> Cari</button>
                <a href="index.php" class="btn btn-outline-secondary w-100 mt-2"><i class="fas fa-sync-alt"></i> Sermua Semua</a>
            </div>
        </form>
    </div>
</div>

<?php
$search = $_GET['search'] ?? '';
if($search){
    $stmt = $pdo->prepare("SELECT * FROM buku WHERE judul LIKE ? OR penulis LIKE ? ORDER BY id DESC");
    $stmt->execute(["%$search%", "%$search%"]);
} else {
    $stmt = $pdo->query("SELECT * FROM buku ORDER BY id DESC");
}
$buku = $stmt->fetchAll();
?>

<div class="card shadow">
    <div class="card-body">
        <table class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr><th>ID</th><th>Judul</th><th>Penulis</th><th>Penerbit</th><th>Tahun</th><th>Stok</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                <?php foreach($buku as $row): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['judul']) ?></td>
                    <td><?= htmlspecialchars($row['penulis']) ?></td>
                    <td><?= htmlspecialchars($row['penerbit']) ?></td>
                    <td><?= $row['tahun'] ?></td>
                    <td><?= $row['stok'] ?></td>
                    <td>
                        <a href="edit_buku.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                        <a href="hapus_buku.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin?')">Hapus</a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if(count($buku)==0): ?>
                <tr><td colspan="7" class="text-center">Tidak ada buku</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'footer.php'; ?>