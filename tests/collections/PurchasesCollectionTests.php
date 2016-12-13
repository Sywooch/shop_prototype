<?php

namespace app\tests\collections;

use PHPUnit\Framework\TestCase;
use app\collections\PurchasesCollection;

/**
 * Тестирует трейт PurchasesCollection
 */
class PurchasesCollectionTests extends TestCase
{
    /**
    * Тестирует метод PurchasesCollection::totalQuantity
    */
    public function testTotalQuantity()
    {
        $model_1 = new class() {
            public $quantity = 2;
        };
        
        $model_2 = new class() {
            public $quantity = 12;
        };
        
        $collection = new PurchasesCollection();
        
        $reflection = new \ReflectionProperty($collection, 'items');
        $reflection->setAccessible(true);
        $reflection->setValue($collection, [$model_1, $model_2]);
        
        $result = $collection->totalQuantity();
        
        $this->assertSame(14, $result);
    }
    
    /**
    * Тестирует метод PurchasesCollection::totalPrice
    */
    public function testTotalPrice()
    {
        $model_1 = new class() {
            public $quantity = 2;
            public $price = 24.78;
        };
        
        $model_2 = new class() {
            public $quantity = 12;
            public $price = 105.00;
        };
        
        $collection = new PurchasesCollection();
        
        $reflection = new \ReflectionProperty($collection, 'items');
        $reflection->setAccessible(true);
        $reflection->setValue($collection, [$model_1, $model_2]);
        
        $result = $collection->totalPrice();
        
        $this->assertSame((2 * 24.78) + (12 * 105.00), $result);
    }
}
