<!DOCTYPE html>
<html>
<head>
    <title>Patient Information</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        table {
            width: 80%;
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

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label>Select Start Date:</label>
    <input type="date" name="startDate">
    <label>Select End Date:</label>
    <input type="date" name="endDate">
    <button type="submit">Submit</button>
</form>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $startDate = $_POST['startDate'];
    $endDate = $_POST['endDate'];

    // Database connection
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "hospital";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Query for joining patient table with impatients table
    $sql_impatients = "SELECT p.*, i.room_no, i.doa, i.dod, i.amount
                       FROM patient p
                        JOIN impatients i ON p.pa_id = i.pa_id
                       WHERE p.type = 'impatient' AND p.date BETWEEN ? AND ?";

    $stmt_impatients = $conn->prepare($sql_impatients);
    $stmt_impatients->bind_param("ss", $startDate, $endDate);
    $stmt_impatients->execute();
    $result_impatients = $stmt_impatients->get_result();

    if ($result_impatients->num_rows > 0) {
        echo "<h2>Patients and Impatients Information</h2>";
        echo "<table>";
        echo "<tr><th>Patient ID</th><th>Doctor ID</th><th>Name</th><th>DOB</th><th>Date</th><th>Gender</th><th>Phone Number</th><th>Aadhar</th><th>Disease</th><th>Address</th><th>Room No</th><th>DOA</th><th>DOD</th><th>Amount</th></tr>";

        while ($row = $result_impatients->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["pa_id"] . "</td>";
            echo "<td>" . $row["doc_id"] . "</td>";
            echo "<td>" . $row["first_name"] . " " . $row["middle_name"] . " " . $row["last_name"] . "</td>";
            echo "<td>" . $row["dob"] . "</td>";
            echo "<td>" . $row["date"] . "</td>";
            echo "<td>" . $row["gender"] . "</td>";
            echo "<td>" . $row["phone_num"] . "</td>";
            echo "<td>" . $row["aadhar"] . "</td>";
            echo "<td>" . $row["disease"] . "</td>";
            echo "<td>" . $row["address"] . "</td>";
            echo "<td>" . $row["room_no"] . "</td>";
            echo "<td>" . $row["doa"] . "</td>";
            echo "<td>" . $row["dod"] . "</td>";
            echo "<td>" . $row["amount"] . "</td>";
            echo "</tr>";
        }

        echo "</table>";
    } else {
        echo "<p>No inpatient records found.</p>";
    }

    $stmt_impatients->close();

    // Query for joining patient table with outpatients table
    $sql_outpatients = "SELECT p.*, o.problem, o.meet_date, o.prescription
                        FROM patient p
                         JOIN outpatients o ON p.pa_id = o.pa_id
                        WHERE p.type = 'outpatient' AND p.date BETWEEN ? AND ?";

    $stmt_outpatients = $conn->prepare($sql_outpatients);
    $stmt_outpatients->bind_param("ss", $startDate, $endDate);
    $stmt_outpatients->execute();
    $result_outpatients = $stmt_outpatients->get_result();

    if ($result_outpatients->num_rows > 0) {
        echo "<h2>Patients and Outpatients Information</h2>";
        echo "<table>";
        echo "<tr><th>Patient ID</th><th>Doctor ID</th><th>Name</th><th>DOB</th><th>Date</th><th>Gender</th><th>Phone Number</th><th>Aadhar</th><th>Disease</th><th>Address</th><th>Problem</th><th>Meet Date</th><th>Prescription</th></tr>";

        while ($row = $result_outpatients->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["pa_id"] . "</td>";
            echo "<td>" . $row["doc_id"] . "</td>";
            echo "<td>" . $row["first_name"] . " " . $row["middle_name"] . " " . $row["last_name"] . "</td>";
            echo "<td>" . $row["dob"] . "</td>";
            echo "<td>" . $row["date"] . "</td>";
            echo "<td>" . $row["gender"] . "</td>";
            echo "<td>" . $row["phone_num"] . "</td>";
            echo "<td>" . $row["aadhar"] . "</td>";
            echo "<td>" . $row["disease"] . "</td>";
            echo "<td>" . $row["address"] . "</td>";
            echo "<td>" . $row["problem"] . "</td>";
            echo "<td>" . $row["meet_date"] . "</td>";
            echo "<td>" . $row["prescription"] . "</td>";
            echo "</tr>";
        }

        echo "</table>";
    } else {
        echo "<p>No outpatient records found.</p>";
    }

    $stmt_outpatients->close();
    $conn->close();
}
?>

</body>
</html>
