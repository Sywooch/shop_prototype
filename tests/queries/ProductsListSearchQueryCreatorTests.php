<?php

namespace app\tests\queries;

use app\queries\ProductsListSearchQueryCreator;
use app\mappers\ProductsListMapper;

/**
 * Тестирует класс app\queries\ProductsListSearchQueryCreator
 */
class ProductsListSearchQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_config = [
        'tableName'=>'products',
        'fields'=>['id', 'code', 'name', 'description', 'price', 'images'],
        'otherTablesFields'=>[
            ['table'=>'categories', 'fields'=>[['field'=>'seocode', 'as'=>'categories']]],
            ['table'=>'subcategory', 'fields'=>[['field'=>'seocode', 'as'=>'subcategory']]],
        ],
        'orderByField'=>'date'
    ];
    
    /**
     * Тестирует создание строки SQL запроса с фильтром по параметрам ProductsListSearchQueryCreator::getSelectQuery()
     */
    public function testGetSelectQuery()
    {
        $_GET = ['search'=>'пиджак'];
        
        $productsListMapper = new ProductsListMapper(self::$_config);
        $productsListMapper->visit(new ProductsListSearchQueryCreator());
        
        $query = 'SELECT [[products.id]],[[products.code]],[[products.name]],[[products.description]],[[products.price]],[[products.images]],[[categories.seocode]] AS [[categories]],[[subcategory.seocode]] AS [[subcategory]] FROM {{products}} JOIN {{categories}} ON [[products.id_categories]]=[[categories.id]] JOIN {{subcategory}} ON [[products.id_subcategory]]=[[subcategory.id]] WHERE [[products.description]] LIKE :search ORDER BY [[products.date]] DESC LIMIT 0, 20';
        
        $this->assertEquals($query, $productsListMapper->query);
    }
}
