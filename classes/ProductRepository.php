<?php

namespace App;

use App\Models\Product;
use mysqli;

class ProductRepository
{
    private $connection;

    public function __construct(mysqli $connection)
    {
        $this->connection = $connection;
    }

    // Method to check if the SKU is unique
    public function isSkuUnique(string $sku): bool
    {
        $sql = "SELECT COUNT(*) FROM products WHERE sku = ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("s", $sku);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        return $count === 0;
    }

    // Save product without conditionals
    public function saveProduct(Product $product)
    {
        // Get the SQL query dynamically from the product
        $sql = $product->getInsertQuery();  // Each product knows how to build its own query

        $stmt = $this->connection->prepare($sql);  // Prepare the SQL query

        // Bind the product's attributes to the query
        $product->bindInsertParams($stmt);
        
        if ($stmt->execute()) {
            return true;  // Product saved successfully
        } else {
            throw new \Exception("Error saving product: " . $stmt->error);
        }
    }
}
