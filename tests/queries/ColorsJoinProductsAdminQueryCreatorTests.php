<?php

namespace app\tests\queries;

use app\tests\MockObject;
use app\queries\ColorsJoinProductsAdminQueryCreator;

/**
 * Тестирует класс app\queries\ColorsJoinProductsAdminQueryCreator
 */
class ColorsJoinProductsAdminQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    public static function setUpBeforeClass()
    {
        \Yii::$app->filters->clean();
        \Yii::$app->filters->cleanOther();
    }
    
    /**
     * Тестирует создание строки SQL запроса
     */
    public function testGetSelectQuery()
    {
        $mockObject = new MockObject([
            'tableName'=>'colors',
            'fields'=>['id', 'color'],
        ]);
        
        $queryCreator = new ColorsJoinProductsAdminQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'SELECT DISTINCT [[colors.id]],[[colors.color]] FROM {{colors}} JOIN {{products_colors}} ON [[colors.id]]=[[products_colors.id_colors]]';
        
        $this->assertEquals($query, $mockObject->query);
    }
    
    /**
     * Тестирует создание строки SQL запроса queryForCategory
     */
    public function testQueryForCategory()
    {
        \Yii::$app->filters->categories = 'mensfootwear';
        
        $mockObject = new MockObject([
            'tableName'=>'colors',
            'fields'=>['id', 'color'],
        ]);
        
        $queryCreator = new ColorsJoinProductsAdminQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'SELECT DISTINCT [[colors.id]],[[colors.color]] FROM {{colors}} JOIN {{products_colors}} ON [[colors.id]]=[[products_colors.id_colors]] JOIN {{products}} ON [[products_colors.id_products]]=[[products.id]] JOIN {{categories}} ON [[products.id_categories]]=[[categories.id]] WHERE [[categories.seocode]]=:categories';
        
        $this->assertEquals($query, $mockObject->query);
    }
    
    /**
     * Тестирует создание строки SQL запроса queryForSubCategory
     */
    public function testQueryForSubCategory()
    {
        \Yii::$app->filters->categories = 'mensfootwear';
        \Yii::$app->filters->subcategory = 'boots';
        
        $mockObject = new MockObject([
            'tableName'=>'colors',
            'fields'=>['id', 'color'],
        ]);
        
        $queryCreator = new ColorsJoinProductsAdminQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = 'SELECT DISTINCT [[colors.id]],[[colors.color]] FROM {{colors}} JOIN {{products_colors}} ON [[colors.id]]=[[products_colors.id_colors]] JOIN {{products}} ON [[products_colors.id_products]]=[[products.id]] JOIN {{categories}} ON [[products.id_categories]]=[[categories.id]] JOIN {{subcategory}} ON [[products.id_subcategory]]=[[subcategory.id]] WHERE [[categories.seocode]]=:categories AND [[subcategory.seocode]]=:subcategory';
        
        $this->assertEquals($query, $mockObject->query);
    }
}
