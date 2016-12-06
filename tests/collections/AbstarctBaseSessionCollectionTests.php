<?php

namespace app\tests\collections;

use PHPUnit\Framework\TestCase;
use app\collections\AbstarctBaseSessionCollection;

/**
 * Тестирует класс AbstarctBaseSessionCollection
 */
class AbstarctBaseSessionCollectionTests extends TestCase
{
    private $collection;
    
    public function setUp()
    {
        $this->collection = new class() extends AbstarctBaseSessionCollection {
            public function getModels() {}
            public function getModel() {}
        };
    }
    
    /**
     * Тестирует метод AbstarctBaseSessionCollection::getArray
     * при условии что AbstarctBaseSessionCollection::items пуст
     * @expectedException ErrorException
     * @expectedExceptionMessage Missing required data: items
     */
    public function testGetArrayEmpty()
    {
        $collection = new $this->collection();
        $collection->getArray();
    }
    
    /**
     * Тестирует метод AbstarctBaseSessionCollection::getArray
     * при условии что AbstarctBaseSessionCollection::items содержит не массивы
     * @expectedException ErrorException
     * @expectedExceptionMessage Получен неверный тип данных вместо: items
     */
    public function testGetArrayNotArray()
    {
        $model = new class() {};
        
        $collection = new $this->collection();
        
        $reflection = new \ReflectionProperty($collection, 'items');
        $reflection->setAccessible(true);
        $reflection->setValue($collection, [$model]);
        
        $collection->getArray();
    }
    
    /**
     * Тестирует метод AbstarctBaseSessionCollection::getArray
     */
    public function testGetArray()
    {
        $array = ['id'=>1, 'one'=>'some one text', 'two'=>23.3467];
        
        $collection = new $this->collection();
        
        $reflection = new \ReflectionProperty($collection, 'items');
        $reflection->setAccessible(true);
        $reflection->setValue($collection, [$array]);
        
        $result = $collection->getArray();
        
        $this->assertSame($array, $result);
    }
}
