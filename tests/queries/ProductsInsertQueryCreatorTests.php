<?php

namespace app\queries;

use app\tests\{DbManager,
    MockObject};
use app\queries\ProductsInsertQueryCreator;

/**
 * Тестирует класс app\queries\ProductsInsertQueryCreator
 */
class ProductsInsertQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_params = [[1456878254, 'HP-17897', 'John', 'Some description', 'Short description', 98.75, 'images/12345432', 23, 12]];
    
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
            'tableName'=>'products',
            'fields'=>['date', 'code', 'name', 'description', 'short_description', 'price', 'images', 'id_categories', 'id_subcategory'],
            'params'=>self::$_params
        ]);
        
        $queryCreator = new ProductsInsertQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = "INSERT INTO `products` (`date`, `code`, `name`, `description`, `short_description`, `price`, `images`, `id_categories`, `id_subcategory`) VALUES ('" . implode("', '", array_slice(self::$_params[0], 0, -2)) . "', " . implode(', ', array_slice(self::$_params[0], -2)) . ")";
        
        $this->assertEquals($query, $mockObject->execute->getRawSql());
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
