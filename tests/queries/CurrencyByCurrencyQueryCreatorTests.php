<?php

namespace app\tests\queries;

use app\tests\{DbManager,
    MockModel,
    MockObject};
use app\queries\CurrencyByCurrencyQueryCreator;

/**
 * Тестирует класс app\queries\CurrencyByCurrencyQueryCreator
 */
class CurrencyByCurrencyQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_currency = 'USD';
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager();
        self::$_dbClass->createDb();
    }
    
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetSelectQuery()
    {
        $mockObject = new MockObject([
            'tableName'=>'currency',
            'fields'=>['id', 'currency', 'exchange_rate', 'main'],
            'model'=>new MockModel(['currency'=>self::$_currency])
        ]);
        
        $queryCreator = new CurrencyByCurrencyQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = "SELECT `currency`.`id`, `currency`.`currency`, `currency`.`exchange_rate`, `currency`.`main` FROM `currency` WHERE `currency`.`currency`='" . self::$_currency . "'";
        
        $this->assertEquals($query, $mockObject->query->createCommand()->getRawSql());
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
