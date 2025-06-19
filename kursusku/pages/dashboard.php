<?php
require_once "config.php";
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

// Ambil data statistik
$total_siswa = $conn->query("SELECT COUNT(*) as total FROM siswa")->fetch_assoc()['total'];
$total_kelas = $conn->query("SELECT COUNT(*) as total FROM kelas")->fetch_assoc()['total'];
$total_pembayaran = $conn->query("SELECT SUM(jumlah) as total FROM pembayaran WHERE MONTH(tanggal_bayar) = MONTH(CURRENT_DATE())")->fetch_assoc()['total'];

// Ambil data aktivitas terakhir
$aktivitas = $conn->query("
    SELECT s.nama_lengkap, k.nama_kelas, p.jumlah, p.tanggal_bayar, p.status
    FROM pembayaran p
    JOIN siswa s ON p.siswa_id = s.id
    JOIN kelas k ON p.kelas_id = k.id
    ORDER BY p.tanggal_bayar DESC LIMIT 5
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Kursusku</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>
    <div class="sidebar">
        <div class="logo">Kursusku</div>
        <ul class="nav-list">
            <li class="nav-item active"><i class="fas fa-tachometer-alt"></i> <span>Dashboard</span></li>
            <li class="nav-item"><i class="fas fa-user-plus"></i> <span>Pendaftaran</span></li>
            <li class="nav-item"><i class="fas fa-users"></i> <span>Data Siswa</span></li>
            <li class="nav-item"><i class="fas fa-chalkboard"></i> <span>Data Kelas</span></li>
            <li class="nav-item"><i class="fas fa-money-bill-wave"></i> <span>Pembayaran</span></li>
            <li class="nav-item"><i class="fas fa-sign-out-alt"></i> <span>Keluar</span></li>
        </ul>
    </div>
    
    <div class="main-content">
        <div class="header">
            <h2>Dashboard</h2>
            <div class="user-info">
                <div class="user-avatar">A</div>
                <span><?= $_SESSION['username'] ?></span>
            </div>
        </div>
        
        <div class="dashboard-cards">
            <div class="card">
                <div class="card-icon"><i class="fas fa-users"></i></div>
                <h3>Total Siswa</h3>
                <p><?= $total_siswa ?></p>
            </div>
            <div class="card">
                <div class="card-icon"><i class="fas fa-chalkboard"></i></div>
                <h3>Total Kelas</h3>
                <p><?= $total_kelas ?></p>
            </div>
            <div class="card">
                <div class="card-icon"><i class="fas fa-money-bill-wave"></i></div>
                <h3>Pembayaran Bulan Ini</h3>
                <p>Rp <?= number_format($total_pembayaran, 0, ',', '.') ?></p>
            </div>
        </div>
        
        <h3 style="margin-bottom: 15px;">Aktivitas Terakhir</h3>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Nama Siswa</th>
                    <th>Kelas</th>
                    <th>Pembayaran</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $aktivitas->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['nama_lengkap'] ?></td>
                    <td><?= $row['nama_kelas'] ?></td>
                    <td>Rp <?= number_format($row['jumlah'], 0, ',', '.') ?></td>
                    <td><?= date('d M Y', strtotime($row['tanggal_bayar'])) ?></td>
                    <td><span style="color: <?= $row['status'] == 'Lunas' ? '#4CAF50' : '#F44336' ?>">
                        <?= $row['status'] ?>
                    </span></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <script>
        // Script untuk navigasi sidebar
        document.querySelectorAll('.nav-item').forEach(item => {
            item.addEventListener('click', function() {
                const menuItems = this.parentElement.children;
                for (let i = 0; i < menuItems.length; i++) {
                    menuItems[i].classList.remove('active');
                }
                this.classList.add('active');
                
                // Redirect ke halaman yang sesuai
                const menuText = this.querySelector('span').textContent;
                if (menuText === 'Pendaftaran') window.location.href = 'pendaftaran.php';
                if (menuText === 'Data Siswa') window.location.href = 'data_siswa.php';
                if (menuText === 'Data Kelas') window.location.href = 'data_kelas.php';
                if (menuText === 'Pembayaran') window.location.href = 'pembayaran.php';
                if (menuText === 'Keluar') window.location.href = 'logout.php';
            });
        });
    </script>
</body>
</html>