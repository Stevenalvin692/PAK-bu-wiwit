<?php
session_start();
$host = 'localhost';
$dbname = 'db_perpustakaan';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Koneksi gagal: " . $e->getMessage());
}

// Function untuk cek login dan role admin
function isAdmin() {
    return isset($_SESSION['user_id']) && $_SESSION['role'] == 'admin';
}

// Redirect jika belum login atau bukan admin (kecuali halaman tertentu)
$allowed_pages = ['login.php', 'register.php'];
if (!in_array(basename($_SERVER['PHP_SELF']), $allowed_pages)) {
    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit;
    }
    // Untuk halaman admin (semua kecuali katalog, profile bisa diakses user biasa? Sesuaikan)
    // Di sini kita buat: index.php, tambah, edit, hapus hanya untuk admin
    $admin_only = ['index.php', 'tambah_buku.php', 'edit_buku.php', 'hapus_buku.php'];
    if (in_array(basename($_SERVER['PHP_SELF']), $admin_only) && $_SESSION['role'] != 'admin') {
        header('Location: katalog.php');
        exit;
    }
}
?>