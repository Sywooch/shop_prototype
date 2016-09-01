<?php

namespace app\tests\queries;

use app\tests\{DbManager,
    MockObject};
use app\queries\GetColorsRelatedProductsAdminQueryCreator;

/**
 * Тестирует класс app\queries\GetColorsRelatedProductsAdminQueryCreator
 */
class GetColorsRelatedProductsAdminQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_categorySeocode = 'phones';
    private static $_subcategorySeocode = 'AVG';
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager();
        self::$_dbClass->createDb();
        
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
        
        $queryCreator = new GetColorsRelatedProductsAdminQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = "SELECT DISTINCT `colors`.`id`, `colors`.`color` FROM `colors` INNER JOIN `products_colors` ON `colors`.`id`=`products_colors`.`id_colors`";
        
        $this->assertEquals($query, $mockObject->query->createCommand()->getRawSql());
    }
    
    /**
     * Тестирует создание строки SQL запроса queryForCategory
     */
    public function testQueryForCategory()
    {
        \Yii::$app->filters->categories = self::$_categorySeocode;
        
        $mockObject = new MockObject([
            'tableName'=>'colors',
            'fields'=>['id', 'color'],
        ]);
        
        $queryCreator = new GetColorsRelatedProductsAdminQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = "SELECT DISTINCT `colors`.`id`, `colors`.`color` FROM `colors` INNER JOIN `products_colors` ON `colors`.`id`=`products_colors`.`id_colors` INNER JOIN `products` ON `products_colors`.`id_products`=`products`.`id` INNER JOIN `categories` ON `products`.`id_categories`=`categories`.`id` WHERE `categories`.`seocode`='" . self::$_categorySeocode . "'";
        
        $this->assertEquals($query, $mockObject->query->createCommand()->getRawSql());
    }
    
    /**
     * Тестирует создание строки SQL запроса queryForSubCategory
     */
    public function testQueryForSubCategory()
    {
        \Yii::$app->filters->categories = self::$_categorySeocode;
        \Yii::$app->filters->subcategory = self::$_subcategorySeocode;
        
        $mockObject = new MockObject([
            'tableName'=>'colors',
            'fields'=>['id', 'color'],
        ]);
        
        $queryCreator = new GetColorsRelatedProductsAdminQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = "SELECT DISTINCT `colors`.`id`, `colors`.`color` FROM `colors` INNER JOIN `products_colors` ON `colors`.`id`=`products_colors`.`id_colors` INNER JOIN `products` ON `products_colors`.`id_products`=`products`.`id` INNER JOIN `categories` ON `products`.`id_categories`=`categories`.`id` INNER JOIN `subcategory` ON `products`.`id_subcategory`=`subcategory`.`id` WHERE (`categories`.`seocode`='" . self::$_categorySeocode . "') AND (`subcategory`.`seocode`='" . self::$_subcategorySeocode . "')";
        
        $this->assertEquals($query, $mockObject->query->createCommand()->getRawSql());
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
