<?php

require_once 'classes/Database.php';        // Database class
require_once 'classes/ProductRepository.php'; // ProductRepository class

use App\Database;  // Correct namespace for Database
use App\ProductRepository;

try {
    // Ensure the request method is GET
    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        throw new Exception('Invalid request method.');
    }

    // Create a Database connection
    $db = new Database();
    $connection = $db->getConnection();
    
    // Check if the connection is valid
    if (!$connection) {
        throw new Exception('Database connection failed.');
    }

    // Prepare SQL query
    $stmt = $connection->prepare("SELECT * FROM products");

    // Check for any query preparation errors
    if ($stmt === false) {
        throw new Exception('MySQL prepare failed: ' . $connection->error);
    }

    // Execute the query
    $stmt->execute();

    // Debugging: Check if the query executes properly
    if ($stmt->error) {
        throw new Exception('Query execution failed: ' . $stmt->error);
    }

    // Fetch the results
    $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    // Debugging: Log the result to make sure we are getting data
    // var_dump($result); exit; // Uncomment this for debugging

    // Check if results are empty
    if (empty($result)) {
        throw new Exception('No products found.');
    }

    // Return the result as JSON
    echo json_encode(['success' => true, 'products' => $result]);

} catch (Exception $e) {
    // Return error message as JSON
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
