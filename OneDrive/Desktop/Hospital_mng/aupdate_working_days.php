<!DOCTYPE html>
<html>
<head>
    <title>Doctor Working Days</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            width: 50%;
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
        <h2>Update Doctor Working Days</h2>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <label for="doc_id">Enter Doctor ID:</label>
            <input type="text" name="doc_id" id="doc_id" required>
            <br><br>
            <label>Choose Working Days:</label>
            <input type="checkbox" name="days[]" value="Monday">Monday
            <input type="checkbox" name="days[]" value="Tuesday">Tuesday
            <input type="checkbox" name="days[]" value="Wednesday">Wednesday
            <input type="checkbox" name="days[]" value="Thursday">Thursday
            <input type="checkbox" name="days[]" value="Friday">Friday
            <input type="checkbox" name="days[]" value="Saturday">Saturday
            <input type="checkbox" name="days[]" value="Sunday">Sunday
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
        $days = $_POST['days'];

        // Set all days to 0 for the doc_id
        $sql_reset = "UPDATE doctor_working_days SET Monday=0, Tuesday=0, Wednesday=0, Thursday=0, Friday=0, Saturday=0, Sunday=0 WHERE doc_id = ?";
        $stmt_reset = $conn->prepare($sql_reset);
        $stmt_reset->bind_param("s", $doc_id);
        $stmt_reset->execute();

        // Update selected days to 1 for the doc_id
        foreach ($days as $day) {
            $sql_update = "UPDATE doctor_working_days SET $day=1 WHERE doc_id = ?";
            $stmt_update = $conn->prepare($sql_update);
            $stmt_update->bind_param("s", $doc_id);
            $stmt_update->execute();
        }

        echo "<p>Working days updated successfully!</p>";
    }

    $conn->close();
    ?>
</body>
</html>
