<?php

namespace app\tests\queries;

use app\tests\MockObject;
use app\queries\ProductsDeleteQueryCreator;

/**
 * Тестирует класс app\queries\ProductsDeleteQueryCreator
 */
class ProductsDeleteQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_params = [67, 13];
    
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetDeleteQuery()
    {
        $mockObject = new MockObject([
            'tableName'=>'products',
            'params'=>self::$_params
        ]);
        
        $queryCreator = new ProductsDeleteQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = "DELETE FROM `products` WHERE `id` IN (" . implode(', ', self::$_params) . ")";
        
        $this->assertEquals($query, $mockObject->execute->getRawSql());
    }
}
