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
     */
    public function testGetArray()
    {
        $currencyArray = ['id'=>1, 'one'=>'some one text', 'two'=>23.3467];
        
        $collection = new BaseSessionCollection();
        
        $reflection = new \ReflectionProperty($collection, 'items');
        $reflection->setAccessible(true);
        $reflection->setValue($collection, [$currencyArray]);
        
        $result = $collection->getArray();
        
        $this->assertSame($currencyArray, $result);
    }
}
