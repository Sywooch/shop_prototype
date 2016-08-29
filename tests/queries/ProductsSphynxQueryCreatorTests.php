<?php

namespace app\tests\queries;

use app\tests\{DbManager,
    MockObject};
use app\queries\ProductsSphynxQueryCreator;

/**
 * Тестирует класс app\queries\ProductsSphynxQueryCreator
 */
class ProductsSphynxQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_tableName = 'shop';
    private static $_fields = ['id'];
    private static $_searchPhrase = 'пиджак';
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager();
        self::$_dbClass->createDb();
    }
    
    /**
     * Тестирует создание строки SQL запроса с фильтром по параметру search ProductsSphynxQueryCreator::getSelectQuery()
     */
    public function testGetSelectQuery()
    {
        $_GET = ['search'=>self::$_searchPhrase];
        
        $mockObject = new MockObject([
            'tableName'=>self::$_tableName,
            'fields'=>self::$_fields
        ]);
        
        $queryCreator = new ProductsSphynxQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = "SELECT `shop`.`id` FROM `shop` WHERE MATCH('" .self::$_searchPhrase . "')";
        
        $this->assertEquals($query, $mockObject->query->createCommand()->getRawSql());
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
