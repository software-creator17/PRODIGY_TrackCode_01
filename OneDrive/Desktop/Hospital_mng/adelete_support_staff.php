<!DOCTYPE html>
<html>
<head>
    <title>Update Doctor Status by work ID</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 500px;
            margin: 50px auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            font-weight: bold;
        }
        input[type="text"] {
            width: 100%;
            padding: 10px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }
        input[type="submit"] {
            background-color: #007bff;
            color: #ffffff;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Update worker Status by work ID</h2>
    <form method="post">
        <div class="form-group">
            <label for="work_id">work ID:</label>
            <input type="text" name="admin_id" id="admin_id" required>
        </div>
        <div class="form-group">
            <input type="submit" name="submit" value="Update Status">
        </div>
    </form>
</div>

<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hospital";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $admin_id = $_POST['admin_id'];

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Update admin table
    $sql = "UPDATE support_staff SET current_working='inactive' WHERE work_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $admin_id);
    $stmt->execute();

    echo "<div class='container'><p>worker deleted successfully.</p></div>";

    $stmt->close();
    $conn->close();
}
?>

</body>
</html>
