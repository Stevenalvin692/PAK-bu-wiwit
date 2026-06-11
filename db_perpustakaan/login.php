<?php
require_once 'config.php';
if(isset($_SESSION['user_id'])) header('Location: index.php');

$error = '';
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $username = $_POST['username'];
    $password = $_POST['password'];
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
    $stmt->execute([$username, $username]);
    $user = $stmt->fetch();
    if($user && password_verify($password, $user['password'])){
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        header('Location: index.php');
        exit;
    } else {
        $error = 'Username/email atau password salah!';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - Sova Buku</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container">
    <div class="card mx-auto mt-5" style="max-width: 400px;">
        <div class="card-header bg-dark text-white">🔐 Login Administrator / User</div>
        <div class="card-body">
            <?php if($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
            <form method="POST">
                <div class="mb-3"><label>Username atau Email</label><input type="text" name="username" class="form-control" required></div>
                <div class="mb-3"><label>Password</label><input type="password" name="password" class="form-control" required></div>
                <button type="submit" class="btn btn-primary w-100">Masuk</button>
                <div class="mt-3 text-center">Belum punya akun? <a href="register.php">Registrasi</a></div>
            </form>
        </div>
    </div>
</div>
</body>
</html>