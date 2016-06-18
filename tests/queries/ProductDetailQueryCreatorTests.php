<?php

namespace app\tests\queries;

use app\tests\MockObject;
use app\queries\ProductDetailQueryCreator;

/**
 * Тестирует класс app\queries\ProductDetailQueryCreator
 */
class ProductDetailQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetSelectQuery()
    {
        $mockObject = new MockObject([
            'tableName'=>'products',
            'fields'=>['id', 'code', 'name', 'description', 'price', 'images'],
        ]);
        
        $queryCreator = new ProductDetailQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'SELECT [[products.id]],[[products.code]],[[products.name]],[[products.description]],[[products.price]],[[products.images]] FROM {{products}} WHERE [[products.id]]=:id';
        
        $this->assertEquals($query, $mockObject->query);
    }
}
