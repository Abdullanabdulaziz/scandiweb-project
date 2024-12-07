<?php

namespace App\Models;

class Furniture extends Product
{
    private $height, $width, $length; // Dimensions in CM

    public function __construct(string $sku, string $name, float $price, ?float $height = 0, ?float $width = 0, ?float $length = 0)
    {
        parent::__construct($sku, $name, $price);
        // If height, width, or length is null, default to 0
        $this->height = $height ?? 0;
        $this->width = $width ?? 0;
        $this->length = $length ?? 0;
    }

    public function getHeight(): float
    {
        return $this->height;
    }

    public function getWidth(): float
    {
        return $this->width;
    }

    public function getLength(): float
    {
        return $this->length;
    }

    // Implementing the abstract method from Product
    public function getInsertStatement(\mysqli $db): \mysqli_stmt
    {
        // Correct query with 7 placeholders (height, width, and length included)
        $query = "INSERT INTO products (sku, name, price, type, height, width, length) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $db->prepare($query);
        return $stmt;
    }

    public function getBindTypes(): string
    {
        return 'ssddddd'; // 7 variables are being bound (string, string, double, string, double, double, double)
    }

    public function getBindValues(): array
    {
        return [$this->sku, $this->name, $this->price, $this->type, $this->height, $this->width, $this->length];
    }
}
