<?php

namespace app\tests\queries;

use app\tests\MockObject;
use app\queries\ProductsListQueryCreator;

/**
 * Тестирует класс app\queries\ProductsListQueryCreator
 */
class ProductsListQueryCreatorTests extends \PHPUnit_Framework_TestCase
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
     * Тестирует создание строки SQL запроса без категорий и фильтров ProductsListQueryCreator::queryForAll()
     */
    public function testQueryForAll()
    {
        $_GET = [];
        
        $mockObject = new MockObject(self::$_config);
        
        $queryCreator = new ProductsListQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'SELECT [[products.id]],[[products.code]],[[products.name]],[[products.description]],[[products.price]],[[products.images]],[[categories.seocode]] AS [[categories]],[[subcategory.seocode]] AS [[subcategory]] FROM {{products}} JOIN {{categories}} ON [[products.id_categories]]=[[categories.id]] JOIN {{subcategory}} ON [[products.id_subcategory]]=[[subcategory.id]] ORDER BY [[products.date]] DESC LIMIT 0, 20';
        
        $this->assertEquals($query, $mockObject->query);
    }
    
    /**
     * Тестирует создание строки SQL c выборкой по категории ProductsListQueryCreator::queryForCategory()
     */
    public function testQueryForCategory()
    {
        $_GET = ['categories'=>'menswear'];
        
        $mockObject = new MockObject(self::$_config);
        
        $queryCreator = new ProductsListQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'SELECT [[products.id]],[[products.code]],[[products.name]],[[products.description]],[[products.price]],[[products.images]],[[categories.seocode]] AS [[categories]],[[subcategory.seocode]] AS [[subcategory]] FROM {{products}} JOIN {{categories}} ON [[products.id_categories]]=[[categories.id]] JOIN {{subcategory}} ON [[products.id_subcategory]]=[[subcategory.id]] WHERE [[categories.seocode]]=:categories ORDER BY [[products.date]] DESC LIMIT 0, 20';
        
        $this->assertEquals($query, $mockObject->query);
    }
    
    /**
     * Тестирует создание строки SQL c выборкой по категории и подкатегории ProductsListQueryCreator::queryForSubCategory()
     */
    public function testQueryForSubCategory()
    {
        $_GET = ['categories'=>'menswear', 'subcategory'=>'coats'];
        
        $mockObject = new MockObject(self::$_config);
        
        $queryCreator = new ProductsListQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'SELECT [[products.id]],[[products.code]],[[products.name]],[[products.description]],[[products.price]],[[products.images]],[[categories.seocode]] AS [[categories]],[[subcategory.seocode]] AS [[subcategory]] FROM {{products}} JOIN {{categories}} ON [[products.id_categories]]=[[categories.id]] JOIN {{subcategory}} ON [[products.id_subcategory]]=[[subcategory.id]] WHERE [[categories.seocode]]=:categories AND [[subcategory.seocode]]=:subcategory ORDER BY [[products.date]] DESC LIMIT 0, 20';
        
        $this->assertEquals($query, $mockObject->query);
    }
    
    /**
     * Тестирует создание строки SQL c выборкой по категории, подкатегории и фильтру ProductsListQueryCreator::queryForSubCategory(), 
     * ProductsListQueryCreator::addFilters()
     */
    public function testQueryForSubCategoryAndFilter()
    {
        $_GET = ['categories'=>'menswear', 'subcategory'=>'coats', 'colors'=>'black'];
        
        $mockObject = new MockObject(self::$_config);
        
        $queryCreator = new ProductsListQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'SELECT [[products.id]],[[products.code]],[[products.name]],[[products.description]],[[products.price]],[[products.images]],[[categories.seocode]] AS [[categories]],[[subcategory.seocode]] AS [[subcategory]] FROM {{products}} JOIN {{categories}} ON [[products.id_categories]]=[[categories.id]] JOIN {{subcategory}} ON [[products.id_subcategory]]=[[subcategory.id]] JOIN {{products_colors}} ON [[products.id]]=[[products_colors.id_products]] JOIN {{colors}} ON [[products_colors.id_colors]]=[[colors.id]] WHERE [[colors.color]]=:colors AND [[categories.seocode]]=:categories AND [[subcategory.seocode]]=:subcategory ORDER BY [[products.date]] DESC LIMIT 0, 20';
        
        $this->assertEquals($query, $mockObject->query);
    }
    
    /**
     * Тестирует создание строки SQL c выборкой по категории, подкатегории и нескольким фильтрам ProductsListQueryCreator::queryForSubCategory(), 
     * ProductsListQueryCreator::addFilters()
     */
    public function testQueryForSubCategoryAndManyFilters()
    {
        $_GET = ['categories'=>'mensfootwear', 'subcategory'=>'boots', 'colors'=>'black', 'sizes'=>34];
        
        $mockObject = new MockObject(self::$_config);
        
        $queryCreator = new ProductsListQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'SELECT [[products.id]],[[products.code]],[[products.name]],[[products.description]],[[products.price]],[[products.images]],[[categories.seocode]] AS [[categories]],[[subcategory.seocode]] AS [[subcategory]] FROM {{products}} JOIN {{categories}} ON [[products.id_categories]]=[[categories.id]] JOIN {{subcategory}} ON [[products.id_subcategory]]=[[subcategory.id]] JOIN {{products_colors}} ON [[products.id]]=[[products_colors.id_products]] JOIN {{colors}} ON [[products_colors.id_colors]]=[[colors.id]] JOIN {{products_sizes}} ON [[products.id]]=[[products_sizes.id_products]] JOIN {{sizes}} ON [[products_sizes.id_sizes]]=[[sizes.id]] WHERE [[colors.color]]=:colors AND [[sizes.size]]=:sizes AND [[categories.seocode]]=:categories AND [[subcategory.seocode]]=:subcategory ORDER BY [[products.date]] DESC LIMIT 0, 20';
        
        $this->assertEquals($query, $mockObject->query);
    }
    
    /**
     * Тестирует создание строки SQL запроса без категорий, но с фильтром ProductsListQueryCreator::queryForAll(), ProductsListQueryCreator::addFilters()
     */
    public function testQueryForAllAndFilter()
    {
        $_GET = ['colors'=>'black'];
        
        $mockObject = new MockObject(self::$_config);
        
        $queryCreator = new ProductsListQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'SELECT [[products.id]],[[products.code]],[[products.name]],[[products.description]],[[products.price]],[[products.images]],[[categories.seocode]] AS [[categories]],[[subcategory.seocode]] AS [[subcategory]] FROM {{products}} JOIN {{categories}} ON [[products.id_categories]]=[[categories.id]] JOIN {{subcategory}} ON [[products.id_subcategory]]=[[subcategory.id]] JOIN {{products_colors}} ON [[products.id]]=[[products_colors.id_products]] JOIN {{colors}} ON [[products_colors.id_colors]]=[[colors.id]] WHERE [[colors.color]]=:colors ORDER BY [[products.date]] DESC LIMIT 0, 20';
        
        $this->assertEquals($query, $mockObject->query);
    }
    
    /**
     * Тестирует создание строки SQL запроса без категорий, но с несколькими фильтрами ProductsListQueryCreator::queryForAll(), 
     * ProductsListQueryCreator::addFilters()
     */
    public function testQueryForAllAndManyFilters()
    {
        $_GET = ['colors'=>'black', 'sizes'=>56.5];
        
        $mockObject = new MockObject(self::$_config);
        
        $queryCreator = new ProductsListQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'SELECT [[products.id]],[[products.code]],[[products.name]],[[products.description]],[[products.price]],[[products.images]],[[categories.seocode]] AS [[categories]],[[subcategory.seocode]] AS [[subcategory]] FROM {{products}} JOIN {{categories}} ON [[products.id_categories]]=[[categories.id]] JOIN {{subcategory}} ON [[products.id_subcategory]]=[[subcategory.id]] JOIN {{products_colors}} ON [[products.id]]=[[products_colors.id_products]] JOIN {{colors}} ON [[products_colors.id_colors]]=[[colors.id]] JOIN {{products_sizes}} ON [[products.id]]=[[products_sizes.id_products]] JOIN {{sizes}} ON [[products_sizes.id_sizes]]=[[sizes.id]] WHERE [[colors.color]]=:colors AND [[sizes.size]]=:sizes ORDER BY [[products.date]] DESC LIMIT 0, 20';
        
        $this->assertEquals($query, $mockObject->query);
    }
    
    /**
     * Тестирует создание строки SQL c выборкой по категории и фильтру ProductsListQueryCreator::queryForCategory(), ProductsListQueryCreator::addFilters()
     */
    public function testQueryForCategoryAndFilter()
    {
        $_GET = ['categories'=>'menswear', 'sizes'=>50];
        
        $mockObject = new MockObject(self::$_config);
        
        $queryCreator = new ProductsListQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'SELECT [[products.id]],[[products.code]],[[products.name]],[[products.description]],[[products.price]],[[products.images]],[[categories.seocode]] AS [[categories]],[[subcategory.seocode]] AS [[subcategory]] FROM {{products}} JOIN {{categories}} ON [[products.id_categories]]=[[categories.id]] JOIN {{subcategory}} ON [[products.id_subcategory]]=[[subcategory.id]] JOIN {{products_sizes}} ON [[products.id]]=[[products_sizes.id_products]] JOIN {{sizes}} ON [[products_sizes.id_sizes]]=[[sizes.id]] WHERE [[sizes.size]]=:sizes AND [[categories.seocode]]=:categories ORDER BY [[products.date]] DESC LIMIT 0, 20';
        
        $this->assertEquals($query, $mockObject->query);
    }
    
    /**
     * Тестирует создание строки SQL c выборкой по категории и нескольким фильтрам ProductsListQueryCreator::queryForCategory(), 
     * ProductsListQueryCreator::addFilters()
     */
    public function testQueryForCategoryAndMenyFilters()
    {
        $_GET = ['categories'=>'menswear', 'sizes'=>50, 'colors'=>'black'];
        
        $mockObject = new MockObject(self::$_config);
        
        $queryCreator = new ProductsListQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'SELECT [[products.id]],[[products.code]],[[products.name]],[[products.description]],[[products.price]],[[products.images]],[[categories.seocode]] AS [[categories]],[[subcategory.seocode]] AS [[subcategory]] FROM {{products}} JOIN {{categories}} ON [[products.id_categories]]=[[categories.id]] JOIN {{subcategory}} ON [[products.id_subcategory]]=[[subcategory.id]] JOIN {{products_colors}} ON [[products.id]]=[[products_colors.id_products]] JOIN {{colors}} ON [[products_colors.id_colors]]=[[colors.id]] JOIN {{products_sizes}} ON [[products.id]]=[[products_sizes.id_products]] JOIN {{sizes}} ON [[products_sizes.id_sizes]]=[[sizes.id]] WHERE [[colors.color]]=:colors AND [[sizes.size]]=:sizes AND [[categories.seocode]]=:categories ORDER BY [[products.date]] DESC LIMIT 0, 20';
        
        $this->assertEquals($query, $mockObject->query);
    }
}
