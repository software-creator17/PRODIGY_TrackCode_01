<!DOCTYPE html>
<html>

<head>
    <title>Patient Discharge Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f2f2f2;
        }

        .container {
            width: 50%;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, 0.1);
        }

        input[type="text"],
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Patient Discharge Form</h2>
        <form method="post">
            <label for="patient_id">Patient ID:</label>
            <input type="text" id="patient_id" name="patient_id" required><br>
            <input type="submit" value="Submit">
        </form>

        <?php
// Establish database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hospital";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $patient_id = $_POST['patient_id'];

    // Update impatients table with dod
    $dod = date("Y-m-d");
    $sql_update_imp = "UPDATE impatients SET dod='$dod' WHERE pa_id='$patient_id'";

    if ($conn->query($sql_update_imp) === TRUE) {
        echo "Record updated successfully.";

        // Update room_status and pa_id in rooms table
        $sql_get_room_info = "SELECT room_type FROM rooms WHERE pa_id='$patient_id'";
        $result = $conn->query($sql_get_room_info);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $room_type = $row['room_type'];
                $room_status = "vacant";
                $pa_id = NULL;

                // Calculate number of days
                $sql_get_dates = "SELECT doa FROM impatients WHERE pa_id='$patient_id'";
                $result_dates = $conn->query($sql_get_dates);
                if ($result_dates->num_rows > 0) {
                    $row_dates = $result_dates->fetch_assoc();
                    $doa = $row_dates['doa'];
                    $date1 = new DateTime($dod);
                    $date2 = new DateTime($doa);
                    $interval = $date1->diff($date2);
                    $numberOfDays = $interval->days;

                    // Define cost per day for AC and non-AC
                    $costPerDayAC = 500;
                    $costPerDayNonAC = 200;

                    // Calculate total cost based on room type
                    if ($room_type == 'ac') {
                        $totalCost = $numberOfDays * $costPerDayAC;
                        echo "Total cost for AC room for $numberOfDays days: $totalCost";
                    } else {
                        $totalCost = $numberOfDays * $costPerDayNonAC;
                        echo "Total cost for non-AC room for $numberOfDays days: $totalCost";
                    }

                    // Update the amount in impatients table
                    $sql_update_amount = "UPDATE impatients SET amount='$totalCost' WHERE pa_id='$patient_id'";
                    if ($conn->query($sql_update_amount) === TRUE) {
                        echo "Amount updated successfully in impatients table.";
                    } else {
                        echo "Error updating amount in impatients table: " . $conn->error;
                    }

                    // Update room_status and pa_id in rooms table
                    $sql_update_room = "UPDATE rooms SET room_status='$room_status', pa_id=NULL WHERE pa_id='$patient_id'";
                    if ($conn->query($sql_update_room) === TRUE) {
                        echo "Room status updated successfully.";
                    } else {
                        echo "Error updating room status: " . $conn->error;
                    }
                } else {
                    echo "No admission date found for the given patient ID.";
                }
            }
        } else {
            echo "No room found for the given patient ID.";
        }
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

$conn->close();
?>

    </div>
</body>

</html>