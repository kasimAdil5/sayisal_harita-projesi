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
    echo "Connected successfully<br>";
}

// Veritabanı seçimi
$sql = "USE login_registration";
if ($conn->query($sql) === FALSE) {
    die("Error selecting database: " . $conn->error);
} else {
    echo "Database selected successfully<br>";
}

// Türkçe karakter seti UTF-8 olarak ayarlanması
$sql = "SET NAMES 'utf8'";
if ($conn->query($sql) === FALSE) {
    die("Error setting charset: " . $conn->error);
} else {
    echo "Charset set successfully<br>";
}
?>
