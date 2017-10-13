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
     * @var bool
     */
    private $visible;
    
    /**
     * @var SimpleProductInterface
     */
    private $products;
    
    /**
     * @param string $sku
     * @param string $title
     * @param bool $visible
     */    
    public function __construct($sku){
        
        $this->sku          = $sku;          //Set SKU
        $this->title        = ucfirst($sku); //Same as SKU but with first uppercase 
        $this->visible      = true;          //Set visible to true
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
     * If this product is in stock
     *
     * @return bool
     */
    public function isInStock(): bool{
        
        //Traverse Simple Products to see if any of them is in stock
        foreach($this->products as $product){
            if($product->isInStock()){
                return true; //Is in stock
            }
        }
        
        return false; //None Simple Products in stock
    }
    
    /**
     * Get the simple product price
     *
     * @return float
     */
    public function getPrice(): float{
        
        //Set lowest price to null
        $lowest = null;
        
        //Traverse the SimpleProducts 
        foreach($this->products as $product){
            
            //If the lowest price is NULL or bigger than the SimpleProduct price
            //set a new lowest price for the ConfigurableProduct
            if(is_null($lowest) || $product->getPrice() < $lowest){
                $lowest = $product->getPrice();
            }
        }
        //Return the lowest price
        return $lowest;
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
        
        $this->products[] = $product;
    }

    /**
     * Get the simple products for this configurable product
     *
     * @return SimpleProductInterface[]
     */
    public function getSimpleProducts(): array{
        
        return $this->products;
    }

    /**
     * Get the attributes which are configured for this configurable product
     *
     * @return array
     */
    public function getAttributes(): ?array{
        
        //New array of attributes
        $configAttribtues = array();
        
        //Traverse each SimpleProduct to get their attributes.
        foreach($this->products as $product){
            foreach($product->getAttributes() as $attribute){
                
                //If not already added as a configured attribute for the
                //ConfigurableProduct, add it to it
                if(!in_array($attribute, $configAttribtues)){
                    $configAttribtues[] = $attribute;
                }
            }
        }
        
        //If attributes found, return them
        if(count($configAttribtues) > 0){
            return $configAttribtues;
        }
        
        //Else return null
        return null;
    }
}