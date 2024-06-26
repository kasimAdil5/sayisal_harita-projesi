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
                session_start();
                $_SESSION["user"] = $row['id']; // Kullanıcı oturumunu başlat

                // Başarılı giriş, yönlendir
                header("Location: index.html");
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

// Yönlendirme işlemi sonrasında hata mesajını göstermek için $error_message değişkenini kullanabilirsiniz
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
        /* Body and general styling */
/* Body and general styling */
body {
    font-family: 'Roboto', sans-serif; /* Google font for a modern look */
    padding-top: 50px; /* Space from top */
    background: url('pexels-dreamypixel-547114.jpg') no-repeat center center fixed;
    background-size: cover; /* Cover the entire background */
}

.container {
    max-width: 400px; /* Center the form and limit its width */
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 0 20px rgba(0,0,0,0.1); /* Soft shadow */
    margin: auto; /* Center the container horizontally */
}

/* Form and input styling */
.form-control {
    border: 2px solid #3498db; /* Blue border */
    border-radius: 20px; /* Rounded corners */
    padding: 10px 15px; /* Padding inside input fields */
    margin-bottom: 20px; /* Space between inputs */
    transition: border-color 0.3s ease-in-out; /* Smooth transition on hover and focus */
}

.form-control:focus {
    border-color: #1abc9c; /* Light green focus color */
    outline: none; /* Remove default focus outline */
    box-shadow: 0 0 10px rgba(26, 188, 156, 0.5); /* Light green shadow on focus */
}

/* Button styling */
.btn-primary {
    background-color: #3498db; /* Blue primary button */
    border: none;
    border-radius: 20px; /* Rounded corners */
    padding: 10px 20px; /* Padding inside button */
    transition: background-color 0.3s ease-in-out; /* Smooth transition on hover */
}

.btn-primary:hover {
    background-color: #1abc9c; /* Light green shade on hover */
}

/* Link styling */
a {
    color: #3498db; /* Blue link color */
    text-decoration: none; /* Remove underline */
    transition: color 0.3s ease-in-out; /* Smooth transition on hover */
}

a:hover {
    color: #1abc9c; /* Light green link color on hover */
}

/* Optional: Error message styling */
.error-message {
    color: #e74c3c; /* Red error message color */
    font-size: 14px;
    margin-top: 5px;
}

/* Responsive adjustments */
@media (max-width: 576px) {
    .container {
        padding: 15px; /* Adjust padding on smaller screens */
    }
}


    </style>
</head>
<body>
    <div class="container">
        <?php if (isset($error_message)) { echo $error_message; } ?>
        <form action="login.php" method="post">
            <div class="form-group">
                <input type="email" placeholder="E-posta Giriniz:" name="email" class="form-control">
            </div>
            <div class="form-group">
                <input type="password" placeholder="Şifre Giriniz:" name="password" class="form-control">
            </div>
            <div class="form-btn">
                <input type="submit" value="Giriş Yap" name="login" class="btn btn-primary">
            </div>
        </form>
        <div><p>Henüz kayıtlı değil misiniz? <a href="registration.html">Buradan Kayıt Olun</a></p></div>
    </div>

</body>
</html>
