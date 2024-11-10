<?php

namespace App\Models;

/**
 * Represents a Book product with a weight attribute.
 */
class Book extends Product
{
    /** @var float Weight in kilograms */
    private float $weight;

    /**
     * Book constructor.
     *
     * @param string $sku Unique SKU for the product
     * @param string $name Name of the product
     * @param float $price Price of the product
     * @param float $weight Weight in kilograms
     * @throws \Exception
     */
    public function __construct(string $sku, string $name, float $price, float $weight)
    {
        parent::__construct($sku, $name, $price);
        $this->setWeight($weight);
    }

    /**
     * Get the weight of the book.
     *
     * @return float
     */
    public function getWeight(): float
    {
        return $this->weight;
    }

    /**
     * Set the weight of the book.
     *
     * @param float $weight
     * @throws \Exception if weight is negative
     */
    public function setWeight(float $weight): void
    {
        if ($weight < 0) {
            throw new \Exception("Weight cannot be negative.");
        }
        $this->weight = $weight;
    }

    /**
     * Get a specific attribute representation for display purposes.
     *
     * @return string
     */
    public function getSpecificAttribute(): string
    {
        return "Weight: " . $this->getWeight() . " KG";
    }
}
