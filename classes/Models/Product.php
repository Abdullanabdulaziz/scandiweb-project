<?php

namespace App\Models;

abstract class Product
{
    protected $sku;
    protected $name;
    protected $price;
    protected $type;

    public function __construct(string $sku, string $name, float $price)
    {
        $this->sku = $sku;
        $this->name = $name;
        $this->price = $price;
        $this->type = get_class($this);
    }

    abstract public function getInsertStatement(\mysqli $db): \mysqli_stmt;
    abstract public function getBindTypes(): string;
    abstract public function getBindValues(): array;

    public function getSku(): string
    {
        return $this->sku;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getType(): string
    {
        return $this->type;
    }
}
