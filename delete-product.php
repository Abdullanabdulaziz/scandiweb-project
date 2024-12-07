<?php
require_once 'vendor/autoload.php';

use App\Database;
use App\ProductRepository;

header('Access-Control-Allow-Origin: *'); // Replace * with your domain in production
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

try {
    // Get the raw POST data
    $input = json_decode(file_get_contents('php://input'), true);

    // Check if 'ids' parameter is present and is an array
    if (!isset($input['ids']) || !is_array($input['ids'])) {
        throw new Exception('Invalid input. Expected an array of product IDs.');
    }

    // Extract product IDs (sku or id)
    $ids = $input['ids'];

    // Check for empty array
    if (empty($ids)) {
        throw new Exception('No product IDs provided for deletion.');
    }

    // Initialize Database connection
    $db = new Database();
    $productRepository = new ProductRepository($db->getConnection());

    // Prepare the DELETE SQL query for the selected products
    $stmt = $db->getConnection()->prepare(
        'DELETE FROM products WHERE sku IN (' . implode(',', array_fill(0, count($ids), '?')) . ')'
    );

    // Dynamically bind the product IDs (sku) as parameters to the query
    $stmt->bind_param(str_repeat('s', count($ids)), ...$ids);

    // Execute the DELETE query
    $stmt->execute();

    // Handle response based on affected rows
    if ($stmt->affected_rows > 0) {
        echo json_encode(['success' => true, 'message' => 'Products deleted successfully.']);
    } elseif ($stmt->affected_rows === 0) {
        echo json_encode(['success' => false, 'message' => 'No matching products found for the provided IDs.']);
    } else {
        throw new Exception('Failed to execute the delete query.');
    }

    $stmt->close();
} catch (Exception $e) {
    // If there's an exception, return error message
    http_response_code(400);  // Bad request
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
