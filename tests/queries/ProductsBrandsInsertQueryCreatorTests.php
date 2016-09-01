<?php

namespace app\queries;

use app\tests\{DbManager,
    MockObject,
    MockModel};
use app\queries\ProductsBrandsInsertQueryCreator;

/**
 * Тестирует класс app\queries\ProductsBrandsInsertQueryCreator
 */
class ProductsBrandsInsertQueryCreatorTests extends \PHPUnit_Framework_TestCase
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
            'tableName'=>'products_brands',
            'fields'=>['id_products', 'id_brands'],
            'objectsArray'=>[
                new MockModel([
                    'id_products'=>self::$_id,
                    'id_brands'=>self::$_id,
                ]),
            ],
        ]);
        
        $queryCreator = new ProductsBrandsInsertQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = "INSERT INTO `products_brands` (`id_products`, `id_brands`) VALUES (" . self::$_id . ', ' . self::$_id . ")";
        
        $this->assertEquals($query, $mockObject->execute->getRawSql());
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
