<?php

namespace app\tests\collections;

use PHPUnit\Framework\TestCase;
use app\collections\BaseSessionCollection;
use app\models\CurrencyModel;

/**
 * Тестирует класс BaseSessionCollection
 */
class BaseSessionCollectionTests extends TestCase
{
    /**
     * Тестирует метод BaseSessionCollection::getArray
     * при условии что BaseSessionCollection::items пуст
     * @expectedException ErrorException
     * @expectedExceptionMessage Missing required data: items
     */
    public function testGetArrayEmpty()
    {
        $collection = new BaseSessionCollection();
        $collection->getArray();
    }
    
    /**
     * Тестирует метод BaseSessionCollection::getArray
     * при условии что BaseSessionCollection::items содержит не массивы
     * @expectedException ErrorException
     * @expectedExceptionMessage Получен неверный тип данных вместо: items
     */
    public function testGetArrayNotArray()
    {
        $model = new class() {};
        
        $collection = new BaseSessionCollection();
        
        $reflection = new \ReflectionProperty($collection, 'items');
        $reflection->setAccessible(true);
        $reflection->setValue($collection, [$model]);
        
        $collection->getArray();
    }
    
    /**
     * Тестирует метод BaseSessionCollection::getArray
     */
    public function testGetArray()
    {
        $array = ['id'=>1, 'one'=>'some one text', 'two'=>23.3467];
        
        $collection = new BaseSessionCollection();
        
        $reflection = new \ReflectionProperty($collection, 'items');
        $reflection->setAccessible(true);
        $reflection->setValue($collection, [$array]);
        
        $result = $collection->getArray();
        
        $this->assertSame($array, $result);
    }
}
