<?php

namespace app\tests\queries;

use app\tests\{DbManager,
    MockObject};
use app\queries\GetColorsRelatedProductsClientQueryCreator;

/**
 * Тестирует класс app\queries\GetColorsRelatedProductsClientQueryCreator
 */
class GetColorsRelatedProductsClientQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_active = true;
    private static $_seocodeCategories = 'mensfootwear';
    private static $_seocodeSubcategory = 'boots';
    
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
        $_GET = [];
        
        $mockObject = new MockObject([
            'tableName'=>'colors',
            'fields'=>['id', 'color'],
        ]);
        
        $queryCreator = new GetColorsRelatedProductsClientQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = "SELECT DISTINCT `colors`.`id`, `colors`.`color` FROM `colors` INNER JOIN `products_colors` ON `colors`.`id`=`products_colors`.`id_colors` INNER JOIN `products` ON `products_colors`.`id_products`=`products`.`id` WHERE `products`.`active`=TRUE";
        
        $this->assertEquals($query, $mockObject->query->createCommand()->getRawSql());
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
        
        $queryCreator = new GetColorsRelatedProductsClientQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = "SELECT DISTINCT `colors`.`id`, `colors`.`color` FROM `colors` INNER JOIN `products_colors` ON `colors`.`id`=`products_colors`.`id_colors` INNER JOIN `products` ON `products_colors`.`id_products`=`products`.`id` INNER JOIN `categories` ON `products`.`id_categories`=`categories`.`id` WHERE (`categories`.`seocode`='" . self::$_seocodeCategories . "') AND (`products`.`active`=TRUE)";
        
        $this->assertEquals($query, $mockObject->query->createCommand()->getRawSql());
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
        
        $queryCreator = new GetColorsRelatedProductsClientQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = "SELECT DISTINCT `colors`.`id`, `colors`.`color` FROM `colors` INNER JOIN `products_colors` ON `colors`.`id`=`products_colors`.`id_colors` INNER JOIN `products` ON `products_colors`.`id_products`=`products`.`id` INNER JOIN `categories` ON `products`.`id_categories`=`categories`.`id` INNER JOIN `subcategory` ON `products`.`id_subcategory`=`subcategory`.`id` WHERE ((`categories`.`seocode`='" . self::$_seocodeCategories . "') AND (`subcategory`.`seocode`='" . self::$_seocodeSubcategory . "')) AND (`products`.`active`=TRUE)";
        
        $this->assertEquals($query, $mockObject->query->createCommand()->getRawSql());
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
