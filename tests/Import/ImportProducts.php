<?php

namespace SDMTests\Import;

use SDM\Import\ConfigurableProductInterface;
use SDM\Import\ProductInterface;
use SDM\Import\SimpleProductInterface;

class ImportProducts
{
    /**
     * @var ProductInterface[]
     */
    public array $products = [];

    /**
     * @var ConfigurableProductInterface[]
     */
    public array $configurables = [];

    /**
     * @var SimpleProductInterface[]
     */
    public array $simples = [];

    /**
     * @var ProductInterface[]
     */
    public array $visibles = [];

    /**
     * @var ProductInterface[]
     */
    public array $nonvisibles = [];
}
