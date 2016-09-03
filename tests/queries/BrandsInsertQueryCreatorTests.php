<?php

namespace app\queries;

use app\tests\{DbManager,
    MockObject};
use app\queries\BrandsInsertQueryCreator;

/**
 * Тестирует класс app\queries\BrandsInsertQueryCreator
 */
class BrandsInsertQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_params = [['Midnight worker']];
    
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
            'tableName'=>'brands',
            'fields'=>['brand'],
            'params'=>self::$_params
        ]);
        
        $queryCreator = new BrandsInsertQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = "INSERT INTO `brands` (`brand`) VALUES ('" . self::$_params[0][0] . "')";
        
        $this->assertEquals($query, $mockObject->execute->getSql());
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
