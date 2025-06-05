<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Read Patient Info</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-top: 0;
        }

        label {
            display: block;
            margin-bottom: 10px;
        }

        select, input[type="text"] {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            margin-bottom: 20px;
        }

        input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
            width: 100%;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 8px 12px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
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
        <h2>Read Patient Info</h2>

        <form action="" method="POST">
            <label for="field">Select Field:</label>
            <select name="field" id="field" required>
                <option value="pa_id">pa_id</option>
                <option value="doc_id">doc_id</option>
                <option value="first_name">First Name</option>
                <option value="middle_name">Middle Name</option>
                <option value="last_name">Last Name</option>
                <option value="dob">Date of Birth</option>
                <option value="date">Date</option>
                <option value="gender">Gender</option>
                <option value="phone_num">Phone Number</option>
                <option value="aadhar">Aadhar</option>
                <option value="disease">Disease</option>
                <option value="address">Address</option>
                <option value="type">Type</option>
            </select>

            <label for="value">Enter Value:</label>
            <input type="text" name="value" id="value" required>

            <input type="submit" name="submit" value="Fetch Info">
        </form>

        <?php
        if (isset($_POST['submit'])) {
            $field = $_POST['field'];
            $value = $_POST['value'];

            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "hospital";

            $conn = new mysqli($servername, $username, $password, $dbname);

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $sql = "SELECT * FROM patient WHERE $field = '$value'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                echo "<table>";
                echo "<tr><th>pa_id</th><th>doc_id</th><th>First Name</th><th>Middle Name</th><th>Last Name</th><th>Date of Birth</th><th>Date</th><th>Gender</th><th>Phone Number</th><th>Aadhar</th><th>Disease</th><th>Address</th><th>Type</th></tr>";

                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["pa_id"] . "</td>";
                    echo "<td>" . $row["doc_id"] . "</td>";
                    echo "<td>" . $row["first_name"] . "</td>";
                    echo "<td>" . $row["middle_name"] . "</td>";
                    echo "<td>" . $row["last_name"] . "</td>";
                    echo "<td>" . $row["dob"] . "</td>";
                    echo "<td>" . $row["date"] . "</td>";
                    echo "<td>" . $row["gender"] . "</td>";
                    echo "<td>" . $row["phone_num"] . "</td>";
                    echo "<td>" . $row["aadhar"] . "</td>";
                    echo "<td>" . $row["disease"] . "</td>";
                    echo "<td>" . $row["address"] . "</td>";
                    echo "<td>" . $row["type"] . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "<div class='message'>No records found for the specified criteria.</div>";
            }

            $conn->close();
        }
        ?>
    </div>
</body>

</html>
