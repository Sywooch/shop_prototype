<?php

namespace app\tests\queries;

use app\queries\SizesQueryCreator;
use app\mappers\SizesMapper;

/**
 * Тестирует класс app\queries\SizesQueryCreator
 */
class SizesQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testQueryForAll()
    {
        $_GET = [];
        
        $sizesMapper = new SizesMapper([
            'tableName'=>'sizes',
            'fields'=>['id', 'size'],
            'orderByField'=>'size'
        ]);
        $sizesMapper->visit(new SizesQueryCreator());
        
        $query = 'SELECT DISTINCT [[sizes.id]],[[sizes.size]] FROM {{sizes}} JOIN {{products_sizes}} ON [[sizes.id]]=[[products_sizes.id_sizes]]';
        
        $this->assertEquals($query, $sizesMapper->query);
    }
    
    /**
     * Тестирует создание строки SQL запроса queryForCategory
     */
    public function testQueryForCategory()
    {
        $_GET = ['categories'=>'mensfootwear'];
        
        $sizesMapper = new SizesMapper([
            'tableName'=>'sizes',
            'fields'=>['id', 'size'],
            'orderByField'=>'size'
        ]);
        $sizesMapper->visit(new SizesQueryCreator());
        
        $query = 'SELECT DISTINCT [[sizes.id]],[[sizes.size]] FROM {{sizes}} JOIN {{products_sizes}} ON [[sizes.id]]=[[products_sizes.id_sizes]] JOIN {{products}} ON [[products_sizes.id_products]]=[[products.id]] JOIN {{categories}} ON [[products.id_categories]]=[[categories.id]] WHERE [[categories.seocode]]=:categories';
        
        $this->assertEquals($query, $sizesMapper->query);
    }
    
     /**
     * Тестирует создание строки SQL запроса queryForSubCategory
     */
    public function testQueryForSubCategory()
    {
        $_GET = ['categories'=>'mensfootwear', 'subcategory'=>'boots'];
        
        $sizesMapper = new SizesMapper([
            'tableName'=>'sizes',
            'fields'=>['id', 'size'],
            'orderByField'=>'size'
        ]);
        $sizesMapper->visit(new SizesQueryCreator());
        
        $query = 'SELECT DISTINCT [[sizes.id]],[[sizes.size]] FROM {{sizes}} JOIN {{products_sizes}} ON [[sizes.id]]=[[products_sizes.id_sizes]] JOIN {{products}} ON [[products_sizes.id_products]]=[[products.id]] JOIN {{categories}} ON [[products.id_categories]]=[[categories.id]] JOIN {{subcategory}} ON [[products.id_subcategory]]=[[subcategory.id]] WHERE [[categories.seocode]]=:categories AND [[subcategory.seocode]]=:subcategory';
        
        $this->assertEquals($query, $sizesMapper->query);
    }
}
