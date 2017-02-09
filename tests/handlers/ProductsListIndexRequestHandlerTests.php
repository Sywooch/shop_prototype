<?php

namespace app\tests\handlers;

use PHPUnit\Framework\TestCase;
use yii\web\User;
use yii\helpers\Url;
use app\handlers\ProductsListIndexRequestHandler;
use app\tests\DbManager;
use app\tests\sources\fixtures\{BrandsFixture,
    CategoriesFixture,
    CurrencyFixture,
    ProductsColorsFixture,
    ProductsSizesFixture,
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
use app\helpers\HashHelper;

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
                'products_colors'=>ProductsColorsFixture::class,
                'products_sizes'=>ProductsSizesFixture::class,
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
        
        $this->assertArrayHasKey('user', $result);
        $this->assertArrayHasKey('template', $result);
        
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
        
        $this->assertArrayHasKey('purchases', $result);
        $this->assertArrayHasKey('currency', $result);
        $this->assertArrayHasKey('template', $result);
        
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
        
        $this->assertArrayHasKey('currency', $result);
        $this->assertArrayHasKey('form', $result);
        $this->assertArrayHasKey('header', $result);
        $this->assertArrayHasKey('template', $result);
        
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
        
        $this->assertArrayHasKey('text', $result);
        $this->assertArrayHasKey('template', $result);
        
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
        
        $this->assertArrayHasKey('categories', $result);
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
        
        $this->assertArrayHasKey('template', $result);
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
        
        $this->assertArrayHasKey('products', $result);
        $this->assertArrayHasKey('currency', $result);
        $this->assertArrayHasKey('template', $result);
        
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
        
        $this->assertArrayHasKey('pagination', $result);
        $this->assertArrayHasKey('template', $result);
        
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
        
        $this->assertArrayHasKey('category', $result);
        $this->assertArrayHasKey('subcategory', $result);
        
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
        
        $reflection = new \ReflectionMethod($handler, 'filtersWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($handler, $category, $subcategory, $filtersModel);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('colors', $result);
        $this->assertArrayHasKey('sizes', $result);
        $this->assertArrayHasKey('brands', $result);
        $this->assertArrayHasKey('sortingFields', $result);
        $this->assertArrayHasKey('sortingTypes', $result);
        $this->assertArrayHasKey('form', $result);
        $this->assertArrayHasKey('header', $result);
        $this->assertArrayHasKey('template', $result);
        
        $this->assertInternalType('array', $result['colors']);
        $this->assertInternalType('array', $result['sizes']);
        $this->assertInternalType('array', $result['brands']);
        $this->assertInternalType('array', $result['sortingFields']);
        $this->assertInternalType('array', $result['sortingTypes']);
        $this->assertInstanceOf(FiltersForm::class, $result['form']);
        $this->assertInternalType('string', $result['header']);
        $this->assertInternalType('string', $result['template']);
    }
    
    /**
     * Тестирует метод ProductsListIndexRequestHandler::handle
     * если пуст request
     * @expectedException ErrorException
     */
    public function testHandleEmptyRequest()
    {
        $handler = new ProductsListIndexRequestHandler();
        $handler->handle();
    }
    
    /**
     * Тестирует метод ProductsListIndexRequestHandler::handle
     * category === true
     * subcategory === true
     * page === 0
     */
    public function testHandleCategory()
    {
        $request = new class() {
            public $category;
            public $subcategory;
            public function get($name=null, $defaultValue=null)
            {
                switch ($name) {
                    case 'category':
                        return $this->category;
                    case 'subcategory':
                        return $this->subcategory;
                    default:
                        return null;
                }
            }
        };
        $reflection = new \ReflectionProperty($request, 'category');
        $reflection->setAccessible(true);
        $reflection->setValue($request, self::$dbClass->categories['category_1']['seocode']);
        $reflection = new \ReflectionProperty($request, 'subcategory');
        $reflection->setAccessible(true);
        $reflection->setValue($request, self::$dbClass->subcategory['subcategory_2']['seocode']);
        
        $handler = new ProductsListIndexRequestHandler();
        $result = $handler->handle($request);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('userInfoWidgetConfig', $result);
        $this->assertArrayHasKey('shortCartWidgetConfig', $result);
        $this->assertArrayHasKey('currencyWidgetConfig', $result);
        $this->assertArrayHasKey('searchWidgetConfig', $result);
        $this->assertArrayHasKey('categoriesMenuWidgetConfig', $result);
        $this->assertArrayHasKey('productsWidgetConfig', $result);
        $this->assertArrayHasKey('paginationWidgetConfig', $result);
        $this->assertArrayHasKey('categoriesBreadcrumbsWidgetConfig', $result);
        $this->assertArrayHasKey('filtersWidgetConfig', $result);
        
        $this->assertArrayNotHasKey('emptyProductsWidgetConfig', $result);
        
        $this->assertInternalType('array', $result['userInfoWidgetConfig']);
        $this->assertInternalType('array', $result['shortCartWidgetConfig']);
        $this->assertInternalType('array', $result['currencyWidgetConfig']);
        $this->assertInternalType('array', $result['searchWidgetConfig']);
        $this->assertInternalType('array', $result['categoriesMenuWidgetConfig']);
        $this->assertInternalType('array', $result['productsWidgetConfig']);
        $this->assertInternalType('array', $result['paginationWidgetConfig']);
        $this->assertInternalType('array', $result['categoriesBreadcrumbsWidgetConfig']);
        $this->assertInternalType('array', $result['filtersWidgetConfig']);
    }
    
    /**
     * Тестирует метод ProductsListIndexRequestHandler::handle
     * category === null
     * subcategory === null
     * page === true
     */
    public function testHandlePage()
    {
        $request = new class() {
            public function get($name=null, $defaultValue=null)
            {
                switch ($name) {
                    case 'page':
                        return 2;
                    default:
                        return null;
                }
            }
        };
        
        $handler = new ProductsListIndexRequestHandler();
        $result = $handler->handle($request);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('userInfoWidgetConfig', $result);
        $this->assertArrayHasKey('shortCartWidgetConfig', $result);
        $this->assertArrayHasKey('currencyWidgetConfig', $result);
        $this->assertArrayHasKey('searchWidgetConfig', $result);
        $this->assertArrayHasKey('categoriesMenuWidgetConfig', $result);
        $this->assertArrayHasKey('productsWidgetConfig', $result);
        $this->assertArrayHasKey('paginationWidgetConfig', $result);
        $this->assertArrayHasKey('categoriesBreadcrumbsWidgetConfig', $result);
        $this->assertArrayHasKey('filtersWidgetConfig', $result);
        
        $this->assertArrayNotHasKey('emptyProductsWidgetConfig', $result);
        
        $this->assertInternalType('array', $result['userInfoWidgetConfig']);
        $this->assertInternalType('array', $result['shortCartWidgetConfig']);
        $this->assertInternalType('array', $result['currencyWidgetConfig']);
        $this->assertInternalType('array', $result['searchWidgetConfig']);
        $this->assertInternalType('array', $result['categoriesMenuWidgetConfig']);
        $this->assertInternalType('array', $result['productsWidgetConfig']);
        $this->assertInternalType('array', $result['paginationWidgetConfig']);
        $this->assertInternalType('array', $result['categoriesBreadcrumbsWidgetConfig']);
        $this->assertInternalType('array', $result['filtersWidgetConfig']);
    }
    
    /**
     * Тестирует метод ProductsListIndexRequestHandler::handle
     * несуществующая страница
     * @expectedException yii\web\NotFoundHttpException
     */
    public function testHandleNotPage()
    {
        $request = new class() {
            public function get($name=null, $defaultValue=null)
            {
                switch ($name) {
                    case 'page':
                        return 200;
                    default:
                        return null;
                }
            }
        };
        
        $handler = new ProductsListIndexRequestHandler();
        $result = $handler->handle($request);
    }
    
    /**
     * Тестирует метод ProductsListIndexRequestHandler::handle
     * нет товаров
     */
    public function testHandleNotProducts()
    {
        $key = HashHelper::createFiltersKey(Url::current());
        $session = \Yii::$app->session;
        $session->open();
        $session->set($key, ['colors'=>[123, 12], 'sizes'=>[44]]);
        
        $request = new class() {
            public function get($name=null, $defaultValue=null)
            {
                return null;
            }
        };
        
        $handler = new ProductsListIndexRequestHandler();
        $result = $handler->handle($request);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('userInfoWidgetConfig', $result);
        $this->assertArrayHasKey('shortCartWidgetConfig', $result);
        $this->assertArrayHasKey('currencyWidgetConfig', $result);
        $this->assertArrayHasKey('searchWidgetConfig', $result);
        $this->assertArrayHasKey('categoriesMenuWidgetConfig', $result);
        $this->assertArrayHasKey('emptyProductsWidgetConfig', $result);
        $this->assertArrayHasKey('categoriesBreadcrumbsWidgetConfig', $result);
        $this->assertArrayHasKey('filtersWidgetConfig', $result);
        
        $this->assertArrayNotHasKey('productsWidgetConfig', $result);
        $this->assertArrayNotHasKey('paginationWidgetConfig', $result);
        
        $this->assertInternalType('array', $result['userInfoWidgetConfig']);
        $this->assertInternalType('array', $result['shortCartWidgetConfig']);
        $this->assertInternalType('array', $result['currencyWidgetConfig']);
        $this->assertInternalType('array', $result['searchWidgetConfig']);
        $this->assertInternalType('array', $result['categoriesMenuWidgetConfig']);
        $this->assertInternalType('array', $result['emptyProductsWidgetConfig']);
        $this->assertInternalType('array', $result['categoriesBreadcrumbsWidgetConfig']);
        $this->assertInternalType('array', $result['filtersWidgetConfig']);
        
        $session->remove($key);
        $session->close();
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
