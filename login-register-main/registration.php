<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Veritabanı bağlantısı (veritabanı ayarlarınıza göre düzenleyin)
require_once "database.php";

// Fazladan çıktı olmadığından emin olun
ob_start();

// İstek yöntemi POST ise
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Form verilerini alın
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $repeat_password = $_POST['repeat_password'];

    // Veri doğrulama
    $errors = array();

    if (empty($fullname) || empty($email) || empty($password) || empty($repeat_password)) {
        $errors[] = "Tüm alanlar gereklidir.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Geçersiz e-posta formatı.";
    }

    if ($password !== $repeat_password) {
        $errors[] = "Şifreler uyuşmuyor.";
    }

    if (strlen($password) < 8) {
        $errors[] = "Şifre en az 8 karakter uzunluğunda olmalıdır.";
    }

    // E-posta zaten veritabanında var mı kontrol edin
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt === false) {
        $errors[] = 'MySQL hazırlık hatası: ' . mysqli_error($conn);
    } else {
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            $errors[] = "E-posta zaten mevcut.";
        }
    }

    // Hatalar varsa hata mesajını göster ve çık
    if (!empty($errors)) {
        echo '<div class="error-message">' . implode(" ", $errors) . '</div>';
        exit;
    }

    // Kullanıcıyı veritabanına ekleyin
    $insertSql = "INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)";
    $insertStmt = mysqli_prepare($conn, $insertSql);
    if ($insertStmt === false) {
        echo '<div class="error-message">MySQL hazırlık hatası: ' . mysqli_error($conn) . '</div>';
        exit;
    }
    mysqli_stmt_bind_param($insertStmt, "sss", $fullname, $email, $password);

    if (mysqli_stmt_execute($insertStmt)) {
        $_SESSION['success_message'] = "Kayıt başarılı. Lütfen giriş yapın.";
        header('Location: login.php');
        exit();
    } else {
        echo '<div class="error-message">Hata: ' . mysqli_error($conn) . '</div>';
    }

    // İfadeleri ve bağlantıyı kapatın
    mysqli_stmt_close($insertStmt);
    mysqli_close($conn);
    ob_end_flush();
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kayıt Formu</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    
    <style>
        /* Bootstrap varsayılanlarını geçersiz kıl */
        body {
            font-family: 'Roboto', sans-serif;
            padding-top: 50px;
            background: url('pexels-dreamypixel-547114.jpg') no-repeat center center fixed;
            background-size: cover;
        }

        .container {
            max-width: 400px;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            margin: auto;
        }

        .form-control {
            border: 2px solid #3498db;
            border-radius: 20px;
            padding: 10px 15px;
            margin-bottom: 20px;
            transition: border-color 0.3s ease-in-out;
        }

        .form-control:focus {
            border-color: #1abc9c;
            outline: none;
            box-shadow: 0 0 10px rgba(26, 188, 156, 0.5);
        }

        .btn-primary {
            background-color: #3498db;
            border: none;
            border-radius: 20px;
            padding: 10px 20px;
            transition: background-color 0.3s ease-in-out;
        }

        .btn-primary:hover {
            background-color: #1abc9c;
        }

        a {
            color: #3498db;
            text-decoration: none;
            transition: color 0.3s ease-in-out;
        }

        a:hover {
            color: #1abc9c;
        }

        .error-message {
            color: #e74c3c;
            font-size: 14px;
            margin-top: 5px;
        }

        .success-message {
            color: #2ecc71;
            font-size: 14px;
            margin-top: 5px;
        }

        @media (max-width: 576px) {
            .container {
                padding: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <form id="registrationForm" action="" method="POST">
            <div class="form-group">
                <input type="text" class="form-control" name="fullname" placeholder="Adınız Soyadınız:" required>
            </div>
            <div class="form-group">
                <input type="email" class="form-control" name="email" placeholder="E-posta:" required>
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="password" placeholder="Şifre:" required>
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="repeat_password" placeholder="Şifre Tekrarı:" required>
            </div>
            <div class="form-btn">
                <button type="submit" class="btn btn-primary" name="submit">Kayıt Ol</button>
            </div>
        </form>
        <div>
            <p>Zaten kayıtlı mı? <a href="login.php">Buradan giriş yapın</a></p>
        </div>
    </div>
</body>
</html>
