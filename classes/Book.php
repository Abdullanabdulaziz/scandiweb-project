<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/classes/product.php';

class Book extends Product {
    protected $weight;

    public function __construct($sku, $name, $price, $weight) {
        parent::__construct($sku, $name, $price);
        $this->weight = $weight;
    }

    // Return the book-specific attribute
    public function getSpecificAttribute() {
        return "Weight: " . $this->weight . " KG";
    }

    // Save Book data to the database
    public function save($conn) {
        $stmt = $conn->prepare("INSERT INTO products (sku, name, price, type, weight) VALUES (?, ?, ?, 'Book', ?)");
        if ($stmt === false) {
            throw new Exception("Failed to prepare statement: " . $conn->error);
        }
        $stmt->bind_param("ssdi", $this->sku, $this->name, $this->price, $this->weight);
        $stmt->execute();
        $stmt->close();
    }
}
