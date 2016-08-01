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
        'tableName'=>'shop',
        'fields'=>['id', 'date', 'code', 'name', 'description', 'short_description', 'price', 'images','categories', 'subcategory'],
        'orderByField'=>'date',
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
        
        $query = 'SELECT id,date,code,name,description,short_description,price,images,categories,subcategory FROM shop WHERE MATCH(:' . \Yii::$app->params['sphynxKey'] . ') ORDER BY date DESC LIMIT 0, 20';
        
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
        
        $query = 'SELECT id,date,code,name,description,short_description,price,images,categories,subcategory FROM shop WHERE colors_id IN (:0colors_id,:1colors_id,:2colors_id) AND MATCH(:' . \Yii::$app->params['sphynxKey'] . ') ORDER BY date DESC LIMIT 0, 20';
        
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
        
        $query = 'SELECT id,date,code,name,description,short_description,price,images,categories,subcategory FROM shop WHERE colors_id IN (:0colors_id,:1colors_id) AND sizes_id IN (:0sizes_id,:1sizes_id) AND MATCH(:' . \Yii::$app->params['sphynxKey'] . ') ORDER BY date DESC LIMIT 0, 20';
        
        $this->assertEquals($query, $mockObject->query);
    }
    
    /**
     * Тестирует создание строки SQL запроса с фильтром по параметру search ProductsListSearchQueryCreator::getSelectQuery(), 
     * и нескольким дополнительным фильтрам ProductsListQueryCreator::addFilters()
     */
    public function testGetSelectAndManyFiltersQueryTwo()
    {
        $_GET = ['search'=>self::$_search];
        
        \Yii::configure(\Yii::$app->filters, ['colors'=>[2,4], 'sizes'=>[1,2], 'brands'=>[1]]);
        
        $mockObject = new MockObject(self::$_config);
        
        $queryCreator = new ProductsListSearchQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'SELECT id,date,code,name,description,short_description,price,images,categories,subcategory FROM shop WHERE colors_id IN (:0colors_id,:1colors_id) AND sizes_id IN (:0sizes_id,:1sizes_id) AND brands_id IN (:0brands_id) AND MATCH(:' . \Yii::$app->params['sphynxKey'] . ') ORDER BY date DESC LIMIT 0, 20';
        
        $this->assertEquals($query, $mockObject->query);
    }
}
