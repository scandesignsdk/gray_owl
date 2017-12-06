<?php
namespace SDM\Import;

class ConfiguredProduct implements ConfigurableProductInterface
{
	private $simpleProducts = [];
	private $SKU = '';
	private $title = '';
	private $price = 0;
	private $isVisible = true;
	private $attributes = [];

	public function __construct($sku, $title)
	{
		$this->SKU = $sku;
		$this->title = $title;
	}

	/**
     * Add simple product to the configurable product
     *
     * @param SimpleProductInterface $product
     * @return void
     */
	public function addSimpleProduct(SimpleProductInterface $product)
	{
		$product->setVisible(false);
		$this->simpleProducts[] = $product;

		if ($product->getPrice() > 0 && ($product->getPrice() < $this->getPrice() || $this->getPrice() == 0)) {
			$this->price = $product->getPrice();
		}

		if ($product->isInStock()) {
			$this->isVisible = true;
		}

		$this->attributes = array_unique(array_merge($this->attributes, array_keys($product->getAttributes())));
	}

    /**
     * Get the simple products for this configurable product
     *
     * @return SimpleProductInterface[]
     */
	public function getSimpleProducts(): array
	{
		return $this->simpleProducts;
	}

    /**
     * Get the attributes which are configured for this configurable product
     *
     * @return array
     */
	public function getAttributes() : array
	{
		return $this->attributes;
	}

	/**
     * Get the simple product SKU
     *
     * @return string
     */
    public function getSku(): string
	{
		return $this->SKU;
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
     * Is the simple product visible
     *
     * @return bool
     */
    public function isVisible(): bool
	{
		return $this->isVisible;
	}

    /**
     * Get the simple product price
     *
     * @return float
     */
    public function getPrice(): float
	{
		return $this->price;
	}

    /**
     * If this product is in stock
     *
     * @return bool
     */
	public function isInStock(): bool
	{
		foreach ($this->simpleProducts as $product) {
			if ($product->isInStock()) {
				return true;
			}
		}

		return false;
	}
}
