<?php

namespace app\tests\queries;

use app\tests\MockObject;
use app\queries\ProductsListSearchQueryCreator;

/**
 * Тестирует класс app\queries\ProductsListSearchQueryCreator
 */
class ProductsListSearchQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_search = 'some';
    private static $_config = [
        'tableName'=>'products',
        'fields'=>['id', 'code', 'name', 'description', 'price', 'images'],
        'otherTablesFields'=>[
            ['table'=>'categories', 'fields'=>[['field'=>'seocode', 'as'=>'categories']]],
            ['table'=>'subcategory', 'fields'=>[['field'=>'seocode', 'as'=>'subcategory']]],
        ],
        'orderByField'=>'date',
        'sphynxArray'=>[1,2,4],
    ];
    
    /**
     * Тестирует создание строки SQL запроса с фильтром по параметру search ProductsListSearchQueryCreator::getSelectQuery()
     */
    public function testGetSelectQuery()
    {
        $_GET = ['search'=>self::$_search];
        
        \Yii::configure(\Yii::$app->filters, ['colors'=>[], 'sizes'=>[]]);
        
        $mockObject = new MockObject(self::$_config);
        
        $queryCreator = new ProductsListSearchQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'SELECT DISTINCT [[products.id]],[[products.code]],[[products.name]],[[products.description]],[[products.price]],[[products.images]],[[categories.seocode]] AS [[categories]],[[subcategory.seocode]] AS [[subcategory]] FROM {{products}} JOIN {{categories}} ON [[products.id_categories]]=[[categories.id]] JOIN {{subcategory}} ON [[products.id_subcategory]]=[[subcategory.id]] WHERE [[products.id]] IN (:0_1,:1_2,:2_4) ORDER BY [[products.date]] DESC LIMIT 0, 20';
        
        $this->assertEquals($query, $mockObject->query);
    }
    
    /**
     * Тестирует создание строки SQL запроса с фильтром по параметру search ProductsListSearchQueryCreator::getSelectQuery(), 
     * и одному дополнительному фильтру ProductsListQueryCreator::addFilters()
     */
    public function testGetSelectAndFilterQuery()
    {
        $_GET = ['search'=>self::$_search];
        
        \Yii::configure(\Yii::$app->filters, ['colors'=>[2,4,1]]);
        
        $mockObject = new MockObject(self::$_config);
        
        $queryCreator = new ProductsListSearchQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'SELECT DISTINCT [[products.id]],[[products.code]],[[products.name]],[[products.description]],[[products.price]],[[products.images]],[[categories.seocode]] AS [[categories]],[[subcategory.seocode]] AS [[subcategory]] FROM {{products}} JOIN {{categories}} ON [[products.id_categories]]=[[categories.id]] JOIN {{subcategory}} ON [[products.id_subcategory]]=[[subcategory.id]] JOIN {{products_colors}} ON [[products.id]]=[[products_colors.id_products]] JOIN {{colors}} ON [[products_colors.id_colors]]=[[colors.id]] WHERE [[colors.id]] IN (:0colors_id,:1colors_id,:2colors_id) AND [[products.id]] IN (:0_1,:1_2,:2_4) ORDER BY [[products.date]] DESC LIMIT 0, 20';
        
        $this->assertEquals($query, $mockObject->query);
    }
    
    /**
     * Тестирует создание строки SQL запроса с фильтром по параметру search ProductsListSearchQueryCreator::getSelectQuery(), 
     * и нескольким дополнительным фильтрам ProductsListQueryCreator::addFilters()
     */
    public function testGetSelectAndManyFiltersQuery()
    {
        $_GET = ['search'=>self::$_search];
        
        \Yii::configure(\Yii::$app->filters, ['colors'=>[2,4], 'sizes'=>[1,2]]);
        
        $mockObject = new MockObject(self::$_config);
        
        $queryCreator = new ProductsListSearchQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'SELECT DISTINCT [[products.id]],[[products.code]],[[products.name]],[[products.description]],[[products.price]],[[products.images]],[[categories.seocode]] AS [[categories]],[[subcategory.seocode]] AS [[subcategory]] FROM {{products}} JOIN {{categories}} ON [[products.id_categories]]=[[categories.id]] JOIN {{subcategory}} ON [[products.id_subcategory]]=[[subcategory.id]] JOIN {{products_colors}} ON [[products.id]]=[[products_colors.id_products]] JOIN {{colors}} ON [[products_colors.id_colors]]=[[colors.id]] JOIN {{products_sizes}} ON [[products.id]]=[[products_sizes.id_products]] JOIN {{sizes}} ON [[products_sizes.id_sizes]]=[[sizes.id]] WHERE [[colors.id]] IN (:0colors_id,:1colors_id) AND [[sizes.id]] IN (:0sizes_id,:1sizes_id) AND [[products.id]] IN (:0_1,:1_2,:2_4) ORDER BY [[products.date]] DESC LIMIT 0, 20';
        
        $this->assertEquals($query, $mockObject->query);
    }
}
