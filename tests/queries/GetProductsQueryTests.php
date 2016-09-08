<?php

namespace app\tests;

use app\queries\GetProductsQuery;
use app\models\ProductsModel;

/**
 * Тестирует класс app\queries\GetProductsQuery
 */
class GetProductsQueryTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_id = 1;
    private static $_date = 1462453595;
    private static $_code = 'YU-6709';
    private static $_name = 'name';
    private static $_description = 'description';
    private static $_price = 14.45;
    private static $_images = 'images/';
    private static $_active = true;
    private static $_total_products = 23;
    private static $_categorySeocode = 'mensfootwear';
    private static $_subcategorySeocode = 'boots';
    private static $_color = 'grey';
    private static $_size = 45;
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager();
        self::$_dbClass->createDb();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{categories}} SET [[id]]=:id, [[name]]=:name, [[seocode]]=:seocode');
        $command->bindValues([':id'=>self::$_id, ':name'=>self::$_name, ':seocode'=>self::$_categorySeocode]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{subcategory}} SET [[id]]=:id, [[name]]=:name, [[id_categories]]=:id_categories, [[seocode]]=:seocode');
        $command->bindValues([':id'=>self::$_id, ':name'=>self::$_name, ':id_categories'=>self::$_id, ':seocode'=>self::$_subcategorySeocode]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{products}} SET [[id]]=:id, [[date]]=:date, [[code]]=:code, [[name]]=:name, [[short_description]]=:short_description, [[description]]=:description, [[price]]=:price, [[images]]=:images, [[id_categories]]=:id_categories, [[id_subcategory]]=:id_subcategory, [[active]]=:active, [[total_products]]=:total_products');
        $command->bindValues([':id'=>self::$_id, ':date'=>self::$_date, ':code'=>self::$_code, ':name'=>self::$_name, ':short_description'=>self::$_description, ':description'=>self::$_description, ':price'=>self::$_price, ':images'=>self::$_images, ':id_categories'=>self::$_id, ':id_subcategory'=>self::$_id, ':active'=>self::$_active, ':total_products'=>self::$_total_products]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{colors}} SET [[id]]=:id, [[color]]=:color');
        $command->bindValues([':id'=>self::$_id, ':color'=>self::$_color]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{products_colors}} SET [[id_products]]=:id_products, [[id_colors]]=:id_colors');
        $command->bindValues([':id_products'=>self::$_id, ':id_colors'=>self::$_id]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{sizes}} SET [[id]]=:id, [[size]]=:size');
        $command->bindValues([':id'=>self::$_id, ':size'=>self::$_size]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{products_sizes}} SET [[id_products]]=:id_products, [[id_sizes]]=:id_sizes');
        $command->bindValues([':id_products'=>self::$_id, ':id_sizes'=>self::$_id]);
        $command->execute();
    }
    
    /**
     * Тестирует метод GetProductsQuery::getAll()
     * без категорий и фильтров
     */
    public function testGetAll()
    {
        $_GET = [];
        \Yii::$app->filters->clean();
        
        $productsQuery = new GetProductsQuery([
            'fields'=>['id', 'date', 'name', 'short_description', 'price', 'images', 'id_categories', 'id_subcategory', 'active'],
            'sorting'=>['date'=>SORT_DESC]
        ]);
        
        $query = $productsQuery->getAll();
        $queryRaw = clone $query;
        
        $expectQuery = "SELECT `products`.`id`, `products`.`date`, `products`.`name`, `products`.`short_description`, `products`.`price`, `products`.`images`, `products`.`id_categories`, `products`.`id_subcategory`, `products`.`active` FROM `products` WHERE `products`.`active`=TRUE ORDER BY `products`.`date` DESC LIMIT 10";
        
        $this->assertEquals($expectQuery, $queryRaw->createCommand()->getRawSql());
        
        $result = $query->all();
        
        $this->assertTrue(is_array($result));
        $this->assertTrue($result[0] instanceof ProductsModel);
    }
    
    /**
     * Тестирует метод GetProductsQuery::getAll()
     * c выборкой по категории
     */
    public function testGetAllTwo()
    {
        $_GET = ['categories'=>self::$_categorySeocode];
        \Yii::$app->filters->clean();
        
        $productsQuery = new GetProductsQuery([
            'fields'=>['id', 'date', 'name', 'short_description', 'description', 'price', 'images', 'id_categories', 'id_subcategory', 'active'],
            'sorting'=>['date'=>SORT_ASC]
        ]);
        
        $query = $productsQuery->getAll();
        $queryRaw = clone $query;
        
        $expectQuery = "SELECT `products`.`id`, `products`.`date`, `products`.`name`, `products`.`short_description`, `products`.`description`, `products`.`price`, `products`.`images`, `products`.`id_categories`, `products`.`id_subcategory`, `products`.`active` FROM `products` INNER JOIN `categories` ON `categories`.`id`=`products`.`id_categories` WHERE (`categories`.`seocode`='mensfootwear') AND (`products`.`active`=TRUE) ORDER BY `products`.`date` LIMIT 10";
        
        $this->assertEquals($expectQuery, $queryRaw->createCommand()->getRawSql());
        
        $result = $query->all();
        
        $this->assertTrue(is_array($result));
        $this->assertTrue($result[0] instanceof ProductsModel);
    }
    
    /**
     * Тестирует метод GetProductsQuery::getAll()
     * c выборкой по категории и подкатегории
     */
    public function testGetAllThree()
    {
        $_GET = ['categories'=>self::$_categorySeocode, 'subcategory'=>self::$_subcategorySeocode];
        \Yii::$app->filters->clean();
        
        $productsQuery = new GetProductsQuery([
            'fields'=>['id', 'date', 'name', 'short_description', 'description', 'price', 'images', 'id_categories', 'id_subcategory', 'active'],
            'sorting'=>['price'=>SORT_DESC]
        ]);
        
        $query = $productsQuery->getAll();
        $queryRaw = clone $query;
        
        $expectQuery = "SELECT `products`.`id`, `products`.`date`, `products`.`name`, `products`.`short_description`, `products`.`description`, `products`.`price`, `products`.`images`, `products`.`id_categories`, `products`.`id_subcategory`, `products`.`active` FROM `products` INNER JOIN `categories` ON `categories`.`id`=`products`.`id_categories` INNER JOIN `subcategory` ON `subcategory`.`id`=`products`.`id_subcategory` WHERE ((`categories`.`seocode`='mensfootwear') AND (`subcategory`.`seocode`='boots')) AND (`products`.`active`=TRUE) ORDER BY `products`.`price` DESC LIMIT 10";
        
        $this->assertEquals($expectQuery, $queryRaw->createCommand()->getRawSql());
        
        $result = $query->all();
        
        $this->assertTrue(is_array($result));
        $this->assertTrue($result[0] instanceof ProductsModel);
    }
    
    /**
     * Тестирует метод GetProductsQuery::getAll()
     * c выборкой по категории, подкатегории и фильтру
     */
    public function testGetAllFour()
    {
        $_GET = ['categories'=>self::$_categorySeocode, 'subcategory'=>self::$_subcategorySeocode];
        
        \Yii::$app->filters->clean();
        \Yii::configure(\Yii::$app->filters, ['colors'=>[self::$_id]]);
        
        $productsQuery = new GetProductsQuery([
            'fields'=>['id', 'date', 'name', 'short_description', 'description', 'price', 'images', 'id_categories', 'id_subcategory', 'active'],
            'sorting'=>['price'=>SORT_DESC]
        ]);
        
        $query = $productsQuery->getAll();
        $queryRaw = clone $query;
        
        $expectQuery = "SELECT `products`.`id`, `products`.`date`, `products`.`name`, `products`.`short_description`, `products`.`description`, `products`.`price`, `products`.`images`, `products`.`id_categories`, `products`.`id_subcategory`, `products`.`active` FROM `products` INNER JOIN `categories` ON `categories`.`id`=`products`.`id_categories` INNER JOIN `subcategory` ON `subcategory`.`id`=`products`.`id_subcategory` INNER JOIN `products_colors` ON products.id=products_colors.id_products INNER JOIN `colors` ON products_colors.id_colors=colors.id WHERE (((`categories`.`seocode`='mensfootwear') AND (`subcategory`.`seocode`='boots')) AND (`products`.`active`=TRUE)) AND (`colors`.`id`=1) ORDER BY `products`.`price` DESC LIMIT 10";
        
        $this->assertEquals($expectQuery, $queryRaw->createCommand()->getRawSql());
        
        $result = $query->all();
        
        $this->assertTrue(is_array($result));
        $this->assertTrue($result[0] instanceof ProductsModel);
    }
    
    /**
     * Тестирует метод GetProductsQuery::getAll()
     * c выборкой по категории, подкатегории и нескольким фильтрам
     */
    public function testGetAllFive()
    {
        $_GET = ['categories'=>self::$_categorySeocode, 'subcategory'=>self::$_subcategorySeocode];
        
        \Yii::$app->filters->clean();
        \Yii::configure(\Yii::$app->filters, ['colors'=>[self::$_id], 'sizes'=>[self::$_id]]);
        
        $productsQuery = new GetProductsQuery([
            'fields'=>['id', 'date', 'name', 'short_description', 'description', 'price', 'images', 'id_categories', 'id_subcategory', 'active'],
            'sorting'=>['price'=>SORT_DESC]
        ]);
        
        $query = $productsQuery->getAll();
        $queryRaw = clone $query;
        
        $expectQuery = "SELECT `products`.`id`, `products`.`date`, `products`.`name`, `products`.`short_description`, `products`.`description`, `products`.`price`, `products`.`images`, `products`.`id_categories`, `products`.`id_subcategory`, `products`.`active` FROM `products` INNER JOIN `categories` ON `categories`.`id`=`products`.`id_categories` INNER JOIN `subcategory` ON `subcategory`.`id`=`products`.`id_subcategory` INNER JOIN `products_colors` ON products.id=products_colors.id_products INNER JOIN `colors` ON products_colors.id_colors=colors.id INNER JOIN `products_sizes` ON products.id=products_sizes.id_products INNER JOIN `sizes` ON products_sizes.id_sizes=sizes.id WHERE ((((`categories`.`seocode`='mensfootwear') AND (`subcategory`.`seocode`='boots')) AND (`products`.`active`=TRUE)) AND (`colors`.`id`=1)) AND (`sizes`.`id`=1) ORDER BY `products`.`price` DESC LIMIT 10";
        
        $this->assertEquals($expectQuery, $queryRaw->createCommand()->getRawSql());
        
        $result = $query->all();
        
        $this->assertTrue(is_array($result));
        $this->assertTrue($result[0] instanceof ProductsModel);
    }
    
    /**
     * Тестирует метод GetProductsQuery::getAll()
     * c выборкой по категории и нескольким фильтрам
     */
    public function testGetAllSix()
    {
        $_GET = ['categories'=>self::$_categorySeocode];
        
        \Yii::$app->filters->clean();
        \Yii::configure(\Yii::$app->filters, ['colors'=>[self::$_id,self::$_id + 1], 'sizes'=>[self::$_id]]);
        
        $productsQuery = new GetProductsQuery([
            'fields'=>['id', 'date', 'name', 'short_description', 'description', 'price', 'images', 'id_categories', 'id_subcategory', 'active'],
            'sorting'=>['date'=>SORT_DESC]
        ]);
        
        $query = $productsQuery->getAll();
        $queryRaw = clone $query;
        
        $expectQuery = "SELECT `products`.`id`, `products`.`date`, `products`.`name`, `products`.`short_description`, `products`.`description`, `products`.`price`, `products`.`images`, `products`.`id_categories`, `products`.`id_subcategory`, `products`.`active` FROM `products` INNER JOIN `categories` ON `categories`.`id`=`products`.`id_categories` INNER JOIN `products_colors` ON products.id=products_colors.id_products INNER JOIN `colors` ON products_colors.id_colors=colors.id INNER JOIN `products_sizes` ON products.id=products_sizes.id_products INNER JOIN `sizes` ON products_sizes.id_sizes=sizes.id WHERE (((`categories`.`seocode`='mensfootwear') AND (`products`.`active`=TRUE)) AND (`colors`.`id` IN (1, 2))) AND (`sizes`.`id`=1) ORDER BY `products`.`date` DESC LIMIT 10";
        
        $this->assertEquals($expectQuery, $queryRaw->createCommand()->getRawSql());
        
        $result = $query->all();
        
        $this->assertTrue(is_array($result));
        $this->assertTrue($result[0] instanceof ProductsModel);
    }
    
    /**
     * Тестирует метод GetProductsQuery::getOne()
     */
    public function testtestGetOne()
    {
         $_GET = [];
        
        \Yii::$app->filters->clean();
        
        $productsQuery = new GetProductsQuery([
            'fields'=>['id', 'date', 'name', 'short_description', 'description', 'price', 'images', 'id_categories', 'id_subcategory', 'active'],
            'extraWhere'=>['products.id'=>self::$_id]
        ]);
        
        $query = $productsQuery->getOne();
        $queryRaw = clone $query;
        
        $expectQuery = "SELECT `products`.`id`, `products`.`date`, `products`.`name`, `products`.`short_description`, `products`.`description`, `products`.`price`, `products`.`images`, `products`.`id_categories`, `products`.`id_subcategory`, `products`.`active` FROM `products` WHERE `products`.`id`=1";
        
        $this->assertEquals($expectQuery, $queryRaw->createCommand()->getRawSql());
        
        $result = $query->one();
        
        $this->assertTrue($result instanceof ProductsModel);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
