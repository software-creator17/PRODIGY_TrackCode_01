<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Department</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h2>Select an option:</h2>
    <button onclick="showDepartment()">Doctor Department</button>
    <button onclick="showPatient()">Doctor Patient</button>

    <div id="department" style="display:none;">
        <h2>Department Table</h2>
        <?php
            // Your database connection code here
            $servername = "localhost";
            $username = "root";
            $password = "";
            $database = "hospital";
            $conn=mysqli_connect($servername, $username, $password, $database);
            // Assuming you have a database connection object named $conn
            $sql = "SELECT dep_id, dep_name FROM department";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                echo "<table><tr><th>dep_id</th><th>dep_name</th></tr>";
                while($row = $result->fetch_assoc()) {
                    echo "<tr><td>".$row["dep_id"]."</td><td>".$row["dep_name"]."</td></tr>";
                }
                echo "</table>";
            } else {
                echo "0 results";
            }
        ?>
        <h2>Doctor Department Table</h2>
        <?php
            $sql = "SELECT dep_id, COUNT(DISTINCT doc_id) AS num_of_doctors, GROUP_CONCAT(DISTINCT doc_id) AS doctor_ids FROM doc_dep GROUP BY dep_id";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                echo "<table><tr><th>dep_id</th><th>no. of doctors</th><th>doctor_ids</th></tr>";
                while($row = $result->fetch_assoc()) {
                    echo "<tr><td>".$row["dep_id"]."</td><td>".$row["num_of_doctors"]."</td><td>".$row["doctor_ids"]."</td></tr>";
                }
                echo "</table>";
            } else {
                echo "0 results";
            }
            // Close database connection
            $conn->close();
        ?>
    </div>

    <div id="patient" style="display:none;">
        <h2>Doctor Patient Table</h2>
        <?php
            // Your database connection code here
            $servername = "localhost";
            $username = "root";
            $password = "";
            $database = "hospital";
            $conn=mysqli_connect($servername, $username, $password, $database);
            // Assuming you have a database connection object named $conn
            $sql = "SELECT doc_id, COUNT(*) AS num_of_patients, GROUP_CONCAT(pa_id) AS patient_ids FROM patient GROUP BY doc_id";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                echo "<table><tr><th>doc_id</th><th>no. of patients</th><th>patient_ids</th></tr>";
                while($row = $result->fetch_assoc()) {
                    echo "<tr><td>".$row["doc_id"]."</td><td>".$row["num_of_patients"]."</td><td>".$row["patient_ids"]."</td></tr>";
                }
                echo "</table>";
            } else {
                echo "0 results";
            }
            // Close database connection
            $conn->close();
        ?>
    </div>

    <script>
        function showDepartment() {
            document.getElementById("department").style.display = "block";
            document.getElementById("patient").style.display = "none";
        }
        function showPatient() {
            document.getElementById("department").style.display = "none";
            document.getElementById("patient").style.display = "block";
        }
    </script>
</body>
</html>
