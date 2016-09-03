<?php

namespace app\queries;

use app\tests\{DbManager,
    MockObject};
use app\queries\CurrencyInsertQueryCreator;

/**
 * Тестирует класс app\queries\CurrencyInsertQueryCreator
 */
class CurrencyInsertQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_params = [['UAH', 27.05698, true]];
    
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
            'params'=>self::$_params
        ]);
        
        $queryCreator = new CurrencyInsertQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = "INSERT INTO `currency` (`currency`, `exchange_rate`, `main`) VALUES ('" . implode("', '", array_slice(self::$_params[0], 0, -1)) . "', " . array_pop(self::$_params[0]) . ")";
        
        $this->assertEquals($query, $mockObject->execute->getRawSql());
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
