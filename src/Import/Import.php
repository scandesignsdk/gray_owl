<?php

/* IMPORTANT: 
 *
 * Since my dev enviroment is only using PHP 7.0 i had to remove some of 
 * the PHP 7.1 functionality.
 *
 */

namespace SDM\Import;

class Import
{

    /**
     * All products imported
     *
     * @var ProductInterface[]
     */
    private $products = [];

    /**
     * @var string
     */
    private $filePath;

    /**
     * @var string
     */
    private $delimiter;

    /**
     * Importer
     *
     * @param string $filePath The path to the csv file
     * @param string $delimiter CSV delimter
     */
    public function __construct(string $filePath, $delimiter = ',')
    {
        $this->filePath = $filePath;
        $this->delimiter = $delimiter;
    }

    /**
     * Parse the csv file
     *
     * @return void
     */
    public function parse()
    {
        $file = fopen($this->filePath, 'r');
        $i = 0;
        $this->products = array();

        while(!feof($file)) {

            $product_array = fgetcsv($file);

            // Ignore the first line, for now
            if ($i == 0 || is_null($product_array[0]) ) { $i++; continue; }
            $product = New SimpleProduct();

            $product->setSku($product_array[0]);
            $product->setTitle($product_array[1]);
            $product->setAttributes($this->parse_attr($product_array[2]));
            $product->setStock($product_array[3]);
            $product->setPrice($product_array[4]);
            $product->setVisibility(TRUE);

            $i++;

            $this->products[] = $product;
        }
        
        $this->generate_products_array();
    }

    private function generate_products_array() {

        $conf_product_array = array();

        foreach ($this->products as $simple_product) {
            if (strpos($simple_product->getSku(), '-')) {

                $conf_product_sku = strtok($simple_product->getSku(), '-');
                $simple_product->setVisibility(FALSE);

                $conf_product_array[$conf_product_sku][] = $simple_product;
            }
        }

        foreach ($conf_product_array as $key => $simple_products) {
            $conf_product = new ConfigurableProduct($key);

            foreach ($simple_products as $simple_product) {
                $conf_product->addSimpleProduct($simple_product);
            }

            $this->products[] = $conf_product;
        }
    }

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

    private function parse_attr(string $attributes_string) {

        if ($attributes_string == '') {
            return NULL;
        }

        $attributes = array();

        $attr_string_array = explode(';', $attributes_string);
        
        foreach ($attr_string_array as $attr_string) {
            $attributes[] = explode(":", $attr_string);
        }

        return $attributes;
    }

}
