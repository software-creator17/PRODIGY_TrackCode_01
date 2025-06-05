<!DOCTYPE html>
<html>
<head>
    <title>Add Doctor Working Time</title>
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
        input[type="text"] {
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

<h2>Add Doctor Working Time</h2>

<?php
// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
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

    // Prepare SQL statement to insert data into the doctor_working_time table
    $sql = "INSERT INTO doctor_working_time (doc_id, nine_10am, ten_11am, eleven_12pm, two_4pm, four_5pm) VALUES (?, ?, ?, ?, ?, ?)";

    // Prepare and bind parameters
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $doc_id, $nine_10am, $ten_11am, $eleven_12pm, $two_4pm, $four_5pm);

    // Set parameters from the form submission
    $doc_id = $_POST['doc_id'];
    $nine_10am = $_POST['nine_10am'];
    $ten_11am = $_POST['ten_11am'];
    $eleven_12pm = $_POST['eleven_12pm'];
    $two_4pm = $_POST['two_4pm'];
    $four_5pm = $_POST['four_5pm'];

    // Execute the SQL statement
    if ($stmt->execute() === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Close the prepared statement and database connection
    $stmt->close();
    $conn->close();
}
?>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <label for="doc_id">Doctor ID:</label>
    <input type="text" id="doc_id" name="doc_id" maxlength="10" required><br>

    <label for="nine_10am">9-10am:</label>
    <input type="text" id="nine_10am" name="nine_10am" maxlength="1" required><br>

    <label for="ten_11am">10-11am:</label>
    <input type="text" id="ten_11am" name="ten_11am" maxlength="1" required><br>

    <label for="eleven_12pm">11-12pm:</label>
    <input type="text" id="eleven_12pm" name="eleven_12pm" maxlength="1" required><br>

    <label for="two_4pm">2-4pm:</label>
    <input type="text" id="two_4pm" name="two_4pm" maxlength="1" required><br>

    <label for="four_5pm">4-5pm:</label>
    <input type="text" id="four_5pm" name="four_5pm" maxlength="1" required><br>

    <input type="submit" value="Submit">
</form>

</body>
</html>
