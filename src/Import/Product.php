<?php
namespace SDM\Import;

class Product implements SimpleProductInterface
{

	private $SKU = '';
	private $title = '';
	private $attributes = [];
	private $stock = 0;
	private $price = 0;
	private $visible = false;

	public function __construct ($sku, $title, $attributes, $stock, $price)
	{
		$this->SKU = $sku;
		$this->title = $title;
		$this->stock = $stock;
		$this->price = $price;
		$this->setAttributes($attributes);
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
     * Get the simple products attributes
     *
     * @return null|array
     */
    public function getAttributes(): array
	{
		return $this->attributes;
	}

    /**
     * Is the simple product visible
     *
     * @return bool
     */
	public function isVisible(): bool
	{
		return $this->visible;
	}

	public function setVisible(bool $visible)
	{
		$this->visible = $visible;
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
		return $this->stock > 0;
	}

	/**
     * Get the simple product stock
     *
     * @return int
     */
	public function getStock(): int
	{
		return $this->stock;
	}

	public function setAttributes($attributes) {
		$attributes = trim($attributes);

		if (!$attributes) {
			return;
		}

		$data = preg_split('/[:|;]/', $attributes);
		for ($i=0; $i<count($data) - 1; $i += 2) {
			$this->attributes[$data[$i]] = $data[$i+1];
		}
	}
}
