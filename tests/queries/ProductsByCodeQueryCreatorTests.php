<?php

namespace app\tests\queries;

use app\tests\MockObject;
use app\queries\ProductsByCodeQueryCreator;

/**
 * Тестирует класс app\queries\ProductsByCodeQueryCreator
 */
class ProductsByCodeQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetSelectQuery()
    {
        $mockObject = new MockObject([
            'tableName'=>'products',
            'fields'=>['id', 'date', 'code', 'name', 'description', 'price', 'images', 'id_categories', 'id_subcategory'],
        ]);
        
        $queryCreator = new ProductsByCodeQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'SELECT [[products.id]],[[products.date]],[[products.code]],[[products.name]],[[products.description]],[[products.price]],[[products.images]],[[products.id_categories]],[[products.id_subcategory]] FROM {{products}} WHERE [[products.code]]=:code';
        
        $this->assertEquals($query, $mockObject->query);
    }
}
