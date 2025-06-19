<?php
require_once 'config.php';
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

$sql = "SELECT * FROM siswa";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Data Siswa - Kursusku</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include 'header.php'; ?>
    
    <div class="container">
        <h2>Data Siswa</h2>
        
        <table class="data-table">
            <thead>
                <tr>
                    <th>NIS</th>
                    <th>Nama Lengkap</th>
                    <th>Jenis Kelamin</th>
                    <th>Tanggal Daftar</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['nis'] ?></td>
                    <td><?= $row['nama_lengkap'] ?></td>
                    <td><?= $row['jenis_kelamin'] ?></td>
                    <td><?= date('d/m/Y', strtotime($row['tanggal_daftar'])) ?></td>
                    <td>
                        <a href="edit_siswa.php?id=<?= $row['id'] ?>" class="btn-edit">Edit</a>
                        <a href="hapus_siswa.php?id=<?= $row['id'] ?>" class="btn-hapus">Hapus</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
