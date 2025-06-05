<!DOCTYPE html>
<html>
<head>
    <title>Doctor Information</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        form {
            width: 300px;
            margin: 50px auto;
        }
        label, select {
            display: block;
            margin-bottom: 10px;
        }
        input[type="submit"] {
            padding: 10px;
            background-color: #007BFF;
            color: #fff;
            border: none;
            cursor: pointer;
        }
        table {
            width: 50%;
            margin: 50px auto;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

<form method="post" action="">
    <label for="doc_id">Select Doctor ID:</label>
    <select name="doc_id" id="doc_id">
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

        $sql = "SELECT doc_id FROM doctor";
        $result = $conn->query($sql);

        while ($row = $result->fetch_assoc()) {
            echo "<option value='" . $row['doc_id'] . "'>" . $row['doc_id'] . "</option>";
        }

        $conn->close();
        ?>
    </select>
    <input type="submit" name="submit" value="Get Information">
</form>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $selected_doc_id = $_POST['doc_id'];

    // Database connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Query to fetch doctor information along with working days and times
    $sql = "SELECT d.*, dwd.monday, dwd.tuesday, dwd.wednesday, dwd.thursday, dwd.friday, dwd.saturday, dwd.sunday,
                   dwt.nine_10am, dwt.ten_11am, dwt.eleven_12pm, dwt.two_4pm, dwt.four_5pm
            FROM doctor d
             JOIN doctor_working_days dwd ON d.doc_id = dwd.doc_id
             JOIN doctor_working_time dwt ON d.doc_id = dwt.doc_id
            WHERE d.doc_id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $selected_doc_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<table>";
        echo "<tr><th>Doctor ID</th><th>Name</th><th>Experience</th><th>Age</th><th>Specializations</th><th>Tenure</th><th>Current Working</th><th>Monday</th><th>Tuesday</th><th>Wednesday</th><th>Thursday</th><th>Friday</th><th>Saturday</th><th>Sunday</th><th>9-10am</th><th>10-11am</th><th>11-12pm</th><th>2-4pm</th><th>4-5pm</th></tr>";

        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["doc_id"] . "</td>";
            echo "<td>" . $row["first_name"] . " " . $row["middle_name"] . " " . $row["last_name"] . "</td>";
            echo "<td>" . $row["experience"] . "</td>";
            echo "<td>" . $row["age"] . "</td>";
            echo "<td>" . $row["Specializations"] . "</td>";
            echo "<td>" . $row["tenure"] . "</td>";
            echo "<td>" . $row["current_working"] . "</td>";
            echo "<td>" . $row["monday"] . "</td>";
            echo "<td>" . $row["tuesday"] . "</td>";
            echo "<td>" . $row["wednesday"] . "</td>";
            echo "<td>" . $row["thursday"] . "</td>";
            echo "<td>" . $row["friday"] . "</td>";
            echo "<td>" . $row["saturday"] . "</td>";
            echo "<td>" . $row["sunday"] . "</td>";
            echo "<td>" . $row["nine_10am"] . "</td>";
            echo "<td>" . $row["ten_11am"] . "</td>";
            echo "<td>" . $row["eleven_12pm"] . "</td>";
            echo "<td>" . $row["two_4pm"] . "</td>";
            echo "<td>" . $row["four_5pm"] . "</td>";
            echo "</tr>";
        }

        echo "</table>";
    } else {
        echo "No records found.";
    }

    $stmt->close();
    $conn->close();
}
?>

</body>
</html>
