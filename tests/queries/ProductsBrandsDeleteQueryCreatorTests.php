<?php

namespace app\tests\queries;

use app\tests\MockObject;
use app\queries\ProductsBrandsDeleteQueryCreator;

/**
 * Тестирует класс app\queries\ProductsBrandsDeleteQueryCreator
 */
class ProductsBrandsDeleteQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_params = [78, 23, 45];
    
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetDeleteQuery()
    {
        $mockObject = new MockObject([
            'tableName'=>'products_brands',
            'params'=>self::$_params
        ]);
        
        $queryCreator = new ProductsBrandsDeleteQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = "DELETE FROM `products_brands` WHERE `id_products` IN (" . implode(', ', self::$_params) . ")";
        
        $this->assertEquals($query, $mockObject->execute->getRawSql());
    }
}
