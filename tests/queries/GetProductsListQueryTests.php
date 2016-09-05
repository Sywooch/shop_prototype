<?php

namespace app\tests;

use app\queries\GetProductsListQuery;

/**
 * Тестирует класс app\queries\GetProductsListQuery
 */
class GetProductsListQueryTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_id = 1;
    private static $_categorySeocode = 'mensfootwear';
    private static $_subcategorySeocode = 'boots';
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager();
        self::$_dbClass->createDb();
    }
    
    /**
     * Тестирует метод GetProductsListQuery::getQuery()
     * без категорий и фильтров
     */
    public function testGetQuery()
    {
        $_GET = [];
        \Yii::$app->filters->clean();
        
        $productsQuery = new GetProductsListQuery([
            'fields'=>['id', 'date', 'name', 'short_description', 'price', 'images', 'id_categories', 'id_subcategory', 'active'],
            'sortingField'=>'date'
        ]);
        
        $query = "SELECT `products`.`id`, `products`.`date`, `products`.`name`, `products`.`short_description`, `products`.`price`, `products`.`images`, `products`.`id_categories`, `products`.`id_subcategory`, `products`.`active` FROM `products` WHERE `products`.`active`=TRUE ORDER BY `products`.`date` DESC LIMIT 20";
        
        $this->assertEquals($query, $productsQuery->getQuery()->createCommand()->getRawSql());
    }
    
    /**
     * Тестирует метод GetProductsListQuery::getQuery()
     * c выборкой по категории
     */
    public function testGetQueryTwo()
    {
        $_GET = ['categories'=>self::$_categorySeocode];
        \Yii::$app->filters->clean();
        
        $productsQuery = new GetProductsListQuery([
            'fields'=>['id', 'date', 'name', 'short_description', 'description', 'price', 'images', 'id_categories', 'id_subcategory', 'active'],
            'sortingField'=>'date',
            'sortingType'=>SORT_ASC
        ]);
        
        $query = "SELECT `products`.`id`, `products`.`date`, `products`.`name`, `products`.`short_description`, `products`.`description`, `products`.`price`, `products`.`images`, `products`.`id_categories`, `products`.`id_subcategory`, `products`.`active` FROM `products` INNER JOIN `categories` ON `categories`.`id`=`products`.`id_categories` WHERE (`categories`.`seocode`='mensfootwear') AND (`products`.`active`=TRUE) ORDER BY `products`.`date` LIMIT 20";
        
        $this->assertEquals($query, $productsQuery->getQuery()->createCommand()->getRawSql());
    }
    
    /**
     * Тестирует метод GetProductsListQuery::getQuery()
     * c выборкой по категории и подкатегории
     */
    public function testGetQueryThree()
    {
        $_GET = ['categories'=>self::$_categorySeocode, 'subcategory'=>self::$_subcategorySeocode];
        \Yii::$app->filters->clean();
        
        $productsQuery = new GetProductsListQuery([
            'fields'=>['id', 'date', 'name', 'short_description', 'description', 'price', 'images', 'id_categories', 'id_subcategory', 'active'],
            'sortingField'=>'price',
        ]);
        
        $query = "SELECT `products`.`id`, `products`.`date`, `products`.`name`, `products`.`short_description`, `products`.`description`, `products`.`price`, `products`.`images`, `products`.`id_categories`, `products`.`id_subcategory`, `products`.`active` FROM `products` INNER JOIN `categories` ON `categories`.`id`=`products`.`id_categories` INNER JOIN `subcategory` ON `subcategory`.`id`=`products`.`id_subcategory` WHERE ((`categories`.`seocode`='mensfootwear') AND (`subcategory`.`seocode`='boots')) AND (`products`.`active`=TRUE) ORDER BY `products`.`price` DESC LIMIT 20";
        
        $this->assertEquals($query, $productsQuery->getQuery()->createCommand()->getRawSql());
    }
    
    /**
     * Тестирует метод GetProductsListQuery::getQuery()
     * c выборкой по категории, подкатегории и фильтру
     */
    public function testGetQueryFour()
    {
        $_GET = ['categories'=>self::$_categorySeocode, 'subcategory'=>self::$_subcategorySeocode];
        
        \Yii::$app->filters->clean();
        \Yii::configure(\Yii::$app->filters, ['colors'=>[self::$_id]]);
        
        $productsQuery = new GetProductsListQuery([
            'fields'=>['id', 'date', 'name', 'short_description', 'description', 'price', 'images', 'id_categories', 'id_subcategory', 'active'],
            'sortingField'=>'price',
        ]);
        
        $query = "SELECT `products`.`id`, `products`.`date`, `products`.`name`, `products`.`short_description`, `products`.`description`, `products`.`price`, `products`.`images`, `products`.`id_categories`, `products`.`id_subcategory`, `products`.`active` FROM `products` INNER JOIN `categories` ON `categories`.`id`=`products`.`id_categories` INNER JOIN `subcategory` ON `subcategory`.`id`=`products`.`id_subcategory` INNER JOIN `products_colors` ON products.id=products_colors.id_products INNER JOIN `colors` ON products_colors.id_colors=colors.id WHERE (((`categories`.`seocode`='mensfootwear') AND (`subcategory`.`seocode`='boots')) AND (`products`.`active`=TRUE)) AND (`colors`.`id`=1) ORDER BY `products`.`price` DESC LIMIT 20";
        
        $this->assertEquals($query, $productsQuery->getQuery()->createCommand()->getRawSql());
    }
    
    /**
     * Тестирует метод GetProductsListQuery::getQuery()
     * c выборкой по категории, подкатегории и нескольким фильтрам
     */
    public function testGetQueryFive()
    {
        $_GET = ['categories'=>self::$_categorySeocode, 'subcategory'=>self::$_subcategorySeocode];
        
        \Yii::$app->filters->clean();
        \Yii::configure(\Yii::$app->filters, ['colors'=>[self::$_id], 'sizes'=>[self::$_id]]);
        
        $productsQuery = new GetProductsListQuery([
            'fields'=>['id', 'date', 'name', 'short_description', 'description', 'price', 'images', 'id_categories', 'id_subcategory', 'active'],
            'sortingField'=>'price',
        ]);
        
        $query = "SELECT `products`.`id`, `products`.`date`, `products`.`name`, `products`.`short_description`, `products`.`description`, `products`.`price`, `products`.`images`, `products`.`id_categories`, `products`.`id_subcategory`, `products`.`active` FROM `products` INNER JOIN `categories` ON `categories`.`id`=`products`.`id_categories` INNER JOIN `subcategory` ON `subcategory`.`id`=`products`.`id_subcategory` INNER JOIN `products_colors` ON products.id=products_colors.id_products INNER JOIN `colors` ON products_colors.id_colors=colors.id INNER JOIN `products_sizes` ON products.id=products_sizes.id_products INNER JOIN `sizes` ON products_sizes.id_sizes=sizes.id WHERE ((((`categories`.`seocode`='mensfootwear') AND (`subcategory`.`seocode`='boots')) AND (`products`.`active`=TRUE)) AND (`colors`.`id`=1)) AND (`sizes`.`id`=1) ORDER BY `products`.`price` DESC LIMIT 20";
        
        $this->assertEquals($query, $productsQuery->getQuery()->createCommand()->getRawSql());
    }
    
    /**
     * Тестирует метод GetProductsListQuery::getQuery()
     * c выборкой по категории и нескольким фильтрам
     */
    public function testGetQuerySix()
    {
        $_GET = ['categories'=>self::$_categorySeocode];
        
        \Yii::$app->filters->clean();
        \Yii::configure(\Yii::$app->filters, ['colors'=>[self::$_id,self::$_id + 1], 'sizes'=>[self::$_id]]);
        
        $productsQuery = new GetProductsListQuery([
            'fields'=>['id', 'date', 'name', 'short_description', 'description', 'price', 'images', 'id_categories', 'id_subcategory', 'active'],
            'sortingField'=>'date',
        ]);
        
        $query = "SELECT `products`.`id`, `products`.`date`, `products`.`name`, `products`.`short_description`, `products`.`description`, `products`.`price`, `products`.`images`, `products`.`id_categories`, `products`.`id_subcategory`, `products`.`active` FROM `products` INNER JOIN `categories` ON `categories`.`id`=`products`.`id_categories` INNER JOIN `products_colors` ON products.id=products_colors.id_products INNER JOIN `colors` ON products_colors.id_colors=colors.id INNER JOIN `products_sizes` ON products.id=products_sizes.id_products INNER JOIN `sizes` ON products_sizes.id_sizes=sizes.id WHERE (((`categories`.`seocode`='mensfootwear') AND (`products`.`active`=TRUE)) AND (`colors`.`id` IN (1, 2))) AND (`sizes`.`id`=1) ORDER BY `products`.`date` DESC LIMIT 20";
        
        $this->assertEquals($query, $productsQuery->getQuery()->createCommand()->getRawSql());
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
