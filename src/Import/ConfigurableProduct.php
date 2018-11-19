<?php
namespace SDM\Import;

/**
 * Class ConfigurableProduct
 *
 * @package SDM\Import
 */
class ConfigurableProduct implements ConfigurableProductInterface
{

    private $sku;

    private $title;

    private $attributes = array();

    private $stock;

    private $price;

    private $visible = true;

    private $products = array();

    /**
     * GETTERS AND SETTERS
     **/
    
    public function getSku(): string
    {
        return $this->sku;
    }

    public function setSku($sku)
    {
        $this->sku = $sku;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    // Get price should return the lowest price.
    public function getPrice(): float
    {
        $lowestPrice = array();
        foreach ($this->products as $product) {
            array_push($lowestPrice, $product->getPrice());
        }
        return min($lowestPrice);
    }

    public function setPrice($price)
    {
        $this->price = $price;
    }

    public function isInStock(): bool
    {
        if ($this->stock > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function getStock(): int
    {
        return $this->stock;
    }

    public function setStock($stock)
    {
        $this->stock = $stock;
    }

    public function isVisible(): bool
    {
        return $this->visible;
    }

    // from the interface
    /**
     * Add simple product to the configurable product
     *
     * @param SimpleProductInterface $product
     * @return void
     */
    public function addSimpleProduct(SimpleProductInterface $product): void
    {
        $this->products[] = $product;
    }

    /**
     * Get the simple products for this configurable product
     *
     * @return SimpleProductInterface[]
     */
    public function getSimpleProducts(): array
    {
        return $this->products;
    }

    /**
     * Get the attributes which are configured for this configurable product
     *
     * @return array
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    public function setAttributes($attributes)
    {
        $this->attributes = $attributes;
    }
}