<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\tests\DbManager;
use app\services\ProductsListIndexService;
use app\tests\sources\fixtures\{BrandsFixture,
    CategoriesFixture,
    ColorsFixture,
    CurrencyFixture,
    ProductsColorsFixture,
    ProductsFixture,
    ProductsSizesFixture,
    SizesFixture,
    SubcategoryFixture};
use app\controllers\ProductsListController;
use app\collections\{LightPagination,
    ProductsCollection};
use app\models\CurrencyModel;
use app\forms\FiltersForm;
use app\helpers\HashHelper;
use yii\helpers\Url;
use app\filters\ProductsFilters;

/**
 * Тестирует класс ProductsListIndexService
 */
class ProductsListIndexServiceTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'products'=>ProductsFixture::class,
                'currency'=>CurrencyFixture::class,
                'categories'=>CategoriesFixture::class,
                'subcategory'=>SubcategoryFixture::class,
                'colors'=>ColorsFixture::class,
                'products_colors'=>ProductsColorsFixture::class,
                'sizes'=>SizesFixture::class,
                'products_sizes'=>ProductsSizesFixture::class,
                'brands'=>BrandsFixture::class,
            ],
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства ProductsListIndexService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(ProductsListIndexService::class);
        
        $this->assertTrue($reflection->hasProperty('filtersModel'));
        $this->assertTrue($reflection->hasProperty('productsCollection'));
        $this->assertTrue($reflection->hasProperty('productsArray'));
        $this->assertTrue($reflection->hasProperty('paginationArray'));
        $this->assertTrue($reflection->hasProperty('breadcrumbsArray'));
        $this->assertTrue($reflection->hasProperty('filtersArray'));
    }

    /**
     * Тестирует метод ProductsListIndexService::getFiltersModel
     */
    public function testGetFiltersModel()
    {
        \Yii::$app->controller = new ProductsListController('products-list', \Yii::$app);
        
        $service = new ProductsListIndexService();
        
        $reflection = new \ReflectionMethod($service, 'getFiltersModel');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($service);

        $this->assertInstanceOf(ProductsFilters::class, $result);
    }

    /**
     * Тестирует метод ProductsListIndexService::getProductsCollection
     * если отсутствует параметр $request
     * @expectedException TypeError
     */
    public function testGetProductsCollectionEmptyRequest()
    {
        $service = new ProductsListIndexService();
        
        $reflection = new \ReflectionMethod($service, 'getProductsCollection');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($service);
    }

    /**
     * Тестирует метод ProductsListIndexService::getProductsCollection
     * category === null
     * subcategory === null
     * page === null
     * filters === null
     */
    public function testGetProductsClean()
    {
        $request = [];
        
        $service = new ProductsListIndexService();
        
        $reflection = new \ReflectionMethod($service, 'getProductsCollection');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($service, $request);

        $this->assertInstanceOf(ProductsCollection::class, $result);
        $this->assertFalse($result->isEmpty());
    }
    
    /**
     * Тестирует метод ProductsListIndexService::getProductsCollection
     * category === true
     * subcategory === true
     * page === true
     * filters === null
     */
    public function testGetProductsCategory()
    {
        $request = [
            \Yii::$app->params['categoryKey']=>self::$dbClass->categories['category_1'],
            \Yii::$app->params['subcategoryKey']=>self::$dbClass->subcategory['subcategory_1'],
            \Yii::$app->params['pagePointer']=>2,
        ];
        
        $service = new ProductsListIndexService();
        
        $reflection = new \ReflectionMethod($service, 'getProductsCollection');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($service, $request);

        $this->assertInstanceOf(ProductsCollection::class, $result);
        $this->assertFalse($result->isEmpty());
    }

    /**
     * Тестирует метод ProductsListIndexService::getProductsCollection
     * category === null
     * subcategory === null
     * page === null
     * filters === true
     */
    public function testGetProductsFilters()
    {
        $key = HashHelper::createFiltersKey(Url::current());
        
        $session = \Yii::$app->session;
        $session->open();
        $session->set($key, [
            'colors'=>[1, 2, 3, 4, 5],
            'sizes'=>[1, 2, 3, 4, 5],
            'brands'=>[1, 2, 3, 4, 5],
        ]);

        $request = [];
        
        $service = new ProductsListIndexService();
        
        $reflection = new \ReflectionMethod($service, 'getProductsCollection');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($service, $request);

        $this->assertInstanceOf(ProductsCollection::class, $result);
        $this->assertFalse($result->isEmpty());

        $session->remove($key);
        $session->close();
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
