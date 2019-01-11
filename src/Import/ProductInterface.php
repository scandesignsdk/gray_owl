<?php

namespace SDM\Import;

interface ProductInterface
{
    /**
     * Get the simple product SKU.
     */
    public function getSku(): string;

    /**
     * Get the simple product title.
     */
    public function getTitle(): string;

    /**
     * Get the simple products attributes.
     */
    public function getAttributes(): ?array;

    /**
     * Is the simple product visible.
     */
    public function isVisible(): bool;

    /**
     * Get the simple product price.
     */
    public function getPrice(): float;

    /**
     * If this product is in stock.
     */
    public function isInStock(): bool;
}
