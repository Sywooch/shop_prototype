<?php

namespace app\tests\queries;

use app\tests\{MockObject, 
    MockModel};
use app\queries\ProductsBrandsDeleteQueryCreator;

/**
 * Тестирует класс app\queries\ProductsBrandsDeleteQueryCreator
 */
class ProductsBrandsDeleteQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_id = 1;
    
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetDeleteQuery()
    {
        $mockObject = new MockObject([
            'tableName'=>'products_brands',
            'objectsArray'=>[
                new MockModel(['id_products'=>self::$_id]),
                new MockModel(['id_products'=>self::$_id]),
            ],
        ]);
        
        $queryCreator = new ProductsBrandsDeleteQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'DELETE FROM {{products_brands}} WHERE [[products_brands.id_products]] IN (:0_id_products,:1_id_products)';
        
        $this->assertEquals($query, $mockObject->query);
    }
}
