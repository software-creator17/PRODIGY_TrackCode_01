<!DOCTYPE html>
<html>

<head>
    <title>Hospital Room Allotment</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="submit"],
        select {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
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

        if (isset($_GET['action']) && $_GET['action'] == "allotment") {
            // Allotment of rooms form
            if (isset($_POST['action']) && $_POST['action'] == "submit_details") {
                // Submit details
                $patient_id = $_POST['patient_id'];
                $phone_number = $_POST['phone_number'];

                $sql = "SELECT * FROM patient WHERE pa_id='$patient_id' AND phone_num='$phone_number' AND type='impatient'";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    // Display room type selection form
                    ?>
                    <h2>Select Room Type</h2>
                    <form method="post" action="?action=allotment">
                        <input type="hidden" name="action" value="update_patient_room">
                        <div class="form-group">
                            <label for="room_type">Room Type</label>
                            <select id="room_type" name="room_type" required>
                                <option value="ac">AC</option>
                                <option value="non_ac">Non-AC</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <input type="hidden" name="patient_id" value="<?php echo $patient_id; ?>">
                            <input type="submit" value="Submit">
                        </div>
                    </form>
                    <?php
                } else {
                    echo "No permission from doctor to have room or be admitted.";
                }
            } elseif (isset($_POST['action']) && $_POST['action'] == "update_patient_room") {
                // Handle room selection and allotment
                $room_type = $_POST['room_type'];

                // Check for vacant rooms of the selected type
                $sql = "SELECT room_no FROM rooms WHERE room_type='$room_type' AND room_status='vacant'";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    // Display available room numbers
                    ?>
                    <h2>Available Rooms</h2>
                    <ul>
                        <?php
                        while ($row = $result->fetch_assoc()) {
                            echo "<li>" . $row['room_no'] . "</li>";
                        }
                        ?>
                    </ul>

                    <!-- Form to update impatients table -->
                    <h2>Update Impatients Table</h2>
                    <form method="post" action="?action=update_impatients">
                        <div class="form-group">
                            <label for="pa_id">Patient ID</label>
                            <input type="text" id="pa_id" name="pa_id" value="<?php echo $_POST['patient_id']; ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label for="room_no">Room Number</label>
                            <input type="text" id="room_no" name="room_no" required>
                        </div>
                        <div class="form-group">
                            <input type="submit" value="Update">
                        </div>
                        <input type="hidden" name="action" value="update_impatients">
                    </form>

                    <?php
                } else {
                    echo "No rooms available with the required fields.";
                }
            } else {
                ?>
                <h2>Allotment of Rooms</h2>
                <form method="post" action="?action=allotment">
                    <div class="form-group">
                        <label for="patient_id">Patient ID</label>
                        <input type="text" id="patient_id" name="patient_id" required>
                    </div>
                    <div class="form-group">
                        <label for="phone_number">Phone Number</label>
                        <input type="text" id="phone_number" name="phone_number" required>
                    </div>
                    <div class="form-group">
                        <input type="hidden" name="action" value="submit_details">
                        <input type="submit" value="Submit">
                    </div>
                </form>
                <?php
            }
        } elseif (isset($_GET['action']) && $_GET['action'] == "update_imp_room") {
            // Redirect to update_impatient_room.php
            echo "<script>window.location = 'aupdate_impatient_room.php';</script>";
            exit;
        } else {
            ?>
            <h2>Options</h2>
            <ul>
                <li><a href="?action=allotment">Allotment of Rooms</a></li>
                <li><a href="?action=update_imp_room">Update Impatients and Rooms</a></li>
            </ul>
            <?php
        }

        // Handle updating impatients and rooms table
        if (isset($_POST['action']) && $_POST['action'] == "update_impatients") {
            $pa_id = $_POST['pa_id'];
            $room_no = $_POST['room_no'];
            $current_date = date("Y-m-d");

            // Insert into impatients table
            $sql_insert = "INSERT INTO impatients (pa_id, room_no, doa) VALUES (?, ?, ?)";
            $stmt_insert = $conn->prepare($sql_insert);
            $stmt_insert->bind_param("sss", $pa_id, $room_no, $current_date);

            // Update rooms table
            $sql_update = "UPDATE rooms SET room_status='occupied', pa_id=? WHERE room_no=?";
            $stmt_update = $conn->prepare($sql_update);
            $stmt_update->bind_param("ss", $pa_id, $room_no);

            // Execute queries
            if ($stmt_insert->execute() && $stmt_update->execute()) {
                echo "Record updated successfully.";
            } else {
                echo "Error updating record: " . $conn->error;
            }

            $stmt_insert->close();
            $stmt_update->close();
        }



        ?>
    </div>

</body>

</html>