<?php
require_once 'config.php';
if(!isset($_SESSION['user_id'])) header('Location: login.php');

$buku_id = $_GET['id'] ?? 0;
$error = '';
$success = '';

// Cek apakah buku tersedia
$stmt = $pdo->prepare("SELECT * FROM buku WHERE id = ?");
$stmt->execute([$buku_id]);
$buku = $stmt->fetch();
if(!$buku){
    header('Location: katalog.php');
    exit;
}

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $tgl_pinjam = date('Y-m-d');
    $tgl_harus_kembali = date('Y-m-d', strtotime('+7 days'));
    
    // Cek apakah user sudah meminjam buku yang sama dengan status belum selesai
    $cek = $pdo->prepare("SELECT id FROM peminjaman WHERE user_id = ? AND buku_id = ? AND status IN ('menunggu','dipinjam','dikembalikan')");
    $cek->execute([$_SESSION['user_id'], $buku_id]);
    if($cek->fetch()){
        $error = "Anda sudah meminjam buku ini dan belum selesai!";
    } else {
        $insert = $pdo->prepare("INSERT INTO peminjaman (user_id, buku_id, tgl_pinjam, tgl_harus_kembali, status) VALUES (?,?,?,?, 'menunggu')");
        if($insert->execute([$_SESSION['user_id'], $buku_id, $tgl_pinjam, $tgl_harus_kembali])){
            $success = "Peminjaman diajukan, menunggu konfirmasi admin.";
        } else {
            $error = "Gagal mengajukan peminjaman.";
        }
    }
}
include 'header.php';
?>
<div class="card mx-auto" style="max-width: 600px;">
    <div class="card-header bg-primary text-white">Pinjam Buku</div>
    <div class="card-body">
        <h5><?= htmlspecialchars($buku['judul']) ?></h5>
        <p>Penulis: <?= htmlspecialchars($buku['penulis']) ?><br>Stok tersedia: <?= $buku['stok'] ?></p>
        <?php if($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
        <?php if($success): ?><div class="alert alert-success"><?= $success ?></div><?php endif; ?>
        <?php if(!$success): ?>
        <form method="POST">
            <p>Anda akan meminjam buku ini selama 7 hari. Admin akan mengonfirmasi ketersediaan.</p>
            <button type="submit" class="btn btn-success">Ajukan Peminjaman</button>
            <a href="katalog.php" class="btn btn-secondary">Batal</a>
        </form>
        <?php else: ?>
        <a href="transaksi_saya.php" class="btn btn-primary">Lihat Transaksi Saya</a>
        <?php endif; ?>
    </div>
</div>
<?php include 'footer.php'; ?>