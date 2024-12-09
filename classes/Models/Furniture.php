<?php

namespace App\Models;

class Furniture implements Product
{
    private string $sku;
    private string $name;
    private float $price;
    private float $height;
    private float $width;
    private float $length;
    private string $productType;

    public function __construct(string $sku, string $name, float $price, float $height, float $width, float $length, string $productType = 'Furniture')
    {
        $this->sku = $sku;
        $this->name = $name;
        $this->price = $price;
        $this->height = $height;
        $this->width = $width;
        $this->length = $length;
        $this->productType = $productType;
    }

    public static function createProduct(string $sku, string $name, float $price, array $attributes): Product
    {
        return new self($sku, $name, $price, $attributes['height'], $attributes['width'], $attributes['length']);
    }

    public function getAttributes(): array
    {
        return ['height' => $this->height, 'width' => $this->width, 'length' => $this->length];
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
        return "INSERT INTO products (sku, name, price, height, width, length, type) VALUES (?, ?, ?, ?, ?, ?, ?)";
    }

    // Bind parameters including the type (productType)
    public function bindInsertParams(\mysqli_stmt $stmt): void
    {
        $stmt->bind_param("ssdddds", $this->sku, $this->name, $this->price, $this->height, $this->width, $this->length, $this->productType);
    }
}
