<?php

namespace App\Models;

/**
 * Represents a Furniture product with height, width, and length attributes.
 */
class Furniture extends Product
{
    /** @var float Height in CM */
    private float $height;

    /** @var float Width in CM */
    private float $width;

    /** @var float Length in CM */
    private float $length;

    /**
     * Furniture constructor.
     *
     * @param string $sku Unique SKU for the product
     * @param string $name Name of the product
     * @param float $price Price of the product
     * @param float $height Height in CM
     * @param float $width Width in CM
     * @param float $length Length in CM
     * @throws \Exception if any dimension is negative
     */
    public function __construct(string $sku, string $name, float $price, float $height, float $width, float $length)
    {
        parent::__construct($sku, $name, $price);
        $this->setHeight($height);
        $this->setWidth($width);
        $this->setLength($length);
    }

    /**
     * Get the height of the furniture.
     *
     * @return float
     */
    public function getHeight(): float
    {
        return $this->height;
    }

    /**
     * Set the height of the furniture.
     *
     * @param float $height
     * @throws \Exception if height is negative
     */
    public function setHeight(float $height): void
    {
        if ($height < 0) {
            throw new \Exception("Height cannot be negative.");
        }
        $this->height = $height;
    }

    /**
     * Get the width of the furniture.
     *
     * @return float
     */
    public function getWidth(): float
    {
        return $this->width;
    }

    /**
     * Set the width of the furniture.
     *
     * @param float $width
     * @throws \Exception if width is negative
     */
    public function setWidth(float $width): void
    {
        if ($width < 0) {
            throw new \Exception("Width cannot be negative.");
        }
        $this->width = $width;
    }

    /**
     * Get the length of the furniture.
     *
     * @return float
     */
    public function getLength(): float
    {
        return $this->length;
    }

    /**
     * Set the length of the furniture.
     *
     * @param float $length
     * @throws \Exception if length is negative
     */
    public function setLength(float $length): void
    {
        if ($length < 0) {
            throw new \Exception("Length cannot be negative.");
        }
        $this->length = $length;
    }

    /**
     * Get a specific attribute representation for display purposes.
     *
     * @return string
     */
    public function getSpecificAttribute(): string
    {
        return "Dimensions: {$this->getHeight()}x{$this->getWidth()}x{$this->getLength()} CM";
    }
}
