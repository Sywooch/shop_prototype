<?php

namespace app\tests\queries;

use app\tests\MockObject;
use app\queries\ProductsByIdQueryCreator;

/**
 * Тестирует класс app\queries\ProductsByIdQueryCreator
 */
class ProductsByIdQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetSelectQuery()
    {
        $mockObject = new MockObject([
            'tableName'=>'products',
            'fields'=>['id', 'date', 'code', 'name', 'description', 'short_description', 'price', 'images', 'id_categories', 'id_subcategory'],
        ]);
        
        $queryCreator = new ProductsByIdQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'SELECT [[products.id]],[[products.date]],[[products.code]],[[products.name]],[[products.description]],[[products.short_description]],[[products.price]],[[products.images]],[[products.id_categories]],[[products.id_subcategory]] FROM {{products}} WHERE [[products.id]]=:id';
        
        $this->assertEquals($query, $mockObject->query);
    }
}
