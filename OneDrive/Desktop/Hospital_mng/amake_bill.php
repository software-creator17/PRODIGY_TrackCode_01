<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "hospital";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$last_bill_query = "SELECT MAX(bill_no) as last_bill FROM bill";
$last_bill_result = $conn->query($last_bill_query);
$last_bill_row = $last_bill_result->fetch_assoc();
$last_bill_no = $last_bill_row['last_bill'];

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $bill_no = $_POST['bill_no'];
    $pa_id = $_POST['pa_id'];

    // Check patient type
    $check_type_query = "SELECT type FROM patient WHERE pa_id = '$pa_id'";
    $check_type_result = $conn->query($check_type_query);
    if ($check_type_result->num_rows > 0) {
        $type_row = $check_type_result->fetch_assoc();
        $patient_type = $type_row['type'];

        if ($patient_type == 'impatient') {
            // Insert bill_no and pa_id into bill table
    $current_date = date("Y-m-d");  // Get current date in YYYY-MM-DD format
    $insert_query = "INSERT INTO bill (bill_no, pa_id, bill_date) VALUES ('$bill_no', '$pa_id', '$current_date')";
    if ($conn->query($insert_query) === FALSE) {
        echo "Error inserting data into bill table: " . $conn->error;
        $conn->close();
        exit(); // Exit if insertion fails
    }


    // Calculate room_charge from impatients table
    $room_charge_query = "SELECT amount FROM impatients WHERE pa_id = '$pa_id' AND dod = CURDATE()";
    $room_charge_result = $conn->query($room_charge_query);
    if ($room_charge_result->num_rows > 0) {
        $room_charge_row = $room_charge_result->fetch_assoc();
        $room_charge = $room_charge_row['amount'];
    } else {
        $room_charge = 0; // Set default value if no records found
    }

    // Calculate lab_charge from lab_reports table
    $lab_charge_query = "SELECT SUM(lab_reports.amount) AS total_lab_charge 
                         FROM impatients 
                         JOIN lab_reports ON impatients.pa_id = lab_reports.pa_id 
                         WHERE impatients.pa_id = '$pa_id' 
                             AND impatients.doa <= lab_reports.date 
                             AND impatients.dod >= lab_reports.date";
    $lab_charge_result = $conn->query($lab_charge_query);
    if ($lab_charge_result->num_rows > 0) {
        $lab_charge_row = $lab_charge_result->fetch_assoc();
        $lab_charge = $lab_charge_row['total_lab_charge'];
    } else {
        $lab_charge = 0; // Set lab_charge to 0 if no lab reports found
    }

    // Calculate total_charge
    $total_charge = $room_charge + $lab_charge;

   // Update the bill table with the new values
$update_query = "UPDATE bill SET 
room_charge = '$room_charge', 
lab_charges = '$lab_charge', 
total_charges = '$total_charge' 
WHERE bill_no = '$bill_no' AND pa_id = '$pa_id'";

// Debugging: Print the update query
echo "Update Query = $update_query <br>";

// Execute the update query
if ($conn->query($update_query) === TRUE) {
echo "Bill updated successfully.<br>";
} else {
echo "Error updating bill: " . $conn->error . "<br>";
}



    // Display the bill details
    echo "<h3>Bill Details</h3>";
    echo "<table>";
    echo "<tr><th>Item</th><th>Amount</th></tr>";
    echo "<tr><td>Bill No</td><td>$bill_no</td></tr>";
    echo "<tr><td>PA ID</td><td>$pa_id</td></tr>";
    echo "<tr><td>Room Charge</td><td>$room_charge</td></tr>";
    echo "<tr><td>Lab Charge</td><td>$lab_charge</td></tr>";
    echo "<tr><td>Total Charge</td><td>$total_charge</td></tr>";
    echo "</table>";


        } elseif ($patient_type == 'outpatient') {

            $current_date = date("Y-m-d"); 
            // Calculate lab_charge from lab_reports table
            $lab_charge_query = "SELECT SUM(amount) AS total_lab_charge 
                                 FROM lab_reports 
                                 WHERE pa_id = '$pa_id'";
            $lab_charge_result = $conn->query($lab_charge_query);
            if ($lab_charge_result->num_rows > 0) {
                $lab_charge_row = $lab_charge_result->fetch_assoc();
                $lab_charge = $lab_charge_row['total_lab_charge'];
            } else {
                $lab_charge = 0; // Set lab_charge to 0 if no lab reports found
            }

            // Update the bill table with the new values
            $update_query = "INSERT INTO bill (bill_no,bill_date, pa_id, room_charge, lab_charges, total_charges) 
                             VALUES ('$bill_no', '$current_date', '$pa_id', 0, '$lab_charge', '$lab_charge')";
            if ($conn->query($update_query) === FALSE) {
                echo "Error inserting data into bill table: " . $conn->error;
                $conn->close();
                exit(); // Exit if insertion fails
            }

            // Display the bill details
            echo "<h3>Bill Details</h3>";
            echo "<table>";
            echo "<tr><th>Item</th><th>Amount</th></tr>";
            echo "<tr><td>Bill No</td><td>$bill_no</td></tr>";
            echo "<tr><td>PA ID</td><td>$pa_id</td></tr>";
            echo "<tr><td>Lab Charge</td><td>$lab_charge</td></tr>";
            echo "<tr><td>Total Charge</td><td>$lab_charge</td></tr>";
            echo "</table>";
        } else {
            echo "Invalid patient type!";
        }
    } else {
        echo "Patient not found!";
    }


    // Close database connection
    $conn->close();
    exit(); // Exit after displaying the bill details
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bill Making</title>
    <style>
        /* Your CSS styles here */
    </style>
</head>

<body>

    <h2>Bill Making</h2>

    <div>
        <h3>Last Bill No: <?php echo $last_bill_no; ?></h3>
    </div>

    <form method="post">
        <label for="bill_no">Bill No:</label>
        <input type="text" id="bill_no" name="bill_no" required>
        <label for="pa_id">PA ID:</label>
        <input type="text" id="pa_id" name="pa_id" required>
        <button type="submit">Generate Bill</button>
    </form>


    
</body>

</html>
