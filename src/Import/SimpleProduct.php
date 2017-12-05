<?php
namespace SDM\Import;

class SimpleProduct implements SimpleProductInterface
{
    private $sku = '', $title = '', $attributes, $visible = true, $price = 0, $stock = 0;

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

    public function getAttributes()
	{
        return $this->attributes;
    }

    public function setVisible($visible): void
	{
        $this->visible = (bool)$visible;
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
        $this->stock = (int)$stock;
    }

    public function getStock(): int
	{
        return $this->stock;
    }

    public function isInStock(): bool
	{
        return $this->stock > 0 ? true : false;
    }

}
