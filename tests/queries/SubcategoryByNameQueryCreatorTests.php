<?php

namespace app\tests\queries;

use app\tests\{DbManager,
    MockModel,
    MockObject};
use app\queries\SubcategoryByNameQueryCreator;

/**
 * Тестирует класс app\queries\SubcategoryByNameQueryCreator
 */
class SubcategoryByNameQueryCreatorTests extends \PHPUnit_Framework_TestCase
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
            'tableName'=>'subcategory',
            'fields'=>['id', 'name', 'seocode', 'id_categories'],
            'model'=>new MockModel(['name'=>self::$_name])
        ]);
        
        $queryCreator = new SubcategoryByNameQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = "SELECT `subcategory`.`id`, `subcategory`.`name`, `subcategory`.`seocode`, `subcategory`.`id_categories` FROM `subcategory` WHERE `subcategory`.`name`='" . self::$_name . "'";
        
        $this->assertEquals($query, $mockObject->query->createCommand()->getRawSql());
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
