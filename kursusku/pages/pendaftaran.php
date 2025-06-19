<?php
require_once 'config.php';
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nis = $_POST['nis'];
    $nama = $_POST['nama'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $tanggal_daftar = date('Y-m-d');

    $sql = "INSERT INTO siswa (nis, nama_lengkap, jenis_kelamin, tanggal_daftar) 
            VALUES ('$nis', '$nama', '$jenis_kelamin', '$tanggal_daftar')";
    
    if ($conn->query($sql) === TRUE) {
        $success = "Pendaftaran berhasil!";
    } else {
        $error = "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Pendaftaran Siswa - Kursusku</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include 'header.php'; ?>
    
    <div class="container">
        <h2>Form Pendaftaran Siswa</h2>
        
        <?php if (isset($success)): ?>
            <div class="alert success"><?= $success ?></div>
        <?php endif; ?>
        <?php if (isset($error)): ?>
            <div class="alert error"><?= $error ?></div>
        <?php endif; ?>
        
        <form method="post">
            <div class="form-group">
                <label>NIS</label>
                <input type="text" name="nis" required>
            </div>
            
            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="nama" required>
            </div>
            
            <div class="form-group">
                <label>Jenis Kelamin</label>
                <select name="jenis_kelamin" required>
                    <option value="Laki-laki">Laki-laki</option>
                    <option value="Perempuan">Perempuan</option>
                </select>
            </div>
            
            <button type="submit" class="btn-submit">Daftarkan Siswa</button>
        </form>
    </div>
</body>
</html>
