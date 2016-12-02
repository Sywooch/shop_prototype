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
