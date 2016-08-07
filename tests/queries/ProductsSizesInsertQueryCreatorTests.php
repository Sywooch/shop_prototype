<?php

namespace app\queries;

use app\tests\{MockObject,
    MockModel};
use app\queries\ProductsSizesInsertQueryCreator;

/**
 * Тестирует класс app\queries\ProductsSizesInsertQueryCreator
 */
class ProductsSizesInsertQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_id = 1;
    
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetInsertQuery()
    {
        $mockObject = new MockObject([
            'tableName'=>'products_sizes',
            'fields'=>['id_products', 'id_sizes'],
            'objectsArray'=>[
                new MockModel([
                    'id_products'=>self::$_id,
                    'id_sizes'=>self::$_id,
                ]),
            ],
        ]);
        
        $queryCreator = new ProductsSizesInsertQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'INSERT INTO {{products_sizes}} (id_products,id_sizes) VALUES (:0_id_products,:0_id_sizes)';
        
        $this->assertEquals($query, $mockObject->query);
    }
}
