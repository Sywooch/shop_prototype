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
     * Тестирует создание строки SQL запроса с фильтром по параметру search ProductsListSearchQueryCreator::getSelectQuery()
     */
    public function testGetSelectQuery()
    {
        $_GET = ['search'=>'пиджак'];
        
        $productsListMapper = new ProductsListMapper(self::$_config);
        $productsListMapper->visit(new ProductsListSearchQueryCreator());
        
        $query = 'SELECT [[products.id]],[[products.code]],[[products.name]],[[products.description]],[[products.price]],[[products.images]],[[categories.seocode]] AS [[categories]],[[subcategory.seocode]] AS [[subcategory]] FROM {{products}} JOIN {{categories}} ON [[products.id_categories]]=[[categories.id]] JOIN {{subcategory}} ON [[products.id_subcategory]]=[[subcategory.id]] WHERE [[products.description]] LIKE :search ORDER BY [[products.date]] DESC LIMIT 0, 20';
        
        $this->assertEquals($query, $productsListMapper->query);
    }
    
    /**
     * Тестирует создание строки SQL запроса с фильтром по параметру search ProductsListSearchQueryCreator::getSelectQuery(), 
     * и одному дополнительному фильтру ProductsListQueryCreator::addFilters()
     */
    public function testGetSelectAndFilterQuery()
    {
        $_GET = ['search'=>'пиджак', 'colors'=>'black'];
        
        $productsListMapper = new ProductsListMapper(self::$_config);
        $productsListMapper->visit(new ProductsListSearchQueryCreator());
        
        $query = 'SELECT [[products.id]],[[products.code]],[[products.name]],[[products.description]],[[products.price]],[[products.images]],[[categories.seocode]] AS [[categories]],[[subcategory.seocode]] AS [[subcategory]] FROM {{products}} JOIN {{categories}} ON [[products.id_categories]]=[[categories.id]] JOIN {{subcategory}} ON [[products.id_subcategory]]=[[subcategory.id]] JOIN {{products_colors}} ON [[products.id]]=[[products_colors.id_products]] JOIN {{colors}} ON [[products_colors.id_colors]]=[[colors.id]] WHERE [[colors.color]]=:colors AND [[products.description]] LIKE :search ORDER BY [[products.date]] DESC LIMIT 0, 20';
        
        $this->assertEquals($query, $productsListMapper->query);
    }
    
    /**
     * Тестирует создание строки SQL запроса с фильтром по параметру search ProductsListSearchQueryCreator::getSelectQuery(), 
     * и нескольким дополнительным фильтрам ProductsListQueryCreator::addFilters()
     */
    public function testGetSelectAndManyFiltersQuery()
    {
        $_GET = ['search'=>'пиджак', 'colors'=>'black', 'sizes'=>45];
        
        $productsListMapper = new ProductsListMapper(self::$_config);
        $productsListMapper->visit(new ProductsListSearchQueryCreator());
        
        $query = 'SELECT [[products.id]],[[products.code]],[[products.name]],[[products.description]],[[products.price]],[[products.images]],[[categories.seocode]] AS [[categories]],[[subcategory.seocode]] AS [[subcategory]] FROM {{products}} JOIN {{categories}} ON [[products.id_categories]]=[[categories.id]] JOIN {{subcategory}} ON [[products.id_subcategory]]=[[subcategory.id]] JOIN {{products_colors}} ON [[products.id]]=[[products_colors.id_products]] JOIN {{colors}} ON [[products_colors.id_colors]]=[[colors.id]] JOIN {{products_sizes}} ON [[products.id]]=[[products_sizes.id_products]] JOIN {{sizes}} ON [[products_sizes.id_sizes]]=[[sizes.id]] WHERE [[colors.color]]=:colors AND [[sizes.size]]=:sizes AND [[products.description]] LIKE :search ORDER BY [[products.date]] DESC LIMIT 0, 20';
        
        $this->assertEquals($query, $productsListMapper->query);
    }
}
