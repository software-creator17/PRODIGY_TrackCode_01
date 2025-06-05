<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 8px 12px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .report-section {
            margin-top: 20px;
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

        if (isset($_POST['submit'])) {
            $aadhar = $_POST['aadhar'];

            // Fetch patient details
            $sql_patients = "SELECT * FROM patient WHERE aadhar = '$aadhar'";
            $result_patients = $conn->query($sql_patients);

            if ($result_patients->num_rows > 0) {
                while ($row_patient = $result_patients->fetch_assoc()) {
                    echo "<h2>Patient Information</h2>";
                    echo "<p>First Name: " . $row_patient['first_name'] . "</p>";
                    echo "<p>Middle Name: " . $row_patient['middle_name'] . "</p>";
                    echo "<p>Last Name: " . $row_patient['last_name'] . "</p>";
                    echo "<p>Date of Birth: " . $row_patient['dob'] . "</p>";
                    echo "<p>Gender: " . $row_patient['gender'] . "</p>";
                    echo "<p>Phone Number: " . $row_patient['phone_num'] . "</p>";
                    echo "<p>Aadhar Number: " . $row_patient['aadhar'] . "</p>";
                    echo "<p>Address: " . $row_patient['address'] . "</p>";

                    $pa_id = $row_patient['pa_id'];

                    // Fetch lab reports for the patient ID
                    $sql_reports = "SELECT * FROM lab_reports WHERE pa_id = '$pa_id'";
                    $result_reports = $conn->query($sql_reports);

                    if ($result_reports->num_rows > 0) {
                        echo "<div class='report-section'>";
                        echo "<h3>Lab Reports for Patient ID: " . $pa_id . "</h3>";
                        echo "<table>";
                        echo "<tr><th>Report Number</th><th>Category</th><th>Date</th><th>Remarks</th><th>Amount</th></tr>";

                        while ($row_reports = $result_reports->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row_reports['report_no'] . "</td>";
                            echo "<td>" . $row_reports['category'] . "</td>";
                            echo "<td>" . $row_reports['date'] . "</td>";
                            echo "<td>" . $row_reports['Remarks'] . "</td>";
                            echo "<td>" . $row_reports['amount'] . "</td>";
                            echo "</tr>";
                        }

                        echo "</table>";
                        echo "</div>";
                    } else {
                        echo "<p>No lab reports found for Patient ID: " . $pa_id . "</p>";
                    }

                    // Fetch bills for the patient ID
                    $sql_bills = "SELECT * FROM bill WHERE pa_id = '$pa_id'";
                    $result_bills = $conn->query($sql_bills);

                    if ($result_bills->num_rows > 0) {
                        echo "<div class='report-section'>";
                        echo "<h3>Bills for Patient ID: " . $pa_id . "</h3>";
                        echo "<table>";
                        echo "<tr><th>Bill Number</th><th>Room Charge</th><th>Lab Charges</th><th>Total Charges</th></tr>";

                        while ($row_bills = $result_bills->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row_bills['bill_no'] . "</td>";
                            echo "<td>" . $row_bills['room_charge'] . "</td>";
                            echo "<td>" . $row_bills['lab_charges'] . "</td>";
                            echo "<td>" . $row_bills['total_charges'] . "</td>";
                            echo "</tr>";
                        }

                        echo "</table>";
                        echo "</div>";
                    } else {
                        echo "<p>No bills found for Patient ID: " . $pa_id . "</p>";
                    }

                    // Fetch outpatient details for the patient ID
                    $sql_outpatients = "SELECT * FROM outpatients WHERE pa_id = '$pa_id'";
                    $result_outpatients = $conn->query($sql_outpatients);

                    if ($result_outpatients->num_rows > 0) {
                        echo "<div class='report-section'>";
                        echo "<h3>Outpatient Details for Patient ID: " . $pa_id . "</h3>";
                        echo "<table>";
                        echo "<tr><th>Problem</th><th>Meet Date</th><th>Prescription</th></tr>";

                        while ($row_outpatients = $result_outpatients->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row_outpatients['problem'] . "</td>";
                            echo "<td>" . $row_outpatients['meet_date'] . "</td>";
                            echo "<td>" . $row_outpatients['prescription'] . "</td>";
                            echo "</tr>";
                        }

                        echo "</table>";
                        echo "</div>";
                    } else {
                        echo "<p>No outpatient details found for Patient ID: " . $pa_id . "</p>";
                    }
                }
            } else {
                echo "<p>No patient found with the provided Aadhar number.</p>";
            }

            $conn->close();
        }
        ?>
    </div>
</body>
</html>
