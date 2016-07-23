<?php

namespace app\tests\queries;

use app\tests\MockObject;
use app\queries\SizesJoinProductsQueryCreator;

/**
 * Тестирует класс app\queries\SizesJoinProductsQueryCreator
 */
class SizesJoinProductsQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testQueryForAll()
    {
        $_GET = [];
        
        $mockObject = new MockObject([
            'tableName'=>'sizes',
            'fields'=>['id', 'size'],
        ]);
        
        $queryCreator = new SizesJoinProductsQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'SELECT DISTINCT [[sizes.id]],[[sizes.size]] FROM {{sizes}} JOIN {{products_sizes}} ON [[sizes.id]]=[[products_sizes.id_sizes]]';
        
        $this->assertEquals($query, $mockObject->query);
    }
    
    /**
     * Тестирует создание строки SQL запроса queryForCategory
     */
    public function testQueryForCategory()
    {
        $_GET = ['categories'=>'mensfootwear'];
        
        $mockObject = new MockObject([
            'tableName'=>'sizes',
            'fields'=>['id', 'size'],
        ]);
        
        $queryCreator = new SizesJoinProductsQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'SELECT DISTINCT [[sizes.id]],[[sizes.size]] FROM {{sizes}} JOIN {{products_sizes}} ON [[sizes.id]]=[[products_sizes.id_sizes]] JOIN {{products}} ON [[products_sizes.id_products]]=[[products.id]] JOIN {{categories}} ON [[products.id_categories]]=[[categories.id]] WHERE [[categories.seocode]]=:categories';
        
        $this->assertEquals($query, $mockObject->query);
    }
    
     /**
     * Тестирует создание строки SQL запроса queryForSubCategory
     */
    public function testQueryForSubCategory()
    {
        $_GET = ['categories'=>'mensfootwear', 'subcategory'=>'boots'];
        
        $mockObject = new MockObject([
            'tableName'=>'sizes',
            'fields'=>['id', 'size'],
        ]);
        
        $queryCreator = new SizesJoinProductsQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'SELECT DISTINCT [[sizes.id]],[[sizes.size]] FROM {{sizes}} JOIN {{products_sizes}} ON [[sizes.id]]=[[products_sizes.id_sizes]] JOIN {{products}} ON [[products_sizes.id_products]]=[[products.id]] JOIN {{categories}} ON [[products.id_categories]]=[[categories.id]] JOIN {{subcategory}} ON [[products.id_subcategory]]=[[subcategory.id]] WHERE [[categories.seocode]]=:categories AND [[subcategory.seocode]]=:subcategory';
        
        $this->assertEquals($query, $mockObject->query);
    }
}
