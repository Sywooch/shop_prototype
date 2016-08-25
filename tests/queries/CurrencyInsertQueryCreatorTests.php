<?php

namespace app\queries;

use app\tests\{MockObject,
    MockModel};
use app\queries\CurrencyInsertQueryCreator;

/**
 * Тестирует класс app\queries\CurrencyInsertQueryCreator
 */
class CurrencyInsertQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_currency = 'UAH';
    private static $_exchange_rate = '27.05698';
    private static $_main = true;
    
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetInsertQuery()
    {
        $mockObject = new MockObject([
            'tableName'=>'currency',
            'fields'=>['currency', 'exchange_rate', 'main'],
            'objectsArray'=>[
                new MockModel([
                    'currency'=>self::$_currency,
                    'exchange_rate'=>self::$_exchange_rate,
                    'main'=>self::$_main,
                ]),
            ],
        ]);
        
        $queryCreator = new CurrencyInsertQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'INSERT INTO {{currency}} (currency,exchange_rate,main) VALUES (:0_currency,:0_exchange_rate,:0_main)';
        
        $this->assertEquals($query, $mockObject->query);
    }
}
