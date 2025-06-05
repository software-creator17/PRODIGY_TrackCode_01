<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "hospital";
$con=mysqli_connect($servername, $username, $password, $database);
//$con=mysqli_connect("localhost","root","hospital"); <-- this is alternate method we can use to eatablish connection without variables

if(!$con) // if connection not established
{
    die("error detected". mysqli_error($con));
}
else
{
    echo"connection establish successful";
}
?>