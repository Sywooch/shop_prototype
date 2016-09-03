<?php

namespace app\queries;

use app\tests\{DbManager,
    MockObject,
    MockModel};
use app\queries\ProductsColorsInsertQueryCreator;

/**
 * Тестирует класс app\queries\ProductsColorsInsertQueryCreator
 */
class ProductsColorsInsertQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_params = [[2, 34], [13, 32]];
    
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
            'tableName'=>'products_colors',
            'fields'=>['id_products', 'id_colors'],
            'params'=>self::$_params
        ]);
        
        $queryCreator = new ProductsColorsInsertQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = "INSERT INTO `products_colors` (`id_products`, `id_colors`) VALUES (" . implode(', ', self::$_params[0]) . "), (" . implode(', ', self::$_params[1]) . ")";
        
        $this->assertEquals($query, $mockObject->execute->getRawSql());
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
