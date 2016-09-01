<?php

namespace app\tests\queries;

use app\tests\MockObject;
use app\queries\CurrencyDeleteQueryCreator;

/**
 * Тестирует класс app\queries\CurrencyDeleteQueryCreator
 */
class CurrencyDeleteQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_params = [1, 2];
    
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetDeleteQuery()
    {
        $mockObject = new MockObject([
            'tableName'=>'currency',
            'params'=>self::$_params
        ]);
        
        $queryCreator = new CurrencyDeleteQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = "DELETE FROM `currency` WHERE `id` IN (" . implode(', ', self::$_params) . ")";
        
        $this->assertEquals($query, $mockObject->execute->getRawSql());
    }
}
