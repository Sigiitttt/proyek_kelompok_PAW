<?php
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    exit('Akses ditolak');
}

$id_denda = $_POST['id_denda'];
$aksi = $_POST['aksi'];

if ($aksi == 'setujui') {
    $stmt_denda = $pdo->prepare("UPDATE denda SET status_pembayaran = 'sudah' WHERE id_denda = ?");
    $stmt_denda->execute([$id_denda]);

    $stmt_bayar = $pdo->prepare("UPDATE pembayaran SET tgl_verifikasi = NOW() WHERE id_denda = ?");
    $stmt_bayar->execute([$id_denda]);

} elseif ($aksi == 'tolak') {
    $stmt_denda = $pdo->prepare("UPDATE denda SET status_pembayaran = 'belum' WHERE id_denda = ?");
    $stmt_denda->execute([$id_denda]);

    $stmt_bayar = $pdo->prepare("DELETE FROM pembayaran WHERE id_denda = ?");
    $stmt_bayar->execute([$id_denda]);
}

header("Location: halaman_verifikasi.php");
exit;
?>