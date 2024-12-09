<?php

require_once 'vendor/autoload.php'; // Autoloading via Composer
ini_set('display_errors', 1); // Enable error reporting for debugging (make sure to disable in production)
error_reporting(E_ALL);

use App\ProductFactory;
use App\ProductRepository;
use App\Database;

header('Content-Type: application/json');

// Ensure no unexpected output
ob_clean(); // Clean the output buffer to remove any unwanted content

// Get the incoming JSON data
$data = json_decode(file_get_contents('php://input'), true);

// Ensure the required fields are present
$sku = isset($data['sku']) ? $data['sku'] : null;
$name = isset($data['name']) ? $data['name'] : null;
$price = isset($data['price']) ? $data['price'] : null;
$productType = isset($data['productType']) ? $data['productType'] : null;
$attributes = isset($data['attributes']) ? $data['attributes'] : [];

// Validate the required fields
if (!$sku || !$name || !$price || !$productType) {
    echo json_encode(['error' => 'Missing required fields']);
    exit;
}

$responseData = [
    'productTypeReceived' => $productType,
    'sku' => $sku,
    'name' => $name,
    'price' => $price,
    'attributes' => $attributes,
    'success' => false,
];

try {
    // Create the product using the factory
    $product = ProductFactory::createProduct($sku, $name, $price, $productType, $attributes);

    // Initialize the database connection
    $db = new Database();
    $productRepository = new ProductRepository($db->getConnection());

    // Check if the SKU is unique before saving the product
    if (!$productRepository->isSkuUnique($sku)) {
        $responseData['error'] = 'SKU already exists';
        echo json_encode($responseData);
        exit;
    }

    // Save the product using the repository
    $productRepository->saveProduct($product);

    // If the product is saved successfully, update the response
    $responseData['success'] = true;  // Product saved successfully
    echo json_encode($responseData); // Return success message

} catch (\Exception $e) {
    // Handle unexpected errors and return the error message
    $responseData['error'] = 'Error: ' . $e->getMessage();
    echo json_encode($responseData);
}

