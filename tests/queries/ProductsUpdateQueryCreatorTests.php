<?php

namespace app\queries;

use app\tests\{DbManager,
    MockObject};
use app\queries\ProductsUpdateQueryCreator;

/**
 * Тестирует класс app\queries\ProductsUpdateQueryCreator
 */
class ProductsUpdateQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_params = [[2, 1456102321, 'RU-234', 'John', 'Description', 'Short description', 894.78, 'images/', 8, 2, 1]];
    
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
            'fields'=>['id', 'date', 'code', 'name', 'description', 'short_description', 'price', 'images', 'id_categories', 'id_subcategory', 'active'],
            'params'=>self::$_params
        ]);
        
        $queryCreator = new ProductsUpdateQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = "INSERT INTO `products` (`id`, `date`, `code`, `name`, `description`, `short_description`, `price`, `images`, `id_categories`, `id_subcategory`, `active`) VALUES (" . self::$_params[0][0] . ", '" . self::$_params[0][1] . "', '" . self::$_params[0][2] . "', '" . self::$_params[0][3] . "', '" . self::$_params[0][4] . "', '" . self::$_params[0][5] . "', '" . self::$_params[0][6] . "', '" . self::$_params[0][7] . "', " . self::$_params[0][8] . ', ' . self::$_params[0][9] . ', ' . self::$_params[0][10] . ") ON DUPLICATE KEY UPDATE `date`=VALUES(`date`), `code`=VALUES(`code`), `name`=VALUES(`name`), `description`=VALUES(`description`), `short_description`=VALUES(`short_description`), `price`=VALUES(`price`), `images`=VALUES(`images`), `id_categories`=VALUES(`id_categories`), `id_subcategory`=VALUES(`id_subcategory`), `active`=VALUES(`active`)";
        
        $this->assertEquals($query, $mockObject->execute->getRawSql());
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
