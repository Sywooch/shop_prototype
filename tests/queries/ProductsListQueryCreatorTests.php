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
        $_GET = ['categories'=>'menswear', 'subcategory'=>'coats'];
        
        \Yii::configure(\Yii::$app->filters, ['colors'=>[1]]);
        
        $mockObject = new MockObject(self::$_config);
        
        $queryCreator = new ProductsListQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'SELECT [[products.id]],[[products.code]],[[products.name]],[[products.description]],[[products.price]],[[products.images]],[[categories.seocode]] AS [[categories]],[[subcategory.seocode]] AS [[subcategory]] FROM {{products}} JOIN {{categories}} ON [[products.id_categories]]=[[categories.id]] JOIN {{subcategory}} ON [[products.id_subcategory]]=[[subcategory.id]] JOIN {{products_colors}} ON [[products.id]]=[[products_colors.id_products]] JOIN {{colors}} ON [[products_colors.id_colors]]=[[colors.id]] WHERE [[colors.id]] IN (:0colors_id) AND [[categories.seocode]]=:categories AND [[subcategory.seocode]]=:subcategory ORDER BY [[products.date]] DESC LIMIT 0, 20';
        
        $this->assertEquals($query, $mockObject->query);
    }
    
    /**
     * Тестирует создание строки SQL c выборкой по категории, подкатегории и нескольким фильтрам ProductsListQueryCreator::queryForSubCategory(), 
     * ProductsListQueryCreator::addFilters()
     */
    public function testQueryForSubCategoryAndManyFilters()
    {
        $_GET = ['categories'=>'mensfootwear', 'subcategory'=>'boots'];
        
        \Yii::configure(\Yii::$app->filters, ['colors'=>[1], 'sizes'=>[2]]);
        
        $mockObject = new MockObject(self::$_config);
        
        $queryCreator = new ProductsListQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'SELECT [[products.id]],[[products.code]],[[products.name]],[[products.description]],[[products.price]],[[products.images]],[[categories.seocode]] AS [[categories]],[[subcategory.seocode]] AS [[subcategory]] FROM {{products}} JOIN {{categories}} ON [[products.id_categories]]=[[categories.id]] JOIN {{subcategory}} ON [[products.id_subcategory]]=[[subcategory.id]] JOIN {{products_colors}} ON [[products.id]]=[[products_colors.id_products]] JOIN {{colors}} ON [[products_colors.id_colors]]=[[colors.id]] JOIN {{products_sizes}} ON [[products.id]]=[[products_sizes.id_products]] JOIN {{sizes}} ON [[products_sizes.id_sizes]]=[[sizes.id]] WHERE [[colors.id]] IN (:0colors_id) AND [[sizes.id]] IN (:0sizes_id) AND [[categories.seocode]]=:categories AND [[subcategory.seocode]]=:subcategory ORDER BY [[products.date]] DESC LIMIT 0, 20';
        
        $this->assertEquals($query, $mockObject->query);
    }
    
    /**
     * Тестирует создание строки SQL запроса без категорий, но с фильтром ProductsListQueryCreator::queryForAll(), ProductsListQueryCreator::addFilters()
     */
    public function testQueryForAllAndFilter()
    {
        $_GET = [];
        
        \Yii::configure(\Yii::$app->filters, ['colors'=>[1], 'sizes'=>[]]);
        
        $mockObject = new MockObject(self::$_config);
        
        $queryCreator = new ProductsListQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'SELECT [[products.id]],[[products.code]],[[products.name]],[[products.description]],[[products.price]],[[products.images]],[[categories.seocode]] AS [[categories]],[[subcategory.seocode]] AS [[subcategory]] FROM {{products}} JOIN {{categories}} ON [[products.id_categories]]=[[categories.id]] JOIN {{subcategory}} ON [[products.id_subcategory]]=[[subcategory.id]] JOIN {{products_colors}} ON [[products.id]]=[[products_colors.id_products]] JOIN {{colors}} ON [[products_colors.id_colors]]=[[colors.id]] WHERE [[colors.id]] IN (:0colors_id) ORDER BY [[products.date]] DESC LIMIT 0, 20';
        
        $this->assertEquals($query, $mockObject->query);
    }
    
    /**
     * Тестирует создание строки SQL запроса без категорий, но с несколькими фильтрами ProductsListQueryCreator::queryForAll(), 
     * ProductsListQueryCreator::addFilters()
     */
    public function testQueryForAllAndManyFilters()
    {
        $_GET = [];
        
        \Yii::configure(\Yii::$app->filters, ['colors'=>[1], 'sizes'=>[3]]);
        
        $mockObject = new MockObject(self::$_config);
        
        $queryCreator = new ProductsListQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'SELECT [[products.id]],[[products.code]],[[products.name]],[[products.description]],[[products.price]],[[products.images]],[[categories.seocode]] AS [[categories]],[[subcategory.seocode]] AS [[subcategory]] FROM {{products}} JOIN {{categories}} ON [[products.id_categories]]=[[categories.id]] JOIN {{subcategory}} ON [[products.id_subcategory]]=[[subcategory.id]] JOIN {{products_colors}} ON [[products.id]]=[[products_colors.id_products]] JOIN {{colors}} ON [[products_colors.id_colors]]=[[colors.id]] JOIN {{products_sizes}} ON [[products.id]]=[[products_sizes.id_products]] JOIN {{sizes}} ON [[products_sizes.id_sizes]]=[[sizes.id]] WHERE [[colors.id]] IN (:0colors_id) AND [[sizes.id]] IN (:0sizes_id) ORDER BY [[products.date]] DESC LIMIT 0, 20';
        
        $this->assertEquals($query, $mockObject->query);
    }
    
    /**
     * Тестирует создание строки SQL c выборкой по категории и фильтру ProductsListQueryCreator::queryForCategory(), ProductsListQueryCreator::addFilters()
     */
    public function testQueryForCategoryAndFilter()
    {
        $_GET = ['categories'=>'menswear'];
        
        \Yii::configure(\Yii::$app->filters, ['colors'=>[], 'sizes'=>[3]]);
        
        $mockObject = new MockObject(self::$_config);
        
        $queryCreator = new ProductsListQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'SELECT [[products.id]],[[products.code]],[[products.name]],[[products.description]],[[products.price]],[[products.images]],[[categories.seocode]] AS [[categories]],[[subcategory.seocode]] AS [[subcategory]] FROM {{products}} JOIN {{categories}} ON [[products.id_categories]]=[[categories.id]] JOIN {{subcategory}} ON [[products.id_subcategory]]=[[subcategory.id]] JOIN {{products_sizes}} ON [[products.id]]=[[products_sizes.id_products]] JOIN {{sizes}} ON [[products_sizes.id_sizes]]=[[sizes.id]] WHERE [[sizes.id]] IN (:0sizes_id) AND [[categories.seocode]]=:categories ORDER BY [[products.date]] DESC LIMIT 0, 20';
        
        $this->assertEquals($query, $mockObject->query);
    }
    
    /**
     * Тестирует создание строки SQL c выборкой по категории и нескольким фильтрам ProductsListQueryCreator::queryForCategory(), 
     * ProductsListQueryCreator::addFilters()
     */
    public function testQueryForCategoryAndMenyFilters()
    {
        $_GET = ['categories'=>'menswear'];
        
        \Yii::configure(\Yii::$app->filters, ['colors'=>[2,4], 'sizes'=>[3]]);
        
        $mockObject = new MockObject(self::$_config);
        
        $queryCreator = new ProductsListQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'SELECT [[products.id]],[[products.code]],[[products.name]],[[products.description]],[[products.price]],[[products.images]],[[categories.seocode]] AS [[categories]],[[subcategory.seocode]] AS [[subcategory]] FROM {{products}} JOIN {{categories}} ON [[products.id_categories]]=[[categories.id]] JOIN {{subcategory}} ON [[products.id_subcategory]]=[[subcategory.id]] JOIN {{products_colors}} ON [[products.id]]=[[products_colors.id_products]] JOIN {{colors}} ON [[products_colors.id_colors]]=[[colors.id]] JOIN {{products_sizes}} ON [[products.id]]=[[products_sizes.id_products]] JOIN {{sizes}} ON [[products_sizes.id_sizes]]=[[sizes.id]] WHERE [[colors.id]] IN (:0colors_id,:1colors_id) AND [[sizes.id]] IN (:0sizes_id) AND [[categories.seocode]]=:categories ORDER BY [[products.date]] DESC LIMIT 0, 20';
        
        $this->assertEquals($query, $mockObject->query);
    }
}
