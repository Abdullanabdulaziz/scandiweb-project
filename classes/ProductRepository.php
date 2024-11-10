<?php

namespace App;

use App\Models\Product;
use mysqli;

class ProductRepository
{
    private mysqli $db;

    public function __construct(Database $database)
    {
        $this->db = $database->getConnection();
    }

    /**
     * Check if an SKU exists in the database.
     *
     * @param string $sku
     * @return bool
     */
    public function skuExists(string $sku): bool
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM products WHERE sku = ?");
        if (!$stmt) {
            throw new \Exception("Failed to prepare statement: " . $this->db->error);
        }
        $stmt->bind_param("s", $sku);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();

        return $count > 0;
    }

    /**
     * Save a product to the database.
     *
     * @param Product $product
     */
    public function saveProduct(Product $product): void
    {
        $query = "INSERT INTO products (sku, name, price, type, ";
        $params = [$product->getSku(), $product->getName(), $product->getPrice()];

        if ($product instanceof Models\Book) {
            $query .= "weight) VALUES (?, ?, ?, 'Book', ?)";
            $params[] = $product->getWeight();
        } elseif ($product instanceof Models\DVD) {
            $query .= "size) VALUES (?, ?, ?, 'DVD', ?)";
            $params[] = $product->getSize();
        } elseif ($product instanceof Models\Furniture) {
            $query .= "height, width, length) VALUES (?, ?, ?, 'Furniture', ?, ?, ?)";
            $params[] = $product->getHeight();
            $params[] = $product->getWidth();
            $params[] = $product->getLength();
        } else {
            throw new \Exception("Unsupported product type.");
        }

        $stmt = $this->db->prepare($query);
        if (!$stmt) {
            throw new \Exception("Failed to prepare statement: " . $this->db->error);
        }

        // Use a variable for binding parameters
        $types = str_repeat('s', count($params) - 1) . (count($params) > 3 ? 'd' : 'i'); 
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $stmt->close();
    }

    /**
     * Get all products from the database.
     *
     * @return array
     */
    public function getAllProducts(): array
    {
        $result = $this->db->query("SELECT * FROM products ORDER BY id");
        if (!$result) {
            throw new \Exception("Error retrieving products: " . $this->db->error);
        }

        $products = [];
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
        $result->free();

        return $products;
    }

    /**
     * Delete products based on an array of IDs.
     *
     * @param array $ids
     */
    public function deleteProducts(array $ids): void
    {
        if (empty($ids)) {
            return;
        }

        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $query = "DELETE FROM products WHERE id IN ($placeholders)";
        
        $stmt = $this->db->prepare($query);
        if (!$stmt) {
            throw new \Exception("Failed to prepare statement: " . $this->db->error);
        }

        $types = str_repeat('i', count($ids)); // Assuming 'id' is an integer
        $stmt->bind_param($types, ...$ids);
        $stmt->execute();
        $stmt->close();
    }
}
