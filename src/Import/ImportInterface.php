<?php

namespace SDM\Import;

interface ImportInterface
{

    /**
     * Parse the csv file
     *
     * @return void
     */
    public function parse(): void;

    /**
     * Get products imported
     *
     * @return ProductInterface[]
     */
    public function getProducts(): array;

    /**
     * Add product to the importer
     *
     * @param $product
     * @return Import
     */
    public function addProduct($product): void;

}
