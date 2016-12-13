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
}
