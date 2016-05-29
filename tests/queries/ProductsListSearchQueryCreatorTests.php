<?php

namespace app\tests\queries;

use app\queries\ProductsListSearchQueryCreator;
use app\mappers\ProductsListMapper;

/**
 * Тестирует класс app\queries\ProductsListSearchQueryCreator
 */
class ProductsListSearchQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    /**
     * Тестирует создание строки SQL запроса с фильтром по параметрам ProductsListSearchQueryCreator::getSelectQuery()
     */
    public function testGetSelectQuery()
    {
        $_GET = ['search'=>'пиджак'];
        
        $productsMapper = new ProductsListMapper([
            'tableName'=>'products',
            'fields'=>['id', 'code', 'name', 'description', 'price', 'images'],
            'orderByField'=>'date',
        ]);
        $productsMapper->visit(new ProductsListSearchQueryCreator());
        
        $query = 'SELECT [[products.id]],[[products.code]],[[products.name]],[[products.description]],[[products.price]],[[products.images]] FROM {{products}} WHERE [[products.description]] LIKE :search ORDER BY [[products.date]] DESC LIMIT 0, 20';
        
        $this->assertEquals($query, $productsMapper->query);
    }
}
