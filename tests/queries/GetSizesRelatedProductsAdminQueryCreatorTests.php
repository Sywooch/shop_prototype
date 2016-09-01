<?php

namespace app\tests\queries;

use app\tests\{DbManager,
    MockObject};
use app\queries\GetSizesRelatedProductsAdminQueryCreator;

/**
 * Тестирует класс app\queries\GetSizesRelatedProductsAdminQueryCreator
 */
class GetSizesRelatedProductsAdminQueryCreatorTests extends \PHPUnit_Framework_TestCase
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
            'tableName'=>'sizes',
            'fields'=>['id', 'size'],
        ]);
        
        $queryCreator = new GetSizesRelatedProductsAdminQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = "SELECT DISTINCT `sizes`.`id`, `sizes`.`size` FROM `sizes` INNER JOIN `products_sizes` ON `sizes`.`id`=`products_sizes`.`id_sizes`";
        
        $this->assertEquals($query, $mockObject->query->createCommand()->getRawSql());
    }
    
    /**
     * Тестирует создание строки SQL запроса queryForCategory
     */
    public function testQueryForCategory()
    {
        \Yii::$app->filters->categories = self::$_categorySeocode;
        
        $mockObject = new MockObject([
            'tableName'=>'sizes',
            'fields'=>['id', 'size'],
        ]);
        
        $queryCreator = new GetSizesRelatedProductsAdminQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = "SELECT DISTINCT `sizes`.`id`, `sizes`.`size` FROM `sizes` INNER JOIN `products_sizes` ON `sizes`.`id`=`products_sizes`.`id_sizes` INNER JOIN `products` ON `products_sizes`.`id_products`=`products`.`id` INNER JOIN `categories` ON `products`.`id_categories`=`categories`.`id` WHERE `categories`.`seocode`='" . self::$_categorySeocode . "'";
        
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
            'tableName'=>'sizes',
            'fields'=>['id', 'size'],
        ]);
        
        $queryCreator = new GetSizesRelatedProductsAdminQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = "SELECT DISTINCT `sizes`.`id`, `sizes`.`size` FROM `sizes` INNER JOIN `products_sizes` ON `sizes`.`id`=`products_sizes`.`id_sizes` INNER JOIN `products` ON `products_sizes`.`id_products`=`products`.`id` INNER JOIN `categories` ON `products`.`id_categories`=`categories`.`id` INNER JOIN `subcategory` ON `products`.`id_subcategory`=`subcategory`.`id` WHERE (`categories`.`seocode`='" . self::$_categorySeocode . "') AND (`subcategory`.`seocode`='" . self::$_subcategorySeocode . "')";
        
        $this->assertEquals($query, $mockObject->query->createCommand()->getRawSql());
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
