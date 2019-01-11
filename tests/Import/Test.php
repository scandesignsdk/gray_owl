<?php

namespace SDMTests\Import;

use PHPUnit\Framework\TestCase;
use SDM\Import\ConfigurableProductInterface;
use SDM\Import\Import;
use SDM\Import\SimpleProductInterface;

class Test extends TestCase
{
    public function test_csv5(): void
    {
        $imported = $this->parseCsvData(__DIR__.'/files/test5.csv', ',');
        $this->assertCount(3, $imported->products, 'Product count');
        $this->assertCount(1, $imported->configurables, 'Configuruable count');
        $this->assertCount(2, $imported->simples, 'Simple count');
        $this->assertCount(1, $imported->visibles, 'Visible simple count');
        $this->assertCount(2, $imported->nonvisibles, 'Non visible simple count');

        $config = $imported->configurables[0];
        $this->assertEquals('table', $config->getSku());
        $this->assertEquals('Table', $config->getTitle());
        $this->assertEquals(1495, $config->getPrice());
        $this->assertCount(2, $config->getAttributes());
        $this->assertEquals('color', $config->getAttributes()[0]);
        $this->assertEquals('size', $config->getAttributes()[1]);
        $this->assertFalse($config->isInStock());
    }

    public function test_csv4(): void
    {
        $imported = $this->parseCsvData(__DIR__.'/files/test4.csv', ',');
        $this->assertCount(3, $imported->products, 'Product count');
        $this->assertCount(1, $imported->configurables, 'Configuruable count');
        $this->assertCount(2, $imported->simples, 'Simple count');
        $this->assertCount(1, $imported->visibles, 'Visible simple count');
        $this->assertCount(2, $imported->nonvisibles, 'Non visible simple count');

        $config = $imported->configurables[0];
        $this->assertEquals('table', $config->getSku());
        $this->assertEquals('Table', $config->getTitle());
        $this->assertEquals(1495, $config->getPrice());
        $this->assertCount(2, $config->getAttributes());
        $this->assertEquals('color', $config->getAttributes()[0]);
        $this->assertEquals('size', $config->getAttributes()[1]);
    }

    public function test_csv3(): void
    {
        $imported = $this->parseCsvData(__DIR__.'/files/test3.csv', ',');
        $this->assertCount(3, $imported->products, 'Product count');
        $this->assertCount(1, $imported->configurables, 'Configuruable count');
        $this->assertCount(2, $imported->simples, 'Simple count');
        $this->assertCount(1, $imported->visibles, 'Visible simple count');
        $this->assertCount(2, $imported->nonvisibles, 'Non visible simple count');

        $config = $imported->configurables[0];
        $this->assertEquals('table', $config->getSku());
        $this->assertEquals('Table', $config->getTitle());
        $this->assertEquals(1495, $config->getPrice());
        $this->assertCount(1, $config->getAttributes());
        $this->assertEquals('color', $config->getAttributes()[0]);
    }

    public function test_csv2(): void
    {
        $imported = $this->parseCsvData(__DIR__.'/files/test2.csv', ',');

        $this->assertCount(2, $imported->products, 'Product count');
        $this->assertCount(0, $imported->configurables, 'Configuruable count');
        $this->assertCount(2, $imported->simples, 'Simple count');
        $this->assertCount(2, $imported->visibles, 'Visible simple count');
        $this->assertCount(0, $imported->nonvisibles, 'Non visible simple count');

        $p1 = $imported->simples[0];
        $this->assertEquals('simplesku1', $p1->getSku());
        $this->assertEquals('Simple Product 1', $p1->getTitle());
        $this->assertEquals(75, $p1->getPrice());
        $this->assertTrue($p1->isInStock());
        $this->assertEquals(15, $p1->getStock());
        $this->assertNull($p1->getAttributes());

        $p2 = $imported->simples[1];
        $this->assertEquals('simplesku2', $p2->getSku());
        $this->assertEquals('Simple Product 2', $p2->getTitle());
        $this->assertEquals(25.15, $p2->getPrice());
        $this->assertFalse($p2->isInStock());
        $this->assertEquals(0, $p2->getStock());
        $this->assertNull($p2->getAttributes());
    }

    public function test_csv1(): void
    {
        $imported = $this->parseCsvData(__DIR__.'/files/test1.csv', ',');

        $this->assertCount(16, $imported->products, 'Product count');
        $this->assertCount(4, $imported->configurables, 'Configuruable count');
        $this->assertCount(12, $imported->simples, 'Simple count');
        $this->assertCount(5, $imported->visibles, 'Visible simple count');
        $this->assertCount(11, $imported->nonvisibles, 'Non visible simple count');

        foreach ($imported->configurables as $product) {
            switch ($product->getSku()) {
                case 'table':
                    $this->assertEquals(1495, $product->getPrice());
                    $this->assertCount(2, $product->getSimpleProducts());
                    $this->assertEquals('color', $product->getAttributes()[0]);
                    $this->assertTrue($product->isInStock());
                    break;
                case 'socks':
                    $this->assertEquals(65, $product->getPrice());
                    $this->assertCount(2, $product->getSimpleProducts());
                    $this->assertEquals('size', $product->getAttributes()[0]);
                    $this->assertTrue($product->isInStock());
                    break;
                case 'chair':
                    $this->assertEquals(340, $product->getPrice());
                    $this->assertCount(3, $product->getSimpleProducts());
                    $this->assertEquals('color', $product->getAttributes()[0]);
                    $this->assertTrue($product->isInStock());
                    break;
                case 'shoe':
                    $this->assertEquals(1250, $product->getPrice());
                    $this->assertCount(4, $product->getSimpleProducts());
                    $this->assertEquals('color', $product->getAttributes()[0]);
                    $this->assertEquals('size', $product->getAttributes()[1]);
                    $this->assertTrue($product->isInStock());
                    break;
                default:
                    throw new \InvalidArgumentException('You have created a configurable product with a SKU it shouldnt have!');
            }
        }

        foreach ($imported->visibles as $visible) {
            if ($visible instanceof SimpleProductInterface) {
                $this->assertEquals('simplesku', $visible->getSku());
                $this->assertEquals(200, $visible->getStock());
                $this->assertNull($visible->getAttributes());
            }
        }
    }

    /**
     * @param string $filename
     * @param string $demiliter
     *
     * @return ImportProducts
     */
    private function parseCsvData($filename, $demiliter): ImportProducts
    {
        $importer = new Import($filename, $demiliter);
        $importer->parse();
        $products = $importer->getProducts();

        $class = new ImportProducts();
        foreach ($products as $product) {
            $class->products[] = $product;

            if ($product->isVisible()) {
                $class->visibles[] = $product;
            } else {
                $class->nonvisibles[] = $product;
            }

            if ($product instanceof SimpleProductInterface) {
                $class->simples[] = $product;
            }

            if ($product instanceof ConfigurableProductInterface) {
                $class->configurables[] = $product;
            }
        }

        return $class;
    }
}
