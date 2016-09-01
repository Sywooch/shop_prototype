<?php

namespace app\tests\queries;

use app\tests\{DbManager,
    MockObject};
use app\queries\GetBrandsRelatedProductsClientQueryCreator;

/**
 * Тестирует класс app\queries\GetBrandsRelatedProductsClientQueryCreator
 */
class GetBrandsRelatedProductsClientQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_categoriesSeocode = 'shoes';
    private static $_subcategorySeocode = 'boots';
    
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
            'tableName'=>'brands',
            'fields'=>['id', 'brand'],
        ]);
        
        $queryCreator = new GetBrandsRelatedProductsClientQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = "SELECT DISTINCT `brands`.`id`, `brands`.`brand` FROM `brands` INNER JOIN `products_brands` ON `brands`.`id`=`products_brands`.`id_brands` INNER JOIN `products` ON `products_brands`.`id_products`=`products`.`id` WHERE `products`.`active`=TRUE";
        
        $this->assertEquals($query, $mockObject->query->createCommand()->getRawSql());
    }
    
    /**
     * Тестирует создание строки SQL запроса queryForCategory
     */
    public function testQueryForCategory()
    {
        $_GET = ['categories'=>self::$_categoriesSeocode];
        
        $mockObject = new MockObject([
            'tableName'=>'brands',
            'fields'=>['id', 'brand'],
        ]);
        
        $queryCreator = new GetBrandsRelatedProductsClientQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = "SELECT DISTINCT `brands`.`id`, `brands`.`brand` FROM `brands` INNER JOIN `products_brands` ON `brands`.`id`=`products_brands`.`id_brands` INNER JOIN `products` ON `products_brands`.`id_products`=`products`.`id` INNER JOIN `categories` ON `products`.`id_categories`=`categories`.`id` WHERE (`categories`.`seocode`='" . self::$_categoriesSeocode . "') AND (`products`.`active`=TRUE)";
        
        $this->assertEquals($query, $mockObject->query->createCommand()->getRawSql());
    }
    
    /**
     * Тестирует создание строки SQL запроса queryForSubCategory
     */
    public function testQueryForSubCategory()
    {
        $_GET = ['categories'=>self::$_categoriesSeocode, 'subcategory'=>self::$_subcategorySeocode];
        
        $mockObject = new MockObject([
            'tableName'=>'brands',
            'fields'=>['id', 'brand'],
        ]);
        
        $queryCreator = new GetBrandsRelatedProductsClientQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = "SELECT DISTINCT `brands`.`id`, `brands`.`brand` FROM `brands` INNER JOIN `products_brands` ON `brands`.`id`=`products_brands`.`id_brands` INNER JOIN `products` ON `products_brands`.`id_products`=`products`.`id` INNER JOIN `categories` ON `products`.`id_categories`=`categories`.`id` INNER JOIN `subcategory` ON `products`.`id_subcategory`=`subcategory`.`id` WHERE ((`categories`.`seocode`='" . self::$_categoriesSeocode . "') AND (`subcategory`.`seocode`='" . self::$_subcategorySeocode . "')) AND (`products`.`active`=TRUE)";
        
        $this->assertEquals($query, $mockObject->query->createCommand()->getRawSql());
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
