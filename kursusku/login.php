<?php
// login.php - File Login yang Sudah Diperbaiki
require_once './pages/config.php';
session_start();

// Redirect jika sudah login
if (isset($_SESSION['login'])) {
    header("Location: ./pages/dashboard.php");
    exit;
}

$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password']; // Tidak perlu escape untuk password

    // Query sesuai dengan struktur database kursusku
    $sql = "SELECT * FROM admin WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        
        // Verifikasi password (sesuai dengan data sample di SQL sebelumnya)
        if ($password === $row['password']) { 
            $_SESSION['login'] = true;
            $_SESSION['username'] = $row['username'];
            header("Location: ./pages/dashboard.php");
            exit;
        } else {
            $error_message = "Username atau password salah!";
        }
    } else {
        $error_message = "Username tidak ditemukan!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Kursusku</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="login.css">
    <style>
    
        /* Gaya CSS untuk halaman login */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #3f51b5 0%, #2196F3 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        
        .login-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            padding: 30px;
            width: 350px;
            max-width: 90%;
        }
        
        .login-container h2 {
            color: #3f51b5;
            text-align: center;
            margin-bottom: 25px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #555;
        }
        
        .input-icon {
            position: relative;
        }
        
        .input-icon i {
            position: absolute;
            left: 10px;
            top: 10px;
            color: #777;
        }
        
        .input-icon input {
            width: 100%;
            padding: 10px 10px 10px 35px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }
        
        .btn-login {
            background: linear-gradient(135deg, #3f51b5 0%, #2196F3 100%);
            color: white;
            border: none;
            padding: 12px;
            width: 100%;
            border-radius: 5px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .btn-login:hover {
            opacity: 0.9;
            transform: translateY(-2px);
        }
        
        .error-message {
            color: #f44336;
            text-align: center;
            margin-top: 15px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2><i class="fas fa-book-open"></i> Login Kursusku</h2>
        <form method="POST" action="">
            <div class="form-group">
                <label for="username">Username</label>
                <div class="input-icon">
                    <i class="fas fa-user"></i>
                    <input type="text" id="username" name="username" required>
                </div>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <div class="input-icon">
                    <i class="fas fa-lock"></i>
                    <input type="password" id="password" name="password" required>
                </div>
            </div>
            <button type="submit" class="btn-login">Login</button>
            
            <?php if(!empty($error_message)): ?>
                <p class="error-message"><?= $error_message ?></p>
            <?php endif; ?>
        </form>
    </div>
</body>
</html>