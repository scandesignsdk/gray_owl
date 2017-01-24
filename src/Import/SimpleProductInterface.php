<?php

namespace SDM\Import;

interface SimpleProductInterface extends ProductInterface
{

    /**
     * Get the simple product stock
     *
     * @return int
     */
    public function getStock(): int;

}
