<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insert Data</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
        }

        .section {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            padding: 20px;
        }

        .section h2 {
            text-align: center;
            margin-top: 0;
        }

        .info-bar {
            background-color: #f0f0f0;
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 20px;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table td,
        table th {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        input[type="text"],
        input[type="number"],
        input[type="date"],
        textarea,
        select {
            width: calc(100% - 20px);
            padding: 8px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .message {
            margin-top: 20px;
            text-align: center;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="section">
            <h2>Check Doctor Availability</h2>
            <form action="" method="POST">
                <table>
                    <tr>
                        <td>Specialization</td>
                        <td>
                            <select name="specialization" required>
                                <?php
                                $conn = mysqli_connect('localhost', 'root', '');
                                $db_select = mysqli_select_db($conn, 'hospital');
                                $sql = "SELECT DISTINCT Specializations FROM doctor";
                                $result = mysqli_query($conn, $sql);
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<option value='" . $row['Specializations'] . "'>" . $row['Specializations'] . "</option>";
                                }
                                mysqli_close($conn);
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Day</td>
                        <td>
                            <select name="day" required>
                                <option value="Monday">Monday</option>
                                <option value="Tuesday">Tuesday</option>
                                <option value="Wednesday">Wednesday</option>
                                <option value="Thursday">Thursday</option>
                                <option value="Friday">Friday</option>
                                <option value="Saturday">Saturday</option>
                                <option value="Sunday">Sunday</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <input type="submit" name="check_availability" value="Check Availability">
                        </td>
                    </tr>
                </table>
            </form>
            <?php
            if (isset($_POST['check_availability'])) {
                $specialization = $_POST['specialization'];
                $day = $_POST['day'];

                $conn = mysqli_connect('localhost', 'root', '');
                $db_select = mysqli_select_db($conn, 'hospital');

                $sql = "SELECT DISTINCT dt.doc_id, dt.nine_10am, dt.ten_11am, dt.eleven_12pm, dt.two_4pm, dt.four_5pm
                FROM doctor_working_days dwd
                INNER JOIN doctor_working_time dt ON dwd.doc_id = dt.doc_id
                INNER JOIN doctor d ON dwd.doc_id = d.doc_id
                WHERE d.Specializations = ? AND dwd.$day = 1";

                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, "s", $specialization);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);


                if (mysqli_num_rows($result) > 0) {
                    echo "<h2>Available Time Slots:</h2>";
                    echo "<table>";
                    echo "<tr><th>Doctor ID</th><th>9-10 AM</th><th>10-11 AM</th><th>11-12 AM</th><th>2-4 PM</th><th>4-5 PM</th></tr>";
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . $row['doc_id'] . "</td>";
                        echo "<td>" . $row['nine_10am'] . "</td>";
                        echo "<td>" . $row['ten_11am'] . "</td>";
                        echo "<td>" . $row['eleven_12pm'] . "</td>";
                        echo "<td>" . $row['two_4pm'] . "</td>";
                        echo "<td>" . $row['four_5pm'] . "</td>";
                        echo "</tr>";
                    }
                    echo "</table>";




                    // Fetching the last pa_id from the patient table
                    $conn = mysqli_connect('localhost', 'root', '');
                    $db_select = mysqli_select_db($conn, 'hospital');

                    $sql_last_pa_id = "SELECT pa_id FROM patient ORDER BY pa_id DESC LIMIT 1";
                    $result_last_pa_id = mysqli_query($conn, $sql_last_pa_id);
                    $last_pa_id = mysqli_fetch_assoc($result_last_pa_id)['pa_id'];
                    $next_pa_id = (int) $last_pa_id + 1;

                    echo "<div class='info-bar'>Last pa_id: " . $last_pa_id . "</div>";

                    echo "<div class='section'>";
                    echo "<h2>Insert into Patients Table</h2>";
                    echo "<form action='' method='POST'>";
                    echo "<table>";
                    echo "<tr><td>Doctor ID</td><td>";
                    echo "<select name='doc_id' required>";
                    mysqli_data_seek($result, 0); // Reset result pointer
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<option value='" . $row['doc_id'] . "'>" . $row['doc_id'] . "</option>";
                    }
                    echo "</select>";
                    echo "</td></tr>";
                    echo "<tr><td>pa_id</td><td><input type='text' name='pa_id' placeholder='Enter next patient ID' required></td></tr>";
                    echo "<tr><td>first_name</td><td><input type='text' name='first_name' placeholder='Enter the first name of the patient' required></td></tr>";
                    echo "<tr><td>middle_name</td><td><input type='text' name='middle_name' placeholder='Enter the middle name of the patient' required></td></tr>";
                    echo "<tr><td>last_name</td><td><input type='text' name='last_name' placeholder='Enter the last name of the patient' required></td></tr>";
                    echo "<tr><td>dob</td><td><input type='date' name='dob' required></td></tr>";
                    echo "<tr><td>gender</td><td><select name='gender' required><option value='Male'>Male</option><option value='Female'>Female</option><option value='Other'>Other</option></select></td></tr>";
                    echo "<tr><td>phone_num</td><td><input type='text' name='phone_num' placeholder='Enter the phone number' required></td></tr>";
                    echo "<tr><td>aadhar</td><td><input type='text' name='aadhar' placeholder='Enter the aadhar number' required></td></tr>";
                    echo "<tr><td>disease</td><td><input type='text' name='disease' placeholder='Enter the disease' required></td></tr>";
                    echo "<tr><td>address</td><td><textarea name='address' placeholder='Enter the address' required></textarea></td></tr>";
                    echo "<tr><td colspan='2'><input type='submit' name='patient_submit' value='Submit'></td></tr>";
                    echo "</table></form></div>";
                } else {
                    echo "<div class='message'>No available doctors for the specified specialization and day.</div>";
                }

                mysqli_close($conn);
            }

            if (isset($_POST['patient_submit'])) {
                $doc_id = $_POST['doc_id'];
                $pa_id = $_POST['pa_id'];
                $first_name = $_POST['first_name'];
                $middle_name = $_POST['middle_name'];
                $last_name = $_POST['last_name'];
                $dob = $_POST['dob'];
                $current_date = date("Y-m-d");
                $gender = $_POST['gender'];
                $phone_num = $_POST['phone_num'];
                $aadhar = $_POST['aadhar'];
                $disease = $_POST['disease'];
                $address = $_POST['address'];

                $conn = mysqli_connect('localhost', 'root', '');
                $db_select = mysqli_select_db($conn, 'hospital');

                $sql = "INSERT INTO patient (`doc_id`, `pa_id`, `first_name`, `middle_name`, `last_name`, `dob`,`date`, `gender`, `phone_num`, `aadhar`, `disease`, `address`)
                       VALUES ('$doc_id', '$pa_id', '$first_name', '$middle_name', '$last_name', '$dob','$current_date', '$gender', '$phone_num', '$aadhar', '$disease', '$address')";
                $result = mysqli_query($conn, $sql);

                if ($result == true) {
                    echo "<div class='message'>Data added successfully</div>";
                } else {
                    echo "<div class='message'>Error: " . mysqli_error($conn) . "</div>";
                }
                mysqli_close($conn);
            }
            ?>
        </div>
    </div>
</body>

</html>