<?php

namespace app\tests\collections;

use PHPUnit\Framework\TestCase;
use app\collections\ProductsCollection;

class ProductsCollectionTests extends TestCase
{
    /**
     * Тестирует метод ProductsCollection::init
     * вызываю без ProductsCollection::pagination
     * @expectedException ErrorException
     */
    public function testInitError()
    {
        $collection = new ProductsCollection();
    }
}
