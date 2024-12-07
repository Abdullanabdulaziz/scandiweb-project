<?php

namespace App\Models;

class Book extends Product
{
    private $weight; // Weight in KG

    public function __construct(string $sku, string $name, float $price, ?float $weight = 0)
    {
        parent::__construct($sku, $name, $price);
        // If weight is null, default to 0
        $this->weight = $weight ?? 0;
    }

    public function getWeight(): float
    {
        return $this->weight;
    }

    // Implementing the abstract method from Product
    public function getInsertStatement(\mysqli $db): \mysqli_stmt
    {
        // Correct query with 5 placeholders (no size, height, width, length for Book)
        $query = "INSERT INTO products (sku, name, price, type, weight) VALUES (?, ?, ?, ?, ?)";
        $stmt = $db->prepare($query);
        return $stmt;
    }

    public function getBindTypes(): string
    {
        return 'ssdsd';  // 5 variables are being bound (string, string, double, string, double)
    }

    public function getBindValues(): array
    {
        return [$this->sku, $this->name, $this->price, $this->type, $this->weight];
    }
}
