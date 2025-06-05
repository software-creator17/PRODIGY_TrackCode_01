<!DOCTYPE html>
<html>
<head>
    <title>Report and Bill Summary</title>
    <style>
        table {
            width: 60%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        select, button {
            margin-top: 20px;
        }
    </style>
</head>
<body>

<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hospital";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $option = $_POST["option"];
    $year = $_POST["year"];
    $month = $_POST["month"];
    $startDate = $_POST["startDate"];
    $endDate = $_POST["endDate"];

    switch ($option) {
        case "year":
            $sqlLab = "SELECT l.*, p.first_name, p.middle_name, p.last_name FROM lab_reports l JOIN patient p ON l.pa_id = p.pa_id WHERE YEAR(l.date) = $year";
            $sqlBill = "SELECT b.*, p.first_name, p.middle_name, p.last_name FROM bill b JOIN patient p ON b.pa_id = p.pa_id WHERE YEAR(b.bill_date) = $year";
            break;
        case "month":
            $sqlLab = "SELECT l.*, p.first_name, p.middle_name, p.last_name FROM lab_reports l JOIN patient p ON l.pa_id = p.pa_id WHERE MONTH(l.date) = $month";
            $sqlBill = "SELECT b.*, p.first_name, p.middle_name, p.last_name FROM bill b JOIN patient p ON b.pa_id = p.pa_id WHERE MONTH(b.bill_date) = $month";
            break;
        case "period":
            $sqlLab = "SELECT l.*, p.first_name, p.middle_name, p.last_name, d.first_name AS docfirst_name , d.middle_name AS docmiddle_name, d.last_name AS doclast_name FROM lab_reports l JOIN patient p ON l.pa_id = p.pa_id  JOIN doctor d ON l.doc_id = d.doc_id  WHERE l.date BETWEEN '$startDate' AND '$endDate'";
            $sqlBill = "SELECT b.*, p.first_name, p.middle_name, p.last_name FROM bill b JOIN patient p ON b.pa_id = p.pa_id WHERE b.bill_date BETWEEN '$startDate' AND '$endDate'";
            break;
    }

    $resultLab = $conn->query($sqlLab);
    $resultBill = $conn->query($sqlBill);

    $totalAmountLab = 0;
    $totalAmountBill = 0;

    if ($resultLab->num_rows > 0) {
        echo "<table>";
        echo "<tr><th>Report No</th><th>PA ID</th><th>Patient Name</th><th>Doctor name</th><th>Date</th><th>Category</th><th>Remarks</th><th>Amount</th></tr>";
        while ($row = $resultLab->fetch_assoc()) {
            $fullName = $row["first_name"] . " " . $row["middle_name"] . " " . $row["last_name"];
            $fullNamed = $row["docfirst_name"] . " " . $row["docmiddle_name"] . " " . $row["doclast_name"];

            echo "<tr><td>" . $row["report_no"] . "</td><td>" . $row["pa_id"] . "</td><td>" . $fullName . "</td><td>" . $fullNamed."</td><td>". $row["doc_id"] . "</td><td>" . $row["date"] . "</td><td>" . $row["category"] . "</td><td>" . $row["Remarks"] . "</td><td>" . $row["amount"] . "</td></tr>";
            $totalAmountLab += $row["amount"];
        }
        echo "</table>";
    }

    if ($resultBill->num_rows > 0) {
        echo "<table>";
        echo "<tr><th>Bill No</th><th>Bill Date</th><th>PA ID</th><th>Patient Name</th><th>Room Charge</th><th>Lab Charges</th><th>Total Charges</th></tr>";
        while ($row = $resultBill->fetch_assoc()) {
            $fullName = $row["first_name"] . " " . $row["middle_name"] . " " . $row["last_name"];
            echo "<tr><td>" . $row["bill_no"] . "</td><td>" . $row["bill_date"] . "</td><td>" . $row["pa_id"] . "</td><td>" . $fullName . "</td><td>" . $row["room_charge"] . "</td><td>" . $row["lab_charges"] . "</td><td>" . $row["total_charges"] . "</td></tr>";
            $totalAmountBill += $row["total_charges"];
        }
        echo "</table>";
    }

    echo "<h3>Total Lab Amount: $totalAmountLab</h3>";
    echo "<h3>Total Bill Amount: $totalAmountBill</h3>";
    echo "<h2>Total Amount: " . ($totalAmountLab + $totalAmountBill) . "</h2>";

    $conn->close();
}
?>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label>Select Option:</label>
    <select name="option">
        <option value="year">Year-wise</option>
        <option value="month">Month-wise</option>
        <option value="period">Period-wise</option>
    </select>

    <div id="yearDiv" style="display:none;">
        <label>Select Year:</label>
        <select name="year">
            <?php
            $currentYear = date("Y");
            for ($i = $currentYear; $i >= $currentYear - 10; $i--) {
                echo "<option value='$i'>$i</option>";
            }
            ?>
        </select>
    </div>

    <div id="monthDiv" style="display:none;">
        <label>Select Month:</label>
        <select name="month">
            <?php
            for ($m = 1; $m <= 12; $m++) {
                $monthName = date("F", mktime(0, 0, 0, $m, 1));
                echo "<option value='$m'>$monthName</option>";
            }
            ?>
        </select>
    </div>

    <div id="periodDiv" style="display:none;">
        <label>Select Start Date:</label>
        <input type="date" name="startDate">
        <label>Select End Date:</label>
        <input type="date" name="endDate">
    </div>

    <button type="submit">Submit</button>
</form>

<script>
    document.querySelector("select[name='option']").addEventListener("change", function() {
        document.getElementById("yearDiv").style.display = "none";
        document.getElementById("monthDiv").style.display = "none";
        document.getElementById("periodDiv").style.display = "none";

        var selectedOption = this.value;
        document.getElementById(selectedOption + "Div").style.display = "block";
    });
</script>

</body>
</html>
