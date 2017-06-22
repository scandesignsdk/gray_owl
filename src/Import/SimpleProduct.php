<?php 
namespace SDM\Import;

Class SimpleProduct Implements SimpleProductInterface {

	private $sku;
	private $title;
	private $attributes;
	private $isVisible;
	private $price;
    private $stock;

    public function getSku(): string
    {
        return $this->sku;
    }

    public function setSku(string $sku)
    {
         $this->sku = $sku;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }

    public function setAttributes($attributes)
    {
        $this->attributes = $attributes;
    }

    public function isVisible(): bool
    {
        return ($this->isVisible) ? TRUE : FALSE;
    }

    public function setVisible(bool $visibility)
    {
        $this->isVisible = $visibility;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice(float $price)
    {
        $this->price = $price;
    }

    public function getStock(): int 
    {
        return $this->stock;
    }

    public function setStock(int $stock)
    {
        $this->stock = $stock;
    }

    public function isInStock(): bool
    {
    	$isInStock = ($this->stock > 0) ? TRUE : FALSE;
        return $isInStock; 
    }
}