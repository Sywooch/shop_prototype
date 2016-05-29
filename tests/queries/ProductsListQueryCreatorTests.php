<?php

namespace app\tests\queries;

use app\queries\ProductsListQueryCreator;
use app\mappers\ProductsListMapper;

/**
 * Тестирует класс app\queries\ProductsListQueryCreator
 */
class ProductsListQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    /**
     * Тестирует создание строки SQL запроса без категорий и фильтров ProductsListQueryCreator::queryForAll()
     */
    public function testQueryForAll()
    {
        $productsListMapper = new ProductsListMapper(['tableName'=>'products', 'fields'=>['id', 'name', 'description', 'price'], 'orderByField'=>'date']);
        $productsListMapper->visit(new ProductsListQueryCreator());
        
        $query = 'SELECT [[products.id]],[[products.name]],[[products.description]],[[products.price]] FROM {{products}} ORDER BY [[products.date]] DESC LIMIT 0, 20';
        
        $this->assertEquals($query, $productsListMapper->query);
    }
    
    /**
     * Тестирует создание строки SQL c выборкой по категории ProductsListQueryCreator::queryForCategory()
     */
    public function testQueryForCategory()
    {
        $_GET = ['categories'=>'menswear'];
        
        $productsListMapper = new ProductsListMapper(['tableName'=>'products', 'fields'=>['id', 'name', 'description', 'price'], 'orderByField'=>'date']);
        $productsListMapper->visit(new ProductsListQueryCreator());
        
        $query = 'SELECT [[products.id]],[[products.name]],[[products.description]],[[products.price]] FROM {{products}} JOIN {{categories}} ON [[products.id_categories]]=[[categories.id]] WHERE [[categories.seocode]]=:categories ORDER BY [[products.date]] DESC LIMIT 0, 20';
        
        $this->assertEquals($query, $productsListMapper->query);
    }
    
    /**
     * Тестирует создание строки SQL c выборкой по категории и подкатегории ProductsListQueryCreator::queryForSubCategory()
     */
    public function testQueryForSubCategory()
    {
        $_GET = ['categories'=>'мужская одежда', 'subcategory'=>'пиджаки'];
        
        $productsListMapper = new ProductsListMapper(['tableName'=>'products', 'fields'=>['id', 'name', 'description', 'price'], 'orderByField'=>'price']);
        $productsListMapper->visit(new ProductsListQueryCreator());
        
        $query = 'SELECT [[products.id]],[[products.name]],[[products.description]],[[products.price]] FROM {{products}} JOIN {{categories}} ON [[products.id_categories]]=[[categories.id]] JOIN {{subcategory}} ON [[products.id_subcategory]]=[[subcategory.id]] WHERE [[categories.seocode]]=:categories AND [[subcategory.seocode]]=:subcategory ORDER BY [[products.price]] DESC LIMIT 0, 20';
        
        $this->assertEquals($query, $productsListMapper->query);
    }
    
    /**
     * Тестирует создание строки SQL c выборкой по категории, подкатегории и фильтру ProductsListQueryCreator::queryForSubCategory(), 
     * ProductsListQueryCreator::addFilters()
     */
    public function testQueryForSubCategoryAndFilter()
    {
        $_GET = ['categories'=>'мужская одежда', 'subcategory'=>'пиджаки', 'colors'=>'black'];
        
        $productsListMapper = new ProductsListMapper(['tableName'=>'products', 'fields'=>['id', 'name', 'description', 'price'], 'orderByField'=>'date']);
        $productsListMapper->visit(new ProductsListQueryCreator());
        
        $query = 'SELECT [[products.id]],[[products.name]],[[products.description]],[[products.price]] FROM {{products}} JOIN {{categories}} ON [[products.id_categories]]=[[categories.id]] JOIN {{subcategory}} ON [[products.id_subcategory]]=[[subcategory.id]] JOIN {{products_colors}} ON [[products.id]]=[[products_colors.id_products]] JOIN {{colors}} ON [[products_colors.id_colors]]=[[colors.id]] WHERE [[colors.color]]=:colors AND [[categories.seocode]]=:categories AND [[subcategory.seocode]]=:subcategory ORDER BY [[products.date]] DESC LIMIT 0, 20';
        
        $this->assertEquals($query, $productsListMapper->query);
    }
    
    /**
     * Тестирует создание строки SQL c выборкой по категории, подкатегории и нескольким фильтрам ProductsListQueryCreator::queryForSubCategory(), 
     * ProductsListQueryCreator::addFilters()
     */
    public function testQueryForSubCategoryAndManyFilters()
    {
        $_GET = ['categories'=>'мужская одежда', 'subcategory'=>'пиджаки', 'colors'=>'black', 'sizes'=>34];
        
        $productsListMapper = new ProductsListMapper(['tableName'=>'products', 'fields'=>['id', 'name', 'description', 'price'], 'orderByField'=>'price']);
        $productsListMapper->visit(new ProductsListQueryCreator());
        
        $query = 'SELECT [[products.id]],[[products.name]],[[products.description]],[[products.price]] FROM {{products}} JOIN {{categories}} ON [[products.id_categories]]=[[categories.id]] JOIN {{subcategory}} ON [[products.id_subcategory]]=[[subcategory.id]] JOIN {{products_colors}} ON [[products.id]]=[[products_colors.id_products]] JOIN {{colors}} ON [[products_colors.id_colors]]=[[colors.id]] JOIN {{products_sizes}} ON [[products.id]]=[[products_sizes.id_products]] JOIN {{sizes}} ON [[products_sizes.id_sizes]]=[[sizes.id]] WHERE [[colors.color]]=:colors AND [[sizes.size]]=:sizes AND [[categories.seocode]]=:categories AND [[subcategory.seocode]]=:subcategory ORDER BY [[products.price]] DESC LIMIT 0, 20';
        
        $this->assertEquals($query, $productsListMapper->query);
    }
    
    /**
     * Тестирует создание строки SQL запроса без категорий, но с фильтром ProductsListQueryCreator::queryForAll(), ProductsListQueryCreator::addFilters()
     */
    public function testQueryForAllAndFilter()
    {
        $_GET = ['colors'=>'black'];
        
        $productsListMapper = new ProductsListMapper(['tableName'=>'products', 'fields'=>['id', 'name', 'description', 'price'], 'orderByField'=>'price']);
        $productsListMapper->visit(new ProductsListQueryCreator());
        
        $query = 'SELECT [[products.id]],[[products.name]],[[products.description]],[[products.price]] FROM {{products}} JOIN {{products_colors}} ON [[products.id]]=[[products_colors.id_products]] JOIN {{colors}} ON [[products_colors.id_colors]]=[[colors.id]] WHERE [[colors.color]]=:colors ORDER BY [[products.price]] DESC LIMIT 0, 20';
        
        $this->assertEquals($query, $productsListMapper->query);
    }
    
    /**
     * Тестирует создание строки SQL запроса без категорий, но с несколькими фильтрами ProductsListQueryCreator::queryForAll(), 
     * ProductsListQueryCreator::addFilters()
     */
    public function testQueryForAllAndManyFilters()
    {
        $_GET = ['colors'=>'black', 'sizes'=>56.5];
        
        $productsListMapper = new ProductsListMapper(['tableName'=>'products', 'fields'=>['id', 'name', 'description', 'price'], 'orderByField'=>'price']);
        $productsListMapper->visit(new ProductsListQueryCreator());
        
        $query = 'SELECT [[products.id]],[[products.name]],[[products.description]],[[products.price]] FROM {{products}} JOIN {{products_colors}} ON [[products.id]]=[[products_colors.id_products]] JOIN {{colors}} ON [[products_colors.id_colors]]=[[colors.id]] JOIN {{products_sizes}} ON [[products.id]]=[[products_sizes.id_products]] JOIN {{sizes}} ON [[products_sizes.id_sizes]]=[[sizes.id]] WHERE [[colors.color]]=:colors AND [[sizes.size]]=:sizes ORDER BY [[products.price]] DESC LIMIT 0, 20';
        
        $this->assertEquals($query, $productsListMapper->query);
    }
    
    /**
     * Тестирует создание строки SQL c выборкой по категории и фильтру ProductsListQueryCreator::queryForCategory(), ProductsListQueryCreator::addFilters()
     */
    public function testQueryForCategoryAndFilter()
    {
        $_GET = ['categories'=>'мужская одежда', 'sizes'=>50];
        
        $productsListMapper = new ProductsListMapper(['tableName'=>'products', 'fields'=>['id', 'name', 'description', 'price'], 'orderByField'=>'price']);
        $productsListMapper->visit(new ProductsListQueryCreator());
        
        $query = 'SELECT [[products.id]],[[products.name]],[[products.description]],[[products.price]] FROM {{products}} JOIN {{categories}} ON [[products.id_categories]]=[[categories.id]] JOIN {{products_sizes}} ON [[products.id]]=[[products_sizes.id_products]] JOIN {{sizes}} ON [[products_sizes.id_sizes]]=[[sizes.id]] WHERE [[sizes.size]]=:sizes AND [[categories.seocode]]=:categories ORDER BY [[products.price]] DESC LIMIT 0, 20';
        
        $this->assertEquals($query, $productsListMapper->query);
    }
    
    /**
     * Тестирует создание строки SQL c выборкой по категории и нескольким фильтрам ProductsListQueryCreator::queryForCategory(), 
     * ProductsListQueryCreator::addFilters()
     */
    public function testQueryForCategoryAndMenyFilters()
    {
        $_GET = ['categories'=>'мужская одежда', 'sizes'=>50, 'colors'=>'black'];
        
        $productsListMapper = new ProductsListMapper(['tableName'=>'products', 'fields'=>['id', 'name', 'description', 'price'], 'orderByField'=>'price']);
        $productsListMapper->visit(new ProductsListQueryCreator());
        
        $query = 'SELECT [[products.id]],[[products.name]],[[products.description]],[[products.price]] FROM {{products}} JOIN {{categories}} ON [[products.id_categories]]=[[categories.id]] JOIN {{products_colors}} ON [[products.id]]=[[products_colors.id_products]] JOIN {{colors}} ON [[products_colors.id_colors]]=[[colors.id]] JOIN {{products_sizes}} ON [[products.id]]=[[products_sizes.id_products]] JOIN {{sizes}} ON [[products_sizes.id_sizes]]=[[sizes.id]] WHERE [[colors.color]]=:colors AND [[sizes.size]]=:sizes AND [[categories.seocode]]=:categories ORDER BY [[products.price]] DESC LIMIT 0, 20';
        
        $this->assertEquals($query, $productsListMapper->query);
    }
}
