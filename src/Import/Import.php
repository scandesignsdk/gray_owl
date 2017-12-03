<?php
namespace SDM\Import;

class Import implements ImportInterface
{
    private $filePath;
    private $delimiter;
    private $products = array();
    private $configurableProductTitles = array();
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
    }

    public function parse(): void
    {
      if ($handle = $this->readfile($this->filePath)) {
        $this->addCSVProducts($handle);
      }
    }

    private function addCSVProducts($handle): void
    {
      fgetcsv($handle, $this->delimiter);
      while (($data = fgetcsv($handle, $this->delimiter))) {
        $simpleProductAttribute = $this->getSimpleProductAttributes($data[2]);
        $simpleProduct = new SimpleProduct($data[0], $data[1], $simpleProductAttribute, floatval($data[3]), floatval($data[4]));
        $this->addProduct($simpleProduct);
        $title = $this->getConfigurableProductTitle($data[0]);
        if ($title != '') {
          $this->checkConfigurableProductAlready($title, $data, $simpleProduct);
        }
      }
    }

    private function checkConfigurableProductAlready(string $title, array $data, SimpleProductInterface $simpleProduct): void
    {
      if (!in_array($title, $this->configurableProductTitles)) {
        $configurableProduct = new ConfigurableProduct($title, $data[1]);
        $this->addConfigurableProductTitles($title);
        $configurableProduct->addSimpleProduct($simpleProduct);
        $simpleProduct->setVisible(False);
        $this->addProduct($configurableProduct);
      } else {
        $this->addSimpleProductToConfigurable($title, $simpleProduct);
      }
    }

    private function addSimpleProductToConfigurable(string $title, SimpleProductInterface $simpleProduct): void
    {
      foreach ($this->products as $product) {
        if ($product->getSku() == $title) {
          $product->addSimpleProduct($simpleProduct);
          $simpleProduct->setVisible(False);
          break;
        }
      }
    }

    private function readFile(string $path)
    {
      return fopen($path, "r");
    }

    private function getConfigurableProductTitle(string $data): string
    {
      $hyphenPos = strrpos($data, "-");
      if ($hyphenPos === False) {
        return '';
      }
      return strstr($data, "-", True);
    }

    private function getSimpleProductAttributes(string $attributeData): ?array
    {
      if ($attributeData == '') {
        return null;
      }
      $res = array();
      $attributes = explode(';', $attributeData);
      foreach ($attributes as $value) {
        $attr = explode(':', $value);
        $res[$attr[0]] = $attr[1];
      }
      return $res;
    }

    private function addConfigurableProductTitles(string $title): void
    {
      array_push($this->configurableProductTitles, $title);
    }

    public function getProducts(): array
    {
      return $this->products;
    }

    public function addProduct(ProductInterface $product): Import
    {
      array_push($this->products, $product);
      return $this;
    }

}
