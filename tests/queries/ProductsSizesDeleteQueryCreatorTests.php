<?php

namespace app\tests\queries;

use app\tests\MockObject;
use app\queries\ProductsSizesDeleteQueryCreator;

/**
 * Тестирует класс app\queries\ProductsSizesDeleteQueryCreator
 */
class ProductsSizesDeleteQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_params = [44, 54];
    
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetDeleteQuery()
    {
        $mockObject = new MockObject([
            'tableName'=>'products_sizes',
            'params'=>self::$_params
        ]);
        
        $queryCreator = new ProductsSizesDeleteQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = "DELETE FROM `products_sizes` WHERE `id_products` IN (" . implode(', ', self::$_params) . ")";
        
        $this->assertEquals($query, $mockObject->execute->getRawSql());
    }
}
