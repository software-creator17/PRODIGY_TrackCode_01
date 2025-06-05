<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        form {
            margin: 20px;
            max-width: 400px;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input[type="text"],
        select {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <?php
    $servername = "localhost";
    $username = "root"; // Replace with your MySQL username
    $password = ""; // Replace with your MySQL password
    $database = "hospital"; // Replace with your MySQL database name

    // Create connection
    $conn = new mysqli($servername, $username, $password, $database);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Insert room into the database
    if(isset($_POST['submit'])) {
        $room_no = $_POST['room_no'];
        $room_type = $_POST['room_type'];
        $room_status = $_POST['room_status'];

        $sql = "INSERT INTO rooms (room_no, room_type, room_status) VALUES ('$room_no', '$room_type', '$room_status')";

        if ($conn->query($sql) === TRUE) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    // Update room status and type
    if(isset($_POST['update'])) {
        $room_no = $_POST['room_no'];
        $room_status = $_POST['room_status'];
        $room_type = $_POST['room_type'];

        $sql = "UPDATE rooms SET room_status='$room_status', room_type='$room_type' WHERE room_no='$room_no'";

        if ($conn->query($sql) === TRUE) {
            echo "Record updated successfully";
        } else {
            echo "Error updating record: " . $conn->error;
        }
    }

    // Fetch the last entered room_no
    $last_room_query = "SELECT MAX(room_no) as last_room_no FROM rooms";
    $last_room_result = $conn->query($last_room_query);
    $last_room_row = $last_room_result->fetch_assoc();
    $last_room_no = $last_room_row['last_room_no'];

    $conn->close();
    ?>

    <h2>Last Room Number entered: <?php echo $last_room_no; ?></h2>

    <h2>Insert Room</h2>
    <form method="post">
        <label for="room_no">Room Number:</label>
        <input type="text" id="room_no" name="room_no" required>
        <label for="room_type">Room Type:</label>
        <select id="room_type" name="room_type">
            <option value="ac">AC</option>
            <option value="non_ac">Non-AC</option>
        </select>
        <label for="room_status">Room Status:</label>
        <input type="text" id="room_status" name="room_status" required>
        <input type="submit" name="submit" value="Insert Room">
    </form>

    <h2>Update Room</h2>
    <form method="post">
        <label for="room_no_update">Room Number:</label>
        <input type="text" id="room_no_update" name="room_no" required>
        <label for="room_type_update">Room Type:</label>
        <select id="room_type_update" name="room_type">
            <option value="ac">AC</option>
            <option value="non_ac">Non-AC</option>
        </select>
        <label for="room_status_update">Room Status:</label>
        <input type="text" id="room_status_update" name="room_status" required>
        <input type="submit" name="update" value="Update Room">
    </form>
</body>
</html>
