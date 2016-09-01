<?php

namespace app\queries;

use app\tests\{DbManager,
    MockObject,
    MockModel};
use app\queries\ProductsSizesInsertQueryCreator;

/**
 * Тестирует класс app\queries\ProductsSizesInsertQueryCreator
 */
class ProductsSizesInsertQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_id = 2;
    
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
            'tableName'=>'products_sizes',
            'fields'=>['id_products', 'id_sizes'],
            'objectsArray'=>[
                new MockModel([
                    'id_products'=>self::$_id,
                    'id_sizes'=>self::$_id,
                ]),
            ],
        ]);
        
        $queryCreator = new ProductsSizesInsertQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = "INSERT INTO `products_sizes` (`id_products`, `id_sizes`) VALUES (" . self::$_id . ', ' . self::$_id . ")";
        
        $this->assertEquals($query, $mockObject->execute->getRawSql());
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
