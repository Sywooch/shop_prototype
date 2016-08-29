<?php

namespace app\tests\queries;

use app\tests\{DbManager,
    MockModel,
    MockObject};
use app\queries\CategoriesBySeocodeQueryCreator;

/**
 * Тестирует класс app\queries\CategoriesBySeocodeQueryCreator
 */
class CategoriesBySeocodeQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_seocode = 'somecode';
    
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
            'model'=>new MockModel(['seocode'=>self::$_seocode])
        ]);
        
        $queryCreator = new CategoriesBySeocodeQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = "SELECT `categories`.`id`, `categories`.`name`, `categories`.`seocode` FROM `categories` WHERE `categories`.`seocode`='" . self::$_seocode . "'";
        
        $this->assertEquals($query, $mockObject->query->createCommand()->getRawSql());
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
