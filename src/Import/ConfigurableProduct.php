<?php
/**
 * Created by PhpStorm.
 * User: eric
 * Date: 2/11/17
 * Time: 1:59 PM
 */

namespace SDM\Import;


class ConfigurableProduct implements ConfigurableProductInterface
{
    private $attributes;
    private $configurables;

    /**
     * @return mixed
     */
    public function getSku():string
    {
        if (count($this->configurables) > 0) {
            /**
             * @var SimpleProductInterface $simpleProduct ;
             */
            $simpleProduct = $this->configurables[0];
            $simpleSku = $simpleProduct->getSku();
            $skus = explode('-', $simpleSku);
            $sku = reset($skus);
            return $sku;
        } else {
            return false;
        }


    }

    /**
     * @return string
     */
    public function getTitle():string
    {
        if (count($this->configurables) > 0) {
            /**
             * @var SimpleProductInterface $simpleProduct ;
             */
            $simpleProduct = $this->configurables[0];
            $simpleTitle = $simpleProduct->getTitle();
            return $simpleTitle;

        } else {
            return false;
        }
    }

    /**
     * @return bool
     */
    public function isVisible():bool
    {
        return true;
    }

    /**
     * @return float
     */
    public function getPrice():float
    {
        $lowestPrice = $this->configurables[0]->getPrice();
        foreach ($this->configurables as $simpleProduct) {
            /**
             * @var SimpleProductInterface $simpleProduct
             */
            $simplePrice = $simpleProduct->getPrice();

            if ($lowestPrice > $simplePrice) {
                $lowestPrice = $simplePrice;
            }
        }
        return $lowestPrice;
    }

    /**
     * @return bool
     */
    public function isInStock():bool
    {
        $stock = 0;
        foreach ($this->configurables as $simpleProduct) {
            /**
             * @var SimpleProductInterface $simpleProduct
             */
            $stock += $simpleProduct->getStock();
        }

        if ($stock > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function addSimpleProduct(SimpleProductInterface $product): void
    {
        $this->configurables[] = $product;
    }

    public function getSimpleProducts():array
    {
        return $this->configurables;
    }

    public function getAttributes():array
    {
        $this->attributes = array();
        foreach ($this->configurables as $simpleProduct) {
            /**
             * @var SimpleProductInterface $simpleProduct
             */
            foreach ($simpleProduct->getAttributes() as $attribute) {
                if (!in_array($attribute, $this->attributes)) {
                    $this->attributes[] = $attribute;
                }
            }
        }
        return $this->attributes;
    }

}