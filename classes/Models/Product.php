<?php

namespace App\Models;

interface Product
{
    // Instance methods for accessing product details
    public function getAttributes(): array;
    public function getSku(): string;
    public function setSku(string $sku): void;
    public function getName(): string;
    public function setName(string $name): void;
    public function getPrice(): float;
    public function setPrice(float $price): void;

    // Methods for handling database insertion
    public function getInsertQuery(): string;
    public function bindInsertParams(\mysqli_stmt $stmt): void;
}
