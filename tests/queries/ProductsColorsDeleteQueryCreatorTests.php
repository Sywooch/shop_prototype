<?php

namespace app\tests\queries;

use app\tests\MockObject;
use app\queries\ProductsColorsDeleteQueryCreator;

/**
 * Тестирует класс app\queries\ProductsColorsDeleteQueryCreator
 */
class ProductsColorsDeleteQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_params = [56, 11, 45];
    
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetDeleteQuery()
    {
        $mockObject = new MockObject([
            'tableName'=>'products_colors',
            'params'=>self::$_params
        ]);
        
        $queryCreator = new ProductsColorsDeleteQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = "DELETE FROM `products_colors` WHERE `id_products` IN (" . implode(', ', self::$_params) . ")";
        
        $this->assertEquals($query, $mockObject->execute->getRawSql());
    }
}
