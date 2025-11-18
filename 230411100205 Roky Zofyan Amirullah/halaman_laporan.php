<?php
include 'koneksi.php';

$stmt_denda = $pdo->query(
    "SELECT 
        SUM(jumlah_denda) as total_denda,
        SUM(CASE WHEN status_pembayaran = 'sudah' THEN jumlah_denda ELSE 0 END) as denda_terbayar,
        SUM(CASE WHEN status_pembayaran != 'sudah' THEN jumlah_denda ELSE 0 END) as denda_tertunggak,
        COUNT(*) as total_transaksi_denda
     FROM denda"
);
$laporan_denda = $stmt_denda->fetch();

$stmt_populer = $pdo->query(
    "SELECT b.judul, b.penulis, COUNT(p.id_buku) as total_dipinjam
     FROM peminjaman p
     JOIN buku b ON p.id_buku = b.id_buku
     GROUP BY p.id_buku
     ORDER BY total_dipinjam DESC
     LIMIT 10"
);
$laporan_buku_populer = $stmt_populer->fetchAll();

$stmt_pinjam = $pdo->query(
    "SELECT p.tanggal_pinjam, u.nama, b.judul, p.status
     FROM peminjaman p
     JOIN pengguna u ON p.id_pengguna = u.id_pengguna
     JOIN buku b ON p.id_buku = b.id_buku
     ORDER BY p.tanggal_pinjam DESC LIMIT 50"
);
$laporan_peminjaman = $stmt_pinjam->fetchAll();
?>

<h2>Laporan Sistem Perpustakaan</h2>

<h3>Ringkasan Laporan Denda</h3>
<ul style="list-style: none; padding-left: 0;">
    <li>Total Transaksi Denda: <?php echo $laporan_denda['total_transaksi_denda']; ?></li>
    <li>Total Denda Terbayar: Rp <?php echo number_format($laporan_denda['denda_terbayar']); ?></li>
    <li>Total Denda Tertunggak: Rp <?php echo number_format($laporan_denda['denda_tertunggak']); ?></li>
    <li><b>Total Denda Keseluruhan: Rp <?php echo number_format($laporan_denda['total_denda']); ?></b></li>
</ul>

<h3>Buku Paling Populer (Top 10)</h3>
<table border="1" style="width:100%; border-collapse: collapse;">
    <thead>
        <tr><th>Peringkat</th><th>Judul Buku</th><th>Penulis</th><th>Total Dipinjam</th></tr>
    </thead>
    <tbody>
        <?php $no = 1; foreach ($laporan_buku_populer as $buku) : ?>
        <tr>
            <td><?php echo $no++; ?></td>
            <td><?php echo htmlspecialchars($buku['judul']); ?></td>
            <td><?php echo htmlspecialchars($buku['penulis']); ?></td>
            <td><?php echo $buku['total_dipinjam']; ?> kali</td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<h3>Transaksi Peminjaman Terbaru (50 Terakhir)</h3>
<table border="1" style="width:100%; border-collapse: collapse;">
    <thead>
        <tr><th>Tanggal Pinjam</th><th>Nama Peminjam</th><th>Judul Buku</th><th>Status</th></tr>
    </thead>
    <tbody>
        <?php foreach ($laporan_peminjaman as $pinjam) : ?>
        <tr>
            <td><?php echo $pinjam['tanggal_pinjam']; ?></td>
            <td><?php echo htmlspecialchars($pinjam['nama']); ?></td>
            <td><?php echo htmlspecialchars($pinjam['judul']); ?></td>
            <td><?php echo $pinjam['status']; ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>