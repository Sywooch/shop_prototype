<?php

namespace app\queries;

use app\tests\DbManager;
use app\tests\{MockObject,
    MockModel};
use app\queries\InsertCategoriesQueryCreator;

/**
 * Тестирует класс app\queries\InsertCategoriesQueryCreator
 */
class InsertCategoriesQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_name = 'Очки';
    private static $_seocode = 'glasses';
    
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
            'objectsArray'=>[
                new MockModel([
                    'name'=>self::$_name, 
                    'seocode'=>self::$_seocode
                ])
            ],
        ]);
        
        $queryCreator = new InsertCategoriesQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = "INSERT INTO `categories` (`name`, `seocode`) VALUES ('" . self::$_name . "', '" . self::$_seocode . "')";
        
        $this->assertEquals($query, $mockObject->query);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
