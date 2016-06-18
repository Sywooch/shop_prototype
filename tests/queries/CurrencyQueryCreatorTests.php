<?php

namespace app\tests\queries;

use app\tests\MockObject;
use app\queries\CurrencyQueryCreator;

/**
 * Тестирует класс app\queries\CurrencyQueryCreator
 */
class CurrencyQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetSelectQuery()
    {
        $mockObject = new MockObject([
            'tableName'=>'currency',
            'fields'=>['id', 'currency'],
        ]);
        
        $queryCreator = new CurrencyQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'SELECT [[currency.id]],[[currency.currency]] FROM {{currency}}';
        
        $this->assertEquals($query, $mockObject->query);
    }
}
