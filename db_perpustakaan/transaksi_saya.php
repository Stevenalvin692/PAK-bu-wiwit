<?php
require_once 'config.php';
if(!isset($_SESSION['user_id'])) header('Location: login.php');

$stmt = $pdo->prepare("
    SELECT p.*, b.judul, b.penulis 
    FROM peminjaman p 
    JOIN buku b ON p.buku_id = b.id 
    WHERE p.user_id = ? 
    ORDER BY p.created_at DESC
");
$stmt->execute([$_SESSION['user_id']]);
$transaksi = $stmt->fetchAll();

include 'header.php';
?>
<h2><i class="fas fa-receipt"></i> Transaksi Peminjaman Saya</h2>
<div class="table-responsive">
    <table class="table table-bordered">
        <thead class="table-dark">
            <tr><th>ID</th><th>Buku</th><th>Tgl Pinjam</th><th>Harus Kembali</th><th>Tgl Kembali</th><th>Status</th><th>Denda</th></tr>
        </thead>
        <tbody>
            <?php foreach($transaksi as $t): ?>
            <tr>
                <td><?= $t['id'] ?></td>
                <td><?= htmlspecialchars($t['judul']) ?></td>
                <td><?= $t['tgl_pinjam'] ?></td>
                <td><?= $t['tgl_harus_kembali'] ?></td>
                <td><?= $t['tgl_kembali'] ?? '-' ?></td>
                <td>
                    <?php
                    $status_badge = [
                        'menunggu' => 'warning',
                        'dipinjam' => 'primary',
                        'dikembalikan' => 'info',
                        'selesai' => 'success'
                    ];
                    ?>
                    <span class="badge bg-<?= $status_badge[$t['status']] ?>"><?= $t['status'] ?></span>
                </td>
                <td>Rp <?= number_format($t['denda'], 0, ',', '.') ?></td>
            </tr>
            <?php endforeach; ?>
            <?php if(count($transaksi)==0): ?>
            <tr><td colspan="7" class="text-center">Belum ada transaksi</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?php include 'footer.php'; ?>