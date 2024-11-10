<?php

namespace App\Models;

/**
 * Represents a DVD product with a size attribute.
 */
class DVD extends Product
{
    /** @var int Size in MB */
    private int $size;

    /**
     * DVD constructor.
     *
     * @param string $sku Unique SKU for the product
     * @param string $name Name of the product
     * @param float $price Price of the product
     * @param int $size Size in MB
     * @throws \Exception if size is negative
     */
    public function __construct(string $sku, string $name, float $price, int $size)
    {
        parent::__construct($sku, $name, $price);
        $this->setSize($size);
    }

    /**
     * Get the size of the DVD.
     *
     * @return int
     */
    public function getSize(): int
    {
        return $this->size;
    }

    /**
     * Set the size of the DVD.
     *
     * @param int $size
     * @throws \Exception if size is negative
     */
    public function setSize(int $size): void
    {
        if ($size < 0) {
            throw new \Exception("Size cannot be negative.");
        }
        $this->size = $size;
    }

    /**
     * Get a specific attribute representation for display purposes.
     *
     * @return string
     */
    public function getSpecificAttribute(): string
    {
        return "Size: " . $this->getSize() . " MB";
    }
}
