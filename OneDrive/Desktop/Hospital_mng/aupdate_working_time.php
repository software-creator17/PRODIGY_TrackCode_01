<!DOCTYPE html>
<html>
<head>
    <title>Doctor Working Time</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            width: 60%;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        label {
            display: block;
            margin-bottom: 10px;
        }
        input[type="checkbox"] {
            margin-right: 10px;
        }
        input[type="submit"] {
            padding: 10px 20px;
            background-color: #007BFF;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Update Doctor Working Time</h2>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <label for="doc_id">Enter Doctor ID:</label>
            <input type="text" name="doc_id" id="doc_id" required>
            <br><br>
            <label>Choose Working Time Slots:</label>
            <input type="checkbox" name="times[]" value="nine_10am">9:00 AM - 10:00 AM
            <input type="checkbox" name="times[]" value="ten_11am">10:00 AM - 11:00 AM
            <input type="checkbox" name="times[]" value="eleven_12pm">11:00 AM - 12:00 PM
            <input type="checkbox" name="times[]" value="two_4pm">2:00 PM - 4:00 PM
            <input type="checkbox" name="times[]" value="four_5pm">4:00 PM - 5:00 PM
            <br><br>
            <input type="submit" value="Update">
        </form>
    </div>

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
        $doc_id = $_POST['doc_id'];
        $times = $_POST['times'];

        // Set all times to 0 for the doc_id
        $sql_reset = "UPDATE doctor_working_time SET nine_10am=0, ten_11am=0, eleven_12pm=0, two_4pm=0, four_5pm=0 WHERE doc_id = ?";
        $stmt_reset = $conn->prepare($sql_reset);
        $stmt_reset->bind_param("s", $doc_id);
        $stmt_reset->execute();

        // Update selected times to 1 for the doc_id
        foreach ($times as $time) {
            $sql_update = "UPDATE doctor_working_time SET $time=1 WHERE doc_id = ?";
            $stmt_update = $conn->prepare($sql_update);
            $stmt_update->bind_param("s", $doc_id);
            $stmt_update->execute();
        }

        echo "<p>Working times updated successfully!</p>";
    }

    $conn->close();
    ?>
</body>
</html>
