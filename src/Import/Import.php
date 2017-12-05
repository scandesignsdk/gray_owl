<?php
namespace SDM\Import;

use SDM\Import\SimpleProduct;

class Import implements ImportInterface
{

    private $filePath, $delimiter, $parsed = array();

    /**
     * Importer
     *
     * @param string $filePath The path to the csv file
     * @param string $delimiter CSV delimter
     */
    public function __construct(string $filePath, string $delimiter = ',') {
        $this->filePath = $filePath;
        $this->delimiter = $delimiter;
    }

    public function parse(): void {
        $headers = null;

        $handle = fopen($this->filePath, 'r');
        
        while(($data = fgetcsv($handle, 1000, $this->delimiter)) !== false) {
            if($headers === null) {
                $headers = $data;
            } else {
                $parsed = array_combine($headers, $data);

                $attrs = array();
                foreach(explode(';', $parsed['attributes']) as $attr) {
                    list($key, $val) = explode(':', $attr);
                    $attrs[$key] = $val;
                }
                $parsed['attributes'] = $attrs;
                
                $this->addProduct($parsed);
            }
        }
        
        fclose($handle);
    }

    public function getProducts(): array {
        return $this->parsed;
    }
    
    public function addProduct($product): void {
        $simple_product = new SimpleProduct();
        $simple_product->setSku($product['SKU']);
        $simple_product->setTitle($product['Title']);
        $simple_product->setAttributes($product['attributes']);
        $simple_product->setPrice($product['Price']);
        $simple_product->setStock($product['Stock']);

        $conf_sku = current(explode('-', $product['SKU']));
        
        if(! array_key_exists($conf_sku, $this->parsed)) {
            $configurable_product = new ConfigurableProduct();
            $configurable_product->setSku($conf_sku);
            $configurable_product->setTitle($simple_product->getTitle());
            $configurable_product->setPrice($simple_product->getPrice());
            $configurable_product->setStock($simple_product->getStock() > 0 ? $simple_product->getStock() : 0);
    
            $configurable_product->addSimpleProduct($simple_product);

            $this->parsed[$conf_sku] = $configurable_product;
        } else {
            if($simple_product->getPrice() < $this->parsed[$conf_sku]->getPrice()) {
                $this->parsed[$conf_sku]->setPrice($simple_product->getPrice());
            }

            $this->parsed[$conf_sku]->setStock($simple_product->getStock() > 0 ? $simple_product->getStock() : 0);

            $this->parsed[$conf_sku]->addSimpleProduct($simple_product);
        }
    }

}
