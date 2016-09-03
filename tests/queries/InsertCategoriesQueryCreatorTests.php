<?php

namespace app\queries;

use app\tests\{DbManager,
    MockObject};
use app\queries\InsertCategoriesQueryCreator;

/**
 * Тестирует класс app\queries\InsertCategoriesQueryCreator
 */
class InsertCategoriesQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_params = [['Очки', 'glasses']];
    
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
            'tableName'=>'categories',
            'fields'=>['name', 'seocode'],
            'params'=>self::$_params
        ]);
        
        $queryCreator = new InsertCategoriesQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = "INSERT INTO `categories` (`name`, `seocode`) VALUES ('" . implode("', '", self::$_params[0]) . "')";
        
        $this->assertEquals($query, $mockObject->execute->getRawSql());
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
