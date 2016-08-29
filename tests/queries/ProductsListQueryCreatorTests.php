<?php

namespace app\tests\queries;

use app\tests\DbManager;
use app\tests\MockObject;
use app\queries\ProductsListQueryCreator;

/**
 * Тестирует класс app\queries\ProductsListQueryCreator
 */
class ProductsListQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_id = 1;
    private static $_categories = 'menswear';
    private static $_subcategory = 'coats';
    private static $_config = [
        'tableName'=>'products',
        'fields'=>['id', 'date', 'code', 'name', 'description', 'short_description', 'price', 'images'],
        'orderByField'=>'date'
    ];
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager();
        self::$_dbClass->createDb();
    }
    
    /**
     * Тестирует создание строки SQL запроса без категорий и фильтров ProductsListQueryCreator::queryForAll()
     */
    public function testQueryForAll()
    {
        $_GET = [];
        \Yii::$app->filters->clean();
        
        $mockObject = new MockObject(self::$_config);
        
        $queryCreator = new ProductsListQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = "SELECT DISTINCT `products`.`id`, `products`.`date`, `products`.`code`, `products`.`name`, `products`.`description`, `products`.`short_description`, `products`.`price`, `products`.`images`, `categories`.`seocode` AS `categories`, `subcategory`.`seocode` AS `subcategory` FROM `products` INNER JOIN `categories` ON `products`.`id_categories`=`categories`.`id` INNER JOIN `subcategory` ON `products`.`id_subcategory`=`subcategory`.`id` ORDER BY `products`.`date` DESC LIMIT 20";
        
        $this->assertEquals($query, $mockObject->query->createCommand()->getRawSql());
    }
    
    /**
     * Тестирует создание строки SQL c выборкой по категории ProductsListQueryCreator::queryForCategory()
     */
    public function testQueryForCategory()
    {
        $_GET = ['categories'=>self::$_categories];
        \Yii::$app->filters->clean();
        
        $mockObject = new MockObject(self::$_config);
        
        $queryCreator = new ProductsListQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = "SELECT DISTINCT `products`.`id`, `products`.`date`, `products`.`code`, `products`.`name`, `products`.`description`, `products`.`short_description`, `products`.`price`, `products`.`images`, `categories`.`seocode` AS `categories`, `subcategory`.`seocode` AS `subcategory` FROM `products` INNER JOIN `categories` ON `products`.`id_categories`=`categories`.`id` INNER JOIN `subcategory` ON `products`.`id_subcategory`=`subcategory`.`id` WHERE `categories`.`seocode`='" . self::$_categories . "' ORDER BY `products`.`date` DESC LIMIT 20";
        
        $this->assertEquals($query, $mockObject->query->createCommand()->getRawSql());
    }
    
    /**
     * Тестирует создание строки SQL c выборкой по категории и подкатегории ProductsListQueryCreator::queryForSubCategory()
     */
    public function testQueryForSubCategory()
    {
        $_GET = ['categories'=>self::$_categories, 'subcategory'=>self::$_subcategory];
        \Yii::$app->filters->clean();
        
        $mockObject = new MockObject(self::$_config);
        
        $queryCreator = new ProductsListQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = "SELECT DISTINCT `products`.`id`, `products`.`date`, `products`.`code`, `products`.`name`, `products`.`description`, `products`.`short_description`, `products`.`price`, `products`.`images`, `categories`.`seocode` AS `categories`, `subcategory`.`seocode` AS `subcategory` FROM `products` INNER JOIN `categories` ON `products`.`id_categories`=`categories`.`id` INNER JOIN `subcategory` ON `products`.`id_subcategory`=`subcategory`.`id` WHERE (`categories`.`seocode`='" . self::$_categories . "') AND (`subcategory`.`seocode`='" . self::$_subcategory . "') ORDER BY `products`.`date` DESC LIMIT 20";
        
        $this->assertEquals($query, $mockObject->query->createCommand()->getRawSql());
    }
    
    /**
     * Тестирует создание строки SQL c выборкой по категории, подкатегории и фильтру ProductsListQueryCreator::queryForSubCategory(), 
     * ProductsListQueryCreator::addFilters()
     */
    public function testQueryForSubCategoryAndFilter()
    {
        $_GET = ['categories'=>self::$_categories, 'subcategory'=>self::$_subcategory];
        
        \Yii::$app->filters->clean();
        \Yii::configure(\Yii::$app->filters, ['colors'=>[self::$_id]]);
        
        $mockObject = new MockObject(self::$_config);
        
        $queryCreator = new ProductsListQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = "SELECT DISTINCT `products`.`id`, `products`.`date`, `products`.`code`, `products`.`name`, `products`.`description`, `products`.`short_description`, `products`.`price`, `products`.`images`, `categories`.`seocode` AS `categories`, `subcategory`.`seocode` AS `subcategory` FROM `products` INNER JOIN `categories` ON `products`.`id_categories`=`categories`.`id` INNER JOIN `subcategory` ON `products`.`id_subcategory`=`subcategory`.`id` INNER JOIN `products_colors` ON products.id=products_colors.id_products INNER JOIN `colors` ON products_colors.id_colors=colors.id WHERE (`colors`.`id`=" . self::$_id . ") AND ((`categories`.`seocode`='" . self::$_categories . "') AND (`subcategory`.`seocode`='" . self::$_subcategory . "')) ORDER BY `products`.`date` DESC LIMIT 20";
        
        $this->assertEquals($query, $mockObject->query->createCommand()->getRawSql());
    }
    
    /**
     * Тестирует создание строки SQL c выборкой по категории, подкатегории и нескольким фильтрам ProductsListQueryCreator::queryForSubCategory(), 
     * ProductsListQueryCreator::addFilters()
     */
    public function testQueryForSubCategoryAndManyFilters()
    {
        $_GET = ['categories'=>self::$_categories, 'subcategory'=>self::$_subcategory];
        
        \Yii::$app->filters->clean();
        \Yii::configure(\Yii::$app->filters, ['colors'=>[self::$_id], 'sizes'=>[self::$_id]]);
        
        $mockObject = new MockObject(self::$_config);
        
        $queryCreator = new ProductsListQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = "SELECT DISTINCT `products`.`id`, `products`.`date`, `products`.`code`, `products`.`name`, `products`.`description`, `products`.`short_description`, `products`.`price`, `products`.`images`, `categories`.`seocode` AS `categories`, `subcategory`.`seocode` AS `subcategory` FROM `products` INNER JOIN `categories` ON `products`.`id_categories`=`categories`.`id` INNER JOIN `subcategory` ON `products`.`id_subcategory`=`subcategory`.`id` INNER JOIN `products_colors` ON products.id=products_colors.id_products INNER JOIN `colors` ON products_colors.id_colors=colors.id INNER JOIN `products_sizes` ON products.id=products_sizes.id_products INNER JOIN `sizes` ON products_sizes.id_sizes=sizes.id WHERE ((`colors`.`id`=". self::$_id . ") AND (`sizes`.`id`=". self::$_id . ")) AND ((`categories`.`seocode`='" . self::$_categories . "') AND (`subcategory`.`seocode`='" . self::$_subcategory . "')) ORDER BY `products`.`date` DESC LIMIT 20";
        
        $this->assertEquals($query, $mockObject->query->createCommand()->getRawSql());
    }
    
    /**
     * Тестирует создание строки SQL запроса без категорий, но с фильтром ProductsListQueryCreator::queryForAll(), ProductsListQueryCreator::addFilters()
     */
    public function testQueryForAllAndFilter()
    {
        $_GET = [];
        
        \Yii::$app->filters->clean();
        \Yii::configure(\Yii::$app->filters, ['colors'=>[self::$_id], 'sizes'=>[]]);
        
        $mockObject = new MockObject(self::$_config);
        
        $queryCreator = new ProductsListQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = "SELECT DISTINCT `products`.`id`, `products`.`date`, `products`.`code`, `products`.`name`, `products`.`description`, `products`.`short_description`, `products`.`price`, `products`.`images`, `categories`.`seocode` AS `categories`, `subcategory`.`seocode` AS `subcategory` FROM `products` INNER JOIN `categories` ON `products`.`id_categories`=`categories`.`id` INNER JOIN `subcategory` ON `products`.`id_subcategory`=`subcategory`.`id` INNER JOIN `products_colors` ON products.id=products_colors.id_products INNER JOIN `colors` ON products_colors.id_colors=colors.id WHERE `colors`.`id`=" . self::$_id . " ORDER BY `products`.`date` DESC LIMIT 20";
        
        $this->assertEquals($query, $mockObject->query->createCommand()->getRawSql());
    }
    
    /**
     * Тестирует создание строки SQL запроса без категорий, но с несколькими фильтрами ProductsListQueryCreator::queryForAll(), 
     * ProductsListQueryCreator::addFilters()
     */
    public function testQueryForAllAndManyFilters()
    {
        $_GET = [];
        
        \Yii::$app->filters->clean();
        \Yii::configure(\Yii::$app->filters, ['colors'=>[self::$_id], 'sizes'=>[self::$_id]]);
        
        $mockObject = new MockObject(self::$_config);
        
        $queryCreator = new ProductsListQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = "SELECT DISTINCT `products`.`id`, `products`.`date`, `products`.`code`, `products`.`name`, `products`.`description`, `products`.`short_description`, `products`.`price`, `products`.`images`, `categories`.`seocode` AS `categories`, `subcategory`.`seocode` AS `subcategory` FROM `products` INNER JOIN `categories` ON `products`.`id_categories`=`categories`.`id` INNER JOIN `subcategory` ON `products`.`id_subcategory`=`subcategory`.`id` INNER JOIN `products_colors` ON products.id=products_colors.id_products INNER JOIN `colors` ON products_colors.id_colors=colors.id INNER JOIN `products_sizes` ON products.id=products_sizes.id_products INNER JOIN `sizes` ON products_sizes.id_sizes=sizes.id WHERE (`colors`.`id`=" . self::$_id . ") AND (`sizes`.`id`=" . self::$_id . ") ORDER BY `products`.`date` DESC LIMIT 20";
        
        $this->assertEquals($query, $mockObject->query->createCommand()->getRawSql());
    }
    
    /**
     * Тестирует создание строки SQL c выборкой по категории и фильтру ProductsListQueryCreator::queryForCategory(), ProductsListQueryCreator::addFilters()
     */
    public function testQueryForCategoryAndFilter()
    {
        $_GET = ['categories'=>self::$_categories];
        
        \Yii::configure(\Yii::$app->filters, ['colors'=>[], 'sizes'=>[self::$_id]]);
        
        $mockObject = new MockObject(self::$_config);
        
        $queryCreator = new ProductsListQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = "SELECT DISTINCT `products`.`id`, `products`.`date`, `products`.`code`, `products`.`name`, `products`.`description`, `products`.`short_description`, `products`.`price`, `products`.`images`, `categories`.`seocode` AS `categories`, `subcategory`.`seocode` AS `subcategory` FROM `products` INNER JOIN `categories` ON `products`.`id_categories`=`categories`.`id` INNER JOIN `subcategory` ON `products`.`id_subcategory`=`subcategory`.`id` INNER JOIN `products_sizes` ON products.id=products_sizes.id_products INNER JOIN `sizes` ON products_sizes.id_sizes=sizes.id WHERE (`sizes`.`id`=" . self::$_id . ") AND (`categories`.`seocode`='" . self::$_categories . "') ORDER BY `products`.`date` DESC LIMIT 20";
        
        $this->assertEquals($query, $mockObject->query->createCommand()->getRawSql());
    }
    
    /**
     * Тестирует создание строки SQL c выборкой по категории и нескольким фильтрам ProductsListQueryCreator::queryForCategory(), 
     * ProductsListQueryCreator::addFilters()
     */
    public function testQueryForCategoryAndMenyFilters()
    {
        $_GET = ['categories'=>self::$_categories];
        
        \Yii::$app->filters->clean();
        \Yii::configure(\Yii::$app->filters, ['colors'=>[self::$_id,self::$_id + 1], 'sizes'=>[self::$_id]]);
        
        $mockObject = new MockObject(self::$_config);
        
        $queryCreator = new ProductsListQueryCreator();
        $queryCreator->update($mockObject);
        
        $query = "SELECT DISTINCT `products`.`id`, `products`.`date`, `products`.`code`, `products`.`name`, `products`.`description`, `products`.`short_description`, `products`.`price`, `products`.`images`, `categories`.`seocode` AS `categories`, `subcategory`.`seocode` AS `subcategory` FROM `products` INNER JOIN `categories` ON `products`.`id_categories`=`categories`.`id` INNER JOIN `subcategory` ON `products`.`id_subcategory`=`subcategory`.`id` INNER JOIN `products_colors` ON products.id=products_colors.id_products INNER JOIN `colors` ON products_colors.id_colors=colors.id INNER JOIN `products_sizes` ON products.id=products_sizes.id_products INNER JOIN `sizes` ON products_sizes.id_sizes=sizes.id WHERE ((`colors`.`id` IN (" . self::$_id . ', ' . (self::$_id+1) . ")) AND (`sizes`.`id`=" . self::$_id . ")) AND (`categories`.`seocode`='" . self::$_categories . "') ORDER BY `products`.`date` DESC LIMIT 20";
        
        $this->assertEquals($query, $mockObject->query->createCommand()->getRawSql());
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
