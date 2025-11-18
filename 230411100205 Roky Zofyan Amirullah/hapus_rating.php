<?php
include 'koneksi.php';

if (isset($_GET['id'])) {
    $id_rating = $_GET['id'];
    
    $stmt = $pdo->prepare("DELETE FROM rating WHERE id_rating = ?");
    $stmt->execute([$id_rating]);
}

header("Location: halaman_rating.php");
exit;
?>