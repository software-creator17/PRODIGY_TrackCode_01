<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insert Data</title>
    <style>
        /* ... (Same CSS as provided) ... */
    </style>
</head>

<body>
    <div class="container">
        <div class="section">
            <h2>Insert the new Admin member ID</h2>
            <form action="" method="POST">
                <table>
                    <tr>
                        <td>Admin_id</td>
                        <td>
                            <input type="text" name="staff_id" placeholder="Enter the admin's ID">
                        </td>
                    </tr>
                    <tr>
                        <td>Name</td>
                        <td>
                            <input type="text" name="staff_name" placeholder="Enter the admin's name">
                        </td>
                    </tr>
                    <tr>
                        <td>salary</td>
                        <td>
                            <input type="number" name="salary" placeholder="Enter the admin's salary">
                        </td>
                    </tr>
                    <!-- Hidden input field for current_working -->
                    <input type="hidden" name="current_working" value="active">
                    <tr>
                        <td colspan="2">
                            <input type="submit" name="staff_submit" value="Submit">
                        </td>
                    </tr>
                </table>
            </form>

            <?php
            if (isset($_POST['staff_submit'])) {
                $staff_id = $_POST['staff_id'];
                $staff_name = $_POST['staff_name'];
                $salary = $_POST['salary'];
                $current_date = date("Y-m-d");
                $current_working = $_POST['current_working'];

                $conn = mysqli_connect('localhost', 'root', '', 'hospital');

                if (!$conn) {
                    die("Connection failed: " . mysqli_connect_error());
                }

                $sql = "INSERT INTO staff (staff_id, name, salary, join_date, current_working) VALUES ('$staff_id', '$staff_name', '$salary', '$current_date', '$current_working')";
                
                if (mysqli_query($conn, $sql)) {
                    echo "<div class='message'>Data added successfully</div>";
                } else {
                    echo "<div class='message'>Error: " . mysqli_error($conn) . "</div>";
                }

                mysqli_close($conn);
            }

            // Display the last entered staff_id
            $conn = mysqli_connect('localhost', 'root', '', 'hospital');
            $last_staff_query = "SELECT MAX(staff_id) as last_staff_id FROM staff";
            $last_staff_result = mysqli_query($conn, $last_staff_query);
            $last_staff_row = mysqli_fetch_assoc($last_staff_result);
            $last_staff_id = $last_staff_row['last_staff_id'];

            echo "<div class='message'>Last Staff ID entered: " . $last_staff_id . "</div>";

            mysqli_close($conn);
            ?>

<h2>Update Admin Salary</h2>
            <form action="" method="POST">
                <table>
                    <tr>
                        <td>Admin_id</td>
                        <td>
                            <input type="text" name="update_staff_id" placeholder="Enter the admin's ID">
                        </td>
                    </tr>
                    <tr>
                        <td>Salary</td>
                        <td>
                            <input type="number" name="update_salary" placeholder="Enter the new salary">
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <input type="submit" name="update_submit" value="Update Salary">
                        </td>
                    </tr>
                </table>
            </form>

            <?php
            // Update Salary PHP Code
            if (isset($_POST['update_submit'])) {
                $update_staff_id = $_POST['update_staff_id'];
                $update_salary = $_POST['update_salary'];

                $conn = mysqli_connect('localhost', 'root', '', 'hospital');

                if (!$conn) {
                    die("Connection failed: " . mysqli_connect_error());
                }

                $update_sql = "UPDATE staff SET salary = '$update_salary' WHERE staff_id = '$update_staff_id'";
                
                if (mysqli_query($conn, $update_sql)) {
                    echo "<div class='message'>Salary updated successfully</div>";
                } else {
                    echo "<div class='message'>Error updating salary: " . mysqli_error($conn) . "</div>";
                }

                mysqli_close($conn);
            }
            ?>
        </div>
    </div>
</body>

</html>
