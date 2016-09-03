<?php

namespace app\queries;

use app\tests\{DbManager,
    MockObject};
use app\queries\CurrencyUpdateMainNullQueryCreator;

/**
 * Тестирует класс app\queries\CurrencyUpdateMainNullQueryCreator
 */
class CurrencyUpdateMainNullQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager();
        self::$_dbClass->createDb();
        
        \Yii::$app->filters->clean();
        \Yii::$app->filters->cleanOther();
    }
    
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetInsertQuery()
    {
        $mockObject = new MockObject([
            'tableName'=>'currency',
        ]);
        
        $queryCreator = new CurrencyUpdateMainNullQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = "UPDATE `currency` SET `main`=0";
        
        $this->assertEquals($query, $mockObject->execute->getRawSql());
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
