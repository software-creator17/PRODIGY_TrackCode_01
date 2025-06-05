<!DOCTYPE html>
<html>
<head>
    <title>Doctor Details</title>
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
    </style>
</head>
<body>

<div class="container">
    <h2>Doctor Details</h2>
    <form method="post" action="">
        <div class="form-group">
            <label>Select what you want to see:</label>
            <select name="option" id="option" onchange="showFields()">
                <option value="">Select Option</option>
                <option value="doc_id">doc_id</option>
                <option value="current_working">Current Working</option>
                <option value="tenure">Tenure</option>
            </select>
        </div>
        <div id="doc_id_fields" class="form-group" style="display: none;">
            <label>Enter doc_id:</label>
            <input type="text" name="doc_id">
        </div>
        <div id="current_working_fields" class="form-group" style="display: none;">
            <label>Select Status:</label>
            <select name="status">
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </div>
        <div id="tenure_fields" class="form-group" style="display: none;">
            <label>Select Tenure:</label>
            <select name="tenure_type">
                <option value="permanent">Permanent</option>
                <option value="visiting">Visiting</option>
                <option value="trainee">Trainee</option>
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
        $option = $_POST['option'];

        if ($option == "doc_id") {
            $doc_id = $_POST['doc_id'];
            $stmt = $conn->prepare("SELECT * FROM doctor WHERE doc_id = ?");
            $stmt->bind_param("s", $doc_id);
            $stmt->execute();
            $result = $stmt->get_result();
        } elseif ($option == "current_working") {
            $status = $_POST['status'];
            $stmt = $conn->prepare("SELECT * FROM doctor WHERE current_working = ?");
            $stmt->bind_param("s", $status);
            $stmt->execute();
            $result = $stmt->get_result();
        } elseif ($option == "tenure") {
            $tenure_type = $_POST['tenure_type'];
            $stmt = $conn->prepare("SELECT * FROM doctor WHERE tenure = ?");
            $stmt->bind_param("s", $tenure_type);
            $stmt->execute();
            $result = $stmt->get_result();
        }

        if (isset($result) && $result->num_rows > 0) {
            echo "<h3>Doctor Details</h3>";
            echo "<table border='1'>
                    <tr>
                        <th>doc_id</th>
                        <th>first_name</th>
                        <th>middle_name</th>
                        <th>last_name</th>
                        <th>experience</th>
                        <th>age</th>
                        <th>Specializations</th>
                        <th>tenure</th>
                        <th>current_working</th>
                    </tr>";

            while($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>".$row["doc_id"]."</td>
                        <td>".$row["first_name"]."</td>
                        <td>".$row["middle_name"]."</td>
                        <td>".$row["last_name"]."</td>
                        <td>".$row["experience"]."</td>
                        <td>".$row["age"]."</td>
                        <td>".$row["Specializations"]."</td>
                        <td>".$row["tenure"]."</td>
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

        if (option == "doc_id") {
            document.getElementById("doc_id_fields").style.display = "block";
            document.getElementById("current_working_fields").style.display = "none";
            document.getElementById("tenure_fields").style.display = "none";
        } else if (option == "current_working") {
            document.getElementById("doc_id_fields").style.display = "none";
            document.getElementById("current_working_fields").style.display = "block";
            document.getElementById("tenure_fields").style.display = "none";
        } else if (option == "tenure") {
            document.getElementById("doc_id_fields").style.display = "none";
            document.getElementById("current_working_fields").style.display = "none";
            document.getElementById("tenure_fields").style.display = "block";
        } else {
            document.getElementById("doc_id_fields").style.display = "none";
            document.getElementById("current_working_fields").style.display = "none";
            document.getElementById("tenure_fields").style.display = "none";
        }
    }
</script>

</body>
</html>
