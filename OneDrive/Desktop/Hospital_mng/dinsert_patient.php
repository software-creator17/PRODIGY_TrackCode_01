<!DOCTYPE html>
<html>

<head>
    <title>Patient Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }

        h2 {
            margin-bottom: 10px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        form {
            margin-bottom: 20px;
        }

        input[type="text"],
        input[type="submit"],
        input[type="date"] {
            padding: 6px;
            margin-right: 10px;
        }
    </style>
</head>

<body>

    <?php
    session_start();

    // Database connection
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "hospital";

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Fetch patient details
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["patientId"])) {
        $patient_id = $_POST['patientId'];

        $_SESSION['patientId'] = $patient_id;

        $sql_patient = "SELECT *, TIMESTAMPDIFF(YEAR, dob, CURDATE()) AS age FROM patient WHERE pa_id=?";
        $stmt_patient = $conn->prepare($sql_patient);
        $stmt_patient->bind_param("s", $patient_id);
        $stmt_patient->execute();
        $result_patient = $stmt_patient->get_result();
        $patient = $result_patient->fetch_assoc();

        $docId = $patient['doc_id'];
        $date = $patient['date'];

        $_SESSION['doc_id'] = $docId;
        $_SESSION['date'] = $date;

        $stmt_patient->close();
    }

    $paId = isset($_SESSION['patientId']) ? $_SESSION['patientId'] : null;
    $docId = isset($_SESSION['doc_id']) ? $_SESSION['doc_id'] : null; 
    $date = isset($_SESSION['date']) ? $_SESSION['date'] : null;

    // Function to make new lab report
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["new_report"])) {
        $reportId = $_POST['reportId'];
        $category = $_POST['category'];
        // Insert new lab report
        $sql_insert_report = "INSERT INTO lab_reports (report_no, pa_id, doc_id, date, category) VALUES (?, ?, ?, ?, ?)";
        $stmt_insert_report = $conn->prepare($sql_insert_report);
        $stmt_insert_report->bind_param("sssss", $reportId, $paId, $docId, $date, $category);

        if ($stmt_insert_report->execute()) {
            echo "New lab report created successfully.";
        } else {
            echo "Error creating new lab report: " . $stmt_insert_report->error;
        }

        $stmt_insert_report->close();
    }

    // Function to update patient's doc_id and type
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update_info"])) {
        $patientId = $_POST['patientId'];
        $type = $_POST['type'];

        // Update patient information
        $sql_update_info = "UPDATE patient SET type=? WHERE pa_id=?";
        $stmt_update_info = $conn->prepare($sql_update_info);
        $stmt_update_info->bind_param("ss", $type, $patientId);

        if ($stmt_update_info->execute()) {
            echo "Patient's information updated successfully.";

            // Insert into doc_pa table
            $sql_insert_doc_pa = "INSERT INTO doc_pa (doc_id, pa_id) VALUES (?, ?)";
            $stmt_insert_doc_pa = $conn->prepare($sql_insert_doc_pa);
            $stmt_insert_doc_pa->bind_param("ss", $docId, $paId);

            if ($stmt_insert_doc_pa->execute()) {
                echo "Inserted into Doc_PA table successfully.";
            } else {
                echo "Error inserting into Doc_PA table: " . $stmt_insert_doc_pa->error;
            }

            $stmt_insert_doc_pa->close();

            if ($type == "outpatient") {
                // Open a new form for outpatients data insertion
                echo '
            <h2>Outpatient Form</h2>
            <form action="' . $_SERVER['PHP_SELF'] . '" method="POST">
            <label for="problem">Problem:</label>
            <input type="text" id="problem" name="problem"  required><br><br>
        
            <label for="prescription">Prescription:</label>
            <input type="text" id="prescription" name="prescription" required><br><br>

            <input type="submit" name="submit_outpatient" value="Submit Outpatient">
        </form>';
            }
        } else {
            echo "Error updating patient's information: " . $stmt_update_info->error;
        }

        $stmt_update_info->close();
    }

    // Function to insert data into outpatients table
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit_outpatient"])) {
        $problem = $_POST['problem'];
        $prescription = $_POST['prescription'];

        $sql_insert_outpatient = "INSERT INTO outpatients (pa_id, problem, meet_date, prescription) VALUES (?, ?, ?, ?)";
        $stmt_insert_outpatient = $conn->prepare($sql_insert_outpatient);
        $stmt_insert_outpatient->bind_param("ssss", $paId, $problem, $date, $prescription);

        if ($stmt_insert_outpatient->execute()) {
            echo "Outpatient data inserted successfully.";
        } else {
            echo "Error inserting outpatients data: " . $stmt_insert_outpatient->error;
        }

        $stmt_insert_outpatient->close();
    }

    // Function to update lab remarks
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update_remarks"])) {
        $remarks = $_POST['remarks'];
        $report_no = $_POST['report_no'];

        // Update lab report remarks
        $sql_update_remarks = "UPDATE lab_reports SET Remarks=? WHERE report_no=?";
        $stmt_update_remarks = $conn->prepare($sql_update_remarks);
        $stmt_update_remarks->bind_param("ss", $remarks, $report_no);

        if ($stmt_update_remarks->execute()) {
            echo "Remarks updated successfully.";
        } else {
            echo "Error updating remarks: " . $stmt_update_remarks->error;
        }

        $stmt_update_remarks->close();
    }

    // Fetch reports for the patient
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["patientId"])) {
        $sql_reports = "SELECT * FROM lab_reports WHERE pa_id=?";
        $stmt_reports = $conn->prepare($sql_reports);
        $stmt_reports->bind_param("s", $paId);
        $stmt_reports->execute();
        $reports = $stmt_reports->get_result();
        $stmt_reports->close();
    }

