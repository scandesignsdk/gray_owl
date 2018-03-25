<?php
namespace SDM\Import;

/**
 * Class SimpleProduct
 * @package SDM\Import
 */
class SimpleProduct implements SimpleProductInterface {

  /**
   * @var string;
   */
  private $sku = '';

  /**
   * @var string
   */
  private $title = '';

  /**
   * @var array
   */
  private $attributes = [];

  /**
   * @var bool
   */
  private $stock = 0;

  /**
   * @var float
   */
  private $price = 0.00;

  /**
   * @var bool
   */
  private $visible = TRUE;

  /**
   * Get the simple product SKU
   *
   * @return string
   */
  public function getSku(): string {
    return $this->sku;
  }

  /**
   * Get the simple product title
   *
   * @return string
   */
  public function getTitle(): string {
    return $this->title;
  }

  /**
   * Get the simple products attributes
   *
   * @return null|array
   */
  public function getAttributes(): ? array {

    if (!array_values($this->attributes)) {
      $this->attributes = NULL;
    }

    return $this->attributes;
  }

  /**
   * Get the simple product price
   *
   * @return float
   */
  public function getPrice(): float {
    return $this->price;
  }

  /**
   * Get the simple product stock
   *
   * @return int
   */
  public function getStock(): int {
    return $this->stock;
  }

  /**
   * Set the simple product attributes
   * @param array $attributes
   */
  public function setAttributes(array $attributes) {
    $this->attributes = $attributes;
  }

  /**
   * Set the simple product attributes
   * @param $price
   */
  public function setPrice($price) {
    $this->price = $price;
  }
  /**
   * Set the simple product sku
   * @param $sku
   */
  public function setSku($sku) {
    $this->sku = $sku;
  }

  /**
   * Set the simple product title
   * @param $title
   */
  public function setTitle($title) {
    $this->title = $title;
  }

  /**
   * Set the simple product stock
   *
   * @param $stock
   */
  public function setStock($stock) {
    $this->stock = $stock;
  }

  /**
   * Check if the simple product is in stock
   *
   * @return bool
   */
  public function isInStock(): bool {
    return ($this->stock > 0) ? TRUE : FALSE;
  }

  /**
   * Check if the simple product is visible
   *
   * @return bool
   */
  public function isVisible(): bool {
    return $this->visible;
  }

  /**
   * Set the simple product visible
   *
   * @param $visible
   */
  public function setIsVisible($visible) {
    $this->visible = $visible;
  }
}

