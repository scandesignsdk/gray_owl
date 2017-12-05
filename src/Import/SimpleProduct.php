<?php
namespace SDM\Import;

class SimpleProduct implements SimpleProductInterface
{
    private $sku, $title, $attributes, $visible = false, $price, $stock;

    public function setSku($sku) {
        $this->sku = (string)$sku;
    }

    public function getSku(): string {
        return $this->sku;
    }

    public function setTitle($title) {
        $this->title = (string)$title;
    }
    
    public function getTitle(): string {
        return $this->title;
    }

    public function setAttributes(array $attributes) {
        $this->attributes = $attributes;
    }

    public function getAttributes(): array {
        return $this->attributes;
    }

    public function isVisible(): bool {
        return $this->visible;
    }

    public function setPrice($price) {
        $this->price = (float)$price;
    }

    public function getPrice(): float {
        return $this->price;
    }

    public function setStock($stock) {
        $this->stock = (int)$stock;
    }

    public function getStock(): int {
        return $this->stock;
    }

    public function isInStock(): bool {
        return $this->stock > 0 ? true : false;
    }

}
