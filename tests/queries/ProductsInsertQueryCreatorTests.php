<?php

namespace app\queries;

use app\tests\MockObject;
use app\tests\MockModel;
use app\queries\ProductsInsertQueryCreator;

/**
 * Тестирует класс app\queries\ProductsInsertQueryCreator
 */
class ProductsInsertQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_date = 'date';
    private static $_code = 'code';
    private static $_name = 'name';
    private static $_description = 'description';
    private static $_price = 14.45;
    private static $_images = 'images';
    private static $_id_categories = 1;
    private static $_id_subcategory = 1;
    
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetInsertQuery()
    {
        $mockObject = new MockObject([
            'tableName'=>'products',
            'fields'=>['date', 'code', 'name', 'description', 'price', 'images', 'id_categories', 'id_subcategory'],
            'objectsArray'=>[
                new MockModel([
                    'date'=>self::$_date,
                    'code'=>self::$_code,
                    'name'=>self::$_name,
                    'description'=>self::$_description,
                    'price'=>self::$_price,
                    'images'=>self::$_images,
                    'id_categories'=>self::$_id_categories,
                    'id_subcategory'=>self::$_id_subcategory,
                ]),
            ],
        ]);
        
        $queryCreator = new ProductsInsertQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'INSERT INTO {{products}} (date,code,name,description,price,images,id_categories,id_subcategory) VALUES (:0_date,:0_code,:0_name,:0_description,:0_price,:0_images,:0_id_categories,:0_id_subcategory)';
        
        $this->assertEquals($query, $mockObject->query);
    }
}
