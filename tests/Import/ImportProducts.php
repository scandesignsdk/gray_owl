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
    public $products = [];

    /**
     * @var ConfigurableProductInterface[]
     */
    public $configurables = [];

    /**
     * @var SimpleProductInterface[]
     */
    public $simples = [];

    /**
     * @var ProductInterface[]
     */
    public $visibles = [];

    /**
     * @var ProductInterface[]
     */
    public $nonvisibles = [];
}
