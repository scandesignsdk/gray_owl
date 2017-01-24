<?php
namespace SDM\Import;

class Import
{

    /**
     * All products imported
     *
     * @var ProductInterface[]
     */
    private $products = [];

    /**
     * @var string
     */
    private $filePath;

    /**
     * @var string
     */
    private $delimiter;

    /**
     * Importer
     *
     * @param string $filePath The path to the csv file
     * @param string $delimiter CSV delimter
     */
    public function __construct(string $filePath, $delimiter = ',')
    {
        $this->filePath = $filePath;
        $this->delimiter = $delimiter;
    }

    /**
     * Parse the csv file
     *
     * @return void
     */
    public function parse(): void
    {

    }

    /**
     * Get products imported
     *
     * @return ProductInterface[]
     */
    public function getProducts(): array
    {
        return $this->products;
    }

    /**
     * Add product to the importer
     *
     * @param ProductInterface $product
     * @return Import
     */
    public function addProduct(ProductInterface $product): Import
    {
        $this->products[] = $product;
        return $this;
    }

}
