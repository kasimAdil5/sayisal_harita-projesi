<?php
$servername = "localhost";
$username = "root";
$password = ""; // Varsayılan şifre boşsa
$dbname = "login_registration";
// Veritabanı bağlantısı oluşturma
$conn = new mysqli($servername, $username, $password,$dbname );

// Bağlantı kontrolü
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    echo "";
}

// Veritabanı seçimi
$sql = "USE login_registration";
if ($conn->query($sql) === FALSE) {
    die("Error selecting database: " . $conn->error);
} else {
    echo "";
}

// Türkçe karakter seti UTF-8 olarak ayarlanması
$sql = "SET NAMES 'utf8'";
if ($conn->query($sql) === FALSE) {
    die("Error setting charset: " . $conn->error);
} else {
    echo "";
}
?>
