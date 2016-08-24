<?php

namespace app\queries;

use app\tests\{MockObject,
    MockModel};
use app\queries\ProductsUpdateQueryCreator;

/**
 * Тестирует класс app\queries\ProductsUpdateQueryCreator
 */
class ProductsUpdateQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_some = 1;
    
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetInsertQuery()
    {
        $mockObject = new MockObject([
            'tableName'=>'products',
            'fields'=>['id', 'date', 'code', 'name', 'description', 'short_description', 'price', 'images', 'id_categories', 'id_subcategory', 'active'],
            'objectsArray'=>[
                new MockModel([
                    'id'=>self::$_some, 
                    'date'=>self::$_some, 
                    'code'=>self::$_some, 
                    'name'=>self::$_some, 
                    'description'=>self::$_some, 
                    'short_description'=>self::$_some, 
                    'price'=>self::$_some, 
                    'images'=>self::$_some, 
                    'id_categories'=>self::$_some, 
                    'id_subcategory'=>self::$_some, 
                    'active'=>self::$_some,
                ]),
            ],
        ]);
        
        $queryCreator = new ProductsUpdateQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'INSERT INTO {{products}} (id,date,code,name,description,short_description,price,images,id_categories,id_subcategory,active) VALUES (:0_id,:0_date,:0_code,:0_name,:0_description,:0_short_description,:0_price,:0_images,:0_id_categories,:0_id_subcategory,:0_active) ON DUPLICATE KEY UPDATE date=VALUES(date),code=VALUES(code),name=VALUES(name),description=VALUES(description),short_description=VALUES(short_description),price=VALUES(price),images=VALUES(images),id_categories=VALUES(id_categories),id_subcategory=VALUES(id_subcategory),active=VALUES(active)';
        
        $this->assertEquals($query, $mockObject->query);
    }
}
