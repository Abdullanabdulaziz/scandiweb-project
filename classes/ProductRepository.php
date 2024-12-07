<?php

namespace App;

use App\Models\Product;

class ProductRepository
{
    private \mysqli $db;

    public function __construct(\mysqli $db)
    {
        $this->db = $db;
    }

    /**
     * Check if the SKU already exists in the database.
     *
     * @param string $sku
     * @return bool
     */
    public function skuExists(string $sku): bool
    {
        $stmt = $this->db->prepare("SELECT 1 FROM products WHERE sku = ?");
        $stmt->bind_param('s', $sku);
        $stmt->execute();
        $stmt->store_result();
        $exists = $stmt->num_rows > 0;
        $stmt->close();
        return $exists;
    }

    /**
     * Save the product into the database.
     *
     * @param Product $product
     */
    public function save(Product $product)
    {
        // Check if SKU exists before saving
        if ($this->skuExists($product->getSku())) {
            echo json_encode(['success' => false, 'message' => "SKU already exists."]);
            exit();
        }

        // Get the insert statement from the polymorphic method of the product
        $stmt = $product->getInsertStatement($this->db);

        // Get the bind types and bind values from the polymorphic methods
        $bindTypes = $product->getBindTypes();
        $bindValues = $product->getBindValues();

        // Ensure that the correct number of bind values is passed
        if (count($bindValues) !== substr_count($bindTypes, 's') + substr_count($bindTypes, 'd')) {
            throw new \Exception("Mismatch between number of bind variables and placeholders.");
        }

        // Bind the parameters (Now using variables)
        $stmt->bind_param($bindTypes, ...$bindValues);

        // Execute and close statement
        $stmt->execute();
        $stmt->close();
    }

    public function getAllProducts()
    {
        $result = $this->db->query("SELECT sku, name, price, type, size, weight, height, width, length FROM products");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function delete(string $sku)
    {
        $stmt = $this->db->prepare("DELETE FROM products WHERE sku = ?");
        $stmt->bind_param('s', $sku);
        $stmt->execute();
        $stmt->close();
    }
}
