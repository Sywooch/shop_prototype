<?php

namespace app\tests\models;

use PHPUnit\Framework\TestCase;
use yii\sphinx\{MatchExpression,
    Query};
use app\tests\DbManager;
use app\queries\QueryTrait;
use app\models\{CategoriesModel,
    ProductsModel,
    SubcategoryModel};

/**
 * Тестирует класс app\models\ProductsModel
 */
class ProductsModelTests extends TestCase
{
    use QueryTrait;
    
    private static $_dbClass;
    private static $_reflectionClass;
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager([
            'fixtures'=>[
                'categories'=>'app\tests\source\fixtures\CategoriesFixture',
                'subcategory'=>'app\tests\source\fixtures\SubcategoryFixture',
                'products'=>'app\tests\source\fixtures\ProductsFixture',
                'colors'=>'app\tests\source\fixtures\ColorsFixture',
                'products_colors'=>'app\tests\source\fixtures\ProductsColorsFixture',
                'sizes'=>'app\tests\source\fixtures\SizesFixture',
                'products_sizes'=>'app\tests\source\fixtures\ProductsSizesFixture'
            ],
        ]);
        self::$_dbClass->loadFixtures();
        
        self::$_reflectionClass = new \ReflectionClass('\app\models\ProductsModel');
    }
    
    /**
     * Тестирует наличие свойств у объекта app\models\ProductsModel
     */
    public function testProperties()
    {
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_DB'));
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_FORM'));
        
        $this->assertTrue(self::$_reflectionClass->hasProperty('_tableName'));
        
        $model = new ProductsModel();
        
        $this->assertTrue(array_key_exists('id', $model->attributes));
        $this->assertTrue(array_key_exists('date', $model->attributes));
        $this->assertTrue(array_key_exists('code', $model->attributes));
        $this->assertTrue(array_key_exists('name', $model->attributes));
        $this->assertTrue(array_key_exists('description', $model->attributes));
        $this->assertTrue(array_key_exists('short_description', $model->attributes));
        $this->assertTrue(array_key_exists('price', $model->attributes));
        $this->assertTrue(array_key_exists('images', $model->attributes));
        $this->assertTrue(array_key_exists('id_category', $model->attributes));
        $this->assertTrue(array_key_exists('id_subcategory', $model->attributes));
        $this->assertTrue(array_key_exists('active', $model->attributes));
        $this->assertTrue(array_key_exists('total_products', $model->attributes));
        $this->assertTrue(array_key_exists('seocode', $model->attributes));
    }
    
    /**
     * Тестирует сценарии
     */
    public function testScenarios()
    {
        $fixture = self::$_dbClass->products['product_1'];
        
        $model = new ProductsModel(['scenario'=>ProductsModel::GET_FROM_DB]);
        $model->attributes = [
            'id'=>$fixture['id'], 
            'date'=>$fixture['date'], 
            'code'=>$fixture['code'], 
            'name'=>$fixture['name'], 
            'short_description'=>$fixture['short_description'], 
            'description'=>$fixture['description'], 
            'price'=>$fixture['price'], 
            'images'=>$fixture['images'], 
            'id_category'=>$fixture['id_category'], 
            'id_subcategory'=>$fixture['id_subcategory'], 
            'active'=>$fixture['active'], 
            'total_products'=>$fixture['total_products'],
            'seocode'=>$fixture['seocode']
        ];
        
        $this->assertEquals($fixture['id'], $model->id);
        $this->assertEquals($fixture['date'], $model->date);
        $this->assertEquals($fixture['code'], $model->code);
        $this->assertEquals($fixture['name'], $model->name);
        $this->assertEquals($fixture['short_description'], $model->short_description);
        $this->assertEquals($fixture['description'], $model->description);
        $this->assertEquals($fixture['price'], $model->price);
        $this->assertEquals($fixture['images'], $model->images);
        $this->assertEquals($fixture['id_category'], $model->id_category);
        $this->assertEquals($fixture['id_subcategory'], $model->id_subcategory);
        $this->assertEquals($fixture['active'], $model->active);
        $this->assertEquals($fixture['total_products'], $model->total_products);
        $this->assertEquals($fixture['seocode'], $model->seocode);
        
        $model = new ProductsModel(['scenario'=>ProductsModel::GET_FROM_FORM]);
        $model->attributes = [
            'id'=>$fixture['id'], 
            'date'=>$fixture['date'], 
            'code'=>$fixture['code'], 
            'name'=>$fixture['name'], 
            'short_description'=>$fixture['short_description'], 
            'description'=>$fixture['description'], 
            'price'=>$fixture['price'], 
            'images'=>$fixture['images'], 
            'id_category'=>$fixture['id_category'], 
            'id_subcategory'=>$fixture['id_subcategory'], 
            'active'=>$fixture['active'], 
            'total_products'=>$fixture['total_products'],
            'seocode'=>$fixture['seocode']
        ];
        
        $this->assertEquals($fixture['id'], $model->id);
        $this->assertEquals($fixture['date'], $model->date);
        $this->assertEquals($fixture['code'], $model->code);
        $this->assertEquals($fixture['name'], $model->name);
        $this->assertEquals($fixture['short_description'], $model->short_description);
        $this->assertEquals($fixture['description'], $model->description);
        $this->assertEquals($fixture['price'], $model->price);
        $this->assertEquals($fixture['images'], $model->images);
        $this->assertEquals($fixture['id_category'], $model->id_category);
        $this->assertEquals($fixture['id_subcategory'], $model->id_subcategory);
        $this->assertEquals($fixture['active'], $model->active);
        $this->assertEquals($fixture['total_products'], $model->total_products);
        $this->assertEquals($fixture['seocode'], $model->seocode);
    }
    
    /**
     * Тестирует метод ProductsModel::getCategories
     */
    public function testGetCategories()
    {
        $fixture = self::$_dbClass->products['product_2'];
        
        $model = ProductsModel::find()->where(['products.id'=>$fixture['id']])->one();
        
        $this->assertTrue(is_object($model->categories));
        $this->assertTrue($model->categories instanceof CategoriesModel);
    }
    
    /**
     * Тестирует метод ProductsModel::getSubcategory
     */
    public function testGetSubcategory()
    {
        $fixture = self::$_dbClass->products['product_1'];
        
        $model = ProductsModel::find()->where(['products.id'=>$fixture['id']])->one();
        
        $this->assertTrue(is_object($model->subcategory));
        $this->assertTrue($model->subcategory instanceof SubcategoryModel);
    }
    
    /**
     * Тестирует запрос на получение массива объектов
     * без категорий и фильтров для 
     * - app\controllers\ProductsListController
     */
    public function testGetAll()
    {
        $_GET = [];
        \Yii::$app->filters->clean();
        
        $productsQuery = $this->productsListQuery();
        
        $queryRaw = clone $productsQuery;
        
        $expectedQuery = sprintf("SELECT `products`.`id`, `products`.`date`, `products`.`name`, `products`.`short_description`, `products`.`price`, `products`.`images`, `products`.`id_category`, `products`.`id_subcategory`, `products`.`active`, `products`.`seocode` FROM `products` WHERE `products`.`active`=TRUE ORDER BY `products`.`date` DESC LIMIT %d", \Yii::$app->params['limit']);
        
        $this->assertEquals($expectedQuery, $queryRaw->createCommand()->getRawSql());
        
        $result = $productsQuery->all();
        
        $this->assertTrue(is_array($result));
        $this->assertTrue($result[0] instanceof ProductsModel);
    }
    
    /**
     * Тестирует запрос на получение массива объектов
     * c выборкой по категории для 
     * - app\controllers\ProductsListController
     */
    public function testGetAllTwo()
    {
        $fixture = self::$_dbClass->categories['category_1'];
        
        $_GET = ['category'=>$fixture['seocode']];
        \Yii::$app->filters->clean();
        
        $productsQuery = $this->productsListQuery();
        
        $queryRaw = clone $productsQuery;
        
        $expectedQuery = sprintf("SELECT `products`.`id`, `products`.`date`, `products`.`name`, `products`.`short_description`, `products`.`price`, `products`.`images`, `products`.`id_category`, `products`.`id_subcategory`, `products`.`active`, `products`.`seocode` FROM `products` INNER JOIN `categories` ON `categories`.`id`=`products`.`id_category` WHERE (`categories`.`seocode`='mensfootwear') AND (`products`.`active`=TRUE) ORDER BY `products`.`date` DESC LIMIT %d", \Yii::$app->params['limit']);
        
        $this->assertEquals($expectedQuery, $queryRaw->createCommand()->getRawSql());
        
        $result = $productsQuery->all();
        
        $this->assertTrue(is_array($result));
        $this->assertTrue($result[0] instanceof ProductsModel);
    }
    
    /**
     * Тестирует запрос на получение массива объектов
     * c выборкой по категории и подкатегории для 
     * - app\controllers\ProductsListController
     */
    public function testGetAllThree()
    {
        $fixture = self::$_dbClass->categories['category_1'];
        $fixtureSubcategory = self::$_dbClass->subcategory['subcategory_1'];
        
        $_GET = ['category'=>$fixture['seocode'], 'subcategory'=>$fixtureSubcategory['seocode']];
        \Yii::$app->filters->clean();
        
        $productsQuery = $this->productsListQuery();
        
        $queryRaw = clone $productsQuery;
        
        $expectedQuery = sprintf("SELECT `products`.`id`, `products`.`date`, `products`.`name`, `products`.`short_description`, `products`.`price`, `products`.`images`, `products`.`id_category`, `products`.`id_subcategory`, `products`.`active`, `products`.`seocode` FROM `products` INNER JOIN `categories` ON `categories`.`id`=`products`.`id_category` INNER JOIN `subcategory` ON `subcategory`.`id`=`products`.`id_subcategory` WHERE ((`categories`.`seocode`='mensfootwear') AND (`subcategory`.`seocode`='boots')) AND (`products`.`active`=TRUE) ORDER BY `products`.`date` DESC LIMIT %d", \Yii::$app->params['limit']);
        
        $this->assertEquals($expectedQuery, $queryRaw->createCommand()->getRawSql());
        
        $result = $productsQuery->all();
        
        $this->assertTrue(is_array($result));
        $this->assertTrue($result[0] instanceof ProductsModel);
    }
    
    /**
     * Тестирует запрос на получение массива объектов
     * c выборкой по категории, подкатегории и фильтру для 
     * - app\controllers\ProductsListController
     */
    public function testGetAllFour()
    {
        $fixture = self::$_dbClass->categories['category_1'];
        $fixtureSubcategory = self::$_dbClass->subcategory['subcategory_1'];
        $fixtureColors = self::$_dbClass->colors['color_1'];
        
        $_GET = ['category'=>$fixture['seocode'], 'subcategory'=>$fixtureSubcategory['seocode']];
        
        \Yii::$app->filters->clean();
        \Yii::configure(\Yii::$app->filters, ['colors'=>[(int) $fixtureColors['id']]]);
        
        $productsQuery = $this->productsListQuery();
        
        $queryRaw = clone $productsQuery;
        
        $expectedQuery = sprintf("SELECT `products`.`id`, `products`.`date`, `products`.`name`, `products`.`short_description`, `products`.`price`, `products`.`images`, `products`.`id_category`, `products`.`id_subcategory`, `products`.`active`, `products`.`seocode` FROM `products` INNER JOIN `categories` ON `categories`.`id`=`products`.`id_category` INNER JOIN `subcategory` ON `subcategory`.`id`=`products`.`id_subcategory` INNER JOIN `products_colors` ON products.id=products_colors.id_product INNER JOIN `colors` ON products_colors.id_color=colors.id WHERE (((`categories`.`seocode`='mensfootwear') AND (`subcategory`.`seocode`='boots')) AND (`products`.`active`=TRUE)) AND (`colors`.`id`=1) ORDER BY `products`.`date` DESC LIMIT %d", \Yii::$app->params['limit']);
        
        $this->assertEquals($expectedQuery, $queryRaw->createCommand()->getRawSql());
        
        $result = $productsQuery->all();
        
        $this->assertTrue(is_array($result));
        $this->assertTrue($result[0] instanceof ProductsModel);
    }
    
    /**
     * Тестирует запрос на получение массива объектов
     * c выборкой по категории, подкатегории и нескольким фильтрам для 
     * - app\controllers\ProductsListController
     */
    public function testGetAllFive()
    {
        $fixture = self::$_dbClass->categories['category_1'];
        $fixtureSubcategory = self::$_dbClass->subcategory['subcategory_1'];
        $fixtureColors = self::$_dbClass->colors['color_1'];
        $fixtureSizes = self::$_dbClass->sizes['size_1'];
        
        $_GET = ['category'=>$fixture['seocode'], 'subcategory'=>$fixtureSubcategory['seocode']];
        
        \Yii::$app->filters->clean();
        \Yii::configure(\Yii::$app->filters, ['colors'=>[(int) $fixtureColors['id']], 'sizes'=>[(int) $fixtureSizes['id']], 'sortingField'=>'price']);
        
        $productsQuery = $this->productsListQuery();
        
        $queryRaw = clone $productsQuery;
        
        $expectedQuery = sprintf("SELECT `products`.`id`, `products`.`date`, `products`.`name`, `products`.`short_description`, `products`.`price`, `products`.`images`, `products`.`id_category`, `products`.`id_subcategory`, `products`.`active`, `products`.`seocode` FROM `products` INNER JOIN `categories` ON `categories`.`id`=`products`.`id_category` INNER JOIN `subcategory` ON `subcategory`.`id`=`products`.`id_subcategory` INNER JOIN `products_colors` ON products.id=products_colors.id_product INNER JOIN `colors` ON products_colors.id_color=colors.id INNER JOIN `products_sizes` ON products.id=products_sizes.id_product INNER JOIN `sizes` ON products_sizes.id_size=sizes.id WHERE ((((`categories`.`seocode`='mensfootwear') AND (`subcategory`.`seocode`='boots')) AND (`products`.`active`=TRUE)) AND (`colors`.`id`=1)) AND (`sizes`.`id`=1) ORDER BY `products`.`price` DESC LIMIT %d", \Yii::$app->params['limit']);
        
        $this->assertEquals($expectedQuery, $queryRaw->createCommand()->getRawSql());
        
        $result = $productsQuery->all();
        
        $this->assertTrue(is_array($result));
        $this->assertTrue($result[0] instanceof ProductsModel);
    }
    
    /**
     * Тестирует запрос на получение массива объектов
     * c выборкой по категории и нескольким фильтрам для 
     * - app\controllers\ProductsListController
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
        
        $productsQuery = $this->productsListQuery();
        
        $queryRaw = clone $productsQuery;
        
        $expectedQuery = sprintf("SELECT `products`.`id`, `products`.`date`, `products`.`name`, `products`.`short_description`, `products`.`price`, `products`.`images`, `products`.`id_category`, `products`.`id_subcategory`, `products`.`active`, `products`.`seocode` FROM `products` INNER JOIN `categories` ON `categories`.`id`=`products`.`id_category` INNER JOIN `products_colors` ON products.id=products_colors.id_product INNER JOIN `colors` ON products_colors.id_color=colors.id INNER JOIN `products_sizes` ON products.id=products_sizes.id_product INNER JOIN `sizes` ON products_sizes.id_size=sizes.id WHERE (((`categories`.`seocode`='mensfootwear') AND (`products`.`active`=TRUE)) AND (`colors`.`id` IN (1, 2))) AND (`sizes`.`id`=1) ORDER BY `products`.`date` DESC LIMIT %d", \Yii::$app->params['limit']);
        
        $this->assertEquals($expectedQuery, $queryRaw->createCommand()->getRawSql());
        
        $result = $productsQuery->all();
        
        $this->assertTrue(is_array($result));
        $this->assertTrue($result[0] instanceof ProductsModel);
    }
    
    /**
     * Тестирует запрос на получение 1 объекта для 
     * - app\controllers\ProductDetailController
     */
    public function testGetOne()
    {
        $fixture = self::$_dbClass->products['product_1'];
        
         $_GET = [];
        \Yii::$app->filters->clean();
        
        $productsQuery = ProductsModel::find();
        $productsQuery->extendSelect(['id', 'date', 'name', 'short_description', 'description', 'price', 'images', 'id_category', 'id_subcategory', 'seocode']);
        $productsQuery->where(['products.seocode'=>$fixture['seocode']]);
        
        $queryRaw = clone $productsQuery;
        
        $expectedQuery = sprintf("SELECT `products`.`id`, `products`.`date`, `products`.`name`, `products`.`short_description`, `products`.`description`, `products`.`price`, `products`.`images`, `products`.`id_category`, `products`.`id_subcategory`, `products`.`seocode` FROM `products` WHERE `products`.`seocode`='%s'", $fixture['seocode']);
        
        $this->assertEquals($expectedQuery, $queryRaw->createCommand()->getRawSql());
        
        $result = $productsQuery->one();
        
        $this->assertTrue($result instanceof ProductsModel);
    }
    
    /**
     * Тестирует запрос на получение 1 объекта для 
     * - app\widgets\BreadcrumbsWidget
     */
    public function testGetOneTwo()
    {
        $fixture = self::$_dbClass->products['product_1'];
        
        $productsQuery = ProductsModel::find();
        $productsQuery->extendSelect(['seocode', 'name', 'id_category', 'id_subcategory']);
        $productsQuery->where(['products.seocode'=>$fixture['seocode']]);
        
        $queryRaw = clone $productsQuery;
        
        $expectedQuery = sprintf("SELECT `products`.`seocode`, `products`.`name`, `products`.`id_category`, `products`.`id_subcategory` FROM `products` WHERE `products`.`seocode`='%s'", $fixture['seocode']);
        
        $this->assertEquals($expectedQuery, $queryRaw->createCommand()->getRawSql());
        
        $result = $productsQuery->one();
        
        $this->assertTrue($result instanceof ProductsModel);
    }
    
    /**
     * Тестирует запрос на получение массива данных от сервера sphinx
     */
    public function testGetAllSphinx()
    {
        $_GET = [\Yii::$app->params['searchKey']=>'ботинки'];
        \Yii::$app->filters->clean();
        
        $sphinxQuery = new Query();
        $sphinxQuery->select(['id']);
        $sphinxQuery->from('shop');
        $sphinxQuery->match(new MatchExpression(['*'=>\Yii::$app->request->get(\Yii::$app->params['searchKey'])]));
        
        $queryRaw = clone $sphinxQuery;
        
        $expectedQuery = sprintf("SELECT `id` FROM `shop` WHERE MATCH('@* \\\"%s\\\"')", \Yii::$app->request->get(\Yii::$app->params['searchKey']));
        
        $this->assertEquals($expectedQuery, $queryRaw->createCommand()->getRawSql());
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->unloadFixtures();
    }
}
