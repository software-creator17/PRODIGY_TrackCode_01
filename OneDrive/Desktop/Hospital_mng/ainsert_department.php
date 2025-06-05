<!DOCTYPE html>
<html>
<head>
    <title>Add, Update, or Delete Department</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        form {
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
            margin-bottom: 20px;
        }
        input[type="text"], input[type="number"] {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
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
            width: 100%;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

<h2>Departments</h2>

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

// Fetch departments from the database
$sql = "SELECT dep_id, dep_name, location FROM department";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<table>";
    echo "<tr><th>Department ID</th><th>Department Name</th><th>Location</th></tr>";
    // Output data of each row
    while($row = $result->fetch_assoc()) {
        echo "<tr><td>".$row["dep_id"]."</td><td>".$row["dep_name"]."</td><td>".$row["location"]."</td></tr>";
    }
    echo "</table>";
} else {
    echo "0 results";
}

// Close the database connection
$conn->close();
?>

<h2>Add Department</h2>

<?php
// Check if form is submitted for adding department
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add'])) {
    // Establish connection to your database
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare SQL statement to insert data into the department table
    $sql = "INSERT INTO department (dep_id, dep_name, location) VALUES (?,?, ?)";

    // Prepare and bind parameters
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $dep_id, $dep_name, $location);

    // Set parameters from the form submission
    $dep_id = $_POST['dep_id'];
    $dep_name = $_POST['dep_name'];
    $location = $_POST['location'];

    // Execute the SQL statement
    if ($stmt->execute() === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Close the prepared statement
    $stmt->close();

    // Close the database connection
    $conn->close();
}
?>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <label for="dep_id">Department ID:</label>
    <input type="text" id="dep_id" name="dep_id" maxlength="10" required>

    <label for="dep_name">Department Name:</label>
    <input type="text" id="dep_name" name="dep_name" maxlength="20" required>

    <label for="location">Location:</label>
    <input type="text" id="location" name="location" maxlength="100" required>

    <input type="submit" name="add" value="Add">
</form>

<h2>Update Department</h2>

<?php
// Check if form is submitted for updating department
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    // Establish connection to your database
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare SQL statement to update data in the department table
    $sql = "UPDATE department SET dep_name=?, location=? WHERE dep_id=?";

    // Prepare and bind parameters
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $dep_name, $location, $dep_id);

    // Set parameters from the form submission
    $dep_id = $_POST['update_dep_id'];
    $dep_name = $_POST['dep_name'];
    $location = $_POST['location'];

    // Execute the SQL statement
    if ($stmt->execute() === TRUE) {
        echo "Record updated successfully";
    } else {
        echo "Error updating record: " . $conn->error;
    }

    // Close the prepared statement
    $stmt->close();

    // Close the database connection
    $conn->close();
}
?>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <label for="update_dep_id">Department ID to Update:</label>
    <input type="text" id="update_dep_id" name="update_dep_id" maxlength="10" required>
    
    <label for="update_dep_name">New Department Name:</label>
    <input type="text" id="update_dep_name" name="dep_name" maxlength="20" required>

    <label for="update_location">New Location:</label>
    <input type="text" id="update_location" name="location" maxlength="100" required>

    <input type="submit" name="update" value="Update">
</form>

<h2>Delete Department</h2>

<?php
// Check if form is submitted for deleting department
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
    // Establish connection to your database
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare SQL statement to delete data from the department table
    $sql = "DELETE FROM department WHERE dep_id=?";

    // Prepare and bind parameter
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $dep_id);

    // Set parameter from the form submission
    $dep_id = $_POST['delete_dep_id'];

    // Execute the SQL statement
    if ($stmt->execute() === TRUE) {
        echo "Record deleted successfully";
    } else {
        echo "Error deleting record: " . $conn->error;
    }

    // Close the prepared statement
    $stmt->close();

    // Close the database connection
    $conn->close();
}
?>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <label for="delete_dep_id">Department ID to Delete:</label>
    <input type="text" id="delete_dep_id" name="delete_dep_id" maxlength="10" required>
    
    <input type="submit" name="delete" value="Delete">
</form>

</body>
</html>
