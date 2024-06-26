function generateMainRoom() {
    var areaInput = document.getElementById("area");
    var area = parseInt(areaInput.value);
    if (area >= 50 && area <= 200) {
        var mainRoom = document.getElementById("main-room");
        mainRoom.style.width = area + "px";
        mainRoom.style.height = area + "px";
        // تحديث أبعاد المربع الرئيسي بعد تغيير مساحته
        setTimeout(function() {
            updateRoomDimensions(mainRoom);
        }, 100);
    } else {
        alert("Please enter an area between 50 and 200 square meters.");
    }
}

function updateRoomDimensions(mainRoom) {
    var rooms = mainRoom.querySelectorAll(".room");
    var roomWidth = 100; // عرض الغرفة
    var roomHeight = 100; // ارتفاع الغرفة
    var roomsInRow = Math.floor(mainRoom.offsetWidth / roomWidth); // عدد الغرف في الصف الواحد
    rooms.forEach(function(room, index) {
        var rowNumber = Math.floor(index / roomsInRow); // رقم الصف الحالي للغرف
        var leftPosition = (index % roomsInRow) * roomWidth; // الإزاحة اليسرى للغرفة الحالية
        var topPosition = rowNumber * roomHeight; // الإزاحة العلوية للغرفة الحالية
        room.style.left = leftPosition + "px";
        room.style.top = topPosition + "px";
    });
}

function addRoom(roomType) {
    var mainRoom = document.getElementById("main-room");
    var room = document.createElement("div");
    room.className = "room";
    room.textContent = roomType;
    room.id = "room-" + Date.now(); // تعيين معرف فريد للغرفة
    var mainRoomWidth = mainRoom.offsetWidth;
    var mainRoomHeight = mainRoom.offsetHeight;
    var roomWidth = 100; // عرض الغرفة
    var roomHeight = 100; // ارتفاع الغرفة
    var roomsInRow = Math.floor(mainRoomWidth / roomWidth); // عدد الغرف في الصف الواحد
    var roomCount = mainRoom.children.length + 1; // عدد الغرف الحالية داخل المربع الرئيسي + 1 للغرفة الجديدة
    var rowNumber = Math.ceil(roomCount / roomsInRow); // رقم الصف الحالي للغرف
    var leftPosition = ((roomCount - 1) % roomsInRow) * roomWidth; // الإزاحة اليسرى للغرفة الجديدة
    var topPosition = (rowNumber - 1) * roomHeight; // الإزاحة العلوية للغرفة الجديدة
    if ((mainRoomWidth + roomWidth) <= mainRoom.parentNode.offsetWidth && (mainRoomHeight + roomHeight) <= mainRoom.parentNode.offsetHeight) {
        room.style.left = leftPosition + "px";
        room.style.top = topPosition + "px";
        mainRoom.appendChild(room);
    } else {
        alert("The room cannot be added. It exceeds the boundary of the main room.");
    }
}
