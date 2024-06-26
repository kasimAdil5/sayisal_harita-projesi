<?php
session_start();

if (isset($_SESSION["user"])) {
    header("Location: index.php");
    exit();
}

if (isset($_POST["login"])) {
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Kullanıcı doğrulama işlemleri burada yapılacak
    require_once "database.php"; // Veritabanı bağlantısı

    // Kullanıcıyı veritabanından sorgula
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        $error_message = "<div class='alert alert-danger'>SQL Error</div>";
    } else {
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            // Eğer kullanıcı bulunduysa şifreyi kontrol et
            if (password_verify($password, $row['password'])) {
                // Şifre doğru, oturum başlat
                $_SESSION["user"] = $row['id']; // Kullanıcı oturumunu başlat

                // Başarılı giriş, yönlendir
                header("Location: index.php");
                exit();
            } else {
                // Hatalı şifre durumu
                $error_message = "<div class='alert alert-danger'>Password does not match.</div>";
            }
        } else {
            // Kullanıcı bulunamadı
            $error_message = "<div class='alert alert-danger'>Email not found.</div>";
        }
    }

    // Bağlantıyı kapat
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}

// Başarı mesajını göster
if (isset($_SESSION['success_message'])) {
    $success_message = $_SESSION['success_message'];
    unset($_SESSION['success_message']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <style>
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
        <?php if (isset($success_message)) { echo "<div class='success-message'>$success_message</div>"; } ?>
        <?php if (isset($error_message)) { echo $error_message; } ?>
        <form action="login.php" method="post">
            <div class="form-group">
                <input type="email" placeholder="E-posta Giriniz:" name="email" class="form-control" required>
            </div>
            <div class="form-group">
                <input type="password" placeholder="Şifre Giriniz:" name="password" class="form-control" required>
            </div>
            <div class="form-btn">
                <input type="submit" value="Giriş Yap" name="login" class="btn btn-primary">
            </div>
        </form>
        <div><p>Henüz kayıtlı değil misiniz? <a href="registration.php">Buradan Kayıt Olun</a></p></div>
    </div>
</body>
</html>
