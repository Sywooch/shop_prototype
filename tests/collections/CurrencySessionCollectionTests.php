<?php

namespace app\tests\collections;

use PHPUnit\Framework\TestCase;
use app\collections\CurrencySessionCollection;
use app\models\CurrencyModel;

/**
 * Тестирует класс CurrencySessionCollection
 */
class CurrencySessionCollectionTests extends TestCase
{
    /**
     * Тестирует метод CurrencySessionCollection::getModel
     * при условии что CurrencySessionCollection::items пуст
     * @expectedException ErrorException
     * @expectedExceptionMessage Missing required data: items
     */
    public function testGetModelEmpty()
    {
        $collection = new CurrencySessionCollection();
        $collection->getModel();
    }
    
    /**
     * Тестирует метод CurrencySessionCollection::getModel
     * при условии что CurrencySessionCollection::items содержит не массивы
     * @expectedException ErrorException
     * @expectedExceptionMessage Получен неверный тип данных вместо: items
     */
    public function testGetModelNotArray()
    {
        $model = new class() {};
        
        $collection = new CurrencySessionCollection();
        
        $reflection = new \ReflectionProperty($collection, 'items');
        $reflection->setAccessible(true);
        $reflection->setValue($collection, [$model]);
        
        $collection->getModel();
    }
    
    /**
     * Тестирует метод CurrencySessionCollection::getModel
     */
    public function testGetModel()
    {
        $currencyArray = ['id'=>1, 'code'=>'USD', 'exchange_rate'=>23.3467];
        
        $collection = new CurrencySessionCollection();
        
        $reflection = new \ReflectionProperty($collection, 'items');
        $reflection->setAccessible(true);
        $reflection->setValue($collection, [$currencyArray]);
        
        $result = $collection->getModel();
        
        $this->assertInstanceOf(CurrencyModel::class, $result);
    }
}
