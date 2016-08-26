<?php

namespace app\queries;

use app\tests\{MockObject,
    MockModel};
use app\queries\CurrencyUpdateQueryCreator;

/**
 * Тестирует класс app\queries\CurrencyUpdateQueryCreator
 */
class CurrencyUpdateQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_some = 1;
    
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetInsertQuery()
    {
        $mockObject = new MockObject([
            'tableName'=>'currency',
            'fields'=>['id', 'currency', 'exchange_rate', 'main'],
            'objectsArray'=>[
                new MockModel([
                    'id'=>self::$_some, 
                    'currency'=>self::$_some,
                    'exchange_rate'=>self::$_some,
                    'main'=>self::$_some,
                ]),
            ],
        ]);
        
        $queryCreator = new CurrencyUpdateQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'INSERT INTO {{currency}} (id,currency,exchange_rate,main) VALUES (:0_id,:0_currency,:0_exchange_rate,:0_main) ON DUPLICATE KEY UPDATE currency=VALUES(currency),exchange_rate=VALUES(exchange_rate),main=VALUES(main)';
        
        $this->assertEquals($query, $mockObject->query);
    }
}
