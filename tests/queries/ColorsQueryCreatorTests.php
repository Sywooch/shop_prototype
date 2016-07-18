<?php

namespace app\tests\queries;

use app\tests\MockObject;
use app\queries\ColorsQueryCreator;

/**
 * Тестирует класс app\queries\ColorsQueryCreator
 */
class ColorsQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetSelectQuery()
    {
        $_GET = [];
        
        $mockObject = new MockObject([
            'tableName'=>'colors',
            'fields'=>['id', 'color'],
        ]);
        
        $queryCreator = new ColorsQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'SELECT DISTINCT [[colors.id]],[[colors.color]] FROM {{colors}} JOIN {{products_colors}} ON [[colors.id]]=[[products_colors.id_colors]]';
        
        $this->assertEquals($query, $mockObject->query);
    }
    
    /**
     * Тестирует создание строки SQL запроса queryForCategory
     */
    public function testQueryForCategory()
    {
        $_GET = ['categories'=>'mensfootwear'];
        
        $mockObject = new MockObject([
            'tableName'=>'colors',
            'fields'=>['id', 'color'],
        ]);
        
        $queryCreator = new ColorsQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'SELECT DISTINCT [[colors.id]],[[colors.color]] FROM {{colors}} JOIN {{products_colors}} ON [[colors.id]]=[[products_colors.id_colors]] JOIN {{products}} ON [[products_colors.id_products]]=[[products.id]] JOIN {{categories}} ON [[products.id_categories]]=[[categories.id]] WHERE [[categories.seocode]]=:categories';
        
        $this->assertEquals($query, $mockObject->query);
    }
    
    /**
     * Тестирует создание строки SQL запроса queryForSubCategory
     */
    public function testQueryForSubCategory()
    {
        $_GET = ['categories'=>'mensfootwear', 'subcategory'=>'boots'];
        
        $mockObject = new MockObject([
            'tableName'=>'colors',
            'fields'=>['id', 'color'],
        ]);
        
        $queryCreator = new ColorsQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'SELECT DISTINCT [[colors.id]],[[colors.color]] FROM {{colors}} JOIN {{products_colors}} ON [[colors.id]]=[[products_colors.id_colors]] JOIN {{products}} ON [[products_colors.id_products]]=[[products.id]] JOIN {{categories}} ON [[products.id_categories]]=[[categories.id]] JOIN {{subcategory}} ON [[products.id_subcategory]]=[[subcategory.id]] WHERE [[categories.seocode]]=:categories AND [[subcategory.seocode]]=:subcategory';
        
        $this->assertEquals($query, $mockObject->query);
    }
}
