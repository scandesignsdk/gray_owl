<?php
namespace SDM\Import;

class ConfigurableProduct implements ConfigurableProductInterface
{

    private $sku = '', $title = '', $attributes = [], $visible = true, $price = 0, $stock = 0, $products = [];
    
    public function setSku($sku): void
	{
        $this->sku = (string)$sku;
    }

    public function getSku(): string
	{
        return $this->sku;
    }

    public function setTitle($title): void
	{
        $this->title = (string)$title;
    }
    
    public function getTitle(): string
	{
        return $this->title;
    }

    public function setAttributes(array $attributes): void
	{
        $this->attributes = $attributes;
    }

    public function getAttributes(): array
	{
        return $this->attributes;
    }

    public function isVisible(): bool
	{
        return $this->visible;
    }

    public function setPrice($price): void
	{
        $this->price = (float)$price;
    }

    public function getPrice(): float
	{
        return $this->price;
    }

    public function setStock($stock): void
	{
        $this->stock += (int)$stock;
    }

    public function getStock(): int
	{
        return $this->stock;
    }

    public function isInStock(): bool
	{
        return $this->stock > 0 ? true : false;
    }
    
    public function addSimpleProduct(SimpleProductInterface $product): void
	{
        $this->products[] = $product;
    }

    public function getSimpleProducts(): array
	{
        return $this->products;
    }

}
