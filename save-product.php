<?php
file_put_contents('request_log.txt', file_get_contents('php://input') . "\n", FILE_APPEND);

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Enable CORS to allow requests from any origin
header('Access-Control-Allow-Origin: *'); 
header('Access-Control-Allow-Methods: POST, OPTIONS'); 
header('Access-Control-Allow-Headers: Content-Type');

// Handle OPTIONS request for CORS preflight check
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Include database connection
include 'database.php';

// Include the product classes (DVD, Book, Furniture)
require_once $_SERVER['DOCUMENT_ROOT'] . '/classes/DVD.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/classes/Book.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/classes/Furniture.php';

// Check if the request is a JSON request (for API usage) or a regular form POST
$data = json_decode(file_get_contents('php://input'), true);
$isJsonRequest = $data !== null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve POST or JSON data based on request type
    $sku = $isJsonRequest ? htmlspecialchars(trim($data['sku'] ?? '')) : htmlspecialchars(trim($_POST['sku'] ?? ''));
    $name = $isJsonRequest ? htmlspecialchars(trim($data['name'] ?? '')) : htmlspecialchars(trim($_POST['name'] ?? ''));
    $price = $isJsonRequest ? filter_var($data['price'] ?? 0, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION) : filter_var($_POST['price'] ?? 0, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $productType = $isJsonRequest ? htmlspecialchars(trim($data['productType'] ?? '')) : htmlspecialchars(trim($_POST['productType'] ?? ''));

    // Validate required fields
    if (empty($sku) || empty($name) || empty($price) || empty($productType)) {
        http_response_code(400); // Bad request
        echo json_encode(['success' => false, 'message' => 'Please submit all required data (SKU, Name, Price, and Product Type).']);
        exit();
    }

    // Check if SKU is unique
    $stmt = $conn->prepare("SELECT * FROM products WHERE sku = ?");
    if ($stmt === false) {
        http_response_code(500); // Internal Server Error
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $conn->error]);
        exit();
    }

    $stmt->bind_param("s", $sku);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        http_response_code(409); // Conflict
        echo json_encode(['success' => false, 'message' => "SKU must be unique."]);
        exit();
    }
    $stmt->close();

    // Handle product type logic and save product
    try {
        switch ($productType) {
            case 'DVD':
                $size = $isJsonRequest ? filter_var($data['size'] ?? 0, FILTER_SANITIZE_NUMBER_INT) : filter_var($_POST['size'] ?? 0, FILTER_SANITIZE_NUMBER_INT);
                if (empty($size)) {
                    throw new Exception("Please provide the size for the DVD.");
                }
                $product = new DVD($sku, $name, $price, $size);
                break;

            case 'Book':
                $weight = $isJsonRequest ? filter_var($data['weight'] ?? 0, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION) : filter_var($_POST['weight'] ?? 0, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                if (empty($weight)) {
                    throw new Exception("Please provide the weight for the Book.");
                }
                $product = new Book($sku, $name, $price, $weight);
                break;

            case 'Furniture':
                $height = $isJsonRequest ? filter_var($data['height'] ?? 0, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION) : filter_var($_POST['height'] ?? 0, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $width = $isJsonRequest ? filter_var($data['width'] ?? 0, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION) : filter_var($_POST['width'] ?? 0, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $length = $isJsonRequest ? filter_var($data['length'] ?? 0, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION) : filter_var($_POST['length'] ?? 0, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                if (empty($height) || empty($width) || empty($length)) {
                    throw new Exception("Please provide all dimensions for the Furniture (Height, Width, Length).");
                }
                $product = new Furniture($sku, $name, $price, $height, $width, $length);
                break;

            default:
                throw new Exception("Invalid product type.");
        }

        // Save the product to the database
        $product->save($conn);

        http_response_code(201); // Created
        echo json_encode(['success' => true, 'message' => 'Product added successfully']);
    } catch (Exception $e) {
        http_response_code(400); // Bad request
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} else {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['success' => false, 'message' => 'Only POST requests are allowed.']);
}

// Close the database connection
$conn->close();
?>
