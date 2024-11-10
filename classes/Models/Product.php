<?php

namespace App\Models;

/**
 * Abstract class Product that serves as a base for different product types.
 */
abstract class Product
{
    /** @var string Unique SKU for the product */
    protected string $sku;

    /** @var string Name of the product */
    protected string $name;

    /** @var float Price of the product */
    protected float $price;

    /**
     * Product constructor.
     *
     * @param string $sku Unique identifier for the product
     * @param string $name Name of the product
     * @param float $price Price of the product
     * @throws \Exception if any of the values are invalid
     */
    public function __construct(string $sku, string $name, float $price)
    {
        $this->setSku($sku);
        $this->setName($name);
        $this->setPrice($price);
    }

    /**
     * Get the SKU of the product.
     *
     * @return string
     */
    public function getSku(): string
    {
        return $this->sku;
    }

    /**
     * Set the SKU of the product.
     *
     * @param string $sku
     * @throws \Exception if SKU is empty
     */
    public function setSku(string $sku): void
    {
        if (empty($sku)) {
            throw new \Exception("SKU cannot be empty.");
        }
        $this->sku = $sku;
    }

    /**
     * Get the name of the product.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set the name of the product.
     *
     * @param string $name
     * @throws \Exception if name is empty
     */
    public function setName(string $name): void
    {
        if (empty($name)) {
            throw new \Exception("Name cannot be empty.");
        }
        $this->name = $name;
    }

    /**
     * Get the price of the product.
     *
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * Set the price of the product.
     *
     * @param float $price
     * @throws \Exception if price is negative
     */
    public function setPrice(float $price): void
    {
        if ($price < 0) {
            throw new \Exception("Price cannot be negative.");
        }
        $this->price = $price;
    }

    /**
     * Abstract method to get a product-specific attribute, to be implemented by subclasses.
     *
     * @return string
     */
    abstract public function getSpecificAttribute(): string;
}
