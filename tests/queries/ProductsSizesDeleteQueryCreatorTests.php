<?php

namespace app\tests\queries;

use app\tests\{MockObject, 
    MockModel};
use app\queries\ProductsSizesDeleteQueryCreator;

/**
 * Тестирует класс app\queries\ProductsSizesDeleteQueryCreator
 */
class ProductsSizesDeleteQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_id = 1;
    
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetDeleteQuery()
    {
        $mockObject = new MockObject([
            'tableName'=>'products_sizes',
            'objectsArray'=>[
                new MockModel(['id_products'=>self::$_id]),
                new MockModel(['id_products'=>self::$_id]),
            ],
        ]);
        
        $queryCreator = new ProductsSizesDeleteQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'DELETE FROM {{products_sizes}} WHERE [[products_sizes.id_products]] IN (:0_id_products,:1_id_products)';
        
        $this->assertEquals($query, $mockObject->query);
    }
}
