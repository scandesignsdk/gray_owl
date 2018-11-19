<?php
namespace SDM\Import;

class Import implements ImportInterface
{

    private $filePath;

    private $delimiter;

    private $products = array();

    /**
     * Importer
     *
     * @param string $filePath
     *            The path to the csv file
     * @param string $delimiter
     *            CSV delimter
     */
    public function __construct(string $filePath, string $delimiter = ',')
    {
        $this->filePath = $filePath;
        $this->delimiter = $delimiter;
    }

    // Parse the csv file
    public function parse(): void
    {
        // Check if the file exists, and if we can read it.
        if (file_exists($this->filePath) && is_readable($this->filePath)) {
            
            $filePointer = fopen($this->filePath, 'r');
            
            // Use this to get the first line of the csv
            $firstLine = null;
            
            // Our array of products
            $aProducts = array();
            // Check if we can find a file pointer resource
            if ($filePointer !== false) {
                // print_r($filePointer);
                while (($input = fgetcsv($filePointer, 0, $this->delimiter)) !== false) {
                    // print_r($input);
                    if ($firstLine == null) {
                        $firstLine = $input;
                    } else {
                        array_push($aProducts, array_combine($firstLine, $input));
                    }
                }
            }
            
            // Create SimpleProduct now
            // print_r($product);
            $this->createSimpleProduct($aProducts);
            // check configurable products and add them to products
            $this->checkConfigurableProducts();
            // close the file
            fclose($filePointer);
        }
    }

    // Create simpleProduct
    private function createSimpleProduct(array $aProducts): void
    {
        // print_r($product);
        if (! empty($aProducts)) {
            foreach ($aProducts as $singleProduct) {
                // print_r($singleProduct);
                $simpleProduct = new SimpleProduct();
                
                // Start by setting the easy ones.
                $simpleProduct->setSku($singleProduct['SKU']);
                $simpleProduct->setTitle($singleProduct['Title']);
                $simpleProduct->setStock($singleProduct['Stock']);
                $simpleProduct->setPrice($singleProduct['Price']);
                
                // Check if the product attributes is not empty
                if (! empty($singleProduct['attributes'])) {
                    
                    // There can be more than one attribute in the value.
                    // If there are more attributes, they are seperated by a ";"
                    $attributes = explode(";", $singleProduct['attributes']);
                    // print_r($attributes);
                    
                    // lets get the attribute names.
                    $attrForPush = array();
                    foreach ($attributes as $attribute) {
                        // anything before ":" is our name
                        array_push($attrForPush, strstr($attribute, ':', true));
                    }
                    // print_r($attrForPush);
                    if (! empty($attrForPush)) {
                        // Set the simple product attributes
                        $simpleProduct->setAttributes($attrForPush);
                    }
                }
                // Add simple product to products list
                $this->addProduct($simpleProduct);
            }
        }
    }

    // check configurable products
    private function checkConfigurableProducts(): void
    {
        if (! empty($this->products)) {
            // print_r($this->products);
            $configurableProducts = array();
            foreach ($this->products as $product) {
                // print_r($product);
                
                // if the SKU has a "-" in it - it is a configurable product
                if (strpos($product->getSku(), "-") !== false) {
                    
                    // the real value we need, is the first word until the first "-"
                    $configurableSku = strtok($product->getSku(), "-");
                    if (strpos($product->getSku(), $configurableSku) !== false) {
                        // needs to be invisable
                        $product->setIsVisible(false);
                        // set the product at the SKU key
                        $configurableProducts[$configurableSku][] = $product;
                    }
                }
            }
            // Create configurable products
            if (is_array($configurableProducts) && sizeof($configurableProducts) > 0) {
                //print_r(sizeof($configurableProducts));
                $this->createConfigurableProduct($configurableProducts);
            }
        }
    }

    // Create configurable products
    private function createConfigurableProduct($aConfigurableProducts): void
    {
        // print_r($aConfigurableProducts);
        if (! empty($aConfigurableProducts)) {
            foreach ($aConfigurableProducts as $skuKey => $simpleProducts) {
                $configurableProduct = new ConfigurableProduct();
                foreach ($simpleProducts as $singleProduct) {
                    // Again - set the variables.
                    $configurableProduct->setSku($skuKey);
                    $configurableProduct->setTitle($singleProduct->getTitle());
                    $configurableProduct->setPrice($singleProduct->getPrice());
                    $configurableProduct->setStock($singleProduct->getStock());
                    // attributes are set the same way here.
                    $configurableProduct->setAttributes($singleProduct->getAttributes());
                    // Add the simple product
                    $configurableProduct->addSimpleProduct($singleProduct);
                }
                // Add the configurable product to product list
                $this->addProduct($configurableProduct);
            }
        }
    }

    // required functions from the interface.
    /**
     * Get products imported
     *
     * @return ProductInterface[]
     */
    public function getProducts(): array
    {
        return $this->products;
    }

    /**
     * Add product to the importer
     *
     * @param ProductInterface $product
     * @return Import
     */
    public function addProduct(ProductInterface $product): Import
    {
        $this->products[] = $product;
        return $this;
    }
}//End of class