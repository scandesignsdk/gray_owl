<?php

namespace SDM\Import;

class SimpleProduct extends Product implements SimpleProductInterface {

    public function __construct() {
        
    }

    public function getStock(): int {
        return $this->stock;
    }

    public function setStock($stock) {
        $this->stock = $stock;
    }

}
