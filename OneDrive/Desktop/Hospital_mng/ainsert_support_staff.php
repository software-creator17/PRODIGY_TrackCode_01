<!DOCTYPE html>
<html>

<head>
    <title>Add Support Staff</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }

        form {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        label {
            display: inline-block;
            width: 120px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="date"],
        input[type="number"] {
            width: calc(100% - 130px);
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 3px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>

<body>

    <h2>Add Support Staff</h2>

    <?php
    // Establish connection to your database
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

    // Fetch the last entered work_id
    $last_work_query = "SELECT MAX(work_id) as last_work_id FROM support_staff";
    $last_work_result = $conn->query($last_work_query);
    $last_work_row = $last_work_result->fetch_assoc();
    $last_work_id = $last_work_row['last_work_id'];

    echo "Last Work ID entered: " . $last_work_id;

    // Check if form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Prepare SQL statement to insert data into the support_staff table
        $sql = "INSERT INTO support_staff (work_id, doj, salary, current_working) VALUES (?, ?, ?, 'active')";

        // Prepare and bind parameters
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssd", $work_id, $current_date, $salary);

        // Set parameters from the form submission
        $work_id = $_POST['work_id'];
        $current_date = date("Y-m-d");
        $salary = $_POST['salary'];

        // Execute the SQL statement
        if ($stmt->execute() === TRUE) {
            echo "<br>New record created successfully";
        } else {
            echo "<br>Error: " . $sql . "<br>" . $conn->error;
        }

        // Close the prepared statement
        $stmt->close();
    }

    // Close the database connection
    $conn->close();
    ?>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="work_id">Work ID:</label>
        <input type="text" id="work_id" name="work_id" maxlength="10" required><br>

        <label for="salary">Salary:</label>
        <input type="number" id="salary" name="salary" required><br>

        <input type="submit" value="Submit">
    </form>

</body>

</html>
