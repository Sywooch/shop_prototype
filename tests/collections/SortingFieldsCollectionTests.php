<?php

namespace app\tests\collections;

use PHPUnit\Framework\TestCase;
use app\collections\SortingFieldsCollection;

/**
 * Тестирует класс SortingFieldsCollection
 */
class SortingFieldsCollectionTests extends TestCase
{
    /**
     * Тестирует метод SortingFieldsCollection::addArray
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testAddArrayError()
    {
        $collection = new SortingFieldsCollection();
        $collection->addArray('some');
    }
    
    /**
     * Тестирует метод SortingFieldsCollection::addArray
     */
    public function testAddArray()
    {
        $array = ['name'=>'Name', 'value'=>'Value'];
        
        $collection = new SortingFieldsCollection();
        $collection->addArray($array);
        
        $reflection = new \ReflectionProperty($collection, 'items');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($collection);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        $this->assertCount(1, $result);
        $this->assertSame($array, $result[0]);
    }
    
    /**
     * Тестирует метод SortingFieldsCollection::getDefault
     */
    public function testGetDefault()
    {
        $array = ['name'=>'date', 'value'=>'Value'];
        
        $collection = new SortingFieldsCollection();
        
        $reflection = new \ReflectionProperty($collection, 'items');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($collection, [$array]);
        
        $result = $collection->getDefault();
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        $this->assertSame($array, $result);
    }
}
