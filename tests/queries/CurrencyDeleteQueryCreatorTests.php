<?php

namespace app\tests\queries;

use app\tests\{MockObject, 
    MockModel};
use app\queries\CurrencyDeleteQueryCreator;

/**
 * Тестирует класс app\queries\CurrencyDeleteQueryCreator
 */
class CurrencyDeleteQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_id = 1;
    
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetDeleteQuery()
    {
        $mockObject = new MockObject([
            'tableName'=>'currency',
            'objectsArray'=>[
                new MockModel(['id'=>self::$_id]),
                new MockModel(['id'=>self::$_id]),
            ],
        ]);
        
        $queryCreator = new CurrencyDeleteQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'DELETE FROM {{currency}} WHERE [[currency.id]] IN (:0_id,:1_id)';
        
        $this->assertEquals($query, $mockObject->query);
    }
}
