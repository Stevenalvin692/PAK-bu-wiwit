<?php require_once 'config.php';
if($_SESSION['role'] != 'admin') header('Location: katalog.php');
include 'header.php';
$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM buku WHERE id=?");
$stmt->execute([$id]);
$buku = $stmt->fetch();
if(!$buku) header('Location: index.php');
?>
<div class="card">
    <div class="card-header bg-warning">Edit Buku</div>
    <div class="card-body">
        <form method="POST">
            <div class="mb-3"><label>Judul</label><input type="text" name="judul" value="<?= htmlspecialchars($buku['judul']) ?>" class="form-control" required></div>
            <div class="mb-3"><label>Penulis</label><input type="text" name="penulis" value="<?= htmlspecialchars($buku['penulis']) ?>" class="form-control" required></div>
            <div class="mb-3"><label>Penerbit</label><input type="text" name="penerbit" value="<?= htmlspecialchars($buku['penerbit']) ?>" class="form-control"></div>
            <div class="mb-3"><label>Tahun</label><input type="number" name="tahun" value="<?= $buku['tahun'] ?>" class="form-control"></div>
            <div class="mb-3"><label>Stok</label><input type="number" name="stok" value="<?= $buku['stok'] ?>" class="form-control"></div>
            <button type="submit" name="update" class="btn btn-primary">Update</button>
            <a href="index.php" class="btn btn-secondary">Batal</a>
        </form>
        <?php if(isset($_POST['update'])){
            $upd = $pdo->prepare("UPDATE buku SET judul=?, penulis=?, penerbit=?, tahun=?, stok=? WHERE id=?");
            $upd->execute([$_POST['judul'], $_POST['penulis'], $_POST['penerbit'], $_POST['tahun'], $_POST['stok'], $id]);
            echo "<script>alert('Updated'); location.href='index.php';</script>";
        } ?>
    </div>
</div>
<?php include 'footer.php'; ?>