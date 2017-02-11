<?php
/**
 * Created by PhpStorm.
 * User: eric
 * Date: 2/11/17
 * Time: 11:23 AM
 */

namespace SDM\Import;


class SimpleProduct implements SimpleProductInterface
{

    private $sku, $title, $attributes, $price, $stock;
    public $visible;

    public function __construct($sku, $title, $attributesStr, $stock, $price)
    {
        $this->sku = $sku;
        $this->title = $title;

        if (empty($attributesStr)) {
            $this->attributes = null;
        } else {
            $attrStrs = explode(';', $attributesStr);
            foreach ($attrStrs as $attrStr) {
                $stringsArray = explode(':', $attrStr);
                $this->attributes[] = reset($stringsArray);
            }
        }

        $this->stock = $stock;
        $this->price = $price;
        $this->visible = true;
    }


    /**
     * @return string
     */
    public function getSku():string
    {
        return $this->sku;
    }

    /**
     * @return string
     */
    public function getTitle():string
    {
        return $this->title;
    }

    /**
     * @return array
     */
    public function getAttributes():?array
    {
        return $this->attributes;
    }

    /**
     * @return bool
     */
    public function isVisible():bool
    {
        return $this->visible;
    }

    /**
     * @return float
     */
    public function getPrice():float
    {
        return $this->price;
    }

    /**
     * @return bool
     */
    public function isInStock():bool
    {
        if ($this->stock > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return int
     */
    public function getStock():int
    {
        if (isset($this->stock)) {
            return $this->stock;
        } else {
            return 0;
        }
    }


}
