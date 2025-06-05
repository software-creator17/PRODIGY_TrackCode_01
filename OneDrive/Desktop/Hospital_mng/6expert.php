<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to MSG Hospital</title>
    <style>
       
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #4eb5b2;
            color: #ffffff;
            padding: 30px 0;
            text-align: center;
        }

        nav {
            background-color: rgb(24, 21, 21);
            color: #fff;
            padding: 14px 0;
            text-align: center;
            height: auto;
        }

        nav a {
            color: #fff;
            text-decoration: none;
            margin: 10px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1{
            color: black;
            text-transform: uppercase;
            text-align: center;
        }
        h2 {
            color: #4eb5b2;
            text-transform: uppercase;
            text-align: center;
        }

        h1 {
            font-size: 36px;
            margin-top: 10px;
        }

        h2 {
            font-size: 24px;
            margin-bottom: 20px;
        }

        p {
            color: #000000;
            line-height: 1.6;
            font-size: 18px;
            text-align: justify;
            margin-bottom: 20px;
        }

        .hospital-image {
            width: 100%;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .content-image {
            width: 100%;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        th {
            background-color: #4eb5b2;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
    </style>

</head>

<body>
    <header>
        <h1>Welcome to MSG Hospital</h1>
    </header>

    <nav>
        <a href="http://localhost/DBMS_Project/home.html">Home</a>
        <a href="http://localhost/DBMS_Project/2about.html">About</a>
        <a href="http://localhost/DBMS_Project/3contact.html">Contact Information</a>
        <a href="http://localhost/DBMS_Project/6expert.php">Our Experts</a>
        <a href="http://localhost/DBMS_Project/5specialization.php">Specializations</a>
        <a href="http://localhost/DBMS_Project/4login.html">Login</a>
    </nav>
    

    <div class="container">
        <h2>Experts in Various Department</h2>
        <?php
        // Establish connection to MySQL database
        $servername = "localhost";
        $username = "root";
        $password = ""; // Assuming no password set
        $database = "hospital"; // Replace with your actual database name
        $conn = new mysqli($servername, $username, $password, $database);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // SQL query to fetch experts with experience >= 5 years grouped by specialization
        $sql = "SELECT first_name, middle_name, last_name, Specializations, experience 
                FROM doctor 
                WHERE experience >= 5
                GROUP BY Specializations";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Output data of each row
            echo "<table>";
            echo "<tr><th>First Name</th><th>Middle Name</th><th>Last Name</th><th>Specializations</th><th>Experience</th></tr>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["first_name"] . "</td>";
                echo "<td>" . $row["middle_name"] . "</td>";
                echo "<td>" . $row["last_name"] . "</td>";
                echo "<td>" . $row["Specializations"] . "</td>";
                echo "<td>" . $row["experience"] . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "0 results";
        }

        // Close connection
        $conn->close();
        ?>
    </div>
</body>

</html>
