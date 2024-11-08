<?php

abstract class Product {
    protected $sku;
    protected $name;
    protected $price;

    public function __construct($sku, $name, $price) {
        $this->sku = $sku;
        $this->name = $name;
        $this->price = $price;
    }

    public function getSku() {
        return $this->sku;
    }

    public function getName() {
        return $this->name;
    }

    public function getPrice() {
        return $this->price;
    }

    // Abstract method to get the product-specific attribute (overridden by subclasses)
    abstract public function getSpecificAttribute();

    // Abstract method to save product data (overridden by subclasses)
    abstract public function save($conn);
}

