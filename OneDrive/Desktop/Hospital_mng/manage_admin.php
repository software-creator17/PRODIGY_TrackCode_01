<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome Admin</title>
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
        }

        .section {
            margin-bottom: 20px;
        }

        .section h3 {
            color: #007bff;
            margin-bottom: 10px;
        }

        .action-table {
            width: 100%;
            border-collapse: collapse;
        }

        .action-table th,
        .action-table td {
            padding: 10px;
            text-align: center;
            border: 1px solid #ddd;
        }

        .action-table th {
            background-color: #007bff;
            color: #fff;
        }

        .action-table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .action-table tr:hover {
            background-color: #ddd;
        }

        .action-table a {
            text-decoration: none;
            color: #007bff;
        }
    </style>
</head>

<body>
    <div class="container">
        <?php
        // Establish connection to MySQL database
        $servername = "localhost";
        $username = "root";
        $password = ""; // Assuming no password set
        $database = "hospital"; // Replace with your actual database name
        $conn = new mysqli($servername, $username, $password, $database);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Check if form is submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Retrieve user ID from form
            $userId = $_POST["adminId"];

            // Query to check if user ID exists in the database
            $sql = "SELECT * FROM staff WHERE staff_id = '$userId' AND current_working = 'active'";

            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // If user ID exists and active, display options
                echo "<h2>Welcome, Admin !</h2>";
                echo "<div class='section'>";
                echo "<h3>Actions</h3>";
                echo "<table class='action-table'>";
                echo "<tr>";
                echo "<th>Insert</th>";
                echo "<th>Update</th>";
                echo "<th>Delete</th>";
                echo "<th>Read</th>";
                echo "</tr>";

                // admin Actions
                echo "<tr>";
                echo "<td><a href='ainsert_admin.php'>admin --</a></td>";  //working
                echo "<td><a href='update_admin.php'>admin---</a></td>";
                echo "<td><a href='adelete_admin.php'>admin--</a></td>";
                echo "<td><a href='aread_admin.php'>admin--</a></td>";
                echo "</tr>";

                // Doctor Actions
                echo "<tr>";
                echo "<td><a href='ainsert_doctor.php'>Doctor --</a></td>";  //working
                echo "<td><a href='update_doctor.php'>Doctor</a></td>";
                echo "<td><a href='adelete_doctor.php'>Doctor--</a></td>";
                echo "<td><a href='aread_doctor.php'>Doctor--</a></td>";
                echo "</tr>";

                // Patient Actions
        
                echo "<tr>";
                echo "<td><a href='ainsert_patient.php'>patient --</a></td>"; //working
                echo "<td><a href='update_patient.php'>patient</a></td>";
                echo "<td><a href='adelete_patient.php'>patient</a></td>";
                echo "<td><a href='aread_patient.php'>patient--</a></td>";
                echo "</tr>";

                //lab reports
        
                echo "<tr>";
                echo "<td><a href='ainsert_lab_reports.php'>lab_reports --</a></td>";
                echo "<td><a href='aupdate_lab_reports.php'>lab_reports --</a></td>";
                echo "<td><a href='delete_lab_reports.php'>lab_reports</a></td>";
                echo "<td><a href='read_lab_reports.php'>lab_reports --</a></td>";

                //Working days
        
                echo "<tr>";
                echo "<td><a href='ainsert_working_days.php'>working_days --</a></td>";
                echo "<td><a href='aupdate_working_days.php'>working_days--</a></td>";
                echo "<td><a href='delete_working_days.php'>working_days--</a></td>";
                echo "<td><a href='aread_working_days.php'>working_days--</a></td>";
                echo "</tr>";

                //Working time
        
                echo "<tr>";
                echo "<td><a href='ainsert_working_time.php'>working_time --</a></td>";
                echo "<td><a href='aupdate_working_time.php'>working_time--</a></td>";
                echo "<td><a href='delete_working_time.php'>working_time--</a></td>";
                echo "<td><a href='aread_working_time.php'>working_time--</a></td>";
                echo "</tr>";

                //Support Staff
        
                echo "<tr>";
                echo "<td><a href='ainsert_support_staff.php'>support_staff --</a></td>";
                echo "<td><a href='update_support_staff.php'>support_staff</a></td>";
                echo "<td><a href='adelete_support_staff.php'>support_staff--</a></td>";
                echo "<td><a href='aread_support_staff.php'>support_staff--</a></td>";
                echo "</tr>";


                //rooms
        
                echo "<tr>";
                echo "<td><a href='ainsert_rooms.php'>rooms --</a></td>";
                echo "<td><a href='update_rooms.php'>rooms --</a></td>";
                echo "<td><a href='adelete_rooms.php'>rooms--</a></td>";
                echo "<td><a href='aread_rooms.php'>rooms--</a></td>";
                echo "</tr>";


                //department
        
                echo "<tr>";
                echo "<td><a href='ainsert_department.php'>department --</a></td>";
                // echo "<td><a href='update_department.php'>department --</a></td>";
                // echo "<td><a href='delete_department.php'>department --</a></td>";
                // echo "<td><a href='read_department.php'>department --</a></td>";
                echo "</tr>";




                // Repeat the similar structure for other categories...
        
                echo "</table>";
                echo "</div>";

                // Additional Options
                // Make Bill Option
                echo "<tr>";
                echo "<td><a href='amake_bill.php'>Make Bill</a> </td><br>";
                echo "<td><a href='aallot_room.php'>Room Allotment</a> </td><br>";
                echo "<td><a href='adoc_dep_pa.php'>doctor_patient and doctor_department</a> </td><br>";
                echo "<td><a href='asalary_check.php'>total salary given</a> </td><br>";
                echo "<td><a href='acheck_revenue.php'>Revenue check</a> </td><br>";
                echo "<td><a href='doc_day_time.php'>doctor days and time check</a> </td><br>";
                echo "<td><a href='pa_outpa_impa.php'>Impatient and Outpatient</a> </td><br>";
                echo "</tr><br><br>";
                echo "AC room=500rs per day<br>Non-AC room=200 rs per day";

            } else {
                // If user ID does not exist, display error message
                echo "Admin does not exist or is not active. Please try again with other id";
            }
        }

        $conn->close();
        ?>
    </div>
</body>

</html>