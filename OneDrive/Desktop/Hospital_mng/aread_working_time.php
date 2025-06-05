<!DOCTYPE html>
<html>
<head>
    <title>Doctor Working Time</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .form-group {
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Doctor Working Time</h2>
    <form method="post" action="">
        <div class="form-group">
            <label>Select doc_id:</label>
            <select name="doc_id">
                <option value="">Select doc_id</option>
                <option value="dr101">dr101</option>
                <option value="dr102">dr102</option>
                <option value="dr103">dr103</option>
                <option value="dr104">dr104</option>
            </select>
        </div>
        <div class="form-group">
            <input type="submit" name="submit" value="Submit">
        </div>
    </form>

    <?php
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

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $doc_id = $_POST['doc_id'];

        $stmt = $conn->prepare("SELECT * FROM doctor_working_time WHERE doc_id = ?");
        $stmt->bind_param("s", $doc_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if (isset($result) && $result->num_rows > 0) {
            echo "<h3>Doctor Working Time for $doc_id</h3>";
            echo "<table border='1'>
                    <tr>
                        <th>doc_id</th>
                        <th>9:00 - 10:00 AM</th>
                        <th>10:00 - 11:00 AM</th>
                        <th>11:00 AM - 12:00 PM</th>
                        <th>2:00 - 4:00 PM</th>
                        <th>4:00 - 5:00 PM</th>
                    </tr>";

            while($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>".$row["doc_id"]."</td>
                        <td>".$row["nine_10am"]."</td>
                        <td>".$row["ten_11am"]."</td>
                        <td>".$row["eleven_12pm"]."</td>
                        <td>".$row["two_4pm"]."</td>
                        <td>".$row["four_5pm"]."</td>
                      </tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No results found.</p>";
        }

        $stmt->close();
        $conn->close();
    }
    ?>

</div>

</body>
</html>
