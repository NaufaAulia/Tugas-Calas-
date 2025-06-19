<?php
require_once 'config.php';
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama_kelas'];
    $biaya = $_POST['biaya'];

    $sql = "INSERT INTO kelas (nama_kelas, biaya) VALUES ('$nama', '$biaya')";
    $conn->query($sql);
}

$kelas = $conn->query("SELECT * FROM kelas");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Data Kelas - Kursusku</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include 'header.php'; ?>
    
    <div class="container">
        <h2>Data Kelas</h2>
        
        <div class="form-container">
            <h3>Tambah Kelas Baru</h3>
            <form method="post">
                <div class="form-group">
                    <label>Nama Kelas</label>
                    <input type="text" name="nama_kelas" required>
                </div>
                
                <div class="form-group">
                    <label>Biaya</label>
                    <input type="number" name="biaya" required>
                </div>
                
                <button type="submit" class="btn-submit">Tambahkan Kelas</button>
            </form>
        </div>
        
        <table class="data-table">
            <thead>
                <tr>
                    <th>Nama Kelas</th>
                    <th>Biaya</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $kelas->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['nama_kelas'] ?></td>
                    <td>Rp <?= number_format($row['biaya'], 0, ',', '.') ?></td>
                    <td>
                        <a href="edit_kelas.php?id=<?= $row['id'] ?>" class="btn-edit">Edit</a>
                        <a href="hapus_kelas.php?id=<?= $row['id'] ?>" class="btn-hapus">Hapus</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
