<?php
namespace SDM\Import;
 /**
 * Class SimpleProduct
 * @package SDM\Import
 */
class SimpleProduct implements SimpleProductInterface {
    
  
  private $sku;
  
  private $title;
   
  private $attributes = array();
  
  private $stock;
  
  private $price;
  
  private $visible = TRUE;
  
  /**
   * GETTERS AND SETTERS
   **/
  
  //SKU
  public function getSku(): string {
    return $this->sku;
  }
  public function setSku($sku) {
      $this->sku = $sku;
  }
  
  
  //title
  public function getTitle(): string {
    return $this->title;
  }
  public function setTitle($title) {
      $this->title = $title;
  }
  
  //attributes
  public function getAttributes(): ? array {
     if (!$this->attributes) {
      $this->attributes = NULL;
    }
     return $this->attributes;
  }
  public function setAttributes(array $attributes) {
      $this->attributes = $attributes;
  }
  
  //price
  public function getPrice(): float {
    return $this->price;
  }
  public function setPrice($price) {
      $this->price = $price;
  }
  
  //stock
  public function getStock(): int {
    return $this->stock;
  }
  public function setStock($stock) {
      $this->stock = $stock;
  }
  
  
  
  // Check if the simple product is in stock
  
  public function isInStock(): bool
  {
      if ($this->stock > 0) {
          return true;
      } else {
          return false;
      }
  }
  
  //visability
  public function isVisible(): bool {
    return $this->visible;
  }
  public function setIsVisible($visible) {
    $this->visible = $visible;
  }
}