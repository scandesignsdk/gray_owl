<?php 
namespace SDM\Import;

Class SimpleProduct Implements SimpleProductInterface {

	private $stock;
	private $sku;
	private $title;
	private $attributes;
	private $isVisible;
	private $price;

	 /**
     * Get the simple product stock
     *
     * @return int
     */
    public function getStock(): int 
    {
        return $this->stock;
    }

     /**
     * Set the simple product stock
     *
     * @param int
     */
    public function setStock(int $stock)
    {
        $this->stock = $stock;
    }

    /**
     * Get the simple product SKU
     *
     * @return string
     */
    public function getSku(): string
    {
    	return $this->sku;
    }

    /**
     * Set the simple product SKU
     *
     * @param string
     */
    public function setSku(string $sku)
    {
         $this->sku = $sku;
    }

    /**
     * Get the simple product title
     *
     * @return string
     */
    public function getTitle(): string
    {
    	return $this->title;
    }

     /**
     * Set the simple product title
     *
     * @param string
     */
    public function SetTitle(string $title)
    {
        $this->title = $title;
    }

    /**
     * Get the simple products attributes
     *
     * @return null|array
     */
    public function getAttributes()
    {
    	return $this->attributes;
    }

    /**
     * Set the simple products attributes
     *
     * @param null|array
     */
    public function SetAttributes($attributes)
    {
        $this->attributes = $attributes;
    }

     /**
     * Get the simple product price
     *
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * Set the simple product price
     *
     * @param float
     */
    public function setPrice(float $price)
    {
        $this->price = $price;
    }

    /**
     * Is the simple product visible
     *
     * @return bool
     */
    public function isVisible(): bool
    {
    	return $this->isVisible;
    }

     /**
     * Set visibility for product
     *
     * @return bool
     */
    public function setVisibility(bool $visibility)
    {
        $this->isVisible = $visibility;
    }

    /**
     * If this product is in stock
     *
     * @return bool
     */
    public function isInStock(): bool
    {
    	if ($this->stock > 0) 
            return TRUE; 
        else 
            return FALSE;
    }
}