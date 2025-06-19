<?php
// Tampilkan error agar debugging lebih mudah
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

// Cek apakah user sudah login
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

// Koneksi ke database
require_once 'config.php';

// Handle form jika disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $siswa_id = $_POST['siswa_id'];
    $kelas_id = $_POST['kelas_id'];
    $jumlah = $_POST['jumlah'];
    $tanggal = date('Y-m-d');

    $sql = "INSERT INTO pembayaran (siswa_id, kelas_id, jumlah, tanggal_bayar)
            VALUES ('$siswa_id', '$kelas_id', '$jumlah', '$tanggal')";

    if (!$conn->query($sql)) {
        echo "Gagal menyimpan pembayaran: " . $conn->error;
    }
}

// Ambil data siswa & kelas
$siswa = $conn->query("SELECT * FROM siswa");
$kelas = $conn->query("SELECT * FROM kelas");

// Ambil data pembayaran dengan join ke siswa & kelas
$pembayaran = $conn->query("
    SELECT p.*, s.nama_lengkap, k.nama_kelas 
    FROM pembayaran p
    JOIN siswa s ON p.siswa_id = s.id
    JOIN kelas k ON p.kelas_id = k.id
");
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Pembayaran Kursus</title>
    <link rel="stylesheet" href="../styles.css"> <!-- Sesuaikan jika styles ada di luar -->
</head>
<body>
    <?php include './pages/header.php'; ?>

    <div class="container">
        <h2>Pembayaran Kursus</h2>

        <div class="form-container">
            <form method="post">
                <div class="form-group">
                    <label>Siswa</label>
                    <select name="siswa_id" required>
                        <option value="">-- Pilih Siswa --</option>
                        <?php while($row = $siswa->fetch_assoc()): ?>
                            <option value="<?= $row['id'] ?>"><?= $row['nama_lengkap'] ?> (<?= $row['nis'] ?>)</option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Kelas</label>
                    <select name="kelas_id" required>
                        <option value="">-- Pilih Kelas --</option>
                        <?php while($row = $kelas->fetch_assoc()): ?>
                            <option value="<?= $row['id'] ?>"><?= $row['nama_kelas'] ?> (Rp <?= number_format($row['biaya'], 0, ',', '.') ?>)</option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Jumlah Pembayaran</label>
                    <input type="number" name="jumlah" required>
                </div>

                <button type="submit" class="btn-submit">Simpan Pembayaran</button>
            </form>
        </div>

        <h3>Riwayat Pembayaran</h3>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Siswa</th>
                    <th>Kelas</th>
                    <th>Jumlah</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $pembayaran->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['nama_lengkap'] ?></td>
                        <td><?= $row['nama_kelas'] ?></td>
                        <td>Rp <?= number_format($row['jumlah'], 0, ',', '.') ?></td>
                        <td><?= date('d/m/Y', strtotime($row['tanggal_bayar'])) ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>