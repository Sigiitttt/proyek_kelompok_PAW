<?php include 'koneksi.php'; ?>
<h2>Manajemen Ulasan dan Rating Buku</h2>

<table border="1" style="width:100%; border-collapse: collapse;">
    <thead>
        <tr>
            <th>Tanggal</th>
            <th>Mahasiswa</th>
            <th>Judul Buku</th>
            <th>Rating (1-5)</th>
            <th>Ulasan</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $stmt = $pdo->query(
            "SELECT r.id_rating, u.nama, b.judul, r.nilai_rating, r.ulasan, r.dibuat_pada
             FROM rating r
             JOIN pengguna u ON r.id_pengguna = u.id_pengguna
             JOIN buku b ON r.id_buku = b.id_buku
             ORDER BY r.dibuat_pada DESC"
        );
        $semua_rating = $stmt->fetchAll();

        if (empty($semua_rating)) {
            echo '<tr><td colspan="6">Belum ada ulasan yang masuk.</td></tr>';
        }

        foreach ($semua_rating as $rating) : ?>
        <tr>
            <td><?php echo $rating['dibuat_pada']; ?></td>
            <td><?php echo htmlspecialchars($rating['nama']); ?></td>
            <td><?php echo htmlspecialchars($rating['judul']); ?></td>
            <td><?php echo $rating['nilai_rating']; ?> ★</td>
            <td><?php echo nl2br(htmlspecialchars($rating['ulasan'])); ?></td>
            <td>
                <a href="hapus_rating.php?id=<?php echo $rating['id_rating']; ?>" 
                   onclick="return confirm('Yakin ingin menghapus ulasan ini?');">Hapus</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>