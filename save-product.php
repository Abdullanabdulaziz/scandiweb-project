<?php

header('Content-Type: application/json');
session_start();
require_once 'vendor/autoload.php';

use App\Database;
use App\ProductFactory;
use App\ProductRepository;

try {
    // Handle incoming data
    $data = json_decode(file_get_contents('php://input'), true);
    $sku = htmlspecialchars(trim($data['sku'] ?? ''));
    $name = htmlspecialchars(trim($data['name'] ?? ''));
    $price = filter_var($data['price'] ?? 0, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $productType = htmlspecialchars(trim($data['productType'] ?? ''));
    $attributes = array_filter($data, function ($key) {
        return !in_array($key, ['sku', 'name', 'price', 'productType']);
    }, ARRAY_FILTER_USE_KEY);

    // Validate required fields
    if (!$sku || !$name || !$price || !$productType) {
        echo json_encode(['success' => false, 'message' => "Please submit all required data (SKU, Name, Price, and Product Type)."]);
        exit();
    }

    // Initialize database and repository
    $db = new Database();
    $repository = new ProductRepository($db);

    // Check for unique SKU
    if ($repository->skuExists($sku)) {
        echo json_encode(['success' => false, 'message' => "SKU must be unique."]);
        exit();
    }

    // Create and save the product
    $product = ProductFactory::createProduct($productType, $sku, $name, $price, $attributes);
    $repository->saveProduct($product);

    echo json_encode(['success' => true, 'message' => "Product saved successfully."]);
    exit();

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => "An error occurred: " . $e->getMessage()]);
    exit();
}
