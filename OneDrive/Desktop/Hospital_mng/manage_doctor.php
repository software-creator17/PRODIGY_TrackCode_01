<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome Doctor</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #333;
            text-align: center;
            margin-top: 0;
        }

        .options {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .options a {
            text-decoration: none;
            color: #fff;
            background-color: #007bff;
            padding: 10px 20px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .options a:hover {
            background-color: #0056b3;
        }

        .patient-login {
            text-align: center;
            margin-top: 30px;
        }

        .patient-login input[type="text"],
        .patient-login input[type="submit"] {
            padding: 10px;
            margin-right: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .patient-login input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .patient-login input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .doctor-profile {
            margin-bottom: 20px;
        }

        .doctor-profile table {
            width: 100%;
            border-collapse: collapse;
        }

        .doctor-profile th, .doctor-profile td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .doctor-profile th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
<div class="container">
<?php
// Fetch doctor's profile based on doctorId
$doctorId = $_POST['doctorId']; // assuming it's sanitized
// Fetch doctor's profile details from the database (replace this with your database connection and query)
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

// Fetch doctor's profile
$sql = "SELECT * FROM doctor WHERE doc_id='$doctorId' AND current_working='active'";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0)
{
    echo "<div class='doctor-profile'>";
    echo "<h2>Doctor's Profile</h2>";
    echo "<table>";
    while($row = $result->fetch_assoc()) {
        echo "<tr><th>Doctor ID</th><td>".$row["doc_id"]."</td></tr>";
        echo "<tr><th>First Name</th><td>".$row["first_name"]."</td></tr>";
        echo "<tr><th>Middle Name</th><td>".$row["middle_name"]."</td></tr>";
        echo "<tr><th>Last Name</th><td>".$row["last_name"]."</td></tr>";
        echo "<tr><th>Experience</th><td>".$row["experience"]."</td></tr>";
        echo "<tr><th>Age</th><td>".$row["age"]."</td></tr>";
        echo "<tr><th>Specializations</th><td>".$row["Specializations"]."</td></tr>";
        echo "<tr><th>Tenure</th><td>".$row["tenure"]."</td></tr>";
    }
    echo "</table>";
    echo "</div>";

    // Fetching first table
    $sql_working_time = "SELECT * FROM doctor_working_time WHERE doc_id='$doctorId'";
    $result_working_time = $conn->query($sql_working_time);

    if ($result_working_time && $result_working_time->num_rows > 0) {
        echo "<div class='doctor-profile'>";
        echo "<h2>Doctor's Working Time</h2>";
        echo "<table>";
        while ($row_time = $result_working_time->fetch_assoc()) {
            echo "<tr>";
            echo "<th>Doctor ID</th>";
            echo "<td>" . $row_time["doc_id"] . "</td>";
            echo "</tr>";
            echo "<tr>";
            echo "<th>9-10am</th>";
            echo "<td>" . $row_time["nine_10am"] . "</td>";
            echo "</tr>";
            echo "<tr>";
            echo "<th>10-11am</th>";
            echo "<td>" . $row_time["ten_11am"] . "</td>";
            echo "</tr>";
            echo "<tr>";
            echo "<th>11-12am</th>";
            echo "<td>" . $row_time["eleven_12pm"] . "</td>";
            echo "</tr>";
            echo "<tr>";
            echo "<th>2-4pm</th>";
            echo "<td>" . $row_time["two_4pm"] . "</td>";
            echo "</tr>";
            echo "<tr>";
            echo "<th>4-5pm</th>";
            echo "<td>" . $row_time["four_5pm"] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        echo "</div>";
    } else {
        echo "<div class='doctor-profile'>";
        echo "<h2>Doctor's Working Time</h2>";
        echo "<p>No working time found for this doctor.</p>";
        echo "</div>";
    }

    // Fetching second table
    $sql_working_days = "SELECT * FROM doctor_working_days WHERE doc_id='$doctorId'";
    $result_working_days = $conn->query($sql_working_days);

    if ($result_working_days && $result_working_days->num_rows > 0) {
        echo "<div class='doctor-profile'>";
        echo "<h2>Doctor's Working Days</h2>";
        echo "<table>";
        while ($row_days = $result_working_days->fetch_assoc()) {
            echo "<tr>";
            echo "<th>Doctor ID</th>";
            echo "<td>" . $row_days["doc_id"] . "</td>";
            echo "</tr>";
            echo "<tr>";
            echo "<th>Monday</th>";
            echo "<td>" . $row_days["monday"] . "</td>";
            echo "</tr>";
            echo "<tr>";
            echo "<th>Tuesday</th>";
            echo "<td>" . $row_days["tuesday"] . "</td>";
            echo "</tr>";
            echo "<tr>";
            echo "<th>Wednesday</th>";
            echo "<td>" . $row_days["wednesday"] . "</td>";
            echo "</tr>";
            echo "<tr>";
            echo "<th>Thursday</th>";
            echo "<td>" . $row_days["thursday"] . "</td>";
            echo "</tr>";
            echo "<tr>";
            echo "<th>Friday</th>";
            echo "<td>" . $row_days["friday"] . "</td>";
            echo "</tr>";
            echo "<tr>";
            echo "<th>Saturday</th>";
            echo "<td>" . $row_days["saturday"] . "</td>";
            echo "</tr>";
            echo "<tr>";
            echo "<th>Sunday</th>";
            echo "<td>" . $row_days["sunday"] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        echo "</div>";
    } else {
        echo "<div class='doctor-profile'>";
        echo "<h2>Doctor's Working Days</h2>";
        echo "<p>No working days found for this doctor.</p>";
        echo "</div>";
    }
} else {
    echo "<div class='doctor-profile'>";
    echo "<h2>Doctor doesn't work here</h2>";
    echo "</div>";
}
$conn->close();
?>

    <?php if ($result && $result->num_rows > 0): ?>
        <div class="options">
            <a href="#" onclick="togglePatientLogin()">Patient Login</a>
        </div>

        <div class="patient-login" id="patient-login" style="display: none;">
            <form action="dinsert_patient.php" method="POST">
                <label for="patientId">Patient ID:</label>
                <input type="text" id="patientId" name="patientId" required><br><br>
                <input type="submit" value="Submit">
            </form>
        </div>
    <?php endif; ?>

    <script>
        function togglePatientLogin() {
            var patientLoginForm = document.getElementById("patient-login");
            if (patientLoginForm.style.display === "none") {
                patientLoginForm.style.display = "block";
            } else {
                patientLoginForm.style.display = "none";
            }
        }
    </script>
</body>
</html>
