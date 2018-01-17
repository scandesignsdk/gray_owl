<?php

namespace SDM\Import;

class Import implements ImportInterface
{

    /**
     * Importer
     *
     * @param string $filePath The path to the csv file
     * @param string $delimiter CSV delimter
     */
    public function __construct( string $filePath, string $delimiter = ',' )
    {
    }

    /**
     * Parse the csv file
     *
     * @return void
     */
    public function parse(): void
    {
        // TODO: Implement parse() method.
    }

    /**
     * Get products imported
     *
     * @return ProductInterface[]
     */
    public function getProducts(): array
    {
        // TODO: Implement getProducts() method.
    }

    /**
     * Add product to the importer
     *
     * @param ProductInterface $product
     *
     * @return Import
     */
    public function addProduct( ProductInterface $product ): Import
    {
        // TODO: Implement addProduct() method.
    }
}
