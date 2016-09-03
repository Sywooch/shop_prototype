<?php

namespace app\queries;

use app\tests\{DbManager,
    MockObject};
use app\queries\ProductsBrandsInsertQueryCreator;

/**
 * Тестирует класс app\queries\ProductsBrandsInsertQueryCreator
 */
class ProductsBrandsInsertQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_params = [[12, 88], [5, 90]];
    
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
            'tableName'=>'products_brands',
            'fields'=>['id_products', 'id_brands'],
            'params'=>self::$_params
        ]);
        
        $queryCreator = new ProductsBrandsInsertQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = "INSERT INTO `products_brands` (`id_products`, `id_brands`) VALUES (" . implode(', ', self::$_params[0]) . "), (" . implode(', ', self::$_params[1]) . ")";
        
        $this->assertEquals($query, $mockObject->execute->getRawSql());
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
