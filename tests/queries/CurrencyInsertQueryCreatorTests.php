<?php

namespace app\queries;

use app\tests\{DbManager,
    MockObject,
    MockModel};
use app\queries\CurrencyInsertQueryCreator;

/**
 * Тестирует класс app\queries\CurrencyInsertQueryCreator
 */
class CurrencyInsertQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_currency = 'UAH';
    private static $_exchange_rate = '27.05698';
    private static $_main = true;
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager();
        self::$_dbClass->createDb();
    }
    
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
        
        $query = "INSERT INTO `currency` (`currency`, `exchange_rate`, `main`) VALUES ('" . self::$_currency . "', '" . self::$_exchange_rate . "', " . self::$_main . ")";
        
        $this->assertEquals($query, $mockObject->execute->getRawSql());
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
