<?php

namespace app\queries;

use app\tests\{DbManager,
    MockObject};
use app\queries\BrandsUpdateQueryCreator;

/**
 * Тестирует класс app\queries\BrandsUpdateQueryCreator
 */
class BrandsUpdateQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_params = [[3, 'Yusimitomoro']];
    
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
            'fields'=>['id', 'brand'],
            'params'=>self::$_params
        ]);
        
        $queryCreator = new BrandsUpdateQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = "INSERT INTO `brands` (`id`, `brand`) VALUES (" . self::$_params[0][0] . ", '" . self::$_params[0][1] . "') ON DUPLICATE KEY UPDATE `brand`=VALUES(`brand`)";
        
        $this->assertEquals($query, $mockObject->execute->getRawSql());
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
