<?php

namespace app\tests\queries;

use app\tests\MockObject;
use app\queries\ProductsByIdCategoriesQueryCreator;

/**
 * Тестирует класс app\queries\ProductsByIdCategoriesQueryCreator
 */
class ProductsByIdCategoriesQueryCreatorTests extends \PHPUnit_Framework_TestCase
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
        
        $queryCreator = new ProductsByIdCategoriesQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'SELECT [[products.id]],[[products.date]],[[products.code]],[[products.name]],[[products.description]],[[products.price]],[[products.images]],[[products.id_categories]],[[products.id_subcategory]] FROM {{products}} WHERE [[products.id_categories]]=:id_categories';
        
        $this->assertEquals($query, $mockObject->query);
    }
}
