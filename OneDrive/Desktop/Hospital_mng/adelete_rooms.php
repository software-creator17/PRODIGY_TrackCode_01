<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rooms Information</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        h2 {
            color: #333;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 10px 15px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        p.error {
            color: #d9534f;
            margin-top: 10px;
        }
        p.success {
            color: #28a745;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php
        // Database connection
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "hospital";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Fetch all rooms
        $sql_rooms = "SELECT * FROM rooms";
        $result_rooms = $conn->query($sql_rooms);

        if ($result_rooms->num_rows > 0) {
            echo "<h2>Rooms Information</h2>";
            echo "<table>";
            echo "<tr><th>Room Number</th><th>Room Type</th><th>Room Status</th></tr>";

            while ($row = $result_rooms->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['room_no'] . "</td>";
                echo "<td>" . $row['room_type'] . "</td>";
                echo "<td>" . $row['room_status'] . "</td>";
                echo "</tr>";
            }

            echo "</table>";

            // Delete room form
            echo "<h2>Delete Room</h2>";
            echo "<form method='post'>";
            echo "Room Number: <input type='text' name='room_no' required>";
            echo "<input type='submit' name='delete' value='Delete'>";
            echo "</form>";

            if (isset($_POST['delete'])) {
                $room_no = $_POST['room_no'];

                // Check if the room is occupied
                $sql_check = "SELECT * FROM rooms WHERE room_no = '$room_no' AND room_status = 'Occupied' AND pa_id IS NOT NULL";
                $result_check = $conn->query($sql_check);

                if ($result_check->num_rows > 0) {
                    echo "<p class='error'>This room cannot be deleted as it is occupied.</p>";
                } else {
                    // Delete the room
                    $sql_delete = "DELETE FROM rooms WHERE room_no = '$room_no'";
                    if ($conn->query($sql_delete) === TRUE) {
                        echo "<p class='success'>Room deleted successfully.</p>";
                    } else {
                        echo "<p class='error'>Error deleting room: " . $conn->error . "</p>";
                    }
                }
            }
        } else {
            echo "<p class='error'>No rooms found.</p>";
        }

        $conn->close();
        ?>
    </div>
</body>
</html>
