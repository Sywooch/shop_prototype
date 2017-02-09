<?php

namespace app\tests\handlers;

use PHPUnit\Framework\TestCase;
use yii\web\User;
use app\handlers\ProductsListIndexRequestHandler;
use app\tests\DbManager;
use app\tests\sources\fixtures\{BrandsFixture,
    CategoriesFixture,
    ColorsFixture,
    CurrencyFixture,
    ProductsColorsFixture,
    ProductsFixture,
    SizesFixture,
    SubcategoryFixture};
use app\models\{CategoriesModel,
    CurrencyInterface,
    CurrencyModel,
    SubcategoryModel};
use app\collections\{CollectionInterface,
    LightPagination,
    PaginationInterface,
    ProductsCollection,
    PurchasesCollectionInterface};
use app\forms\{ChangeCurrencyForm,
    FiltersForm};
use app\controllers\ProductsListController;
use app\filters\ProductsFilters;

/**
 * Тестирует класс ProductsListIndexRequestHandler
 */
class ProductsListIndexRequestHandlerTests extends TestCase
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
                'sizes'=>ColorsFixture::class,
                'brands'=>BrandsFixture::class
            ],
        ]);
        self::$dbClass->loadFixtures();
        
        \Yii::$app->controller = new ProductsListController('list', \Yii::$app);
    }
    
    public function setUp()
    {
        \Yii::$app->registry->clean();
    }
    
    /**
     * Тестирует свойства ProductsListIndexRequestHandler
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(ProductsListIndexRequestHandler::class);
        
        $this->assertTrue($reflection->hasProperty('dataArray'));
    }
    
    /**
     * Тестирует метод ProductsListIndexRequestHandler::userInfoWidgetConfig
     */
    public function testUserInfoWidgetConfig()
    {
        $handler = new ProductsListIndexRequestHandler();
        
        $reflection = new \ReflectionMethod($handler, 'userInfoWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($handler);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayhasKey('user', $result);
        $this->assertArrayhasKey('template', $result);
        
        $this->assertInstanceOf(User::class, $result['user']);
        $this->assertInternalType('string', $result['template']);
    }
    
    /**
     * Тестирует метод ProductsListIndexRequestHandler::shortCartWidgetConfig
     */
    public function testShortCartWidgetConfig()
    {
        $currencyModel = new class() extends CurrencyModel {};
        
        $handler = new ProductsListIndexRequestHandler();
        
        $reflection = new \ReflectionMethod($handler, 'shortCartWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($handler, $currencyModel);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayhasKey('purchases', $result);
        $this->assertArrayhasKey('currency', $result);
        $this->assertArrayhasKey('template', $result);
        
        $this->assertInstanceOf(PurchasesCollectionInterface::class, $result['purchases']);
        $this->assertInstanceOf(CurrencyInterface::class, $result['currency']);
        $this->assertInternalType('string', $result['template']);
    }
    
    /**
     * Тестирует метод ProductsListIndexRequestHandler::currencyWidgetConfig
     */
    public function testCurrencyWidgetConfig()
    {
        $currencyModel = new class() extends CurrencyModel {};
        
        $handler = new ProductsListIndexRequestHandler();
        
        $reflection = new \ReflectionMethod($handler, 'currencyWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($handler, $currencyModel);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayhasKey('currency', $result);
        $this->assertArrayhasKey('form', $result);
        $this->assertArrayhasKey('header', $result);
        $this->assertArrayhasKey('template', $result);
        
        $this->assertInternalType('array', $result['currency']);
        $this->assertInstanceOf(ChangeCurrencyForm::class, $result['form']);
        $this->assertInternalType('string', $result['header']);
        $this->assertInternalType('string', $result['template']);
    }
    
    /**
     * Тестирует метод ProductsListIndexRequestHandler::searchWidgetConfig
     */
    public function testSearchWidgetConfig()
    {
        $handler = new ProductsListIndexRequestHandler();
        
        $reflection = new \ReflectionMethod($handler, 'searchWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($handler);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayhasKey('text', $result);
        $this->assertArrayhasKey('template', $result);
        
        $this->assertInternalType('string', $result['text']);
        $this->assertInternalType('string', $result['template']);
    }
    
    /**
     * Тестирует метод ProductsListIndexRequestHandler::categoriesMenuWidgetConfig
     */
    public function testCategoriesMenuWidgetConfig()
    {
        $handler = new ProductsListIndexRequestHandler();
        
        $reflection = new \ReflectionMethod($handler, 'categoriesMenuWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($handler);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayhasKey('categories', $result);
        $this->assertInternalType('array', $result['categories']);
    }
    
    /**
     * Тестирует метод ProductsListIndexRequestHandler::emptyProductsWidgetConfig
     */
    public function testEmptyProductsWidgetConfig()
    {
        $handler = new ProductsListIndexRequestHandler();
        
        $reflection = new \ReflectionMethod($handler, 'emptyProductsWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($handler);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayhasKey('template', $result);
        $this->assertInternalType('string', $result['template']);
    }
    
    /**
     * Тестирует метод ProductsListIndexRequestHandler::productsWidgetConfig
     */
    public function testProductsWidgetConfig()
    {
        $productsCollection = new class() extends ProductsCollection {};
        $currencyModel = new class() extends CurrencyModel {};
        
        $handler = new ProductsListIndexRequestHandler();
        
        $reflection = new \ReflectionMethod($handler, 'productsWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($handler, $productsCollection, $currencyModel);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayhasKey('products', $result);
        $this->assertArrayhasKey('currency', $result);
        $this->assertArrayhasKey('template', $result);
        
        $this->assertInstanceOf(CollectionInterface::class, $result['products']);
        $this->assertInstanceOf(CurrencyInterface::class, $result['currency']);
        $this->assertInternalType('string', $result['template']);
    }
    
    /**
     * Тестирует метод ProductsListIndexRequestHandler::paginationWidgetConfig
     */
    public function testPaginationWidgetConfig()
    {
        $productsCollection = new class() extends ProductsCollection {
            public $pagination;
            public function __construct()
            {
                $this->pagination = new LightPagination();
            }
        };
        
        $handler = new ProductsListIndexRequestHandler();
        
        $reflection = new \ReflectionMethod($handler, 'paginationWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($handler, $productsCollection);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayhasKey('pagination', $result);
        $this->assertArrayhasKey('template', $result);
        
        $this->assertInstanceOf(PaginationInterface::class, $result['pagination']);
        $this->assertInternalType('string', $result['template']);
    }
    
    /**
     * Тестирует метод ProductsListIndexRequestHandler::categoriesBreadcrumbsWidgetConfig
     */
    public function testCategoriesBreadcrumbsWidgetConfig()
    {
        $category = self::$dbClass->categories['category_1']['seocode'];
        $subcategory = self::$dbClass->subcategory['subcategory_1']['seocode'];
        
        $handler = new ProductsListIndexRequestHandler();
        
        $reflection = new \ReflectionMethod($handler, 'categoriesBreadcrumbsWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($handler, $category, $subcategory);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayhasKey('category', $result);
        $this->assertArrayhasKey('subcategory', $result);
        
        $this->assertInstanceOf(CategoriesModel::class, $result['category']);
        $this->assertInstanceOf(SubcategoryModel::class, $result['subcategory']);
    }
    
    /**
     * Тестирует метод ProductsListIndexRequestHandler::filtersWidgetConfig
     */
    public function testFiltersWidgetConfig()
    {
        $category = self::$dbClass->categories['category_1']['seocode'];
        $subcategory = self::$dbClass->subcategory['subcategory_2']['seocode'];
        $filtersModel = new class() extends ProductsFilters {};
        
        $handler = new ProductsListIndexRequestHandler();
        
        $reflection = new \ReflectionMethod($handler, 'categoriesBreadcrumbsWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($handler, $category, $subcategory, $filtersModel);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayhasKey('colors', $result);
        $this->assertArrayhasKey('sizes', $result);
        $this->assertArrayhasKey('brands', $result);
        $this->assertArrayhasKey('sortingFields', $result);
        $this->assertArrayhasKey('sortingTypes', $result);
        $this->assertArrayhasKey('form', $result);
        $this->assertArrayhasKey('header', $result);
        $this->assertArrayhasKey('template', $result);
        
        $this->assertInternalType('array', $result['colors']);
        $this->assertInternalType('array', $result['sizes']);
        $this->assertInternalType('array', $result['brands']);
        $this->assertInternalType('array', $result['sortingFields']);
        $this->assertInternalType('array', $result['sortingTypes']);
        $this->assertInstanceOf(FiltersForm::class, $result['form']);
        $this->assertInternalType('string', $result['header']);
        $this->assertInternalType('string', $result['template']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
