<?php

namespace SDM\Import;

class Product implements ProductInterface {

    protected $attributes;
    protected $isInStock;
    protected $stock;
    private $price;
    private $sku;
    private $title;
    private $isVisible;

    public function __construct() {
        $this->isVisible = false;
    }

    public function getAttributes(): ?array {
        return $this->attributes;
    }

    public function getPrice(): float {
        return $this->price;
    }

    public function getSku(): string {
        return $this->sku;
    }

    public function getTitle(): string {
        return $this->title;
    }

    public function isInStock(): bool {
        $this->isInStock = ($this->stock > 0);
        return $this->isInStock;
    }

    public function isVisible(): bool {
        return $this->isVisible;
    }

    public function setAttributes(array $attributes) {
        $this->attributes = $attributes;
    }

    public function setPrice($price) {
        $this->price = $price;
    }

    public function setSku($sku) {
        $this->sku = $sku;
    }

    public function setTitle($title) {
        $this->title = $title;
    }

    public function setIsInStock($isInStock) {
        $this->isInStock = $isInStock;
    }

    public function setIsVisible(bool $isVisible) {
        $this->isVisible = $isVisible;
    }

}
