<?php 

namespace SDM\Import;

Class ConfigurableProduct Implements ConfigurableProductInterface {

	private $sku;
	private $title;
	private $attributes;
	private $isVisible;
	private $simple_products;

	function __construct(string $configurable_product_sku) {
		$this->sku = $configurable_product_sku;
		$this->isVisible = TRUE;
		$this->title = ucfirst($this->sku);
		$this->attributes = array();
	}

    /**
     * Get the simple product SKU
     *
     * @return string
     */
    public function getSku(): string
    {
    	return $this->sku;
    }

    /**
     * Get the simple product title
     *
     * @return string
     */
    public function getTitle(): string
    {

    	return $this->title;
    }

    /**
     * Get the attributes which are configured for this configurable product
     *
     * @return array
     */
    public function getAttributes(): array {

    	$conf_attributes = array();

    	foreach ($this->simple_products as $simple_product) {
    		foreach ($simple_product->getAttributes() as $simple_product_attr) {
    			if (!in_array($simple_product_attr[0], $conf_attributes))
    				$conf_attributes[] = $simple_product_attr[0];			
    		}
    	}

    	return (count($conf_attributes) > 0 ? $conf_attributes : NULL);
    }

     /**
     * Get the simple product price
     *
     * @return float
     */
    public function getPrice(): float
    {
    	$lowest_price = NULL;
        foreach ($this->simple_products as $simple_product) {
        	if (is_null($lowest_price) || $simple_product->getPrice() < $lowest_price )
        		$lowest_price = $simple_product->getPrice();
        }

        return $lowest_price;
    }

    /**
     * If this product is in stock
     *
     * @return bool
     */
    public function isInStock(): bool
    {
    	foreach ($this->simple_products as $simple_product) {
    		if ($simple_product->isInStock()) {
    			return TRUE;
    		}
    	}

    	return FALSE;
    }

    /**
     * Is the simple product visible
     *
     * @return bool
     */
    public function isVisible(): bool
    {
    	return $this->isVisible;
    }

    /**
     * Add simple product to the configurable product
     *
     * @param SimpleProductInterface $product
     * @return void
     */
    public function addSimpleProduct(SimpleProductInterface $product) 
    {
    	$this->simple_products[] = $product;
    }

    /**
     * Get the simple products for this configurable product
     *
     * @return SimpleProductInterface[]
     */
    public function getSimpleProducts(): array 
    {
    	return $this->simple_products;
    }
}