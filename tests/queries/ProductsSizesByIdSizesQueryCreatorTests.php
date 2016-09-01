<?php

namespace app\tests\queries;

use app\tests\{MockModel,
    MockObject};
use app\queries\ProductsSizesByIdSizesQueryCreator;

/**
 * Тестирует класс app\queries\ProductsSizesByIdSizesQueryCreator
 */
class ProductsSizesByIdSizesQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_id = 77;
    
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetSelectQuery()
    {
        $mockObject = new MockObject([
            'tableName'=>'products_sizes',
            'fields'=>['id_products', 'id_sizes'],
            'model'=>new MockModel(['id'=>self::$_id])
        ]);
        
        $queryCreator = new ProductsSizesByIdSizesQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = "SELECT `products_sizes`.`id_products`, `products_sizes`.`id_sizes` FROM `products_sizes` WHERE `products_sizes`.`id_sizes`=" . self::$_id;
        
        $this->assertEquals($query, $mockObject->query->createCommand()->getRawSql());
    }
}
