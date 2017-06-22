<?php 
namespace SDM\Import;

Class ConfigurableProduct Implements ConfigurableProductInterface {

	private $sku;
	private $title;
	private $attributes;
	private $isVisible;
	private $price;
    private $stock;
    private $simpleProducts = [];

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
        return $this->isVisible;
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

    public function addSimpleProduct(SimpleProductInterface $product): void
    {
        $this->simpleProducts[] = $product;
    }

    public function getSimpleProducts(): array
    {
    	return $this->simpleProducts;
    }

}