<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff and Support Staff Salary</title>
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

        .total {
            margin-top: 20px;
            font-weight: bold;
            text-align: right;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Staff and Support Staff Salary</h2>

        <?php
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "hospital";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Fetch staff salary
        $sql_staff = "SELECT staff_id, name, salary FROM staff";
        $result_staff = $conn->query($sql_staff);

        // Fetch support staff salary
        $sql_support_staff = "SELECT work_id, salary FROM support_staff";
        $result_support_staff = $conn->query($sql_support_staff);

        echo "<h3>Staff Salary</h3>";
        echo "<table>";
        echo "<tr><th>Staff ID</th><th>Name</th><th>Salary</th></tr>";
        $total_staff_salary = 0;

        while ($row = $result_staff->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["staff_id"] . "</td>";
            echo "<td>" . $row["name"] . "</td>";
            echo "<td>" . $row["salary"] . "</td>";
            echo "</tr>";
            $total_staff_salary += $row["salary"];
        }

        echo "</table>";
        echo "<div class='total'>Total Staff Salary: rs-" . $total_staff_salary . "</div>";

        echo "<h3>Support Staff Salary</h3>";
        echo "<table>";
        echo "<tr><th>Work ID</th><th>Salary</th></tr>";
        $total_support_staff_salary = 0;

        while ($row = $result_support_staff->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["work_id"] . "</td>";
            echo "<td>" . $row["salary"] . "</td>";
            echo "</tr>";
            $total_support_staff_salary += $row["salary"];
        }

        echo "</table>";
        echo "<div class='total'>Total Support Staff Salary: rs-" . $total_support_staff_salary . "</div>";

        $total_combined_salary = $total_staff_salary + $total_support_staff_salary;
        echo "<div class='total'>Combined Total Salary: rs-" . $total_combined_salary . "</div>";

        $conn->close();
        ?>
    </div>
</body>

</html>
