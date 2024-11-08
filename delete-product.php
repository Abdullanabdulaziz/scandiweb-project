<?php
// Enable CORS to allow requests from any origin
header('Access-Control-Allow-Origin: *'); 
header('Access-Control-Allow-Methods: POST, OPTIONS'); 
header('Access-Control-Allow-Headers: Content-Type');

// Handle OPTIONS request for CORS preflight check
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Include the database connection
include 'database.php';

// Decode the JSON data received
$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['ids']) && is_array($data['ids'])) {
    $ids = array_filter($data['ids'], 'is_numeric'); // Ensure all IDs are integers

    if (!empty($ids)) {
        $placeholders = implode(',', array_fill(0, count($ids), '?'));

        $stmt = $conn->prepare("DELETE FROM products WHERE id IN ($placeholders)");
        if ($stmt === false) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Failed to prepare SQL statement.']);
            exit();
        }

        $types = str_repeat('i', count($ids)); // "i" for integer IDs
        $stmt->bind_param($types, ...$ids);

        if ($stmt->execute()) {
            http_response_code(200);
            echo json_encode(['success' => true, 'message' => 'Products deleted successfully']);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Failed to execute SQL query.']);
        }

        $stmt->close();
    } else {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid product IDs.']);
    }
} else {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'No product IDs received.']);
}

// Close the database connection
$conn->close();
?>
