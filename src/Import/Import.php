<?php

namespace SDM\Import;

/**
 * Class Import
 * @package SDM\Import
 */
class Import implements ImportInterface {
  /**
   * @var string
   */
  private $filePath;

  /**
   * @var string
   */
  private $delimiter;

  /**
   * @var array
   */
  private $products = [];

  /**
   * Import constructor.
   *
   * @param string $filePath The path to the csv file
   * @param string $delimiter CSV delimiter
   */
  public function __construct(string $filePath, string $delimiter = ',') {
    $this->filePath = $filePath;
    $this->delimiter = $delimiter;
  }

  /**
   * Parse the csv file and create products
   *
   * @return void
   */
  public function parse(): void {
    if (file_exists($this->filePath) && is_readable($this->filePath) && ($handle = fopen($this->filePath, 'r')) !== FALSE) {
      $header = NULL;
      $product = [];

      while (($data = fgetcsv($handle, 1000, $this->delimiter)) !== FALSE) {
        if (!$header) {
          $header = $data;
        }
        else {
          $product[] = array_combine($header, $data);
        }
      }

      // Create simple product
      $this->createSimpleProduct($product);

      fclose($handle);
    }
  }

  /**
   * Create Simple product
   *
   * @param array $products
   */
  private function createSimpleProduct(array $products): void {
    if (!empty($products)) {
      foreach ($products as $product) {
        $simpleProduct = new SimpleProduct();
        // Set the simple product sku
        $simpleProduct->setSku($product['SKU']);
        // Set the simple product title
        $simpleProduct->setTitle($product['Title']);
        // Set the simple product price
        $simpleProduct->setPrice($product['Price']);
        // Set the simple product stock
        $simpleProduct->setStock($product['Stock']);

        // Check if the product attributes is not empty
        if (!empty($product['attributes'])) {
          $attributes = preg_split(("/[;]/"), $product['attributes'], -1, PREG_SPLIT_NO_EMPTY);
          $attributes = array_values($attributes);

          $attr = [];
          foreach ($attributes as $attribute) {
            $attr[] = strstr($attribute, ':', TRUE);
          }

          if (!empty($attr)) {
            // Set the simple product attributes
            $simpleProduct->setAttributes($attr);
          }
        }

        // Add simple product to products list
        $this->addProduct($simpleProduct);
      }

      // Get configurable products and add to products list
      if (!empty($this->products)) {
        $configProducts = [];
        foreach ($this->products as $product) {
          if (preg_match("/[-]/", $product->getSku())) {
            $configurableSku = strtok($product->getSku(), "-");
            if (strpos($product->getSku(), $configurableSku) !== FALSE) {
              $product->setIsVisible(FALSE);
              $configProducts[$configurableSku][] = $product;
            }
          }
        }
        // Create configurable products
        $this->createConfigurableProduct($configProducts);
      }
    }
  }

  /**
   * Create configurable products
   *
   * @param $configProducts
   */
  private function createConfigurableProduct($configProducts): void {
    if (!empty($configProducts)) {
      foreach ($configProducts as $sku => $simpleProducts) {
        $configurableProduct = new ConfigurableProduct();
        foreach ($simpleProducts as $product) {
          // Set the configurable product sku
          $configurableProduct->setSku($sku);
          // Set the configurable product title
          $configurableProduct->setTitle($product->getTitle());
          // Set the configurable product price
          $configurableProduct->setPrice($product->getPrice());
          // Set the configurable product stock
          $configurableProduct->setStock($product->getStock());
          // Set the configurable product attributes
          $configurableProduct->setAttributes($product->getAttributes());

          // Add simple product to the configurable product
          $configurableProduct->addSimpleProduct($product);
        }

        // Add the configurable product to product list
        $this->addProduct($configurableProduct);
      }
    }
  }

  /**
   * Get products imported
   *
   * @return ProductInterface[]
   */
  public function getProducts(): array {
    return $this->products;
  }

  /**
   * Add product to the importer
   *
   * @param ProductInterface $product
   * @return Import
   */
  public function addProduct(ProductInterface $product): Import {
    $this->products[] = $product;
    return $this;
  }
}
