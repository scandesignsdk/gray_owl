<?php
namespace SDM\Import;

use SDM\Import\SimpleProduct;
use SDM\Import\ConfigurableProduct;

class Import implements ImportInterface
{

    private $filePath, $delimiter, $simples = [], $configurables = [];
    
    /**
     * Importer
     *
     * @param string $filePath The path to the csv file
     * @param string $delimiter CSV delimter
     */
    public function __construct(string $filePath, string $delimiter = ',')
    {
        list($this->filePath, $this->delimiter) = array($filePath, $delimiter);
    }

    public function parse(): void
    {
        $headers = null;
        $handle = fopen($this->filePath, 'r');
        
        while (($data = fgetcsv($handle, 1000, $this->delimiter)) !== false) {
            if (! $headers) {
                $headers = array_map('strtolower', $data);
            } else {
                $parsed = array_combine($headers, $data);
                
                if (! empty($parsed['attributes'])) {
                    $attrs = [];
                    foreach (explode(';', $parsed['attributes']) as $attr) {
                        list($key, $val) = explode(':', $attr);
                        // $attrs[$key] = $val;
                        $attrs[] = $key;
                    }
                    $parsed['attributes'] = $attrs;
                }
                
                $simple = $this->generateSimple($parsed);
                $this->addProduct($simple);

                if (strpos($simple->getSku(), '-') !== false) {
                    $configurable = $this->generateConfigurable($simple);
                    $this->addProduct($configurable);
                }
            }
        }
        
        fclose($handle);
    }

    private function generateSimple(array $row): SimpleProduct
    {
        $simple = new SimpleProduct();
        $simple->setSku($row['sku']);
        $simple->setTitle($row['title']);
        $simple->setPrice($row['price']);
        $simple->setStock($row['stock']);

        if (! empty($row['attributes'])) {
            $simple->setAttributes($row['attributes']);
        }

        return $simple;
    }

    private function generateConfigurable(SimpleProduct $simple): ConfigurableProduct
    {
        $configurableSku = explode('-', $simple->getSku());
        $configurableSku = strtolower($configurableSku[0]);
        
        if (! array_key_exists($configurableSku, $this->configurables)) {
            $configurable = new ConfigurableProduct();
            $configurable->setSku($configurableSku);
            $configurable->setTitle($simple->getTitle());
            $configurable->setAttributes($simple->getAttributes());
        } else {
            $configurable = $this->configurables[$configurableSku];
        }

        if ($configurable->getPrice() === (float)0 || $simple->getPrice() < $configurable->getPrice()) {
            $configurable->setPrice($simple->getPrice());
        }

        $configurable->setStock($simple->getStock() > 0 ? $simple->getStock() : 0);

        $simple->setVisible(false);

        $configurable->addSimpleProduct($simple);

        return $configurable;
    }

    public function getProducts(): array
    {
        $merged = array_merge($this->simples, $this->configurables);
        
        return $merged;
    }

    public function addProduct(ProductInterface $product): void
    {
        if ($product instanceof SimpleProduct) {
            $this->simples[] = $product;
        } elseif ($product instanceof ConfigurableProduct) {
            $this->configurables[$product->getSku()] = $product;
        }
    }

}
