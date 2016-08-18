<?php

namespace app\tests\queries;

use app\tests\MockObject;
use app\queries\BrandsJoinProductsClientQueryCreator;

/**
 * Тестирует класс app\queries\BrandsJoinProductsClientQueryCreator
 */
class BrandsJoinProductsClientQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    public static function setUpBeforeClass()
    {
        \Yii::$app->filters->clean();
        \Yii::$app->filters->cleanOther();
    }
    
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testQueryForAll()
    {
        $_GET = [];
        
        $mockObject = new MockObject([
            'tableName'=>'brands',
            'fields'=>['id', 'brand'],
        ]);
        
        $queryCreator = new BrandsJoinProductsClientQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'SELECT DISTINCT [[brands.id]],[[brands.brand]] FROM {{brands}} JOIN {{products_brands}} ON [[brands.id]]=[[products_brands.id_brands]] JOIN {{products}} ON [[products_brands.id_products]]=[[products.id]] WHERE [[products.active]]=:active';
        
        $this->assertEquals($query, $mockObject->query);
    }
    
    /**
     * Тестирует создание строки SQL запроса queryForCategory
     */
    public function testQueryForCategory()
    {
        $_GET = ['categories'=>'mensfootwear'];
        
        $mockObject = new MockObject([
            'tableName'=>'brands',
            'fields'=>['id', 'brand'],
        ]);
        
        $queryCreator = new BrandsJoinProductsClientQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'SELECT DISTINCT [[brands.id]],[[brands.brand]] FROM {{brands}} JOIN {{products_brands}} ON [[brands.id]]=[[products_brands.id_brands]] JOIN {{products}} ON [[products_brands.id_products]]=[[products.id]] JOIN {{categories}} ON [[products.id_categories]]=[[categories.id]] WHERE [[categories.seocode]]=:categories AND [[products.active]]=:active';
        
        $this->assertEquals($query, $mockObject->query);
    }
    
    /**
     * Тестирует создание строки SQL запроса queryForSubCategory
     */
    public function testQueryForSubCategory()
    {
        $_GET = ['categories'=>'mensfootwear', 'subcategory'=>'boots'];
        
        $mockObject = new MockObject([
            'tableName'=>'brands',
            'fields'=>['id', 'brand'],
        ]);
        
        $queryCreator = new BrandsJoinProductsClientQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'SELECT DISTINCT [[brands.id]],[[brands.brand]] FROM {{brands}} JOIN {{products_brands}} ON [[brands.id]]=[[products_brands.id_brands]] JOIN {{products}} ON [[products_brands.id_products]]=[[products.id]] JOIN {{categories}} ON [[products.id_categories]]=[[categories.id]] JOIN {{subcategory}} ON [[products.id_subcategory]]=[[subcategory.id]] WHERE [[categories.seocode]]=:categories AND [[subcategory.seocode]]=:subcategory AND [[products.active]]=:active';
        
        $this->assertEquals($query, $mockObject->query);
    }
}
