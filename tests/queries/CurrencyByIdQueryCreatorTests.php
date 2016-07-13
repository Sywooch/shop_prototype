<?php

namespace app\tests\queries;

use app\tests\MockObject;
use app\queries\CurrencyByIdQueryCreator;

/**
 * Тестирует класс app\queries\CurrencyByIdQueryCreator
 */
class CurrencyByIdQueryCreatorTests extends \PHPUnit_Framework_TestCase
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
        
        $queryCreator = new CurrencyByIdQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'SELECT [[currency.id]],[[currency.currency]],[[currency.exchange_rate]],[[currency.main]] FROM {{currency}} WHERE [[currency.id]]=:id';
        
        $this->assertEquals($query, $mockObject->query);
    }
}
