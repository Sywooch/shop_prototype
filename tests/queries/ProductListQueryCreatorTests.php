<?php

namespace app\tests\queries;

use app\tests\DbManager;
use app\queries\ProductListQueryCreator;
use app\mappers\ProductsListMapper;

/**
 * Тестирует класс ProductListQueryCreator
 */
class ProductListQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    private static $dbClass;
    
    public static function SetUpBeforeClass()
    {
        self::$dbClass = new DbManager();
        self::$dbClass->createDbAndData();
    }
    
    /**
     * Тестирует создание строки SQL запроса без категорий и фильтров ProductListQueryCreator::queryForAll()
     */
    public function testQueryForAll()
    {
        $productsListMapper = new ProductsListMapper(['tableName'=>'products', 'fields'=>['id', 'name', 'description', 'price'], 'orderByField'=>'price']);
        $productsListMapper->visit(new ProductListQueryCreator());
        
        $query = 'SELECT [[products.id]],[[products.name]],[[products.description]],[[products.price]] FROM {{products}} ORDER BY [[products.price]] DESC LIMIT 0, 20';
        
        $this->assertEquals($query, $productsListMapper->query);
    }
    
    /**
     * Тестирует создание строки SQL c выборкой по категории ProductListQueryCreator::queryForCategory()
     */
    public function testQueryForCategory()
    {
        $_GET = ['categories'=>'мужская одежда'];
        
        $productsListMapper = new ProductsListMapper(['tableName'=>'products', 'fields'=>['id', 'name', 'description', 'price'], 'orderByField'=>'price']);
        $productsListMapper->visit(new ProductListQueryCreator());
        
        $query = 'SELECT [[products.id]],[[products.name]],[[products.description]],[[products.price]] FROM {{products}} JOIN {{categories}} ON [[products.id_category]]=[[categories.id]] WHERE [[categories.name]]=:categories ORDER BY [[products.price]] DESC LIMIT 0, 20';
        
        $this->assertEquals($query, $productsListMapper->query);
    }
    
    /**
     * Тестирует создание строки SQL c выборкой по категории и подкатегории ProductListQueryCreator::queryForSubCategory()
     */
    public function testQueryForSubCategory()
    {
        $_GET = ['categories'=>'мужская одежда', 'subcategory'=>'пиджаки'];
        
        $productsListMapper = new ProductsListMapper(['tableName'=>'products', 'fields'=>['id', 'name', 'description', 'price'], 'orderByField'=>'price']);
        $productsListMapper->visit(new ProductListQueryCreator());
        
        $query = 'SELECT [[products.id]],[[products.name]],[[products.description]],[[products.price]] FROM {{products}} JOIN {{categories}} ON [[products.id_category]]=[[categories.id]] JOIN {{subcategory}} ON [[products.id_subcategory]]=[[subcategory.id]] WHERE [[categories.name]]=:categories AND [[subcategory.name]]=:subcategory ORDER BY [[products.price]] DESC LIMIT 0, 20';
        
        $this->assertEquals($query, $productsListMapper->query);
    }
    
    /**
     * Тестирует создание строки SQL c выборкой по категории, подкатегории и фильтру ProductListQueryCreator::queryForSubCategory(), 
     * ProductListQueryCreator::addFilters()
     */
    public function testQueryForSubCategoryAndFilter()
    {
        $_GET = ['categories'=>'мужская одежда', 'subcategory'=>'пиджаки', 'colors'=>'black'];
        
        $productsListMapper = new ProductsListMapper(['tableName'=>'products', 'fields'=>['id', 'name', 'description', 'price'], 'orderByField'=>'price']);
        $productsListMapper->visit(new ProductListQueryCreator());
        
        $query = 'SELECT [[products.id]],[[products.name]],[[products.description]],[[products.price]] FROM {{products}} JOIN {{categories}} ON [[products.id_category]]=[[categories.id]] JOIN {{subcategory}} ON [[products.id_subcategory]]=[[subcategory.id]] JOIN {{products_colors}} ON [[products.id]]=[[products_colors.id_product]] JOIN {{colors}} ON [[products_colors.id_color]]=[[colors.id]] WHERE [[colors.color]]=:colors AND [[categories.name]]=:categories AND [[subcategory.name]]=:subcategory ORDER BY [[products.price]] DESC LIMIT 0, 20';
        
        $this->assertEquals($query, $productsListMapper->query);
    }
    
    /**
     * Тестирует создание строки SQL c выборкой по категории, подкатегории и нескольким фильтрам ProductListQueryCreator::queryForSubCategory(), 
     * ProductListQueryCreator::addFilters()
     */
    public function testQueryForSubCategoryAndManyFilters()
    {
        $_GET = ['categories'=>'мужская одежда', 'subcategory'=>'пиджаки', 'colors'=>'black', 'sizes'=>34];
        
        $productsListMapper = new ProductsListMapper(['tableName'=>'products', 'fields'=>['id', 'name', 'description', 'price'], 'orderByField'=>'price']);
        $productsListMapper->visit(new ProductListQueryCreator());
        
        $query = 'SELECT [[products.id]],[[products.name]],[[products.description]],[[products.price]] FROM {{products}} JOIN {{categories}} ON [[products.id_category]]=[[categories.id]] JOIN {{subcategory}} ON [[products.id_subcategory]]=[[subcategory.id]] JOIN {{products_colors}} ON [[products.id]]=[[products_colors.id_product]] JOIN {{colors}} ON [[products_colors.id_color]]=[[colors.id]] JOIN {{products_sizes}} ON [[products.id]]=[[products_sizes.id_product]] JOIN {{sizes}} ON [[products_sizes.id_size]]=[[sizes.id]] WHERE [[colors.color]]=:colors AND [[sizes.size]]=:sizes AND [[categories.name]]=:categories AND [[subcategory.name]]=:subcategory ORDER BY [[products.price]] DESC LIMIT 0, 20';
        
        $this->assertEquals($query, $productsListMapper->query);
    }
    
    /**
     * Тестирует создание строки SQL запроса без категорий, но с фильтром ProductListQueryCreator::queryForAll(), ProductListQueryCreator::addFilters()
     */
    public function testQueryForAllAndFilter()
    {
        $_GET = ['colors'=>'black'];
        
        $productsListMapper = new ProductsListMapper(['tableName'=>'products', 'fields'=>['id', 'name', 'description', 'price'], 'orderByField'=>'price']);
        $productsListMapper->visit(new ProductListQueryCreator());
        
        $query = 'SELECT [[products.id]],[[products.name]],[[products.description]],[[products.price]] FROM {{products}} JOIN {{products_colors}} ON [[products.id]]=[[products_colors.id_product]] JOIN {{colors}} ON [[products_colors.id_color]]=[[colors.id]] WHERE [[colors.color]]=:colors ORDER BY [[products.price]] DESC LIMIT 0, 20';
        
        $this->assertEquals($query, $productsListMapper->query);
    }
    
    /**
     * Тестирует создание строки SQL запроса без категорий, но с несколькими фильтрами ProductListQueryCreator::queryForAll(), 
     * ProductListQueryCreator::addFilters()
     */
    public function testQueryForAllAndManyFilters()
    {
        $_GET = ['colors'=>'black', 'sizes'=>56.5];
        
        $productsListMapper = new ProductsListMapper(['tableName'=>'products', 'fields'=>['id', 'name', 'description', 'price'], 'orderByField'=>'price']);
        $productsListMapper->visit(new ProductListQueryCreator());
        
        $query = 'SELECT [[products.id]],[[products.name]],[[products.description]],[[products.price]] FROM {{products}} JOIN {{products_colors}} ON [[products.id]]=[[products_colors.id_product]] JOIN {{colors}} ON [[products_colors.id_color]]=[[colors.id]] JOIN {{products_sizes}} ON [[products.id]]=[[products_sizes.id_product]] JOIN {{sizes}} ON [[products_sizes.id_size]]=[[sizes.id]] WHERE [[colors.color]]=:colors AND [[sizes.size]]=:sizes ORDER BY [[products.price]] DESC LIMIT 0, 20';
        
        $this->assertEquals($query, $productsListMapper->query);
    }
    
    /**
     * Тестирует создание строки SQL c выборкой по категории и фильтру ProductListQueryCreator::queryForCategory(), ProductListQueryCreator::addFilters()
     */
    public function testQueryForCategoryAndFilter()
    {
        $_GET = ['categories'=>'мужская одежда', 'sizes'=>50];
        
        $productsListMapper = new ProductsListMapper(['tableName'=>'products', 'fields'=>['id', 'name', 'description', 'price'], 'orderByField'=>'price']);
        $productsListMapper->visit(new ProductListQueryCreator());
        
        $query = 'SELECT [[products.id]],[[products.name]],[[products.description]],[[products.price]] FROM {{products}} JOIN {{categories}} ON [[products.id_category]]=[[categories.id]] JOIN {{products_sizes}} ON [[products.id]]=[[products_sizes.id_product]] JOIN {{sizes}} ON [[products_sizes.id_size]]=[[sizes.id]] WHERE [[sizes.size]]=:sizes AND [[categories.name]]=:categories ORDER BY [[products.price]] DESC LIMIT 0, 20';
        
        $this->assertEquals($query, $productsListMapper->query);
    }
    
    
    /**
     * Тестирует создание строки SQL c выборкой по категории и нескольким фильтрам ProductListQueryCreator::queryForCategory(), 
     * ProductListQueryCreator::addFilters()
     */
    public function testQueryForCategoryAndMenyFilters()
    {
        $_GET = ['categories'=>'мужская одежда', 'sizes'=>50, 'colors'=>'black'];
        
        $productsListMapper = new ProductsListMapper(['tableName'=>'products', 'fields'=>['id', 'name', 'description', 'price'], 'orderByField'=>'price']);
        $productsListMapper->visit(new ProductListQueryCreator());
        
        $query = 'SELECT [[products.id]],[[products.name]],[[products.description]],[[products.price]] FROM {{products}} JOIN {{categories}} ON [[products.id_category]]=[[categories.id]] JOIN {{products_colors}} ON [[products.id]]=[[products_colors.id_product]] JOIN {{colors}} ON [[products_colors.id_color]]=[[colors.id]] JOIN {{products_sizes}} ON [[products.id]]=[[products_sizes.id_product]] JOIN {{sizes}} ON [[products_sizes.id_size]]=[[sizes.id]] WHERE [[colors.color]]=:colors AND [[sizes.size]]=:sizes AND [[categories.name]]=:categories ORDER BY [[products.price]] DESC LIMIT 0, 20';
        
        $this->assertEquals($query, $productsListMapper->query);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->deleteDb();
    }
}
