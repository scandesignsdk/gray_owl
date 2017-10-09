<?php
namespace SDM\Import;

interface ProductInterface
{

    /**
     * Get the simple product SKU
     *
     * @return string
     */
    public function getSku(): string;

    /**
     * Get the simple product title
     *
     * @return string
     */
    public function getTitle(): string;

    /**
     * Get the simple products attributes
     *
     * @return null|array
     */
    public function getAttributes(): ?array;
    
    /**
     * If this product is in stock
     *
     * @return bool
     */
    public function isInStock(): bool;
    
    /**
     * Get the simple product price
     *
     * @return float
     */
    public function getPrice(): float;
    
    /**
     * Is the simple product visible
     *
     * @return bool
     */
    public function isVisible(): bool;
    
    /**
     * Set the visibility of the simple product
     *
     * @return bool
     */
    public function setIsVisible($visible);
}
