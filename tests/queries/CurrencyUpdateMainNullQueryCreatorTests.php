<?php

namespace app\queries;

use app\tests\{MockObject,
    MockModel};
use app\queries\CurrencyUpdateMainNullQueryCreator;

/**
 * Тестирует класс app\queries\CurrencyUpdateMainNullQueryCreator
 */
class CurrencyUpdateMainNullQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetInsertQuery()
    {
        $mockObject = new MockObject([
            'tableName'=>'currency',
            'fields'=>['main'],
        ]);
        
        $queryCreator = new CurrencyUpdateMainNullQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'UPDATE {{currency}} SET [[main]]=:main';
        
        $this->assertEquals($query, $mockObject->query);
    }
}
