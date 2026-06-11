<?php
require_once 'config.php';
if(isset($_SESSION['user_id'])) header('Location: index.php');

$error = '';
$success = '';

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];

    if(empty($username) || empty($email) || empty($password)){
        $error = 'Semua field harus diisi!';
    } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $error = 'Email tidak valid!';
    } elseif($password !== $confirm){
        $error = 'Password dan konfirmasi tidak cocok!';
    } elseif(strlen($password) < 6){
        $error = 'Password minimal 6 karakter!';
    } else {
        // Cek username atau email sudah ada?
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $email]);
        if($stmt->fetch()){
            $error = 'Username atau email sudah terdaftar!';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $role = 'user'; // default user
            // Jika ini adalah user pertama, jadikan admin? (opsional)
            $stmt = $pdo->query("SELECT COUNT(*) FROM users");
            $count = $stmt->fetchColumn();
            if($count == 0) $role = 'admin';
            
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
            if($stmt->execute([$username, $email, $hash, $role])){
                $success = 'Registrasi berhasil! Silakan login.';
            } else {
                $error = 'Registrasi gagal, coba lagi.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Registrasi - Sova Buku</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container">
    <div class="card mx-auto mt-5" style="max-width: 500px;">
        <div class="card-header bg-primary text-white">📚 Daftar Akun Sova Buku</div>
        <div class="card-body">
            <?php if($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
            <?php if($success): ?><div class="alert alert-success"><?= $success ?> <a href="login.php">Login</a></div><?php endif; ?>
            <form method="POST">
                <div class="mb-3"><label>Username</label><input type="text" name="username" class="form-control" required></div>
                <div class="mb-3"><label>Email</label><input type="email" name="email" class="form-control" required></div>
                <div class="mb-3"><label>Password (min 6)</label><input type="password" name="password" class="form-control" required></div>
                <div class="mb-3"><label>Konfirmasi Password</label><input type="password" name="confirm_password" class="form-control" required></div>
                <button type="submit" class="btn btn-primary w-100">Daftar</button>
                <div class="mt-3 text-center">Sudah punya akun? <a href="login.php">Login</a></div>
            </form>
        </div>
    </div>
</div>
</body>
</html>