<?php require_once 'config.php'; include 'header.php'; ?>
<h2><i class="fas fa-th-large"></i> Katalogari Buku</h2>
<div class="row">
    <?php
    $stmt = $pdo->query("SELECT * FROM buku ORDER BY id DESC");
    foreach($stmt as $row): ?>
    <div class="col-md-3 mb-4">
        <div class="card h-100 shadow">
            <div class="card-body">
                <h5 class="card-title"><?= htmlspecialchars($row['judul']) ?></h5>
                <p class="card-text"><strong>Penulis:</strong> <?= htmlspecialchars($row['penulis']) ?><br>
                <strong>Penerbit:</strong> <?= htmlspecialchars($row['penerbit']) ?><br>
                <strong>Tahun:</strong> <?= $row['tahun'] ?><br>
                <strong>Stok:</strong> <?= $row['stok'] ?></p>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php include 'footer.php'; ?>