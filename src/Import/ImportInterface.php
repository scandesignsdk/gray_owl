<?php

namespace SDM\Import;

interface ImportInterface
{

    /**
     * Parse the csv file
     *
     * @return void
     */
    public function parse();

    /**
     * Get products imported
     *
     * @return ProductInterface[]
     */
    public function getProducts(): array;

    /**
     * Add product to the importer
     *
     * @param ProductInterface $product
     * @return Import
     */
    public function addProduct(ProductInterface $product): Import;

}
