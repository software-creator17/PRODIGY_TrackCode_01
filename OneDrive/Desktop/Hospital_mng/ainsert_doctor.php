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
            max-width: 800px;
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

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid #ccc;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        input[type="text"],
        input[type="number"] {
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

<?php
    // Database connection
    $conn = mysqli_connect('localhost', 'root', '', 'hospital');

    // Fetch the last entered doc_id
    $last_doc_query = "SELECT MAX(doc_id) as last_doc_id FROM doctor";
    $last_doc_result = mysqli_query($conn, $last_doc_query);
    $last_doc_row = mysqli_fetch_assoc($last_doc_result);
    $last_doc_id = $last_doc_row['last_doc_id'];

    // Fetch departments for dropdown
    $sql = "SELECT dep_id, dep_name FROM department";
    $result = mysqli_query($conn, $sql);
    $departments = mysqli_fetch_all($result, MYSQLI_ASSOC);

    mysqli_close($conn);
?>

<div class="container">
    <div class="section">
        <h2>Last Doctor ID entered: <?php echo $last_doc_id; ?></h2>
    </div>

    <div class="section">
        <h2>Insert into Doctors Table</h2>
        <form action="" method="POST">
            <table>
                <tr>
                    <td>doc_id</td>
                    <td>
                        <input type="text" name="doc_id" placeholder="Enter the doctor's ID">
                    </td>
                </tr>
                <tr>
                    <td>first_name</td>
                    <td>
                        <input type="text" name="first_name" placeholder="Enter the first name of the doctor">
                    </td>
                </tr>
                <tr>
                    <td>middle_name</td>
                    <td>
                        <input type="text" name="middle_name" placeholder="Enter the middle name of the doctor">
                    </td>
                </tr>
                <tr>
                    <td>last_name</td>
                    <td>
                        <input type="text" name="last_name" placeholder="Enter the last name of the doctor">
                    </td>
                </tr>
                <tr>
                    <td>experience</td>
                    <td>
                        <input type="number" name="experience">
                    </td>
                </tr>
                <tr>
                    <td>age</td>
                    <td>
                        <input type="number" name="age">
                    </td>
                </tr>
                <tr>
                    <td>specializations</td>
                    <td>
                        <input type="text" name="specializations">
                    </td>
                </tr>
                <tr>
                    <td>tenure</td>
                    <td>
                        <input type="text" name="tenure">
                    </td>
                </tr>
                <!-- Hidden input field for current_working -->
                <input type="hidden" name="current_working" value="active">
                <tr>
                    <td colspan="2">
                        <input type="submit" name="doc_submit" value="Submit">
                    </td>
                </tr>
            </table>
        </form>
        <?php
        if (isset($_POST['doc_submit'])) {
            $doc_id = $_POST['doc_id'];
            $firstN = $_POST['first_name'];
            $middleN = $_POST['middle_name'];
            $lastN = $_POST['last_name'];
            $experience = $_POST['experience'];
            $age = $_POST['age'];
            $specializations = $_POST['specializations'];
            $tenure = $_POST['tenure'];
            $current_working = $_POST['current_working'];

            $conn = mysqli_connect('localhost', 'root', '');
            $db_select = mysqli_select_db($conn, 'hospital');

            $sql = "INSERT INTO doctor (`doc_id`, `first_name`, `middle_name`, `last_name`, experience, age, specializations, tenure, current_working) 
                    VALUES ('$doc_id', '$firstN', '$middleN', '$lastN', '$experience', '$age', '$specializations', '$tenure', '$current_working')";
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

    <!-- Assign Doctor to Department -->
    <div class="section">
        <h2>Assign Doctor to Department</h2>
        <p class="message">Choose department for the doctor:</p>
        <table>
            <tr>
                <th>dep_id</th>
                <th>dep_name</th>
            </tr>
            <?php
            // Connect to database
            $conn = mysqli_connect('localhost', 'root', '');
            $db_select = mysqli_select_db($conn, 'hospital');

            // Fetch department information from database
            $sql = "SELECT dep_id, dep_name FROM department";
            $result = mysqli_query($conn, $sql);

            // Display department information in a table
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . $row['dep_id'] . "</td>";
                echo "<td>" . $row['dep_name'] . "</td>";
                echo "</tr>";
            }

            // Close database connection
            mysqli_close($conn);
            ?>
        </table>
        <form action="" method="POST">
            <input type="hidden" name="doc_id_dep" value="<?php echo $last_doc_id; ?>">
            <table>
                <tr>
                    <td>dep_id</td>
                    <td>
                        <select name="dep_id">
                            <?php foreach ($departments as $department) : ?>
                                <option value="<?php echo $department['dep_id']; ?>">
                                    <?php echo $department['dep_name']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <input type="submit" name="assign_submit" value="Assign">
                    </td>
                </tr>
            </table>
        </form>
        <?php
        if (isset($_POST['assign_submit'])) {
            $doc_id_dep = $_POST['doc_id_dep'];
            $dep_id = $_POST['dep_id'];

            $conn = mysqli_connect('localhost', 'root', '');
            $db_select = mysqli_select_db($conn, 'hospital');

            $sql = "INSERT INTO doc_dep (doc_id, dep_id) VALUES ('$doc_id_dep', '$dep_id')";
            $result = mysqli_query($conn, $sql);

            if ($result == true) {
                echo "<div class='message'>Doctor assigned to department successfully</div>";
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
