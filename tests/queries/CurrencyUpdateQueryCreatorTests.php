<?php

namespace app\queries;

use app\tests\{DbManager,
    MockObject};
use app\queries\CurrencyUpdateQueryCreator;

/**
 * Тестирует класс app\queries\CurrencyUpdateQueryCreator
 */
class CurrencyUpdateQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_params = [[1, 'UAH', 27.8954, true]];
    
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
            'fields'=>['id', 'currency', 'exchange_rate', 'main'],
            'params'=>self::$_params
        ]);
        
        $queryCreator = new CurrencyUpdateQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = "INSERT INTO `currency` (`id`, `currency`, `exchange_rate`, `main`) VALUES (" . self::$_params[0][0] . ", '" . self::$_params[0][1] . "', '" . self::$_params[0][2] . "', " . self::$_params[0][3] . ") ON DUPLICATE KEY UPDATE `currency`=VALUES(`currency`), `exchange_rate`=VALUES(`exchange_rate`), `main`=VALUES(`main`)";
        
        $this->assertEquals($query, $mockObject->execute->getSql());
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
