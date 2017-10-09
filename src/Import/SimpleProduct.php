<?php
namespace SDM\Import;

class SimpleProduct implements SimpleProductInterface{
    
    
    /**
     * @var string
     */
    private $sku;

    /**
     * @var string
     */
    private $title;
    
    /**
     * @var null|array
     */
    private $attributes;
    
    /**
     * @var int
     */
    private $stock;    

    /**
     * @var float
     */
    private $price;
    
    /**
     * @var bool
     */
    private $inStock;
    
    /**
     * @var bool
     */
    private $visible;
    
    
    /**
     * @param string $sku
     * @param string $title
     * @param null|array $attributes
     * @param int $stock
     * @param float $price
     * @param bool $inStock
     * @param bool $visible
     */    

    public function __construct($sku, $title, $attributes, $stock, $price, $inStock, $visible){
        
        $this->sku          = $sku;
        $this->title        = $title;
        $this->attributes   = $attributes;
        $this->stock        = $stock;
        $this->price        = $price;
        $this->inStock      = $inStock;
        $this->visible      = $visible;
    }
      
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
     * Get the simple products attributes
     *
     * @return null|array
     */
    public function getAttributes(): ?array{
        
        return $this->attributes;
    }
    
    /**
     * Get the simple product stock
     *
     * @return int
     */
    public function getStock(): int{
        
        return $this->stock;
    }
    
    /**
     * Get the simple product price
     *
     * @return float
     */
    public function getPrice(): float{
        
        return $this->price;
    }
    
    /**
     * If this product is in stock
     *
     * @return bool
     */
    public function isInStock(): bool{
        
        return $this->inStock;
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
     * Set the visibility of the simple product
     *
     * @return bool
     */
    public function setIsVisible($visible){
        
        $this->visible = $visible;
    }
}