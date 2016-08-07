<?php

namespace app\queries;

use app\tests\{MockObject,
    MockModel};
use app\queries\ProductsBrandsInsertQueryCreator;

/**
 * Тестирует класс app\queries\ProductsBrandsInsertQueryCreator
 */
class ProductsBrandsInsertQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_id = 1;
    
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
        
        $query = 'INSERT INTO {{products_brands}} (id_products,id_brands) VALUES (:0_id_products,:0_id_brands)';
        
        $this->assertEquals($query, $mockObject->query);
    }
}
