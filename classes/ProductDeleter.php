<?php

namespace App;

use App\ProductRepository;
use Exception;
use mysqli;

class ProductDeleter
{
    private $db;
    private $productRepository;

    public function __construct(mysqli $db)
    {
        $this->db = $db;
        $this->productRepository = new ProductRepository($db);
    }

    public function deleteProducts(array $ids): array
    {
        if (empty($ids)) {
            throw new Exception('No product IDs provided.');
        }

        $stmt = $this->db->prepare("DELETE FROM products WHERE sku IN (" . implode(',', array_fill(0, count($ids), '?')) . ")");
        $stmt->bind_param(str_repeat('s', count($ids)), ...$ids);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            return ['success' => true, 'message' => 'Products deleted successfully.'];
        }

        return ['success' => false, 'message' => 'No matching products found.'];
    }
}
