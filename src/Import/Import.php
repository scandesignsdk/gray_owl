<?php

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
    public function parse(): void
    {
        $file_arr = file($this->filePath);        
        $delim_arr = array_fill(0, count($file_arr), $this->delimiter);
        $csv = array_map('str_getcsv', file($this->filePath), $delim_arr);

        // Remove the header.
        array_shift($csv);
        //echo '<pre>';print_r($csv);echo '</pre>';
        $configrable_products_array = [];

        foreach ($csv as $key => $value) {
            $one_s_prod = new SimpleProduct();
            $one_s_prod->setSku($value[0]);
            $one_s_prod->setTitle($value[1]);
            $one_s_prod->setAttributes($this->extract_attributes($value[2]));
            $one_s_prod->setStock($value[3]);
            $one_s_prod->setPrice($value[4]);


            $configurable_product_sku = $this->check_if_configurable_product($value[0]);
            if($configurable_product_sku)
            {
				$one_s_prod->setVisible(false);
				$configrable_products_array[$configurable_product_sku][] = $one_s_prod;
            }
            else
            {
            	$one_s_prod->setVisible(true);
            }

            
            $this->addProduct($one_s_prod);
        } 
        //echo '<pre>';print_r($configrable_products_array);echo '</pre>';
        $this->create_configurable_product($configrable_products_array);
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

    /**
     * Check if its a configurable product
     * if yes, return the title of the product before first -
     *
     * @param string $product_title
     * @return string
     */
    public function check_if_configurable_product($product_title): string
    {
        $title_arr = explode('-', $product_title);
        if ($title_arr[0] && (count($title_arr) > 1) ) 
        {
        	return $title_arr[0];
        }
        else
        {
        	return '';
        }
    }

    /**
     * crete configurable product.
     *
     * @param array $configrable_products_array
     * @return void
     */
    public function create_configurable_product($configrable_products_array): void
    {
        foreach ($configrable_products_array as $c_p_sku => $arr_simple_products) {
        	$total_quantity = 0;
        	$attrib = [];
        	$one_c_prod = new ConfigurableProduct();

			$one_c_prod->setSku($c_p_sku);
			$one_c_prod->setVisible(true);

			$first_simple_title = $arr_simple_products[0]->getTitle();
			$one_c_prod->setTitle($first_simple_title);
			
        	$min_price = $arr_simple_products[0]->getPrice();
        	foreach ($arr_simple_products as $key => $each_simple_product) {
        		$one_c_prod->addSimpleProduct($each_simple_product);

        		if($min_price > $each_simple_product->getPrice())
        		{
        			$min_price = $each_simple_product->getPrice();
        		}

        		$total_quantity += ($each_simple_product->getStock() < 0) ? 0 : $each_simple_product->getStock();
        		$attrib = array_unique(array_merge($attrib,$each_simple_product->getAttributes()));
        	}

        	$one_c_prod->setPrice($min_price);
        	$one_c_prod->setAttributes($attrib);

        	($total_quantity < 1) ? $one_c_prod->setStock(0) : $one_c_prod->setStock($total_quantity);


        	$this->addProduct($one_c_prod);
        }
    }

    /**
     * arrange product attributes in propr array format.
     *
     * @param string $product_attributes
     * @return array
     */
    public function extract_attributes($product_attributes): array
    {
        $attributes = [];
        if($product_attributes)
        {
	        $attrib_arr = explode(';', $product_attributes);
			foreach ($attrib_arr as $attr_each) {
			   	$elem_arr = explode(':', $attr_each);
			    $attributes[] = $elem_arr[0];
			}
        }
		return $attributes;
    }

}
