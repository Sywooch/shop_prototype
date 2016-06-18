<?php

namespace app\tests\queries;

use app\tests\MockObject;
use app\queries\BrandsQueryCreator;

/**
 * Тестирует класс app\queries\BrandsQueryCreator
 */
class BrandsQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
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
        
        $queryCreator = new BrandsQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'SELECT DISTINCT [[brands.id]],[[brands.brand]] FROM {{brands}} JOIN {{products_brands}} ON [[brands.id]]=[[products_brands.id_brands]]';
        
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
        
        $queryCreator = new BrandsQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'SELECT DISTINCT [[brands.id]],[[brands.brand]] FROM {{brands}} JOIN {{products_brands}} ON [[brands.id]]=[[products_brands.id_brands]] JOIN {{products}} ON [[products_brands.id_products]]=[[products.id]] JOIN {{categories}} ON [[products.id_categories]]=[[categories.id]] WHERE [[categories.seocode]]=:categories';
        
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
        
        $queryCreator = new BrandsQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'SELECT DISTINCT [[brands.id]],[[brands.brand]] FROM {{brands}} JOIN {{products_brands}} ON [[brands.id]]=[[products_brands.id_brands]] JOIN {{products}} ON [[products_brands.id_products]]=[[products.id]] JOIN {{categories}} ON [[products.id_categories]]=[[categories.id]] JOIN {{subcategory}} ON [[products.id_subcategory]]=[[subcategory.id]] WHERE [[categories.seocode]]=:categories AND [[subcategory.seocode]]=:subcategory';
        
        $this->assertEquals($query, $mockObject->query);
    }
}
