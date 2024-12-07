<?php
header('Content-Type: application/json');
session_start();

// Enable error reporting for debugging purposes
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'vendor/autoload.php';

use App\Database;
use App\ProductFactory;
use App\ProductRepository;

try {
    // Get the raw POST data
    $input = file_get_contents('php://input');
    if (!$input) {
        throw new Exception("No data received.");
    }

    $data = json_decode($input, true);
    if (!$data) {
        throw new Exception("Invalid JSON data received.");
    }

    // Extract common fields
    $sku = htmlspecialchars(trim($data['sku'] ?? ''));
    $name = htmlspecialchars(trim($data['name'] ?? ''));
    $price = filter_var($data['price'] ?? 0, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $productType = htmlspecialchars(trim($data['productType'] ?? ''));

    // Validate required fields
    if (!$sku || !$name || !$price || !$productType) {
        echo json_encode(['success' => false, 'message' => "All fields are required (SKU, Name, Price, Product Type)."]);
        exit();
    }

    // Extract specific attributes
    $attributes = $data['attributes'] ?? [];

    // Initialize database and repository
    $db = new Database();
    $repository = new ProductRepository($db->getConnection());

    // Check if SKU already exists
    if ($repository->skuExists($sku)) {
        echo json_encode(['success' => false, 'message' => "SKU already exists."]);
        exit();
    }

    // Create and save the product
    $product = ProductFactory::createProduct($productType, $sku, $name, $price, $attributes);
    $repository->save($product);

    echo json_encode(['success' => true, 'message' => "Product saved successfully."]);
} catch (Exception $e) {
    // Log the error for debugging
    error_log("Error: " . $e->getMessage());

    // Return the error message as JSON
    echo json_encode(['success' => false, 'message' => "An error occurred: " . $e->getMessage()]);
    exit();
}
