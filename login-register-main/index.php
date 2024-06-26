<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit; // Ensure that script execution stops after redirection
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <title>User Dashboard</title>
</head>
<body>
    <div class="container">
        <h1>Welcome to Dashboard</h1>
        <a href="logout.php" class="btn btn-warning">Logout</a>
    </div>

    <header>
        <div class="login-register">
            <!-- Removed links to login.html and registration.html -->
        </div>
        <h1>Modern Oda Planlayıcı</h1>
    </header>

    <div class="container">
        <div class="input-area">
            <label for="area">Ana Oda Alanı (50-1000 metrekare):</label>
            <input type="number" id="area" name="area" min="50" max="1000">
            <div class="error-message" id="area-error"></div>
        </div>
        <div style="text-align: center;">
            <button class="button" onclick="generateMainRoom()">Ana Oda Oluştur</button>
        </div>
        <div id="main-room"></div>
        <div style="text-align: center;" class="login-register">
            <label for="room-type">Oda Türü Seç:</label>
            <select id="room-type">
                <option value="Oturma Odası">Oturma Odası</option>
                <option value="Mutfak">Mutfak</option>
                <option value="Banyo">Banyo</option>
                <option value="Yatak Odası">Yatak Odası</option>
                <option value="Ofis">Ofis</option>
                <option value="Çocuk Odası">Çocuk Odası</option>
                <!-- Add more options as needed -->
            </select>
            <button class="button" onclick="addRoom()">Oda Ekle</button>
        </div>
    </div>

    <!-- Include jQuery and jQuery UI for resizable functionality -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

    <script>
        // JavaScript functions for room generation and addition
        function generateMainRoom() {
            var areaInput = document.getElementById("area");
            var area = parseInt(areaInput.value);
            var areaError = document.getElementById("area-error");
            if (area >= 50 && area <= 1000) {
                areaError.textContent = ""; // Clear any previous error message
                var mainRoom = document.getElementById("main-room");
                mainRoom.innerHTML = ""; // Clear existing rooms
                mainRoom.style.height = "auto"; // Adjust main room height
                mainRoom.style.width = "auto"; // Adjust main room width

                // Calculate columns and rows based on input area
                var totalRooms = area / 100; // Estimate rooms based on 100 sqm per room
                var cols = Math.ceil(Math.sqrt(totalRooms)); // Number of columns
                var rows = Math.ceil(totalRooms / cols); // Number of rows

                // Calculate size of each room
                var roomWidth = Math.floor(mainRoom.clientWidth / cols);
                var roomHeight = Math.floor(mainRoom.clientHeight / rows);

                // Create rooms inside main room
                for (var i = 0; i < totalRooms; i++) {
                    var room = document.createElement("div");
                    room.className = "room";
                    room.textContent = "Room " + (i + 1);
                    room.style.width = roomWidth + "px";
                    room.style.height = roomHeight + "px";

                    // Calculate position of each room
                    var colIndex = i % cols;
                    var rowIndex = Math.floor(i / cols);
                    var leftPosition = colIndex * roomWidth;
                    var topPosition = rowIndex * roomHeight;

                    room.style.left = leftPosition + "px";
                    room.style.top = topPosition + "px";

                    mainRoom.appendChild(room);
                    $(room).resizable(); // Enable resizable
                }
            } else {
                areaError.textContent = "Please enter a value between 50 and 1000 square meters.";
            }
        }

        function addRoom() {
            var roomType = document.getElementById("room-type").value;
            var mainRoom = document.getElementById("main-room");
            var totalRooms = mainRoom.querySelectorAll(".room").length + 1;

            // Calculate columns and rows based on current rooms
            var cols = Math.ceil(Math.sqrt(totalRooms));
            var rows = Math.ceil(totalRooms / cols);

            // Calculate size of each room
            var roomWidth = Math.floor(mainRoom.clientWidth / cols);
            var roomHeight = Math.floor(mainRoom.clientHeight / rows);

            // Create new room
            var room = document.createElement("div");
            room.className = "room";
            room.textContent = roomType;
            room.style.width = roomWidth + "px";
            room.style.height = roomHeight + "px";

            // Calculate position of new room
            var colIndex = (totalRooms - 1) % cols;
            var rowIndex = Math.floor((totalRooms - 1) / cols);
            var leftPosition = colIndex * roomWidth;
            var topPosition = rowIndex * roomHeight;

            room.style.left = leftPosition + "px";
            room.style.top = topPosition + "px";

            mainRoom.appendChild(room);
            $(room).resizable(); // Enable resizable
        }
    </script>
</body>
</html>
