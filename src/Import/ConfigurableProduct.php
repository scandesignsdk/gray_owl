<?php
namespace SDM\Import;

/**
 * Class ConfigurableProduct
 * @package SDM\Import
 */
class ConfigurableProduct implements ConfigurableProductInterface {

  /**
   * @var string
   */
  private $sku = '';
  /**
   * @var string
   */
  private $title = '';

  /**
   * @var float
   */
  private $price = 0.00;

  /**
   * @var int
   */
  private $stock = 0;

  /**
   * @var bool
   */
  private $visible = TRUE;

  /**
   * @var array
   */
  private $products = [];

  /**
   * @var array
   */
  private $attributes = [];

  /**
   * Get the simple product SKU
   *
   * @return string
   */
  public function getSku(): string{

    return $this->sku;
  }
  /**
   * Get the simple product title
   *
   * @return string
   */
  public function getTitle(): string{

    return $this->title;
  }

  /**
   * Get the lowest price from the simple products
   *
   * @return float
   */
  public function getPrice(): float{
    $lowestPrice = [];
    foreach ($this->products as $product){
      $lowestPrice[] = $product->getPrice();
    }

    return min($lowestPrice);
  }

  /**
   * Set the simple product price
   * @param $price
   */
  public function setPrice($price) {
    $this->price = $price;
  }

  /**
   * Set the simple product sku
   *
   * @param $sku
   */
  public function setSku($sku){
    $this->sku = $sku;
  }

  /**
   * Set the simple product title
   *
   * @param $title
   */
  public function setTitle($title){
    $this->title = $title;
  }

  /**
   * If this product is in stock
   *
   * @return bool
   */
  public function isInStock(): bool{
    return ($this->stock > 0) ? true : false;
  }

  /**
   * Set the simple product stock
   *
   * @param $stock
   */
  public function setStock($stock){
    $this->stock = $stock;
  }

  /**
   * Is the simple product visible
   *
   * @return bool
   */
  public function isVisible(): bool{

    return $this->visible;
  }

  /**
   * Add simple product to the configurable product
   *
   * @param SimpleProductInterface $product
   * @return void
   */
  public function addSimpleProduct(SimpleProductInterface $product): void {
    $this->products[] = $product;
  }

  /**
   * Get the simple products for this configurable product
   *
   * @return SimpleProductInterface[]
   */
  public function getSimpleProducts(): array {
    return $this->products;
  }

  /**
   * Get the attributes which are configured for this configurable product
   *
   * @return array
   */
  public function getAttributes(): array {
    return $this->attributes;
  }

  /**
   * Set the simple product attributes
   * @param array $attributes
   */
  public function setAttributes(array $attributes) {
    $this->attributes = $attributes;
  }

}

