<?php

namespace app\tests\queries;

use app\queries\ProductDetailQueryCreator;
use app\mappers\ProductDetailMapper;

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
        $productMapper = new ProductDetailMapper([
                'tableName'=>'products',
                'fields'=>['id', 'code', 'name', 'description', 'price', 'images'],
            ]);
        $productMapper->visit(new ProductDetailQueryCreator());
        
        $query = 'SELECT [[products.id]],[[products.code]],[[products.name]],[[products.description]],[[products.price]],[[products.images]] FROM {{products}} WHERE [[products.id]]=:id';
        
        $this->assertEquals($query, $productMapper->query);
    }
}
