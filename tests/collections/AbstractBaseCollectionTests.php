<?php

namespace app\tests\collections;

use PHPUnit\Framework\TestCase;
use app\collections\AbstractBaseCollection;
use yii\base\Model;

/**
 * Тестирует трейт AbstractBaseCollection
 */
class AbstractBaseCollectionTests extends TestCase
{
    private $collection;
    
    public function setUp()
    {
        $this->collection = new class() extends AbstractBaseCollection {
            public $items;
        };
    }
    
    /**
     * Тестирует метод AbstractBaseCollection::add
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testAddError()
    {
        $model = new class() {};
        
        $this->collection->add($model);
    }
    
    /**
     * Тестирует метод AbstractBaseCollection::add
     * если AbstractBaseCollection::items содержит объекты
     */
    public function testAdd()
    {
        $model = new class() extends Model {
            public $id = 1;
        };
        
        $this->collection->add($model);
        
        $reflection = new \ReflectionProperty($this->collection, 'items');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->collection);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        $this->assertInstanceOf(Model::class, $result[0]);
    }
    
    /**
     * Тестирует метод AbstractBaseCollection::addArray
     * передаю неверный тип аргумента
     * @expectedException TypeError
     */
    public function testAddArrayError()
    {
        $data = new class() {};
        
        $this->collection->addArray($data);
    }
    
    /**
     * Тестирует метод AbstractBaseCollection::addArray
     */
    public function testAddArray()
    {
        $data = ['name'=>'Name', 'value'=>'Value'];
        
        $this->collection->addArray($data);
        
        $reflection = new \ReflectionProperty($this->collection, 'items');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->collection);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
    }
    
    /**
     * Тестирует метод AbstractBaseCollection::isEmpty
     * если static::items пуст
     */
    public function testIsEmptyTrue()
    {
        $result = $this->collection->isEmpty();
        $this->assertTrue($result);
    }
    
    /**
     * Тестирует метод AbstractBaseCollection::isEmpty
     * если static::items содержит элементы
     */
    public function testIsEmptyFalse()
    {
        $model = new class() extends Model {
            public $id = 1;
        };
        
        $reflection = new \ReflectionProperty($this->collection, 'items');
        $reflection->setValue($this->collection, [$model]);
        
        $result = $this->collection->isEmpty();
        $this->assertFalse($result);
    }
    
    /**
     * Тестирует метод AbstractBaseCollection::multisort
     */
    public function testMultisort()
    {
        $model_1 = new class() {
            public $id = 1;
        };
        
        $model_2 = new class() {
            public $id = 2;
        };
        
        $model_3 = new class() {
            public $id = 3;
        };
        
        $reflection = new \ReflectionProperty($this->collection, 'items');
        $reflection->setAccessible(true);
        $reflection->setValue($this->collection, [$model_3, $model_1, $model_2]);
        $result = $reflection->getValue($this->collection);
        
        $this->assertSame(3, $result[0]->id);
        $this->assertSame(1, $result[1]->id);
        $this->assertSame(2, $result[2]->id);
        
        $this->collection->multisort('id');
        
        $result = $reflection->getValue($this->collection);
        
        $this->assertSame(1, $result[0]->id);
        $this->assertSame(2, $result[1]->id);
        $this->assertSame(3, $result[2]->id);
        
        $this->collection->multisort('id', SORT_DESC);
        
        $result = $reflection->getValue($this->collection);
        
        $this->assertSame(3, $result[0]->id);
        $this->assertSame(2, $result[1]->id);
        $this->assertSame(1, $result[2]->id);
    }
    
    /**
     * Тестирует метод AbstractBaseCollection::map
     */
    public function testMap()
    {
        $model_1 = new class() {
            public $one = 'one';
            public $two = 'two';
        };
        
        $model_2 = new class() {
            public $one = 'three';
            public $two = 'four';
        };
        
        $reflection = new \ReflectionProperty($this->collection, 'items');
        $reflection->setAccessible(true);
        $reflection->setValue($this->collection, [$model_1, $model_2]);
        
        $result = $this->collection->map('one', 'two');
        
        $this->assertInternalType('array', $result);
        $this->assertArrayHasKey('one', $result);
        $this->assertArrayHasKey('three', $result);
        $this->assertContains('two', $result);
        $this->assertContains('four', $result);
    }
    
    /**
     * Тестирует метод AbstractBaseCollection::asArray
     */
    public function testAsArray()
    {
        $model_1 = new class() {
            public $one = 'one';
            public $two = 'two';
        };
        
        $model_2 = new class() {
            public $one = 'three';
            public $two = 'four';
        };
        
        $reflection = new \ReflectionProperty($this->collection, 'items');
        $reflection->setAccessible(true);
        $reflection->setValue($this->collection, [$model_1, $model_2]);
        
        $result = $this->collection->asArray();
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
    }
}
