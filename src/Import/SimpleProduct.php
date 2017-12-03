<?php
namespace SDM\Import;

class SimpleProduct implements SimpleProductInterface
{
  private $sku;
  private $title;
  private $attributes;
  private $isVisible;
  private $stock;
  private $price;

  public function __construct(string $sku, string $title, ?array $attributes, $stock, $price)
  {
    $this->sku = $sku;
    $this->title = $title;
    $this->attributes = $attributes;
    $this->stock = $stock;
    $this->price = $price;
    $this->isVisible = True;
  }
  public function getSku(): string
  {
    return $this->sku;
  }

  public function getTitle(): string
  {
    return $this->title;
  }

  public function getAttributes(): ?array
  {
    return $this->attributes;
  }

  public function isVisible(): bool
  {
    return $this->isVisible;
  }

  public function setVisible($isVisible)
  {
    $this->isVisible = $isVisible;
  }

  public function isInStock(): bool
  {
    return $this->stock > 0;
  }

  public function getStock(): int
  {
    return $this->stock;
  }

  public function getPrice(): float
  {
    return $this->price;
  }

}
