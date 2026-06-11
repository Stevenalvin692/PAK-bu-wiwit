<?php
require_once 'config.php';
if($_SESSION['role'] != 'admin') header('Location: katalog.php');

// Konfirmasi peminjaman (ubah status menunggu -> dipinjam)
if(isset($_GET['konfirmasi'])){
    $id = $_GET['konfirmasi'];
    $update = $pdo->prepare("UPDATE peminjaman SET status='dipinjam' WHERE id=? AND status='menunggu'");
    $update->execute([$id]);
    header('Location: admin_transaksi.php');
    exit;
}

// Proses pengembalian (admin input tgl kembali, hitung denda)
if(isset($_POST['proses_kembali'])){
    $id = $_POST['id'];
    $tgl_kembali = date('Y-m-d');
    $stmt = $pdo->prepare("SELECT * FROM peminjaman WHERE id=?");
    $stmt->execute([$id]);
    $pinjam = $stmt->fetch();
    if($pinjam && $pinjam['status'] == 'dipinjam'){
        $tgl_harus = $pinjam['tgl_harus_kembali'];
        $denda = 0;
        if($tgl_kembali > $tgl_harus){
            $late_days = (strtotime($tgl_kembali) - strtotime($tgl_harus)) / (60*60*24);
            $denda = $late_days * 2000; // Rp2000 per hari
        }
        $update = $pdo->prepare("UPDATE peminjaman SET tgl_kembali=?, denda=?, status='dikembalikan' WHERE id=?");
        $update->execute([$tgl_kembali, $denda, $id]);
    }
    header('Location: admin_transaksi.php');
    exit;
}

// Pembayaran denda (ubah status dikembalikan -> selesai & catat pembayaran)
if(isset($_POST['bayar_denda'])){
    $id = $_POST['id'];
    $tgl_bayar = date('Y-m-d');
    $pdo->beginTransaction();
    try {
        $stmt = $pdo->prepare("SELECT denda FROM peminjaman WHERE id=? AND status='dikembalikan'");
        $stmt->execute([$id]);
        $data = $stmt->fetch();
        if($data && $data['denda'] > 0){
            $ins = $pdo->prepare("INSERT INTO pembayaran_denda (peminjaman_id, jumlah, tgl_bayar, status) VALUES (?,?,?,'lunas')");
            $ins->execute([$id, $data['denda'], $tgl_bayar]);
        }
        $upd = $pdo->prepare("UPDATE peminjaman SET status='selesai' WHERE id=?");
        $upd->execute([$id]);
        $pdo->commit();
    } catch(Exception $e){
        $pdo->rollBack();
    }
    header('Location: admin_transaksi.php');
    exit;
}

// Ambil semua transaksi
$stmt = $pdo->query("
    SELECT p.*, u.username, b.judul 
    FROM peminjaman p
    JOIN users u ON p.user_id = u.id
    JOIN buku b ON p.buku_id = b.id
    ORDER BY p.created_at DESC
");
$transaksi = $stmt->fetchAll();

include 'header.php';
?>
<h2>Manajemen Transaksi Peminjaman</h2>
<div class="table-responsive">
    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>ID</th><th>Peminjam</th><th>Buku</th><th>Tgl Pinjam</th><th>Harus Kembali</th><th>Tgl Kembali</th><th>Status</th><th>Denda</th><th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($transaksi as $t): ?>
            <tr>
                <td><?= $t['id'] ?></td>
                <td><?= htmlspecialchars($t['username']) ?></td>
                <td><?= htmlspecialchars($t['judul']) ?></td>
                <td><?= $t['tgl_pinjam'] ?></td>
                <td><?= $t['tgl_harus_kembali'] ?></td>
                <td><?= $t['tgl_kembali'] ?? '-' ?></td>
                <td><?= $t['status'] ?></td>
                <td>Rp <?= number_format($t['denda'],0,',','.') ?></td>
                <td>
                    <?php if($t['status'] == 'menunggu'): ?>
                        <a href="admin_transaksi.php?konfirmasi=<?= $t['id'] ?>" class="btn btn-sm btn-success" onclick="return confirm('Konfirmasi peminjaman?')">Konfirmasi</a>
                    <?php elseif($t['status'] == 'dipinjam'): ?>
                        <form method="POST" style="display:inline-block" onsubmit="return confirm('Proses pengembalian?')">
                            <input type="hidden" name="id" value="<?= $t['id'] ?>">
                            <button type="submit" name="proses_kembali" class="btn btn-sm btn-warning">Proses Kembali</button>
                        </form>
                    <?php elseif($t['status'] == 'dikembalikan' && $t['denda'] > 0): ?>
                        <form method="POST" style="display:inline-block" onsubmit="return confirm('Bayar denda?')">
                            <input type="hidden" name="id" value="<?= $t['id'] ?>">
                            <button type="submit" name="bayar_denda" class="btn btn-sm btn-primary">Bayar Denda</button>
                        </form>
                    <?php elseif($t['status'] == 'dikembalikan' && $t['denda'] == 0): ?>
                        <form method="POST" style="display:inline-block">
                            <input type="hidden" name="id" value="<?= $t['id'] ?>">
                            <button type="submit" name="bayar_denda" class="btn btn-sm btn-secondary">Selesai</button>
                        </form>
                    <?php else: ?>
                        <span class="badge bg-success">Selesai</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php include 'footer.php'; ?>