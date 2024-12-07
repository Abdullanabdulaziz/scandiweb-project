<?php

namespace App;

use App\Models\DVD;  // Importing specific product types
use App\Models\Book;
use App\Models\Furniture;
use App\Models\Product;  // Import the Product base class

class ProductFactory
{
    public static function createProduct(string $productType, string $sku, string $name, float $price, array $attributes): Product
    {
        // Convert product type to lowercase to ensure case insensitivity
        $productClass = self::getProductClass(strtolower($productType));
        
        return new $productClass($sku, $name, $price, ...array_values($attributes));
    }

    private static function getProductClass(string $productType): string
    {
        // Map product types to their respective classes without conditionals
        $productClasses = [
            'dvd' => DVD::class,
            'book' => Book::class,
            'furniture' => Furniture::class,
        ];

        if (!isset($productClasses[$productType])) {
            throw new \Exception("Invalid product type: $productType");
        }

        return $productClasses[$productType];
    }
}
