<?php

namespace App;

use App\Models\Book;
use App\Models\DVD;
use App\Models\Furniture;

class ProductFactory
{
    public static function createProduct($type, $sku, $name, $price, $attributes)
    {
        switch (strtolower($type)) {
            case 'dvd':
                return new DVD($sku, $name, $price, $attributes['size'] ?? 0);
            case 'book':
                return new Book($sku, $name, $price, $attributes['weight'] ?? 0);
            case 'furniture':
                return new Furniture(
                    $sku,
                    $name,
                    $price,
                    $attributes['height'] ?? 0,
                    $attributes['width'] ?? 0,
                    $attributes['length'] ?? 0
                );
            default:
                throw new \Exception("Invalid product type: " . $type);
        }
    }
}
