<?php

require_once 'classes/Database.php';        // Database class
require_once 'classes/ProductDeleter.php';  // ProductDeleter class
require_once 'classes/ProductRepository.php'; // ProductRepository class

use App\Database;  // Correct namespace for Database
use App\ProductDeleter;
use App\ProductRepository;

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method.');
    }

    $data = json_decode(file_get_contents("php://input"), true);
    if (!$data || !isset($data['ids']) || !is_array($data['ids'])) {
        throw new Exception('Missing or invalid product IDs.');
    }

    // Get the list of product IDs (SKUs)
    $ids = $data['ids'];

    // Create a Database connection
    $db = new Database();

    // Create a ProductDeleter object
    $deleter = new ProductDeleter($db->getConnection());

    // Delete the products
    $result = $deleter->deleteProducts($ids);

    // Return the result as JSON
    echo json_encode($result);
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
