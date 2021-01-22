<?php

namespace SDMTests\Import;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use SDM\Import\ConfigurableProductInterface;
use SDM\Import\Import;
use SDM\Import\SimpleProductInterface;

class Test extends TestCase
{
    public function test_csv5(): void
    {
        $imported = $this->parseCsvData(__DIR__.'/files/test5.csv', ',');
        self::assertCount(3, $imported->products, 'Product count');
        self::assertCount(1, $imported->configurables, 'Configuruable count');
        self::assertCount(2, $imported->simples, 'Simple count');
        self::assertCount(1, $imported->visibles, 'Visible simple count');
        self::assertCount(2, $imported->nonvisibles, 'Non visible simple count');

        $config = $imported->configurables[0];
        self::assertEquals('table', $config->getSku());
        self::assertEquals('Table', $config->getTitle());
        self::assertEquals(1495, $config->getPrice());
        self::assertCount(2, $config->getAttributes());
        self::assertEquals('color', $config->getAttributes()[0]);
        self::assertEquals('size', $config->getAttributes()[1]);
        self::assertFalse($config->isInStock());
    }

    public function test_csv4(): void
    {
        $imported = $this->parseCsvData(__DIR__.'/files/test4.csv', ',');
        self::assertCount(3, $imported->products, 'Product count');
        self::assertCount(1, $imported->configurables, 'Configuruable count');
        self::assertCount(2, $imported->simples, 'Simple count');
        self::assertCount(1, $imported->visibles, 'Visible simple count');
        self::assertCount(2, $imported->nonvisibles, 'Non visible simple count');

        $config = $imported->configurables[0];
        self::assertEquals('table', $config->getSku());
        self::assertEquals('Table', $config->getTitle());
        self::assertEquals(1495, $config->getPrice());
        self::assertCount(2, $config->getAttributes());
        self::assertEquals('color', $config->getAttributes()[0]);
        self::assertEquals('size', $config->getAttributes()[1]);
    }

    public function test_csv3(): void
    {
        $imported = $this->parseCsvData(__DIR__.'/files/test3.csv', ',');
        self::assertCount(3, $imported->products, 'Product count');
        self::assertCount(1, $imported->configurables, 'Configuruable count');
        self::assertCount(2, $imported->simples, 'Simple count');
        self::assertCount(1, $imported->visibles, 'Visible simple count');
        self::assertCount(2, $imported->nonvisibles, 'Non visible simple count');

        $config = $imported->configurables[0];
        self::assertEquals('table', $config->getSku());
        self::assertEquals('Table', $config->getTitle());
        self::assertEquals(1495, $config->getPrice());
        self::assertCount(1, $config->getAttributes());
        self::assertEquals('color', $config->getAttributes()[0]);
    }

    public function test_csv2(): void
    {
        $imported = $this->parseCsvData(__DIR__.'/files/test2.csv', ',');

        self::assertCount(2, $imported->products, 'Product count');
        self::assertCount(0, $imported->configurables, 'Configuruable count');
        self::assertCount(2, $imported->simples, 'Simple count');
        self::assertCount(2, $imported->visibles, 'Visible simple count');
        self::assertCount(0, $imported->nonvisibles, 'Non visible simple count');

        $p1 = $imported->simples[0];
        self::assertEquals('simplesku1', $p1->getSku());
        self::assertEquals('Simple Product 1', $p1->getTitle());
        self::assertEquals(75, $p1->getPrice());
        self::assertTrue($p1->isInStock());
        self::assertEquals(15, $p1->getStock());
        self::assertNull($p1->getAttributes());

        $p2 = $imported->simples[1];
        self::assertEquals('simplesku2', $p2->getSku());
        self::assertEquals('Simple Product 2', $p2->getTitle());
        self::assertEquals(25.15, $p2->getPrice());
        self::assertFalse($p2->isInStock());
        self::assertEquals(0, $p2->getStock());
        self::assertNull($p2->getAttributes());
    }

    public function test_csv1(): void
    {
        $imported = $this->parseCsvData(__DIR__.'/files/test1.csv', ',');

        self::assertCount(16, $imported->products, 'Product count');
        self::assertCount(4, $imported->configurables, 'Configuruable count');
        self::assertCount(12, $imported->simples, 'Simple count');
        self::assertCount(5, $imported->visibles, 'Visible simple count');
        self::assertCount(11, $imported->nonvisibles, 'Non visible simple count');

        foreach ($imported->configurables as $product) {
            switch ($product->getSku()) {
                case 'table':
                    self::assertEquals(1495, $product->getPrice());
                    self::assertCount(2, $product->getSimpleProducts());
                    self::assertEquals('color', $product->getAttributes()[0]);
                    self::assertTrue($product->isInStock());
                    break;
                case 'socks':
                    self::assertEquals(65, $product->getPrice());
                    self::assertCount(2, $product->getSimpleProducts());
                    self::assertEquals('size', $product->getAttributes()[0]);
                    self::assertTrue($product->isInStock());
                    break;
                case 'chair':
                    self::assertEquals(340, $product->getPrice());
                    self::assertCount(3, $product->getSimpleProducts());
                    self::assertEquals('color', $product->getAttributes()[0]);
                    self::assertTrue($product->isInStock());
                    break;
                case 'shoe':
                    self::assertEquals(1250, $product->getPrice());
                    self::assertCount(4, $product->getSimpleProducts());
                    self::assertEquals('color', $product->getAttributes()[0]);
                    self::assertEquals('size', $product->getAttributes()[1]);
                    self::assertTrue($product->isInStock());
                    break;
                default:
                    throw new InvalidArgumentException('You have created a configurable product with a SKU it shouldnt have!');
            }
        }

        foreach ($imported->visibles as $visible) {
            if ($visible instanceof SimpleProductInterface) {
                self::assertEquals('simplesku', $visible->getSku());
                self::assertEquals(200, $visible->getStock());
                self::assertNull($visible->getAttributes());
            }
        }
    }

    private function parseCsvData(string $filename, string $demiliter): ImportProducts
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
