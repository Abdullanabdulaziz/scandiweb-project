<?php
header('Content-Type: application/json');
session_start();
require_once 'vendor/autoload.php';

use App\Database;
use App\ProductFactory;
use App\ProductRepository;

try {
    // Check if request content type is JSON
    if (strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) {
        // Decode JSON data if sent as application/json
        $data = json_decode(file_get_contents('php://input'), true);
        $sku = htmlspecialchars(trim($data['sku'] ?? ''));
        $name = htmlspecialchars(trim($data['name'] ?? ''));
        $price = filter_var($data['price'] ?? 0, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $productType = htmlspecialchars(trim($data['productType'] ?? ''));
        $attributes = $data;
    } else {
        // Otherwise, assume form data was submitted
        $sku = htmlspecialchars(trim($_POST['sku'] ?? ''));
        $name = htmlspecialchars(trim($_POST['name'] ?? ''));
        $price = filter_var($_POST['price'] ?? 0, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $productType = htmlspecialchars(trim($_POST['productType'] ?? ''));
        $attributes = $_POST;
    }

    // Check if all required fields are present
    if (!$sku || !$name || !$price || !$productType) {
        echo json_encode(['success' => false, 'message' => "Please submit all required data (SKU, Name, Price, and Product Type)."]);
        exit();
    }

    // Initialize database and repository
    $db = new Database();
    $repository = new ProductRepository($db);

    // Check if SKU is unique
    if ($repository->skuExists($sku)) {
        echo json_encode(['success' => false, 'message' => "SKU must be unique."]);
        exit();
    }

    // Create and save the product
    $product = ProductFactory::createProduct($productType, $sku, $name, $price, $attributes);
    $repository->saveProduct($product);

    // Return success response
    echo json_encode(['success' => true, 'message' => "Product saved successfully."]);
    exit();

} catch (Exception $e) {
    // Handle unexpected errors
    echo json_encode(['success' => false, 'message' => "An error occurred: " . $e->getMessage()]);
    exit();
}
