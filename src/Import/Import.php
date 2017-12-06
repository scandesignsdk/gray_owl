<?php
namespace SDM\Import;

class Import implements ImportInterface
{

	private $filePath;

	private $delimiter;

	private $products = [];

    /**
     * Importer
     *
     * @param string $filePath The path to the csv file
     * @param string $delimiter CSV delimter
     */
    public function __construct(string $filePath, string $delimiter = ',')
    {
		if (!file_exists($filePath)) {
			throw new \RuntimeException("Could not find import file");
		}

		if (empty($delimiter)) {
			throw new \InvalidArgumentException('Delimiter canno be empty');
		}

		$this->filePath = $filePath;
		$this->delimiter = $delimiter;
    }

	public function getCsvData() {
		$data = [];
		$handle = fopen($this->filePath, 'rt');

		while (!feof($handle)) {
			$data[] = fgetcsv($handle, 0, $this->delimiter);
		}
		fclose($handle);

		return $data;
	}

	public function parse()
	{
		$products = $this->getCsvData();
		array_shift($products);


		if ($products) {
			foreach ($products as $_product) {
				if (!$_product) {
					continue;
				}

				$this->addProduct(new Product($_product[0], $_product[1], $_product[2], $_product[3], $_product[4]));
			}
		}
	}

	public function getProducts(): array
	{
		return $this->products;
	}

	public function addProduct(ProductInterface $SimpleProduct): Import
	{
		$SimpleProduct->setVisible(true);
		if ($this->isConfigurableSKU($SimpleProduct->getSku())) {
			$configurableSku = $this->getConfigurableSKU($SimpleProduct->getSku());
			if (!array_key_exists($configurableSku, $this->products)) {
				$this->products[$configurableSku] = new ConfiguredProduct($configurableSku, $SimpleProduct->getTitle());
			}

			$this->products[$configurableSku]->addSimpleProduct($SimpleProduct);
		}

		$this->products[$SimpleProduct->getSku()] = $SimpleProduct;
		return $this;
	}

	public function isConfigurableSKU($sku)
	{
		return stristr($sku, '-');
	}

	public function getConfigurableSKU($sku)
	{
		$skuArray = explode('-', $sku);
		return array_shift($skuArray);
	}
}
