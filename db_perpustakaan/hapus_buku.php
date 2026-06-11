<?php require_once 'config.php';
if($_SESSION['role'] != 'admin') die('Access denied');
$id = $_GET['id'];
$stmt = $pdo->prepare("DELETE FROM buku WHERE id=?");
$stmt->execute([$id]);
header('Location: index.php');
?>