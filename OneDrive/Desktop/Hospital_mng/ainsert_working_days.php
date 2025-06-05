<!DOCTYPE html>
<html>
<head>
    <title>Add Doctor Working Days</title>
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

<h2>Add Doctor Working Days</h2>

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

    // Prepare SQL statement to insert data into the doctor_working_days table
    $sql = "INSERT INTO doctor_working_days (doc_id, monday, tuesday, wednesday, thursday, friday, saturday, sunday) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    // Prepare and bind parameters
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssss", $doc_id, $monday, $tuesday, $wednesday, $thursday, $friday, $saturday, $sunday);

    // Set parameters from the form submission
    $doc_id = $_POST['doc_id'];
    $monday = $_POST['monday'];
    $tuesday = $_POST['tuesday'];
    $wednesday = $_POST['wednesday'];
    $thursday = $_POST['thursday'];
    $friday = $_POST['friday'];
    $saturday = $_POST['saturday'];
    $sunday = $_POST['sunday'];

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

    <label for="monday">Monday:</label>
    <input type="text" id="monday" name="monday" maxlength="1" required><br>

    <label for="tuesday">Tuesday:</label>
    <input type="text" id="tuesday" name="tuesday" maxlength="1" required><br>

    <label for="wednesday">Wednesday:</label>
    <input type="text" id="wednesday" name="wednesday" maxlength="1" required><br>

    <label for="thursday">Thursday:</label>
    <input type="text" id="thursday" name="thursday" maxlength="1" required><br>

    <label for="friday">Friday:</label>
    <input type="text" id="friday" name="friday" maxlength="1" required><br>

    <label for="saturday">Saturday:</label>
    <input type="text" id="saturday" name="saturday" maxlength="1" required><br>

    <label for="sunday">Sunday:</label>
    <input type="text" id="sunday" name="sunday" maxlength="1" required><br>

    <input type="submit" value="Submit">
</form>

</body>
</html>
