<?php

namespace App\Models;

class DVD extends Product
{
    private $size; // Size in MB

    public function __construct(string $sku, string $name, float $price, float $size)
    {
        parent::__construct($sku, $name, $price);
        $this->size = $size;
    }

    public function getSize(): float
    {
        return $this->size;
    }

    public function getInsertStatement(\mysqli $db): \mysqli_stmt
    {
        // Correct query with 5 placeholders
        $query = "INSERT INTO products (sku, name, price, type, size) VALUES (?, ?, ?, ?, ?)";
        $stmt = $db->prepare($query);
        return $stmt;
    }

    public function getBindTypes(): string
    {
        return 'ssdsd';  // 5 variables are being bound (string, string, double, string, double)
    }

    public function getBindValues(): array
    {
        return [$this->sku, $this->name, $this->price, $this->type, $this->size];
    }
}
