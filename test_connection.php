<?php
$servername = "sql210.infinityfree.com";
$username = "if0_37596667";
$password = "JvuV8ZSXgBmP"; // Make sure this matches your actual password
$dbname = "if0_37596667_product_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";
?>
