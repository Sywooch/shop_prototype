<?php

namespace app\tests\models;

use PHPUnit\Framework\TestCase;
use yii\sphinx\{MatchExpression,
    Query};
use app\tests\DbManager;
use app\models\{CategoriesModel,
    ColorsModel,
    ProductsModel,
    SizesModel,
    SubcategoryModel};

/**
 * Тестирует класс app\models\ProductsModel
 */
class ProductsModelTests extends TestCase
{
    private static $_dbClass;
    private static $_reflectionClass;
    private static $_freshCode = 'YUIHFDK';
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager([
            'fixtures'=>[
                'categories'=>'app\tests\sources\fixtures\CategoriesFixture',
                'subcategory'=>'app\tests\sources\fixtures\SubcategoryFixture',
                'products'=>'app\tests\sources\fixtures\ProductsFixture',
                'colors'=>'app\tests\sources\fixtures\ColorsFixture',
                'brands'=>'app\tests\sources\fixtures\BrandsFixture',
                'products_colors'=>'app\tests\sources\fixtures\ProductsColorsFixture',
                'sizes'=>'app\tests\sources\fixtures\SizesFixture',
                'products_sizes'=>'app\tests\sources\fixtures\ProductsSizesFixture'
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
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_ADD_PRODUCT'));
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_ADD_TO_CART'));
        
        $this->assertTrue(self::$_reflectionClass->hasProperty('categoryName'));
        $this->assertTrue(self::$_reflectionClass->hasProperty('categorySeocode'));
        $this->assertTrue(self::$_reflectionClass->hasProperty('subcategoryName'));
        $this->assertTrue(self::$_reflectionClass->hasProperty('subcategorySeocode'));
        
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
        $this->assertTrue(array_key_exists('id_brand', $model->attributes));
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
        
        $model = new ProductsModel(['scenario'=>ProductsModel::GET_FROM_ADD_PRODUCT]);
        $model->attributes = [
            'code'=>$fixture['code'], 
            'name'=>$fixture['name'], 
            'short_description'=>$fixture['short_description'], 
            'description'=>$fixture['description'], 
            'price'=>$fixture['price'], 
            'images'=>$fixture['images'], 
            'id_category'=>$fixture['id_category'], 
            'id_subcategory'=>$fixture['id_subcategory'], 
            'id_brand'=>$fixture['id_brand'], 
            'active'=>$fixture['active'], 
            'total_products'=>$fixture['total_products'],
            'seocode'=>$fixture['seocode']
        ];
        
        $this->assertEquals($fixture['code'], $model->code);
        $this->assertEquals($fixture['name'], $model->name);
        $this->assertEquals($fixture['short_description'], $model->short_description);
        $this->assertEquals($fixture['description'], $model->description);
        $this->assertEquals($fixture['price'], $model->price);
        $this->assertEquals($fixture['images'], $model->images);
        $this->assertEquals($fixture['id_category'], $model->id_category);
        $this->assertEquals($fixture['id_subcategory'], $model->id_subcategory);
        $this->assertEquals($fixture['id_brand'], $model->id_subcategory);
        $this->assertEquals($fixture['active'], $model->active);
        $this->assertEquals($fixture['total_products'], $model->total_products);
        $this->assertEquals($fixture['seocode'], $model->seocode);
        
        $model = new ProductsModel(['scenario'=>ProductsModel::GET_FROM_ADD_TO_CART]);
        $model->attributes = [
            'price'=>$fixture['price'], 
        ];
        
        $this->assertEquals($fixture['price'], $model->price);
    }
    
    /**
     * Тестирует правила проверки
     */
    public function testRules()
    {
        $fixture = self::$_dbClass->products['product_1'];
        
        $model = new ProductsModel(['scenario'=>ProductsModel::GET_FROM_ADD_PRODUCT]);
        $model->attributes = [];
        $model->validate();
        
        $this->assertEquals(10, count($model->errors));
        $this->assertTrue(array_key_exists('code', $model->errors));
        $this->assertTrue(array_key_exists('name', $model->errors));
        $this->assertTrue(array_key_exists('short_description', $model->errors));
        $this->assertTrue(array_key_exists('description', $model->errors));
        $this->assertTrue(array_key_exists('price', $model->errors));
        $this->assertTrue(array_key_exists('images', $model->errors));
        $this->assertTrue(array_key_exists('id_category', $model->errors));
        $this->assertTrue(array_key_exists('id_subcategory', $model->errors));
        $this->assertTrue(array_key_exists('id_brand', $model->errors));
        $this->assertTrue(array_key_exists('total_products', $model->errors));
        
        $model = new ProductsModel(['scenario'=>ProductsModel::GET_FROM_ADD_PRODUCT]);
        $model->attributes = [
            'code'=>self::$_freshCode, 
            'name'=>$fixture['name'], 
            'short_description'=>$fixture['short_description'], 
            'description'=>$fixture['description'], 
            'price'=>$fixture['price'], 
            'images'=>$fixture['images'], 
            'id_category'=>$fixture['id_category'], 
            'id_subcategory'=>$fixture['id_subcategory'], 
            'id_brand'=>$fixture['id_brand'], 
            'total_products'=>$fixture['total_products'],
        ];
        $model->validate();
        
        $this->assertTrue(empty($model->errors));
        
        $model = new ProductsModel(['scenario'=>ProductsModel::GET_FROM_ADD_TO_CART]);
        $model->attributes = [];
        $model->validate();
        
        $this->assertEquals(1, count($model->errors));
        $this->assertTrue(array_key_exists('price', $model->errors));
        
        $model = new ProductsModel(['scenario'=>ProductsModel::GET_FROM_ADD_TO_CART]);
        $model->attributes = [
            'price'=>$fixture['price'], 
        ];
        $model->validate();
        
        $this->assertTrue(empty($model->errors));
    }
    
    /**
     * Тестирует метод ProductsModel::getCategory
     */
    public function testGetCategory()
    {
        $fixture = self::$_dbClass->products['product_2'];
        
        $model = ProductsModel::find()->where(['products.id'=>$fixture['id']])->one();
        
        $this->assertTrue($model->category instanceof CategoriesModel);
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
     * без категорий и фильтров
     */
    public function testGetAll()
    {
        $_GET = [];
        \Yii::$app->filters->clean();
        
        $productsQuery = $this->productsListQuery();
        
        $queryRaw = clone $productsQuery;
        
        $expectedQuery = sprintf("SELECT `products`.`id`, `products`.`date`, `products`.`name`, `products`.`short_description`, `products`.`price`, `products`.`images`, `products`.`id_category`, `products`.`id_subcategory`, `products`.`id_brand`, `products`.`active`, `products`.`seocode` FROM `products` WHERE `products`.`active`=TRUE ORDER BY `products`.`date` DESC LIMIT %d", \Yii::$app->params['limit']);
        
        $this->assertEquals($expectedQuery, $queryRaw->createCommand()->getRawSql());
        
        $result = $productsQuery->all();
        
        $this->assertTrue(is_array($result));
        $this->assertTrue($result[0] instanceof ProductsModel);
    }
    
    /**
     * Тестирует запрос на получение массива объектов
     * c выборкой по категории
     */
    public function testGetAllTwo()
    {
        $fixture = self::$_dbClass->categories['category_1'];
        
        $_GET = ['category'=>$fixture['seocode']];
        \Yii::$app->filters->clean();
        
        $productsQuery = $this->productsListQuery();
        
        $queryRaw = clone $productsQuery;
        
        $expectedQuery = sprintf("SELECT `products`.`id`, `products`.`date`, `products`.`name`, `products`.`short_description`, `products`.`price`, `products`.`images`, `products`.`id_category`, `products`.`id_subcategory`, `products`.`id_brand`, `products`.`active`, `products`.`seocode` FROM `products` INNER JOIN `categories` ON `categories`.`id`=`products`.`id_category` WHERE (`products`.`active`=TRUE) AND (`categories`.`seocode`='%s') ORDER BY `products`.`date` DESC LIMIT %d", $fixture['seocode'], \Yii::$app->params['limit']);
        
        $this->assertEquals($expectedQuery, $queryRaw->createCommand()->getRawSql());
        
        $result = $productsQuery->all();
        
        $this->assertTrue(is_array($result));
        $this->assertTrue($result[0] instanceof ProductsModel);
    }
    
    /**
     * Тестирует запрос на получение массива объектов
     * c выборкой по категории и подкатегории
     */
    public function testGetAllThree()
    {
        $fixture = self::$_dbClass->categories['category_1'];
        $fixtureSubcategory = self::$_dbClass->subcategory['subcategory_1'];
        
        $_GET = ['category'=>$fixture['seocode'], 'subcategory'=>$fixtureSubcategory['seocode']];
        \Yii::$app->filters->clean();
        
        $productsQuery = $this->productsListQuery();
        
        $queryRaw = clone $productsQuery;
        
        $expectedQuery = sprintf("SELECT `products`.`id`, `products`.`date`, `products`.`name`, `products`.`short_description`, `products`.`price`, `products`.`images`, `products`.`id_category`, `products`.`id_subcategory`, `products`.`id_brand`, `products`.`active`, `products`.`seocode` FROM `products` INNER JOIN `categories` ON `categories`.`id`=`products`.`id_category` INNER JOIN `subcategory` ON `subcategory`.`id`=`products`.`id_subcategory` WHERE ((`products`.`active`=TRUE) AND (`categories`.`seocode`='%s')) AND (`subcategory`.`seocode`='%s') ORDER BY `products`.`date` DESC LIMIT %d", $fixture['seocode'], $fixtureSubcategory['seocode'], \Yii::$app->params['limit']);
        
        $this->assertEquals($expectedQuery, $queryRaw->createCommand()->getRawSql());
        
        $result = $productsQuery->all();
        
        $this->assertTrue(is_array($result));
        $this->assertTrue($result[0] instanceof ProductsModel);
    }
    
    /**
     * Тестирует запрос на получение массива объектов
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
        
        $productsQuery = $this->productsListQuery();
        
        $queryRaw = clone $productsQuery;
        
        $expectedQuery = sprintf("SELECT `products`.`id`, `products`.`date`, `products`.`name`, `products`.`short_description`, `products`.`price`, `products`.`images`, `products`.`id_category`, `products`.`id_subcategory`, `products`.`id_brand`, `products`.`active`, `products`.`seocode` FROM `products` INNER JOIN `categories` ON `categories`.`id`=`products`.`id_category` INNER JOIN `subcategory` ON `subcategory`.`id`=`products`.`id_subcategory` INNER JOIN `products_colors` ON `products_colors`.`id_product`=`products`.`id` INNER JOIN `colors` ON `colors`.`id`=`products_colors`.`id_color` WHERE (((`products`.`active`=TRUE) AND (`categories`.`seocode`='%s')) AND (`subcategory`.`seocode`='%s')) AND (`colors`.`id`=%d) ORDER BY `products`.`date` DESC LIMIT %d", $fixture['seocode'], $fixtureSubcategory['seocode'], $fixtureColors['id'], \Yii::$app->params['limit']);
        
        $this->assertEquals($expectedQuery, $queryRaw->createCommand()->getRawSql());
        
        $result = $productsQuery->all();
        
        $this->assertTrue(is_array($result));
        $this->assertTrue($result[0] instanceof ProductsModel);
    }
    
    /**
     * Тестирует запрос на получение массива объектов
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
        \Yii::configure(\Yii::$app->filters, ['colors'=>[(int) $fixtureColors['id']], 'sizes'=>[(int) $fixtureSizes['id']], 'sortingField'=>'price']);
        
        $productsQuery = $this->productsListQuery();
        
        $queryRaw = clone $productsQuery;
        
        $expectedQuery = sprintf("SELECT `products`.`id`, `products`.`date`, `products`.`name`, `products`.`short_description`, `products`.`price`, `products`.`images`, `products`.`id_category`, `products`.`id_subcategory`, `products`.`id_brand`, `products`.`active`, `products`.`seocode` FROM `products` INNER JOIN `categories` ON `categories`.`id`=`products`.`id_category` INNER JOIN `subcategory` ON `subcategory`.`id`=`products`.`id_subcategory` INNER JOIN `products_colors` ON `products_colors`.`id_product`=`products`.`id` INNER JOIN `colors` ON `colors`.`id`=`products_colors`.`id_color` INNER JOIN `products_sizes` ON `products_sizes`.`id_product`=`products`.`id` INNER JOIN `sizes` ON `sizes`.`id`=`products_sizes`.`id_size` WHERE ((((`products`.`active`=TRUE) AND (`categories`.`seocode`='%s')) AND (`subcategory`.`seocode`='%s')) AND (`colors`.`id`=%d)) AND (`sizes`.`id`=%s) ORDER BY `products`.`price` DESC LIMIT %d", $fixture['seocode'], $fixtureSubcategory['seocode'], $fixtureColors['id'], $fixtureSizes['id'], \Yii::$app->params['limit']);
        
        $this->assertEquals($expectedQuery, $queryRaw->createCommand()->getRawSql());
        
        $result = $productsQuery->all();
        
        $this->assertTrue(is_array($result));
        $this->assertTrue($result[0] instanceof ProductsModel);
    }
    
    /**
     * Тестирует запрос на получение массива объектов
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
        
        $productsQuery = $this->productsListQuery();
        
        $queryRaw = clone $productsQuery;
        
        $expectedQuery = sprintf("SELECT `products`.`id`, `products`.`date`, `products`.`name`, `products`.`short_description`, `products`.`price`, `products`.`images`, `products`.`id_category`, `products`.`id_subcategory`, `products`.`id_brand`, `products`.`active`, `products`.`seocode` FROM `products` INNER JOIN `categories` ON `categories`.`id`=`products`.`id_category` INNER JOIN `products_colors` ON `products_colors`.`id_product`=`products`.`id` INNER JOIN `colors` ON `colors`.`id`=`products_colors`.`id_color` INNER JOIN `products_sizes` ON `products_sizes`.`id_product`=`products`.`id` INNER JOIN `sizes` ON `sizes`.`id`=`products_sizes`.`id_size` WHERE (((`products`.`active`=TRUE) AND (`categories`.`seocode`='%s')) AND (`colors`.`id` IN (%s))) AND (`sizes`.`id`=%d) ORDER BY `products`.`date` DESC LIMIT %d", $fixture['seocode'], implode(', ', [$fixtureColors['id'], $fixtureColors2['id']]), $fixtureSizes['id'], \Yii::$app->params['limit']);
        
        $this->assertEquals($expectedQuery, $queryRaw->createCommand()->getRawSql());
        
        $result = $productsQuery->all();
        
        $this->assertTrue(is_array($result));
        $this->assertTrue($result[0] instanceof ProductsModel);
    }
    
    
    
    
    
    /**
     * Тестирует запрос на получение массива объектов
     * c выборкой по категории, подкатегории и фильтру
     */
    public function testGetAllSeven()
    {
        $fixture = self::$_dbClass->categories['category_1'];
        $fixtureSubcategory = self::$_dbClass->subcategory['subcategory_1'];
        $fixtureColors = self::$_dbClass->colors['color_1'];
        $fixtureBrands = self::$_dbClass->brands['brand_1'];
        
        $_GET = ['category'=>$fixture['seocode'], 'subcategory'=>$fixtureSubcategory['seocode']];
        
        \Yii::$app->filters->clean();
        \Yii::configure(\Yii::$app->filters, ['colors'=>[(int) $fixtureColors['id']], 'brands'=>[(int) $fixtureBrands['id']]]);
        
        $productsQuery = $this->productsListQuery();
        
        $queryRaw = clone $productsQuery;
        
        $expectedQuery = sprintf("SELECT `products`.`id`, `products`.`date`, `products`.`name`, `products`.`short_description`, `products`.`price`, `products`.`images`, `products`.`id_category`, `products`.`id_subcategory`, `products`.`id_brand`, `products`.`active`, `products`.`seocode` FROM `products` INNER JOIN `categories` ON `categories`.`id`=`products`.`id_category` INNER JOIN `subcategory` ON `subcategory`.`id`=`products`.`id_subcategory` INNER JOIN `products_colors` ON `products_colors`.`id_product`=`products`.`id` INNER JOIN `colors` ON `colors`.`id`=`products_colors`.`id_color` WHERE ((((`products`.`active`=TRUE) AND (`categories`.`seocode`='%s')) AND (`subcategory`.`seocode`='%s')) AND (`colors`.`id`=%d)) AND (`products`.`id_brand`=1) ORDER BY `products`.`date` DESC LIMIT %d", $fixture['seocode'], $fixtureSubcategory['seocode'], $fixtureColors['id'], \Yii::$app->params['limit']);
        
        $this->assertEquals($expectedQuery, $queryRaw->createCommand()->getRawSql());
        
        $result = $productsQuery->all();
        
        $this->assertTrue(is_array($result));
        $this->assertTrue($result[0] instanceof ProductsModel);
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    /**
     * Тестирует запрос на получение 1 объекта
     */
    public function testGetOne()
    {
        $fixture = self::$_dbClass->products['product_1'];
        
         $_GET = [];
        \Yii::$app->filters->clean();
        
        $productsQuery = ProductsModel::find();
        $productsQuery->extendSelect(['id', 'date', 'name', 'short_description', 'description', 'price', 'images', 'id_category', 'id_subcategory', 'id_brand', 'seocode']);
        $productsQuery->where(['products.seocode'=>$fixture['seocode']]);
        
        $queryRaw = clone $productsQuery;
        
        $expectedQuery = sprintf("SELECT `products`.`id`, `products`.`date`, `products`.`name`, `products`.`short_description`, `products`.`description`, `products`.`price`, `products`.`images`, `products`.`id_category`, `products`.`id_subcategory`, `products`.`id_brand`, `products`.`seocode` FROM `products` WHERE `products`.`seocode`='%s'", $fixture['seocode']);
        
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
    
    /**
     * Тестирует метод ProductsModel::getColors
     */
    public function testGetColors()
    {
        $fixture = self::$_dbClass->products['product_1'];
        
        $model = ProductsModel::find()->where(['products.id'=>$fixture['id']])->one();
        
        $this->assertTrue(is_array($model->colors));
        $this->assertTrue($model->colors[0] instanceof ColorsModel);
    }
    
    /**
     * Тестирует метод ProductsModel::getSizes
     */
    public function testGetSizes()
    {
        $fixture = self::$_dbClass->products['product_1'];
        
        $model = ProductsModel::find()->where(['products.id'=>$fixture['id']])->one();
        
        $this->assertTrue(is_array($model->sizes));
        $this->assertTrue($model->sizes[0] instanceof SizesModel);
    }
    
    /**
     * Тестирует метод ExtendActiveQuery::allMap
     */
    public function testAllMap()
    {
        $fixture = self::$_dbClass->products['product_1'];
        $fixture2 = self::$_dbClass->products['product_2'];
        
        $productsQuery = ProductsModel::find();
        $productsQuery->extendSelect(['id', 'name']);
        $productsArray = $productsQuery->allMap('id', 'name');
        
        $this->assertFalse(empty($productsArray));
        $this->assertTrue(array_key_exists($fixture['id'], $productsArray));
        $this->assertTrue(array_key_exists($fixture2['id'], $productsArray));
        $this->assertTrue(in_array($fixture['name'], $productsArray));
        $this->assertTrue(in_array($fixture2['name'], $productsArray));
    }
    
    private function productsListQuery(array $extraWhere=[])
    {
        $productsQuery = ProductsModel::find();
        $productsQuery->extendSelect(['id', 'date', 'name', 'short_description', 'price', 'images', 'id_category', 'id_subcategory', 'id_brand', 'active', 'seocode']);
        $productsQuery->where(['[[products.active]]'=>true]);
        if (!empty(\Yii::$app->request->get(\Yii::$app->params['categoryKey']))) {
            $productsQuery->innerJoin('{{categories}}', '[[categories.id]]=[[products.id_category]]');
            $productsQuery->andWhere(['[[categories.seocode]]'=>\Yii::$app->request->get(\Yii::$app->params['categoryKey'])]);
        }
        if (!empty(\Yii::$app->request->get(\Yii::$app->params['subcategoryKey']))) {
            $productsQuery->innerJoin('{{subcategory}}', '[[subcategory.id]]=[[products.id_subcategory]]');
            $productsQuery->andWhere(['[[subcategory.seocode]]'=>\Yii::$app->request->get(\Yii::$app->params['subcategoryKey'])]);
        }
        if (!empty($extraWhere)) {
            $productsQuery->andWhere($extraWhere);
        }
        $productsQuery->addFilters();
        $productsQuery->extendLimit();
        $sortingField = !empty(\Yii::$app->filters->sortingField) ? \Yii::$app->filters->sortingField : 'date';
        $sortingType = (!empty(\Yii::$app->filters->sortingType) && \Yii::$app->filters->sortingType === 'SORT_ASC') ? SORT_ASC : SORT_DESC;
        $productsQuery->orderBy(['[[products.' . $sortingField . ']]'=>$sortingType]);
        
        return $productsQuery;
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->unloadFixtures();
    }
}
