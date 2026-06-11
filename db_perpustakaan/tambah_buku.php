<?php require_once 'config.php';
if($_SESSION['role'] != 'admin') header('Location: katalog.php');
include 'header.php'; ?>
<div class="card">
    <div class="card-header bg-primary text-white">Tambah Buku Baru</div>
    <div class="card-body">
        <form method="POST">
            <div class="mb-3"><label>Judul</label><input type="text" name="judul" class="form-control" required></div>
            <div class="mb-3"><label>Penulis</label><input type="text" name="penulis" class="form-control" required></div>
            <div class="mb-3"><label>Penerbit</label><input type="text" name="penerbit" class="form-control"></div>
            <div class="mb-3"><label>Tahun</label><input type="number" name="tahun" class="form-control"></div>
            <div class="mb-3"><label>Stok</label><input type="number" name="stok" class="form-control" value="1"></div>
            <button type="submit" name="simpan" class="btn btn-success">Simpan</button>
            <a href="index.php" class="btn btn-secondary">Batal</a>
        </form>
        <?php if(isset($_POST['simpan'])){
            $stmt = $pdo->prepare("INSERT INTO buku (judul, penulis, penerbit, tahun, stok) VALUES (?,?,?,?,?)");
            $stmt->execute([$_POST['judul'], $_POST['penulis'], $_POST['penerbit'], $_POST['tahun'], $_POST['stok']]);
            echo "<script>alert('Tersimpan'); location.href='index.php';</script>";
        } ?>
    </div>
</div>
<?php include 'footer.php'; ?>