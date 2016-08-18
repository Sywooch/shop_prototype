<?php

namespace app\tests\queries;

use app\tests\MockObject;
use app\queries\ProductsBrandsByIdBrandsQueryCreator;

/**
 * Тестирует класс app\queries\ProductsBrandsByIdBrandsQueryCreator
 */
class ProductsBrandsByIdBrandsQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetSelectQuery()
    {
        $mockObject = new MockObject([
            'tableName'=>'products_brands',
            'fields'=>['id_products', 'id_brands'],
        ]);
        
        $queryCreator = new ProductsBrandsByIdBrandsQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'SELECT [[products_brands.id_products]],[[products_brands.id_brands]] FROM {{products_brands}} WHERE [[products_brands.id_brands]]=:id_brands';
        
        $this->assertEquals($query, $mockObject->query);
    }
}
