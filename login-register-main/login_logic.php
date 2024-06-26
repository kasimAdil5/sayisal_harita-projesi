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
