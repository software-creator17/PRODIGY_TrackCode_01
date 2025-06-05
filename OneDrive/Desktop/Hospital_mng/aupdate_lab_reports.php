<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Lab Reports</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }

        h2 {
            text-align: center;
        }

        form {
            margin-top: 20px;
            text-align: center;
        }

        .lab-report {
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 20px;
            max-width: 400px;
            margin: 0 auto;
        }

        .update-form {
            margin-top: 10px;
        }

        .amount-input {
            width: 100%;
            padding: 5px;
            margin-top: 5px;
            box-sizing: border-box;
        }

        .update-button {
            padding: 8px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }

        .update-button:hover {
            background-color: #45a049;
        }
    </style>
</head>

<body>
    <h2>Enter Patient ID and Date</h2>
    <form method="POST" action="">
        <label for="patientId">Patient ID:</label><br>
        <input type="text" id="patientId" name="patientId"><br>
        <input type="submit" value="Submit">
    </form>

    <?php
    session_start();

    // Database connection
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

    $paId = isset($_SESSION['patientId']) ? $_SESSION['patientId'] : null;

    // Check if form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['patientId'])) {
        $paId = $_POST["patientId"];
        $_SESSION['patientId'] = $paId;

        $current_date = date("Y-m-d");

        $sql = "SELECT * FROM lab_reports WHERE pa_id = '$paId' AND date = '$current_date'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='lab-report'>";
                echo "Report No: " . $row["report_no"] . "<br>";
                echo "Patient ID: " . $row["pa_id"] . "<br>";
                echo "Doctor ID: " . $row["doc_id"] . "<br>";
                echo "Date: " . $row["date"] . "<br>";
                echo "Category: " . $row["category"] . "<br>";
                echo "Remarks: " . $row["Remarks"] . "<br>";
                echo "Amount: " . $row["amount"] . "<br><br>";
                ?>

                <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <input type="hidden" id="report_no" name="report_no[]" value="<?php echo $row['report_no']; ?>">
                    <label for="amount">Amount:</label><br>
                    <input type="number" class="amount-input" id="amount" name="amount[]" value="<?php echo $row['amount']; ?>"><br><br>
                <?php
            }
            ?>
                <input type="submit" class="update-button" value="Submit">
                </form>
            <?php
                echo "</div>";
        } else {
            echo "No lab reports found for the given patient ID and date.";
        }
    }

    // Check if the form for updating lab reports is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['amount'])) {
        $amounts = $_POST['amount'];
        $report_nos = $_POST['report_no'];

        for ($i = 0; $i < count($amounts); $i++) {
            $amount = $amounts[$i];
            $report_no = $report_nos[$i];

            $sql_update = "UPDATE lab_reports SET amount = '$amount' WHERE pa_id = '$paId' AND report_no = '$report_no'";

            if ($conn->query($sql_update) !== TRUE) {
                echo "Error updating lab report: " . $conn->error;
                break;
            }
        }
        
        if ($i == count($amounts)) {
            echo "Lab reports updated successfully.";
        }
    }

    // Close database connection
    $conn->close();
    ?>
</body>

</html>
