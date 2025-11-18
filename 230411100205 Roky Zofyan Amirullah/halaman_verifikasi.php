<?php include 'koneksi.php'; ?>
<h2>Verifikasi Pembayaran Denda</h2>

<table border="1" style="width:100%; border-collapse: collapse;">
    <thead>
        <tr>
            <th>ID Denda</th>
            <th>Nama Mahasiswa</th>
            <th>Jumlah Denda</th>
            <th>Metode</th>
            <th>Bukti Bayar</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $stmt = $pdo->prepare(
            "SELECT d.id_denda, p.nama, d.jumlah_denda, pay.bukti_pembayaran, pay.metode_bayar
             FROM denda d
             JOIN pengguna p ON d.id_pengguna = p.id_pengguna
             JOIN pembayaran pay ON d.id_denda = pay.id_denda
             WHERE d.status_pembayaran = 'menunggu_verifikasi'"
        );
        $stmt->execute();
        $list_verifikasi = $stmt->fetchAll();

        if (empty($list_verifikasi)) {
            echo '<tr><td colspan="6">Tidak ada data yang perlu diverifikasi.</td></tr>';
        }

        foreach ($list_verifikasi as $data) : ?>
            <tr>
                <td><?php echo $data['id_denda']; ?></td>
                <td><?php echo htmlspecialchars($data['nama']); ?></td>
                <td>Rp <?php echo number_format($data['jumlah_denda']); ?></td>
                <td><?php echo $data['metode_bayar']; ?></td>
                <td>
                    <?php if ($data['metode_bayar'] == 'QRIS') : ?>
                        <a href="uploads/bukti/<?php echo $data['bukti_pembayaran']; ?>" target="_blank">Lihat Bukti</a>
                    <?php else : echo 'N/A (Cash)'; endif; ?>
                </td>
                <td>
                    <form action="proses_verifikasi.php" method="POST" style="display:inline;">
                        <input type="hidden" name="id_denda" value="<?php echo $data['id_denda']; ?>">
                        <button type="submit" name="aksi" value="setujui">Setujui</button>
                    </form>
                    <form action="proses_verifikasi.php" method="POST" style="display:inline;">
                        <input type="hidden" name="id_denda" value="<?php echo $data['id_denda']; ?>">
                        <button type="submit" name="aksi" value="tolak">Tolak</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>