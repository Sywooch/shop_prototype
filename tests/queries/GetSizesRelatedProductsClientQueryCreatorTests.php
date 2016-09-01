<?php

namespace app\tests\queries;

use app\tests\{DbManager,
    MockObject};
use app\queries\GetSizesRelatedProductsClientQueryCreator;

/**
 * Тестирует класс app\queries\GetSizesRelatedProductsClientQueryCreator
 */
class GetSizesRelatedProductsClientQueryCreatorTests extends \PHPUnit_Framework_TestCase
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
    public function testQueryForAll()
    {
        $_GET = [];
        
        $mockObject = new MockObject([
            'tableName'=>'sizes',
            'fields'=>['id', 'size'],
        ]);
        
        $queryCreator = new GetSizesRelatedProductsClientQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = "SELECT DISTINCT `sizes`.`id`, `sizes`.`size` FROM `sizes` INNER JOIN `products_sizes` ON `sizes`.`id`=`products_sizes`.`id_sizes` INNER JOIN `products` ON `products_sizes`.`id_products`=`products`.`id` WHERE `products`.`active`=TRUE";
        
        $this->assertEquals($query, $mockObject->query->createCommand()->getRawSql());
    }
    
    /**
     * Тестирует создание строки SQL запроса queryForCategory
     */
    public function testQueryForCategory()
    {
        $_GET = ['categories'=>self::$_seocodeCategories];
        
        $mockObject = new MockObject([
            'tableName'=>'sizes',
            'fields'=>['id', 'size'],
        ]);
        
        $queryCreator = new GetSizesRelatedProductsClientQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = "SELECT DISTINCT `sizes`.`id`, `sizes`.`size` FROM `sizes` INNER JOIN `products_sizes` ON `sizes`.`id`=`products_sizes`.`id_sizes` INNER JOIN `products` ON `products_sizes`.`id_products`=`products`.`id` INNER JOIN `categories` ON `products`.`id_categories`=`categories`.`id` WHERE (`categories`.`seocode`='" . self::$_seocodeCategories . "') AND (`products`.`active`=TRUE)";
        
        $this->assertEquals($query, $mockObject->query->createCommand()->getRawSql());
    }
    
     /**
     * Тестирует создание строки SQL запроса queryForSubCategory
     */
    public function testQueryForSubCategory()
    {
        $_GET = ['categories'=>self::$_seocodeCategories, 'subcategory'=>self::$_seocodeSubcategory];
        
        $mockObject = new MockObject([
            'tableName'=>'sizes',
            'fields'=>['id', 'size'],
        ]);
        
        $queryCreator = new GetSizesRelatedProductsClientQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = "SELECT DISTINCT `sizes`.`id`, `sizes`.`size` FROM `sizes` INNER JOIN `products_sizes` ON `sizes`.`id`=`products_sizes`.`id_sizes` INNER JOIN `products` ON `products_sizes`.`id_products`=`products`.`id` INNER JOIN `categories` ON `products`.`id_categories`=`categories`.`id` INNER JOIN `subcategory` ON `products`.`id_subcategory`=`subcategory`.`id` WHERE ((`categories`.`seocode`='" . self::$_seocodeCategories . "') AND (`subcategory`.`seocode`='" . self::$_seocodeSubcategory . "')) AND (`products`.`active`=TRUE)";
        
        $this->assertEquals($query, $mockObject->query->createCommand()->getRawSql());
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
