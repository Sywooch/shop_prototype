<?php

namespace app\tests\queries;

use app\tests\MockObject;
use app\queries\CurrencyByMainQueryCreator;

/**
 * Тестирует класс app\queries\CurrencyByMainQueryCreator
 */
class CurrencyByMainQueryCreatorTests extends \PHPUnit_Framework_TestCase
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
        
        $queryCreator = new CurrencyByMainQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'SELECT [[currency.id]],[[currency.currency]],[[currency.exchange_rate]],[[currency.main]] FROM {{currency}} WHERE [[currency.main]]=:main';
        
        $this->assertEquals($query, $mockObject->query);
    }
}
