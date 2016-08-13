<?php

namespace app\tests\queries;

use app\tests\MockObject;
use app\queries\ProductsListAdminQueryCreator;

/**
 * Тестирует класс app\queries\ProductsListAdminQueryCreator
 */
class ProductsListAdminQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_config = [
        'tableName'=>'products',
        'fields'=>['id', 'date', 'code', 'name', 'description', 'short_description', 'price', 'images', 'id_categories', 'id_subcategory', 'active', 'total_products'],
        'otherTablesFields'=>[
            ['table'=>'categories', 'fields'=>[['field'=>'seocode', 'as'=>'categories']]],
            ['table'=>'subcategory', 'fields'=>[['field'=>'seocode', 'as'=>'subcategory']]],
        ],
        'orderByField'=>'date',
        'getDataSorting'=>false,
    ];
    private static $_categories = 'menswear';
    private static $_subcategory = 'coats';
    
    /**
     * Тестирует создание строки SQL запроса без категорий и фильтров ProductsListAdminQueryCreator::queryForAll()
     */
    public function testQueryForAll()
    {
        $_GET = [];
        \Yii::$app->filters->clean();
        \Yii::$app->filters->cleanOther();
        \Yii::$app->filters->cleanAdmin();
        
        $mockObject = new MockObject(self::$_config);
        
        $queryCreator = new ProductsListAdminQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'SELECT DISTINCT [[products.id]],[[products.date]],[[products.code]],[[products.name]],[[products.description]],[[products.short_description]],[[products.price]],[[products.images]],[[products.id_categories]],[[products.id_subcategory]],[[products.active]],[[products.total_products]],[[categories.seocode]] AS [[categories]],[[subcategory.seocode]] AS [[subcategory]] FROM {{products}} JOIN {{categories}} ON [[products.id_categories]]=[[categories.id]] JOIN {{subcategory}} ON [[products.id_subcategory]]=[[subcategory.id]] WHERE [[products.active]]=:active ORDER BY [[products.date]] DESC LIMIT 0, 20';
        
        $this->assertEquals($query, $mockObject->query);
    }
    
    /**
     * Тестирует создание строки SQL c выборкой по категории ProductsListAdminQueryCreator::queryForCategory()
     */
    public function testQueryForCategory()
    {
        \Yii::$app->filters->categories = self::$_categories;
        
        $mockObject = new MockObject(self::$_config);
        
        $queryCreator = new ProductsListAdminQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'SELECT DISTINCT [[products.id]],[[products.date]],[[products.code]],[[products.name]],[[products.description]],[[products.short_description]],[[products.price]],[[products.images]],[[products.id_categories]],[[products.id_subcategory]],[[products.active]],[[products.total_products]],[[categories.seocode]] AS [[categories]],[[subcategory.seocode]] AS [[subcategory]] FROM {{products}} JOIN {{categories}} ON [[products.id_categories]]=[[categories.id]] JOIN {{subcategory}} ON [[products.id_subcategory]]=[[subcategory.id]] WHERE [[categories.seocode]]=:categories AND [[products.active]]=:active ORDER BY [[products.date]] DESC LIMIT 0, 20';

        
        $this->assertEquals($query, $mockObject->query);
    }
    
    /**
     * Тестирует создание строки SQL c выборкой по категории и подкатегории ProductsListAdminQueryCreator::queryForSubCategory()
     */
    public function testQueryForSubCategory()
    {
        \Yii::$app->filters->categories = self::$_categories;
        \Yii::$app->filters->subcategory = self::$_subcategory;
        
        $mockObject = new MockObject(self::$_config);
        
        $queryCreator = new ProductsListAdminQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'SELECT DISTINCT [[products.id]],[[products.date]],[[products.code]],[[products.name]],[[products.description]],[[products.short_description]],[[products.price]],[[products.images]],[[products.id_categories]],[[products.id_subcategory]],[[products.active]],[[products.total_products]],[[categories.seocode]] AS [[categories]],[[subcategory.seocode]] AS [[subcategory]] FROM {{products}} JOIN {{categories}} ON [[products.id_categories]]=[[categories.id]] JOIN {{subcategory}} ON [[products.id_subcategory]]=[[subcategory.id]] WHERE [[categories.seocode]]=:categories AND [[subcategory.seocode]]=:subcategory AND [[products.active]]=:active ORDER BY [[products.date]] DESC LIMIT 0, 20';

        $this->assertEquals($query, $mockObject->query);
    }
    
    /**
     * Тестирует создание строки SQL c выборкой по категории, подкатегории и фильтру ProductsListAdminQueryCreator::queryForSubCategory(), 
     * ProductsListAdminQueryCreator::addFilters()
     */
    public function testQueryForSubCategoryAndFilter()
    {
        \Yii::$app->filters->categories = self::$_categories;
        \Yii::$app->filters->subcategory = self::$_subcategory;
        \Yii::configure(\Yii::$app->filters, ['colors'=>[1]]);
        
        $mockObject = new MockObject(self::$_config);
        
        $queryCreator = new ProductsListAdminQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'SELECT DISTINCT [[products.id]],[[products.date]],[[products.code]],[[products.name]],[[products.description]],[[products.short_description]],[[products.price]],[[products.images]],[[products.id_categories]],[[products.id_subcategory]],[[products.active]],[[products.total_products]],[[categories.seocode]] AS [[categories]],[[subcategory.seocode]] AS [[subcategory]] FROM {{products}} JOIN {{categories}} ON [[products.id_categories]]=[[categories.id]] JOIN {{subcategory}} ON [[products.id_subcategory]]=[[subcategory.id]] JOIN {{products_colors}} ON [[products.id]]=[[products_colors.id_products]] JOIN {{colors}} ON [[products_colors.id_colors]]=[[colors.id]] WHERE [[colors.id]] IN (:0colors_id) AND [[categories.seocode]]=:categories AND [[subcategory.seocode]]=:subcategory AND [[products.active]]=:active ORDER BY [[products.date]] DESC LIMIT 0, 20';
        
        $this->assertEquals($query, $mockObject->query);
    }
    
    /**
     * Тестирует создание строки SQL c выборкой по категории, подкатегории и нескольким фильтрам ProductsListAdminQueryCreator::queryForSubCategory(), 
     * ProductsListAdminQueryCreator::addFilters()
     */
    public function testQueryForSubCategoryAndManyFilters()
    {
        \Yii::$app->filters->categories = self::$_categories;
        \Yii::$app->filters->subcategory = self::$_subcategory;
        \Yii::configure(\Yii::$app->filters, ['colors'=>[1], 'sizes'=>[2]]);
        
        $mockObject = new MockObject(self::$_config);
        
        $queryCreator = new ProductsListAdminQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'SELECT DISTINCT [[products.id]],[[products.date]],[[products.code]],[[products.name]],[[products.description]],[[products.short_description]],[[products.price]],[[products.images]],[[products.id_categories]],[[products.id_subcategory]],[[products.active]],[[products.total_products]],[[categories.seocode]] AS [[categories]],[[subcategory.seocode]] AS [[subcategory]] FROM {{products}} JOIN {{categories}} ON [[products.id_categories]]=[[categories.id]] JOIN {{subcategory}} ON [[products.id_subcategory]]=[[subcategory.id]] JOIN {{products_colors}} ON [[products.id]]=[[products_colors.id_products]] JOIN {{colors}} ON [[products_colors.id_colors]]=[[colors.id]] JOIN {{products_sizes}} ON [[products.id]]=[[products_sizes.id_products]] JOIN {{sizes}} ON [[products_sizes.id_sizes]]=[[sizes.id]] WHERE [[colors.id]] IN (:0colors_id) AND [[sizes.id]] IN (:0sizes_id) AND [[categories.seocode]]=:categories AND [[subcategory.seocode]]=:subcategory AND [[products.active]]=:active ORDER BY [[products.date]] DESC LIMIT 0, 20';
        
        $this->assertEquals($query, $mockObject->query);
    }
}
