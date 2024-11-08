<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/classes/product.php';

class DVD extends Product {
    protected $size;

    public function __construct($sku, $name, $price, $size) {
        parent::__construct($sku, $name, $price);
        $this->size = $size;
    }

    // Return the DVD-specific attribute
    public function getSpecificAttribute() {
        return "Size: " . $this->size . " MB";
    }

    // Save DVD data to the database
    public function save($conn) {
        $stmt = $conn->prepare("INSERT INTO products (sku, name, price, type, size) VALUES (?, ?, ?, 'DVD', ?)");
        if ($stmt === false) {
            throw new Exception("Failed to prepare statement: " . $conn->error);
        }
        $stmt->bind_param("ssdi", $this->sku, $this->name, $this->price, $this->size);
        $stmt->execute();
        $stmt->close();
    }
}
