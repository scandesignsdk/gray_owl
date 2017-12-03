<?php
namespace SDM\Import;

class ConfigurableProduct implements ConfigurableProductInterface
{
  private $sku;
  private $title;
  private $isVisible;
  private $simpleProducts = array();

  public function __construct(string $sku, string $title)
  {
    $this->sku = $sku;
    $this->title = $title;
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

  public function isVisible(): bool
  {
    return $this->isVisible;
  }

  public function isInStock(): bool
  {
    foreach ($this->simpleProducts as $product) {
      if ($product->isInStock()) {
        return True;
      }
    }
    return False;
  }

  public function getPrice(): float
  {
    $prices = array();
    foreach ($this->simpleProducts as $product) {
      $prices[] = $product->getPrice();
    }
    return min($prices);
  }

  public function addSimpleProduct(SimpleProductInterface $product): void
  {
    array_push($this->simpleProducts, $product);
  }

  public function getSimpleProducts(): array
  {
    return $this->simpleProducts;
  }

  public function getAttributes(): array
  {
    $attributes = array();
    foreach ($this->simpleProducts as $product) {
      foreach ($product->getAttributes() as $key => $value) {
        $attributes[] = $key;
      }
    }
    return array_unique($attributes);
  }

}
