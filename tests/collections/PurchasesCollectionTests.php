<?php

namespace app\tests\collections;

use PHPUnit\Framework\TestCase;
use app\collections\PurchasesCollection;
use yii\base\Model;

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
    
    /**
    * Тестирует метод PurchasesCollection::add
    * если коллекция пуста
    */
    public function testAddEmptyItems()
    {
        $model = new class() extends Model {
            public $quantity = 2;
            public $id_color = 1;
            public $id_size = 15;
            public $id_product = 236;
            public $price = 24.78;
        };
        
        $collection = new PurchasesCollection();
        
        $this->assertTrue($collection->isEmpty());
        
        $collection->add($model);
        
        $this->assertFalse($collection->isEmpty());
        
        $reflection = new \ReflectionProperty($collection, 'items');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($collection);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        $this->assertCount(1, $result);
    }
    
    /**
    * Тестирует метод PurchasesCollection::add
    * если добавляю новый объект к существующему и они разные
    */
    public function testAddNotSimilar()
    {
        $model1 = new class() extends Model {
            public $quantity = 2;
            public $id_color = 1;
            public $id_size = 15;
            public $id_product = 236;
            public $price = 24.78;
        };
        
        $model2 = new class() extends Model {
            public $quantity = 21;
            public $id_color = 4;
            public $id_size = 15;
            public $id_product = 12;
            public $price = 9856.00;
        };
        
        $collection = new PurchasesCollection();
        
        $reflection = new \ReflectionProperty($collection, 'items');
        $reflection->setAccessible(true);
        $reflection->setValue($collection, [$model1]);
        
        $this->assertFalse($collection->isEmpty());
        
        $collection->add($model2);
        
        $this->assertFalse($collection->isEmpty());
        
        $reflection = new \ReflectionProperty($collection, 'items');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($collection);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        $this->assertCount(2, $result);
    }
    
    /**
    * Тестирует метод PurchasesCollection::add
    * если добавляю объект, отличающийся только количеством
    */
    public function testAddQuantityDiff()
    {
        $model1 = new class() extends Model {
            public $quantity = 2;
            public $id_color = 1;
            public $id_size = 15;
            public $id_product = 236;
            public $price = 24.78;
        };
        
        $model2 = new class() extends Model {
            public $quantity = 18;
            public $id_color = 1;
            public $id_size = 15;
            public $id_product = 236;
            public $price = 24.78;
        };
        
        $collection = new PurchasesCollection();
        
        $reflection = new \ReflectionProperty($collection, 'items');
        $reflection->setAccessible(true);
        $reflection->setValue($collection, [$model1]);
        
        $this->assertFalse($collection->isEmpty());
        
        $collection->add($model2);
        
        $this->assertFalse($collection->isEmpty());
        
        $reflection = new \ReflectionProperty($collection, 'items');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($collection);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        $this->assertCount(1, $result);
        $this->assertEquals(20, $result[0]->quantity);
    }
    
    /**
    * Тестирует метод PurchasesCollection::update
    */
    public function testUpdate()
    {
        $model1 = new class() extends Model {
            public $quantity = 2;
            public $id_color = 5;
            public $id_size = 4;
            public $id_product = 236;
            public $price = 24.78;
        };
        
        $model2 = new class() extends Model {
            public $quantity = 18;
            public $id_color = 1;
            public $id_size = 15;
            public $id_product = 236;
        };
        
        $collection = new PurchasesCollection();
        
        $reflection = new \ReflectionProperty($collection, 'items');
        $reflection->setAccessible(true);
        $reflection->setValue($collection, [$model1]);
        
        $reflection = new \ReflectionProperty($collection, 'items');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($collection);
        
        $this->assertCount(1, $result);
        $this->assertEquals(2, $result[0]->quantity);
        $this->assertEquals(5, $result[0]->id_color);
        $this->assertEquals(4, $result[0]->id_size);
        $this->assertEquals(236, $result[0]->id_product);
        $this->assertEquals(24.78, $result[0]->price);
        
        $collection->update($model2);
        
        $reflection = new \ReflectionProperty($collection, 'items');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($collection);
        
        $this->assertCount(1, $result);
        $this->assertEquals(18, $result[0]->quantity);
        $this->assertEquals(1, $result[0]->id_color);
        $this->assertEquals(15, $result[0]->id_size);
        $this->assertEquals(236, $result[0]->id_product);
        $this->assertEquals(24.78, $result[0]->price);
    }
    
    /**
    * Тестирует метод PurchasesCollection::delete
    */
    public function testDelete()
    {
        $model1 = new class() extends Model {
            public $quantity = 2;
            public $id_color = 5;
            public $id_size = 4;
            public $id_product = 236;
            public $price = 24.78;
        };
        
        $model2 = new class() extends Model {
            public $id_product = 236;
        };
        
        $collection = new PurchasesCollection();
        
        $reflection = new \ReflectionProperty($collection, 'items');
        $reflection->setAccessible(true);
        $reflection->setValue($collection, [$model1]);
        
        $reflection = new \ReflectionProperty($collection, 'items');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($collection);
        
        $this->assertNotEmpty($result);
        
        $collection->delete($model2);
        
        $reflection = new \ReflectionProperty($collection, 'items');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($collection);
        
        $this->assertEmpty($result);
    }
    
    /**
    * Тестирует метод PurchasesCollection::addRaw
    * если коллекция пуста
    */
    public function testAddRaw()
    {
        $model = new class() extends Model {
            public $quantity = 2;
            public $id_color = 1;
            public $id_size = 15;
            public $id_product = 236;
            public $price = 24.78;
        };
        
        $collection = new PurchasesCollection();
        
        $this->assertTrue($collection->isEmpty());
        
        $collection->addRaw($model);
        
        $this->assertFalse($collection->isEmpty());
        
        $reflection = new \ReflectionProperty($collection, 'items');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($collection);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        $this->assertCount(1, $result);
    }
}