// Fetch the last report_no from lab_reports table
$sql_last_report = "SELECT report_no FROM lab_reports ORDER BY report_no DESC LIMIT 1";
$result_last_report = $conn->query($sql_last_report);
$last_report = $result_last_report->fetch_assoc();
$last_report_no = isset($last_report['report_no']) ? $last_report['report_no'] : 'N/A';


    ?>

    <h2>Patient Details</h2>
    <?php if (isset($patient)): ?>
        <p>ID: <?php echo $patient['pa_id']; ?></p>
        <p>Name: <?php echo $patient['first_name'] . ' ' . $patient['middle_name'] . ' ' . $patient['last_name']; ?></p>
        <p>Doc ID: <?php echo $patient['doc_id']; ?></p>
        <p>DOB: <?php echo $patient['dob']; ?></p>
        <p>Age: <?php echo $patient['age']; ?></p> <!-- Display Age -->
        <p>date: <?php echo $patient['date']; ?></p>
        <p>Gender: <?php echo $patient['gender']; ?></p>
        <p>Phone Number: <?php echo $patient['phone_num']; ?></p>
        <p>aadhar: <?php echo $patient['aadhar']; ?></p>
        <p>Disease: <?php echo $patient['disease']; ?></p>
        <p>Address: <?php echo $patient['address']; ?></p>
        <p>Type: <?php echo $patient['type']; ?></p>

        <!-- Update Patient Information Form -->
        <h2>Update Patient Information</h2>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <label for="type">Type:</label>
            <input type="text" id="type" name="type" required><br><br>

            <input type="hidden" name="patientId" value="<?php echo $patient_id; ?>">
            <input type="submit" name="update_info" value="Update Information">
        </form>

        <!-- Existing Lab Reports -->
        <h2>Lab Reports</h2>
        <?php if (isset($reports)): ?>
            <table>
                <tr>
                    <th>Report No</th>
                    <th>Patient ID</th>
                    <th>Doc ID</th>
                    <th>date</th>
                    <th>Category</th>
                    <th>Remarks</th>
                    <th>Amount</th>
                    <th>Action</th>
                </tr>
                <?php while ($report = $reports->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $report['report_no']; ?></td>
                        <td><?php echo $report['pa_id']; ?></td>
                        <td><?php echo $report['doc_id']; ?></td>
                        <td><?php echo $report['date']; ?></td>
                        <td><?php echo $report['category']; ?></td>
                        <td><?php echo $report['Remarks']; ?></td>
                        <td><?php echo $report['amount']; ?></td>
                        <td>
                            <!-- Update Remarks Form -->
                            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                                <input type="text" name="remarks" placeholder="Enter remarks">
                                <input type="hidden" name="report_no" value="<?php echo $report['report_no']; ?>">
                                <input type="submit" name="update_remarks" value="Update Remarks">
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php endif; ?>

          <!-- Last Report Number Section -->
          <h3>Last Report Number: <?php echo $last_report_no; ?></h3>

        <!-- New Report Form -->
        <h2>Lab Report Form</h2>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">

            <label for="reportId">Report ID:</label>
            <input type="text" id="reportId" name="reportId" required><br><br>

            <label for="category">Category:</label>
            <input type="text" id="category" name="category" required><br><br>

            <input type="submit" name="new_report" value="Submit">
        </form>
    <?php endif; ?>

    <?php
    // Close connection
    $conn->close();
    ?>

</body>

</html>
