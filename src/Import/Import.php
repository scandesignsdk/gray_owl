<?php
namespace SDM\Import;

class Import implements ImportInterface {

    private $filePath;
    private $delimiter;
    private $products;

    /**
     * Importer
     *
     * @param string $filePath The path to the csv file
     * @param string $delimiter CSV delimter
     */
    public function __construct(string $filePath, string $delimiter = ',') 
    {
        $this->filePath = $filePath;
        $this->delimiter = $delimiter;
        $this->products = [];
    }

    public function addProduct(ProductInterface $product): Import {
        $this->products[] = $product;
        return $this;
    }

    public function getProducts(): array {
        return $this->products;
    }

    public function parse(): void {
        $productsData = array_slice(array_map('str_getcsv', file($this->filePath)), 1);
        $classifiedProductsData = $this->classifyProductsDataByType($productsData);
        $configurableProductsData = $classifiedProductsData['configurable_products'];
        $simpleProductsData = $classifiedProductsData['simple_products'];

        $this->instantiateConfigurableProducts($configurableProductsData);
        $this->instantiateSimpleProducts($simpleProductsData);
    }

    private function classifyProductsDataByType($productsData): array {
        $configurableProducts = [];
        $simpleProducts = [];
        foreach ($productsData as $product) {
            if (count($product) == 5) {
                $pos = strpos($product[0], '-');
                if ($pos !== false) {
                    $configuralbeProductSKU = substr($product[0], 0, $pos);
                    $configurableProducts[$configuralbeProductSKU][] = $product;
                } else {
                    $simpleProducts[] = $product;
                }
            } else {
                echo "Parser error<br>\n";
            }
        }
        return ['configurable_products' => $configurableProducts, 'simple_products' => $simpleProducts];
    }

    private function instantiateConfigurableProducts(array $configurableProducts): void {
        foreach ($configurableProducts as $sku => $simpleProducts) {
            $cp = new ConfigurableProduct();
            $cp->setSku($sku);
            $cp->setTitle($simpleProducts[0][1]);
            $cp->setPrice($this->getLowestPrice($simpleProducts));
            $cp->setIsInStock($this->_isInStock($simpleProducts));
            $cp->setIsVisible(true);
            $cp->setAttributes($this->_getAttributes($simpleProducts));

            foreach ($simpleProducts as $simpleProduct) {
                $sp = new SimpleProduct();
                $sp->setSku($simpleProduct[0]);
                $sp->setTitle($simpleProduct[1]);
                $sp->setStock($simpleProduct[3]);
                $sp->setPrice($simpleProduct[4]);
                $sp->setIsVisible(false);
                $sp->setAttributes($this->_getAttributes($simpleProducts));
                $cp->addSimpleProduct($sp);
                $this->products[] = $sp;
            }
            $this->products[] = $cp;
        }
    }

    /**
     * Instantiate simple product that is not in configurable products.
     * 
     * @param   array    $simpleProductsData
     * @return  void
     */
    private function instantiateSimpleProducts(array $simpleProductsData): void {
        foreach ($simpleProductsData as $simpleProductData) {
            $sp = new SimpleProduct();
            $sp->setSku($simpleProductData[0]);
            $sp->setTitle($simpleProductData[1]);
            $sp->setStock($simpleProductData[3]);
            $sp->setPrice($simpleProductData[4]);
            $sp->setIsVisible(true);
            $this->products[] = $sp;
        }
    }

    private function getLowestPrice($simpleProducts) {
        $lowestPrice = PHP_INT_MAX;
        foreach ($simpleProducts as $simpleProduct) {
            if ($simpleProduct[4] < $lowestPrice) {
                $lowestPrice = $simpleProduct[4];
            }
        }
        return $lowestPrice;
    }

    private function _isInStock($simpleProducts): bool {
        $sum = 0;
        foreach ($simpleProducts as $simpleProduct) {
            $sum += $simpleProduct[3];
        }
        return ($sum > 0);
    }

    private function _getAttributes($simpleProducts): array {
        $attributes = [];
        $attributeNames = [];
        foreach ($simpleProducts as $simpleProduct) {
            $attributes = explode(';', $simpleProduct[2]);
            break;
        }
        foreach ($attributes as $attribute) {
            $pos = strpos($attribute, ':');
            if ($pos !== false) {
                $attributeNames[] = substr($attribute, 0, $pos);
            } else {
                echo "Parser error<br>\n";
            }
        }
        return $attributeNames;
    }

}
