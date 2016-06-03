<?php

namespace app\tests\queries;

use app\queries\BrandsQueryCreator;
use app\mappers\BrandsMapper;

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
        
        $brandsMapper = new BrandsMapper([
            'tableName'=>'brands',
            'fields'=>['id', 'brand'],
            'orderByField'=>'brand'
        ]);
        $brandsMapper->visit(new BrandsQueryCreator());
        
        $query = 'SELECT DISTINCT [[brands.id]],[[brands.brand]] FROM {{brands}}';
        
        $this->assertEquals($query, $brandsMapper->query);
    }
    
    /**
     * Тестирует создание строки SQL запроса queryForCategory
     */
    public function testQueryForCategory()
    {
        $_GET = ['categories'=>'mensfootwear'];
        
        $brandsMapper = new BrandsMapper([
            'tableName'=>'brands',
            'fields'=>['id', 'brand'],
            'orderByField'=>'brand'
        ]);
        $brandsMapper->visit(new BrandsQueryCreator());
        
        $query = 'SELECT DISTINCT [[brands.id]],[[brands.brand]] FROM {{brands}} JOIN {{products_brands}} ON [[brands.id]]=[[products_brands.id_brands]] JOIN {{products}} ON [[products_brands.id_products]]=[[products.id]] JOIN {{categories}} ON [[products.id_categories]]=[[categories.id]] WHERE [[categories.seocode]]=:categories';
        
        $this->assertEquals($query, $brandsMapper->query);
    }
    
    /**
     * Тестирует создание строки SQL запроса queryForSubCategory
     */
    public function testQueryForSubCategory()
    {
        $_GET = ['categories'=>'mensfootwear', 'subcategory'=>'pants'];
        
        $brandsMapper = new BrandsMapper([
            'tableName'=>'brands',
            'fields'=>['id', 'brand'],
            'orderByField'=>'brand'
        ]);
        $brandsMapper->visit(new BrandsQueryCreator());
        
        $query = 'SELECT DISTINCT [[brands.id]],[[brands.brand]] FROM {{brands}} JOIN {{products_brands}} ON [[brands.id]]=[[products_brands.id_brands]] JOIN {{products}} ON [[products_brands.id_products]]=[[products.id]] JOIN {{categories}} ON [[products.id_categories]]=[[categories.id]] JOIN {{subcategory}} ON [[products.id_subcategory]]=[[subcategory.id]] WHERE [[categories.seocode]]=:categories AND [[subcategory.seocode]]=:subcategory';
        
        $this->assertEquals($query, $brandsMapper->query);
    }
}
