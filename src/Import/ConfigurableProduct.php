<?php

namespace SDM\Import;

class ConfigurableProduct extends Product implements ConfigurableProductInterface {

    private $simpleProducts;

    public function __construct() {
        $this->simpleProducts = [];
    }

    public function addSimpleProduct(SimpleProductInterface $product): void {
        $this->simpleProducts[] = $product;
    }

    public function getSimpleProducts(): array {
        return $this->simpleProducts;
    }

    public function getAttributes(): array {
        return $this->attributes;
    }

    public function isInStock(): bool {
        return $this->isInStock;
    }

}
