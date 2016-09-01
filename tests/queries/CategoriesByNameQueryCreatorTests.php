<?php

namespace app\tests\queries;

use app\tests\{DbManager,
    MockModel,
    MockObject};
use app\queries\CategoriesByNameQueryCreator;

/**
 * Тестирует класс app\queries\CategoriesByNameQueryCreator
 */
class CategoriesByNameQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_name = 'some';
    
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
            'tableName'=>'categories',
            'fields'=>['id', 'name', 'seocode'],
            'model'=>new MockModel(['name'=>self::$_name])
        ]);
        
        $queryCreator = new CategoriesByNameQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = "SELECT `categories`.`id`, `categories`.`name`, `categories`.`seocode` FROM `categories` WHERE `categories`.`name`='" . self::$_name . "'";
        
        $this->assertEquals($query, $mockObject->query->createCommand()->getRawSql());
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
