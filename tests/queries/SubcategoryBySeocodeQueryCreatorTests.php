<?php

namespace app\tests\queries;

use app\tests\{DbManager,
    MockModel,
    MockObject};
use app\queries\SubcategoryBySeocodeQueryCreator;

/**
 * Тестирует класс app\queries\SubcategoryBySeocodeQueryCreator
 */
class SubcategoryBySeocodeQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_seocode = 'boots';
    
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
            'model'=>new MockModel(['seocode'=>self::$_seocode])
        ]);
        
        $queryCreator = new SubcategoryBySeocodeQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = "SELECT `subcategory`.`id`, `subcategory`.`name`, `subcategory`.`seocode`, `subcategory`.`id_categories` FROM `subcategory` WHERE `subcategory`.`seocode`='" . self::$_seocode . "'";
        
        $this->assertEquals($query, $mockObject->query->createCommand()->getRawSql());
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
