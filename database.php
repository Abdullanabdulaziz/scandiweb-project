<?php
$servername = "sql210.infinityfree.com";  // MySQL hostname from InfinityFree
$username = "if0_37596667";               // MySQL username from InfinityFree
$password = "JvuVZ8SXgBmP";               // MySQL password from InfinityFree
$dbname = "if0_37596667_product_db";      // MySQL database name from InfinityFree

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
