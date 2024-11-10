<?php
require_once 'vendor/autoload.php';

use App\Database;
use App\ProductRepository;

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

try {
    $db = new Database();
    $repository = new ProductRepository($db);
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['ids']) && is_array($data['ids'])) {
        $repository->deleteProducts($data['ids']);
        echo json_encode(['success' => true, 'message' => 'Products deleted successfully']);
    } else {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid product IDs.']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
