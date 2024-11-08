<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/classes/product.php';

class Furniture extends Product {
    protected $height;
    protected $width;
    protected $length;

    public function __construct($sku, $name, $price, $height, $width, $length) {
        parent::__construct($sku, $name, $price);
        $this->height = $height;
        $this->width = $width;
        $this->length = $length;
    }

    // Return the furniture-specific attribute
    public function getSpecificAttribute() {
        return "Dimensions: {$this->height}x{$this->width}x{$this->length} CM";
    }

    // Save Furniture data to the database
    public function save($conn) {
        $stmt = $conn->prepare("INSERT INTO products (sku, name, price, type, height, width, length) VALUES (?, ?, ?, 'Furniture', ?, ?, ?)");
        if ($stmt === false) {
            throw new Exception("Failed to prepare statement: " . $conn->error);
        }
        $stmt->bind_param("ssdiid", $this->sku, $this->name, $this->price, $this->height, $this->width, $this->length);
        $stmt->execute();
        $stmt->close();
    }
}
