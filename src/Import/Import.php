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
        $products = array();

        $file = fopen($this->filePath, "r");

        $categories = array();
        $configurables = array();

        $header = array();
        $i = 0;
        while (!feof($file)) {
            if ($i == 0) {
                // skip first row
                $header = fgetcsv($file);
            } else {
                $product_data = fgetcsv($file);

                //It would be smarter, if column name according to header, not according to fix sequences.
                $sku = $product_data[0];
                $title = $product_data[1];
                $attributes = $product_data[2];
                $stock = $product_data[3];
                $price = $product_data[4];

                if (!empty($sku) && isset($sku)) {
                    $simpleProduct = new SimpleProduct($sku, $title, $attributes, $stock, $price);
                    $skus = explode('-', $sku);
                    $category = reset($skus);

                    // check a simple product belong a category
                    if (!in_array($category, $categories)) {
                        $categories[] = $category;
                    }
                    $configurables[$category][] = $simpleProduct;
                    $this->products[] = $simpleProduct;
                }
            }
            $i++;
        }

        // get all categories
        $categories_array = array_keys($configurables);
        foreach ($categories_array as $category) {
            $categoryProducts = $configurables[$category];

            // if more than two simple products in a category, they will belong to a configurable product
            if (count($categoryProducts) > 1) {
                $configurableProduct = new ConfigurableProduct();
                foreach ($categoryProducts as $simpleProduct) {
                    /**
                     * @var SimpleProduct $simpleProduct
                     */
                    // set simple product not visible
                    $simpleProduct->visible = false;
                    $configurableProduct->addSimpleProduct($simpleProduct);
                }

                // add configurable product
                $this->products[] = $configurableProduct;
            }
        }
        fclose($file);
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


}
