<?php

namespace app\queries;

use app\tests\{DbManager,
    MockObject};
use app\queries\SubcategoryInsertQueryCreator;

/**
 * Тестирует класс app\queries\SubcategoryInsertQueryCreator
 */
class SubcategoryInsertQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_params = [['Очки', 'glasses', 3]];
    
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
            'tableName'=>'subcategory',
            'fields'=>['name', 'seocode', 'id_categories'],
            'params'=>self::$_params
        ]);
        
        $queryCreator = new SubcategoryInsertQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = "INSERT INTO `subcategory` (`name`, `seocode`, `id_categories`) VALUES ('" . implode("', '", array_slice(self::$_params[0], 0, -1)) . "', " . array_pop(self::$_params[0]) . ")";
        
        $this->assertEquals($query, $mockObject->execute->getRawSql());
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
