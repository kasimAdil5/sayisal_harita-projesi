<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database connection (adjust as per your database setup)
require_once "database.php";

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Form data retrieval
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $repeat_password = $_POST['repeat_password'];

    // Data validation
    $errors = array();

    if (empty($fullname) || empty($email) || empty($password) || empty($repeat_password)) {
        $errors[] = "All fields are required.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    if ($password !== $repeat_password) {
        $errors[] = "Passwords do not match.";
    }

    if (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters long.";
    }

    // Check if email already exists in the database
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt === false) {
        $errors[] = 'mysqli prepare error: ' . mysqli_error($conn);
    } else {
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            $errors[] = "Email already exists.";
        }
    }

    // Return error message in JSON format if there are errors
    if (!empty($errors)) {
        http_response_code(400);
        header('Content-Type: application/json');
        $response = array(
            'success' => false,
            'message' => implode(" ", $errors)
        );
        echo json_encode($response);
        exit;
    }

    // Hash the password
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    // Insert user into database
    $insertSql = "INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)";
    $insertStmt = mysqli_prepare($conn, $insertSql);
    if ($insertStmt === false) {
        http_response_code(500);
        header('Content-Type: application/json');
        $response = array(
            'success' => false,
            'message' => 'mysqli prepare error: ' . mysqli_error($conn)
        );
        echo json_encode($response);
        exit;
    }
    mysqli_stmt_bind_param($insertStmt, "sss", $fullname, $email, $passwordHash);

    if (mysqli_stmt_execute($insertStmt)) {
        $response = array(
            'success' => true   
        );
        echo json_encode($response);
    } else {
        http_response_code(500);
        header('Content-Type: application/json');
        $response = array(
            'success' => false,
            'message' => "Error: " . mysqli_error($conn)
        );
        echo json_encode($response);
    }

    // Close statements and connection
    mysqli_stmt_close($insertStmt);
    mysqli_close($conn);
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
        /* Bootstrap defaults overridden */
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

        @media (max-width: 576px) {
            .container {
                padding: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <form id="registrationForm" action="registration.php" method="POST">
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
    
    <script>
        document.getElementById('registrationForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Form submission prevented
    
            // Form data
            var formData = new FormData(this);
    
            // AJAX request to handle registration
            fetch('registration.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = 'login.php   '; // Redirect on successful registration
                } else {
                    console.log(data.message); // Log error message (optional)
                    alert(data.message); // Show error message
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.'); // Alert on AJAX error
            });
        });
    </script>
</body>
</html>
