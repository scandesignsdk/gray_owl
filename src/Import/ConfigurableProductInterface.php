<?php
namespace SDM\Import;

interface ConfigurableProductInterface extends ProductInterface
{

    /**
     * Add simple product to the configurable product
     *
     * @param SimpleProduct $product
     * @return void
     */
    public function addSimpleProduct(SimpleProduct $product): void;

    /**
     * Get the simple products for this configurable product
     *
     * @return SimpleProduct[]
     */
    public function getSimpleProducts(): array;

    /**
     * Get the attributes which are configured for this configurable product
     *
     * @return array
     */
    public function getAttributes(): array;

}
