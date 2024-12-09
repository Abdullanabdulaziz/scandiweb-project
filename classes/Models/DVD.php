<?php

namespace App\Models;

class DVD implements Product
{
    private string $sku;
    private string $name;
    private float $price;
    private float $size;
    private string $productType;

    public function __construct(string $sku, string $name, float $price, float $size, string $productType = 'DVD')
    {
        $this->sku = $sku;
        $this->name = $name;
        $this->price = $price;
        $this->size = $size;
        $this->productType = $productType;
    }

    public static function createProduct(string $sku, string $name, float $price, array $attributes): Product
    {
        return new self($sku, $name, $price, $attributes['size']);
    }

    public function getAttributes(): array
    {
        return ['size' => $this->size];
    }

    public function getSku(): string
    {
        return $this->sku;
    }

    public function setSku(string $sku): void
    {
        $this->sku = $sku;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice(float $price): void
    {
        $this->price = $price;
    }

    // Updated query to include 'type' as the productType
    public function getInsertQuery(): string
    {
        return "INSERT INTO products (sku, name, price, size, type) VALUES (?, ?, ?, ?, ?)";
    }

    // Bind parameters including the type (productType)
    public function bindInsertParams(\mysqli_stmt $stmt): void
    {
        $stmt->bind_param("ssdds", $this->sku, $this->name, $this->price, $this->size, $this->productType);
    }
}
