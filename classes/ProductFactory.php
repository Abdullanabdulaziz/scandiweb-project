<?php

namespace App;

use App\Models\Product;

class ProductFactory
{
    public static function createProduct(string $sku, string $name, float $price, string $productType, array $attributes): Product
    {
        // Dynamically construct the full class name based on the product type
        $productClass = 'App\\Models\\' . ucfirst($productType);  // Capitalize the product type

        // Check if the class exists and create the product dynamically
        if (class_exists($productClass)) {
            return $productClass::createProduct($sku, $name, $price, $attributes);
        } else {
            // Error if class doesn't exist
            throw new \Exception("Class does not exist: " . $productClass);
        }
    }
}
