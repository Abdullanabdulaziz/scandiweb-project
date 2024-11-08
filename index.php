<?php
// Enable CORS to allow requests from any origin
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

// Handle OPTIONS request for CORS preflight check
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Include the database connection
include 'database.php';

// Query to get all products
$sql = "SELECT * FROM products ORDER BY id";
$result = $conn->query($sql);

// Initialize an array to store the products
$products = [];

if ($result && $result->num_rows > 0) {
    // Fetch products if available
    while ($row = $result->fetch_assoc()) {
        $products[] = [
            'id' => $row['id'],
            'sku' => $row['sku'],
            'name' => $row['name'],
            'price' => $row['price'],
            'type' => $row['type'],
            'size' => $row['size'] ?? null,
            'weight' => $row['weight'] ?? null,
            'height' => $row['height'] ?? null,
            'width' => $row['width'] ?? null,
            'length' => $row['length'] ?? null,
        ];
    }
}

// Close the database connection
$conn->close();

// Output products as JSON
echo json_encode($products);
?>
