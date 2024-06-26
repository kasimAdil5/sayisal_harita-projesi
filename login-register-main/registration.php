<?php
// Veritabanı bağlantısı
require_once "database.php"; // database.php dosyanızın var olduğunu varsayıyorum

// Form verilerini al
$fullname = $_POST['fullname'];
$email = $_POST['email'];
$password = $_POST['password'];
$repeat_password = $_POST['repeat_password'];

// Veri doğrulama (opsiyonel, JavaScript tarafında da yapılabilir)
$errors = array();

if (empty($fullname) || empty($email) || empty($password) || empty($repeat_password)) {
    $errors[] = "All fields are required.";
}

if ($password !== $repeat_password) {
    $errors[] = "Passwords do not match.";
}

// Veri tabanında aynı e-posta adresiyle kayıtlı kullanıcı olup olmadığını kontrol etmek için sorgu
$sql = "SELECT * FROM users WHERE email = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) > 0) {
    $errors[] = "Email already exists.";
}

// Hata varsa JSON formatında hata mesajı gönder
if (!empty($errors)) {
    $response = array(
        'success' => false,
        'message' => implode(" ", $errors)
    );
    echo json_encode($response);
    exit;
}

// Şifre hashleme
$passwordHash = password_hash($password, PASSWORD_DEFAULT);

// Kullanıcıyı veritabanına eklemek için sorgu
$insertSql = "INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)";
$insertStmt = mysqli_prepare($conn, $insertSql);
mysqli_stmt_bind_param($insertStmt, "sss", $fullname, $email, $passwordHash);

if (mysqli_stmt_execute($insertStmt)) {
    $response = array(
        'success' => true
    );
    echo json_encode($response);
} else {
    $response = array(
        'success' => false,
        'message' => "Error: " . mysqli_error($conn)
    );
    echo json_encode($response);
}

// Bağlantıyı kapat
mysqli_stmt_close($insertStmt);
mysqli_close($conn);
?>
