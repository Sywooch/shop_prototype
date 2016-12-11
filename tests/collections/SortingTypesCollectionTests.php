<?php

namespace app\tests\collections;

use PHPUnit\Framework\TestCase;
use app\collections\SortingTypesCollection;

/**
 * Тестирует класс SortingTypesCollection
 */
class SortingTypesCollectionTests extends TestCase
{
    /**
     * Тестирует метод SortingTypesCollection::addArray
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testAddArrayError()
    {
        $collection = new SortingTypesCollection();
        $collection->addArray('some');
    }
    
    /**
     * Тестирует метод SortingTypesCollection::addArray
     */
    public function testAddArray()
    {
        $array = ['name'=>'Name', 'value'=>'Value'];
        
        $collection = new SortingTypesCollection();
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
     * Тестирует метод SortingTypesCollection::getDefault
     */
    public function testGetDefault()
    {
        $array = ['name'=>'SORT_DESC', 'value'=>'Value'];
        
        $collection = new SortingTypesCollection();
        
        $reflection = new \ReflectionProperty($collection, 'items');
        $reflection->setAccessible(true);
        $result = $reflection->setValue($collection, [$array]);
        
        $result = $collection->getDefault();
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        $this->assertSame($array, $result);
    }
}
