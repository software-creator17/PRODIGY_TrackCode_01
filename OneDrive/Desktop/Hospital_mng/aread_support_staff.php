<!DOCTYPE html>
<html>
<head>
    <title>Support Staff Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .date-fields {
            display: none;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Support Staff Details</h2>
    <form method="post" action="">
        <div class="form-group">
            <label>Select what you want to see:</label>
            <select name="option" id="option" onchange="showFields()">
                <option value="">Select Option</option>
                <option value="work_id">Work ID</option>
                <option value="current_working">Current Working</option>
                <option value="date_range">Date of Joining Range</option>
            </select>
        </div>
        <div id="work_id_fields" class="form-group" style="display: none;">
            <label>Enter Work ID:</label>
            <input type="text" name="work_id">
        </div>
        <div id="current_working_fields" class="form-group" style="display: none;">
            <label>Select Status:</label>
            <select name="status">
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </div>
        <div id="date_range_fields" class="date-fields">
            <div class="form-group">
                <label>Start Date:</label>
                <input type="date" name="start_date">
            </div>
            <div class="form-group">
                <label>End Date:</label>
                <input type="date" name="end_date">
            </div>
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
        $option = $_POST['option'];

        if ($option == "work_id") {
            $work_id = $_POST['work_id'];
            $stmt = $conn->prepare("SELECT * FROM support_staff WHERE work_id = ?");
            $stmt->bind_param("s", $work_id);
        } elseif ($option == "current_working") {
            $status = $_POST['status'];
            $stmt = $conn->prepare("SELECT * FROM support_staff WHERE current_working = ?");
            $stmt->bind_param("s", $status);
        } elseif ($option == "date_range") {
            $start_date = $_POST['start_date'];
            $end_date = $_POST['end_date'];
            $stmt = $conn->prepare("SELECT * FROM support_staff WHERE doj BETWEEN ? AND ?");
            $stmt->bind_param("ss", $start_date, $end_date);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "<h3>Support Staff Details</h3>";
            echo "<table border='1'>
                    <tr>
                        <th>Work ID</th>
                        <th>Date of Joining</th>
                        <th>Salary</th>
                        <th>Current Working</th>
                    </tr>";

            while($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>".$row["work_id"]."</td>
                        <td>".$row["doj"]."</td>
                        <td>".$row["salary"]."</td>
                        <td>".$row["current_working"]."</td>
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

<script>
    function showFields() {
        var option = document.getElementById("option").value;

        document.getElementById("work_id_fields").style.display = "none";
        document.getElementById("current_working_fields").style.display = "none";
        document.getElementById("date_range_fields").style.display = "none";

        if (option == "work_id") {
            document.getElementById("work_id_fields").style.display = "block";
        } else if (option == "current_working") {
            document.getElementById("current_working_fields").style.display = "block";
        } else if (option == "date_range") {
            document.getElementById("date_range_fields").style.display = "block";
        }
    }
</script>

</body>
</html>
