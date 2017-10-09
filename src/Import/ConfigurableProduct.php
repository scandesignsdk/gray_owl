<?php
namespace SDM\Import;

class ConfigurableProduct implements ConfigurableProductInterface{
    
    
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
     * @var bool
     */
    private $inStock;
    
    /**
     * @var float
     */
    private $price;
    
    /**
     * @var bool
     */
    private $visible;
    
    /**
     * @var SimpleProductInterface
     */
    private $product;
    
    /**
     * @param string $sku
     * @param string $title
     * @param null|array $attributes
     * @param bool $inStock
     * @param float $price
     * @param bool $visible
     */    
    public function __construct($sku, $title, $attributes, $inStock, $price, $visible){
        
        $this->sku          = $sku;
        $this->title        = $title;
        $this->attributes   = $attributes;
        $this->inStock      = $inStock;
        $this->price        = $price;
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
    /**
    public function getAttributes(): ?array{
        
        return $this->attributes;
    }
**/
    /**
     * If this product is in stock
     *
     * @return bool
     */
    public function isInStock(): bool{
        
        return $this->inStock;
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
    
    /**
     * Add simple product to the configurable product
     *
     * @param SimpleProductInterface $product
     * @return void
     */
    public function addSimpleProduct(SimpleProductInterface $product): void{
        
        
        $this->product[] = $product;
    }

    /**
     * Get the simple products for this configurable product
     *
     * @return SimpleProductInterface[]
     */
    public function getSimpleProducts(): array{
        
        return $this->product;
    }

    /**
     * Get the attributes which are configured for this configurable product
     *
     * @return array
     */
    public function getAttributes(): ?array{
        
        return $this->attributes;
    }
}