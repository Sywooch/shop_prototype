<?php

namespace app\tests\queries;

use PHPUnit\Framework\TestCase;
use app\tests\DbManager;
use app\tests\source\fixtures\{CategoriesFixture,
    ColorsFixture,
    ProductsColorsFixture,
    ProductsFixture,
    ProductsSizesFixture,
    SizesFixture,
    SubcategoryFixture};
use app\queries\GetProductsQuery;
use app\models\ProductsModel;

/**
 * Тестирует класс app\queries\GetProductsQuery
 */
class GetProductsQueryTests extends TestCase
{
    private static $_dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager([
            'fixtures'=>[
                'categories'=>CategoriesFixture::className(),
                'subcategory'=>SubcategoryFixture::className(),
                'products'=>ProductsFixture::className(),
                'colors'=>ColorsFixture::className(),
                'products_colors'=>ProductsColorsFixture::className(),
                'sizes'=>SizesFixture::className(),
                'products_sizes'=>ProductsSizesFixture::className()
            ],
        ]);
        self::$_dbClass->loadFixtures();
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
            'fields'=>['id', 'date', 'name', 'short_description', 'price', 'images', 'id_category', 'id_subcategory', 'active'],
            'sorting'=>['date'=>SORT_DESC]
        ]);
        
        $query = $productsQuery->getAll();
        $queryRaw = clone $query;
        
        $expectQuery = "SELECT `products`.`id`, `products`.`date`, `products`.`name`, `products`.`short_description`, `products`.`price`, `products`.`images`, `products`.`id_category`, `products`.`id_subcategory`, `products`.`active` FROM `products` WHERE `products`.`active`=TRUE ORDER BY `products`.`date` DESC LIMIT " . \Yii::$app->params['limit'];
        
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
        $fixture = self::$_dbClass->categories['category_1'];
        
        $_GET = ['category'=>$fixture['seocode']];
        \Yii::$app->filters->clean();
        
        $productsQuery = new GetProductsQuery([
            'fields'=>['id', 'date', 'name', 'short_description', 'description', 'price', 'images', 'id_category', 'id_subcategory', 'active'],
            'sorting'=>['date'=>SORT_ASC]
        ]);
        
        $query = $productsQuery->getAll();
        $queryRaw = clone $query;
        
        $expectQuery = "SELECT `products`.`id`, `products`.`date`, `products`.`name`, `products`.`short_description`, `products`.`description`, `products`.`price`, `products`.`images`, `products`.`id_category`, `products`.`id_subcategory`, `products`.`active` FROM `products` INNER JOIN `categories` ON `categories`.`id`=`products`.`id_category` WHERE (`categories`.`seocode`='mensfootwear') AND (`products`.`active`=TRUE) ORDER BY `products`.`date` LIMIT " . \Yii::$app->params['limit'];
        
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
        $fixture = self::$_dbClass->categories['category_1'];
        $fixtureSubcategory = self::$_dbClass->subcategory['subcategory_1'];
        
        $_GET = ['category'=>$fixture['seocode'], 'subcategory'=>$fixtureSubcategory['seocode']];
        \Yii::$app->filters->clean();
        
        $productsQuery = new GetProductsQuery([
            'fields'=>['id', 'date', 'name', 'short_description', 'description', 'price', 'images', 'id_category', 'id_subcategory', 'active'],
            'sorting'=>['price'=>SORT_DESC]
        ]);
        
        $query = $productsQuery->getAll();
        $queryRaw = clone $query;
        
        $expectQuery = "SELECT `products`.`id`, `products`.`date`, `products`.`name`, `products`.`short_description`, `products`.`description`, `products`.`price`, `products`.`images`, `products`.`id_category`, `products`.`id_subcategory`, `products`.`active` FROM `products` INNER JOIN `categories` ON `categories`.`id`=`products`.`id_category` INNER JOIN `subcategory` ON `subcategory`.`id`=`products`.`id_subcategory` WHERE ((`categories`.`seocode`='mensfootwear') AND (`subcategory`.`seocode`='boots')) AND (`products`.`active`=TRUE) ORDER BY `products`.`price` DESC LIMIT " . \Yii::$app->params['limit'];
        
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
        $fixture = self::$_dbClass->categories['category_1'];
        $fixtureSubcategory = self::$_dbClass->subcategory['subcategory_1'];
        $fixtureColors = self::$_dbClass->colors['color_1'];
        
        $_GET = ['category'=>$fixture['seocode'], 'subcategory'=>$fixtureSubcategory['seocode']];
        
        \Yii::$app->filters->clean();
        \Yii::configure(\Yii::$app->filters, ['colors'=>[(int) $fixtureColors['id']]]);
        
        $productsQuery = new GetProductsQuery([
            'fields'=>['id', 'date', 'name', 'short_description', 'description', 'price', 'images', 'id_category', 'id_subcategory', 'active'],
            'sorting'=>['price'=>SORT_DESC]
        ]);
        
        $query = $productsQuery->getAll();
        $queryRaw = clone $query;
        
        $expectQuery = "SELECT `products`.`id`, `products`.`date`, `products`.`name`, `products`.`short_description`, `products`.`description`, `products`.`price`, `products`.`images`, `products`.`id_category`, `products`.`id_subcategory`, `products`.`active` FROM `products` INNER JOIN `categories` ON `categories`.`id`=`products`.`id_category` INNER JOIN `subcategory` ON `subcategory`.`id`=`products`.`id_subcategory` INNER JOIN `products_colors` ON products.id=products_colors.id_product INNER JOIN `colors` ON products_colors.id_color=colors.id WHERE (((`categories`.`seocode`='mensfootwear') AND (`subcategory`.`seocode`='boots')) AND (`products`.`active`=TRUE)) AND (`colors`.`id`=1) ORDER BY `products`.`price` DESC LIMIT " . \Yii::$app->params['limit'];
        
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
        $fixture = self::$_dbClass->categories['category_1'];
        $fixtureSubcategory = self::$_dbClass->subcategory['subcategory_1'];
        $fixtureColors = self::$_dbClass->colors['color_1'];
        $fixtureSizes = self::$_dbClass->sizes['size_1'];
        
        $_GET = ['category'=>$fixture['seocode'], 'subcategory'=>$fixtureSubcategory['seocode']];
        
        \Yii::$app->filters->clean();
        \Yii::configure(\Yii::$app->filters, ['colors'=>[(int) $fixtureColors['id']], 'sizes'=>[(int) $fixtureSizes['id']]]);
        
        $productsQuery = new GetProductsQuery([
            'fields'=>['id', 'date', 'name', 'short_description', 'description', 'price', 'images', 'id_category', 'id_subcategory', 'active'],
            'sorting'=>['price'=>SORT_DESC]
        ]);
        
        $query = $productsQuery->getAll();
        $queryRaw = clone $query;
        
        $expectQuery = "SELECT `products`.`id`, `products`.`date`, `products`.`name`, `products`.`short_description`, `products`.`description`, `products`.`price`, `products`.`images`, `products`.`id_category`, `products`.`id_subcategory`, `products`.`active` FROM `products` INNER JOIN `categories` ON `categories`.`id`=`products`.`id_category` INNER JOIN `subcategory` ON `subcategory`.`id`=`products`.`id_subcategory` INNER JOIN `products_colors` ON products.id=products_colors.id_product INNER JOIN `colors` ON products_colors.id_color=colors.id INNER JOIN `products_sizes` ON products.id=products_sizes.id_product INNER JOIN `sizes` ON products_sizes.id_size=sizes.id WHERE ((((`categories`.`seocode`='mensfootwear') AND (`subcategory`.`seocode`='boots')) AND (`products`.`active`=TRUE)) AND (`colors`.`id`=1)) AND (`sizes`.`id`=1) ORDER BY `products`.`price` DESC LIMIT " . \Yii::$app->params['limit'];
        
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
        $fixture = self::$_dbClass->categories['category_1'];
        $fixtureColors = self::$_dbClass->colors['color_1'];
        $fixtureColors2 = self::$_dbClass->colors['color_2'];
        $fixtureSizes = self::$_dbClass->sizes['size_1'];
        
        $_GET = ['category'=>$fixture['seocode']];
        
        \Yii::$app->filters->clean();
        \Yii::configure(\Yii::$app->filters, ['colors'=>[(int) $fixtureColors['id'], (int) $fixtureColors2['id']], 'sizes'=>[(int) $fixtureSizes['id']]]);
        
        $productsQuery = new GetProductsQuery([
            'fields'=>['id', 'date', 'name', 'short_description', 'description', 'price', 'images', 'id_category', 'id_subcategory', 'active'],
            'sorting'=>['date'=>SORT_DESC]
        ]);
        
        $query = $productsQuery->getAll();
        $queryRaw = clone $query;
        
        $expectQuery = "SELECT `products`.`id`, `products`.`date`, `products`.`name`, `products`.`short_description`, `products`.`description`, `products`.`price`, `products`.`images`, `products`.`id_category`, `products`.`id_subcategory`, `products`.`active` FROM `products` INNER JOIN `categories` ON `categories`.`id`=`products`.`id_category` INNER JOIN `products_colors` ON products.id=products_colors.id_product INNER JOIN `colors` ON products_colors.id_color=colors.id INNER JOIN `products_sizes` ON products.id=products_sizes.id_product INNER JOIN `sizes` ON products_sizes.id_size=sizes.id WHERE (((`categories`.`seocode`='mensfootwear') AND (`products`.`active`=TRUE)) AND (`colors`.`id` IN (1, 2))) AND (`sizes`.`id`=1) ORDER BY `products`.`date` DESC LIMIT " . \Yii::$app->params['limit'];
        
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
        $fixture = self::$_dbClass->products['product_1'];
        
         $_GET = [];
        
        \Yii::$app->filters->clean();
        
        $productsQuery = new GetProductsQuery([
            'fields'=>['id', 'date', 'name', 'short_description', 'description', 'price', 'images', 'id_category', 'id_subcategory', 'active'],
            'extraWhere'=>['products.id'=>(int) $fixture['id']]
        ]);
        
        $query = $productsQuery->getOne();
        $queryRaw = clone $query;
        
        $expectQuery = "SELECT `products`.`id`, `products`.`date`, `products`.`name`, `products`.`short_description`, `products`.`description`, `products`.`price`, `products`.`images`, `products`.`id_category`, `products`.`id_subcategory`, `products`.`active` FROM `products` WHERE `products`.`id`=1";
        
        $this->assertEquals($expectQuery, $queryRaw->createCommand()->getRawSql());
        
        $result = $query->one();
        
        $this->assertTrue($result instanceof ProductsModel);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->unloadFixtures();
    }
}
