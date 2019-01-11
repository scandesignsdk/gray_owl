<?php

namespace SDM\Import;

interface SimpleProductInterface extends ProductInterface
{
    /**
     * Get the simple product stock.
     */
    public function getStock(): int;
}
