<!DOCTYPE html>
<html>
<head>
    <title>Room Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .form-group {
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Room Details</h2>
    <form method="post" action="">
        <div class="form-group">
            <label>Select Attribute:</label>
            <select name="attribute" id="attribute" onchange="showOptions()">
                <option value="">Select Attribute</option>
                <option value="room_no">Room Number</option>
                <option value="room_type">Room Type</option>
                <option value="room_status">Room Status</option>
            </select>
        </div>
        <div class="form-group" id="options" style="display:none;">
            <!-- Options will be populated here based on the selected attribute -->
        </div>
        <div class="form-group">
            <input type="submit" name="submit" value="Submit">
        </div>
    </form>

    <?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "hospital";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $attribute = $_POST['attribute'];
        $value = $_POST['value'];

        $stmt = $conn->prepare("SELECT * FROM rooms WHERE $attribute = ?");
        $stmt->bind_param("s", $value);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "<h3>Room Details for $attribute: $value</h3>";
            echo "<table border='1'>
                    <tr>
                        <th>Room Number</th>
                        <th>Room Type</th>
                        <th>Room Status</th>
                        <th>PA ID</th>
                    </tr>";

            while($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>".$row["room_no"]."</td>
                        <td>".$row["room_type"]."</td>
                        <td>".$row["room_status"]."</td>
                        <td>".$row["pa_id"]."</td>
                      </tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No results found.</p>";
        }

        $stmt->close();
        $conn->close();
    }
    ?>

    <script>
        function showOptions() {
            var attribute = document.getElementById("attribute").value;
            var optionsDiv = document.getElementById("options");

            if (attribute === "room_type") {
                optionsDiv.innerHTML = '<label>Select Room Type:</label>' +
                                       '<select name="value">' +
                                       '<option value="AC">AC</option>' +
                                       '<option value="non_AC">Non-AC</option>' +
                                       '</select>';
                optionsDiv.style.display = "block";
            } else if (attribute === "room_status") {
                optionsDiv.innerHTML = '<label>Select Room Status:</label>' +
                                       '<select name="value">' +
                                       '<option value="vacant">Vacant</option>' +
                                       '<option value="occupied">Occupied</option>' +
                                       '<option value="under-maintenance">Under-Maintenance</option>' +
                                       '</select>';
                optionsDiv.style.display = "block";
            } else if (attribute === "room_no") {
                optionsDiv.innerHTML = '<label>Select Room Number:</label>' +
                                       '<select name="value">' +
                                       '<option value="101">101</option>' +
                                       '<option value="102">102</option>' +
                                       '<option value="103">103</option>' +
                                       '<option value="104">104</option>' +
                                       '</select>';
                optionsDiv.style.display = "block";
            } else {
                optionsDiv.style.display = "none";
            }
        }
    </script>

</div>

</body>
</html>
