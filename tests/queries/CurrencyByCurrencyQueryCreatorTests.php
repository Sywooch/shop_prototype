<?php

namespace app\tests\queries;

use app\tests\MockObject;
use app\queries\CurrencyByCurrencyQueryCreator;

/**
 * Тестирует класс app\queries\CurrencyByCurrencyQueryCreator
 */
class CurrencyByCurrencyQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetSelectQuery()
    {
        $mockObject = new MockObject([
            'tableName'=>'currency',
            'fields'=>['id', 'currency', 'exchange_rate', 'main'],
        ]);
        
        $queryCreator = new CurrencyByCurrencyQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'SELECT [[currency.id]],[[currency.currency]],[[currency.exchange_rate]],[[currency.main]] FROM {{currency}} WHERE [[currency.currency]]=:currency';
        
        $this->assertEquals($query, $mockObject->query);
    }
}
