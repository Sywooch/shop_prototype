<?php

namespace app\tests\queries;

use app\tests\{DbManager,
    MockModel,
    MockObject};
use app\queries\BrandsByBrandQueryCreator;

/**
 * Тестирует класс app\queries\BrandsByBrandQueryCreator
 */
class BrandsByBrandQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_brand = 'some';
    
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
            'tableName'=>'brands',
            'fields'=>['id', 'brand'],
            'model'=>new MockModel(['brand'=>self::$_brand])
        ]);
        
        $queryCreator = new BrandsByBrandQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = "SELECT `brands`.`id`, `brands`.`brand` FROM `brands` WHERE `brands`.`brand`='" . self::$_brand . "'";
        
        $this->assertEquals($query, $mockObject->query->createCommand()->getRawSql());
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
