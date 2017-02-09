<?php

namespace app\tests\handlers;

use PHPUnit\Framework\TestCase;
use yii\web\User;
use yii\helpers\Url;
use app\handlers\AbstractBaseHandler;
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
use app\filters\{ProductsFilters,
    ProductsFiltersInterface};
use app\helpers\HashHelper;

/**
 * Тестирует класс AbstractBaseHandler
 */
class AbstractBaseHandlerTests extends TestCase
{
    private static $dbClass;
    private $handler;
    
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
        $this->handler = new class() extends AbstractBaseHandler {
            public function handle($data) {}
        };
    }
    
    /**
     * Тестирует метод AbstractBaseHandler::getCurrentCurrency
     */
    public function testGetCurrentCurrency()
    {
        $reflection = new \ReflectionMethod($this->handler, 'getCurrentCurrency');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler);
        
        $this->assertInstanceOf(CurrencyInterface::class, $result);
    }
    
    /**
     * Тестирует метод AbstractBaseHandler::getProductsFilters
     */
    public function testGetProductsFilters()
    {
        $reflection = new \ReflectionMethod($this->handler, 'getProductsFilters');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler);
        
        $this->assertInstanceOf(ProductsFiltersInterface::class, $result);
    }
    
    /**
     * Тестирует метод AbstractBaseHandler::userInfoWidgetConfig
     */
    public function testUserInfoWidgetConfig()
    {
        $reflection = new \ReflectionMethod($this->handler, 'userInfoWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('user', $result);
        $this->assertArrayHasKey('template', $result);
        
        $this->assertInstanceOf(User::class, $result['user']);
        $this->assertInternalType('string', $result['template']);
    }
    
    /**
     * Тестирует метод AbstractBaseHandler::shortCartWidgetConfig
     */
    public function testShortCartWidgetConfig()
    {
        $currencyModel = new class() extends CurrencyModel {};
        
        $reflection = new \ReflectionMethod($this->handler, 'shortCartWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler, $currencyModel);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('purchases', $result);
        $this->assertArrayHasKey('currency', $result);
        $this->assertArrayHasKey('template', $result);
        
        $this->assertInstanceOf(PurchasesCollectionInterface::class, $result['purchases']);
        $this->assertInstanceOf(CurrencyInterface::class, $result['currency']);
        $this->assertInternalType('string', $result['template']);
    }
    
    /**
     * Тестирует метод AbstractBaseHandler::currencyWidgetConfig
     */
    public function testCurrencyWidgetConfig()
    {
        $currencyModel = new class() extends CurrencyModel {};
        
        $reflection = new \ReflectionMethod($this->handler, 'currencyWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler, $currencyModel);
        
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
     * Тестирует метод AbstractBaseHandler::searchWidgetConfig
     */
    public function testSearchWidgetConfig()
    {
        $reflection = new \ReflectionMethod($this->handler, 'searchWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('text', $result);
        $this->assertArrayHasKey('template', $result);
        
        $this->assertInternalType('string', $result['text']);
        $this->assertInternalType('string', $result['template']);
    }
    
    /**
     * Тестирует метод AbstractBaseHandler::categoriesMenuWidgetConfig
     */
    public function testCategoriesMenuWidgetConfig()
    {
        $reflection = new \ReflectionMethod($this->handler, 'categoriesMenuWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('categories', $result);
        $this->assertInternalType('array', $result['categories']);
    }
    
    /**
     * Тестирует метод AbstractBaseHandler::emptyProductsWidgetConfig
     */
    public function testEmptyProductsWidgetConfig()
    {
        $reflection = new \ReflectionMethod($this->handler, 'emptyProductsWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('template', $result);
        $this->assertInternalType('string', $result['template']);
    }
    
    /**
     * Тестирует метод AbstractBaseHandler::productsWidgetConfig
     */
    public function testProductsWidgetConfig()
    {
        $productsCollection = new class() extends ProductsCollection {};
        $currencyModel = new class() extends CurrencyModel {};
        
        $reflection = new \ReflectionMethod($this->handler, 'productsWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler, $productsCollection, $currencyModel);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('products', $result);
        $this->assertArrayHasKey('currency', $result);
        $this->assertArrayHasKey('template', $result);
        
        $this->assertInstanceOf(CollectionInterface::class, $result['products']);
        $this->assertInstanceOf(CurrencyInterface::class, $result['currency']);
        $this->assertInternalType('string', $result['template']);
    }
    
    /**
     * Тестирует метод AbstractBaseHandler::paginationWidgetConfig
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
        
        $reflection = new \ReflectionMethod($this->handler, 'paginationWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler, $productsCollection);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('pagination', $result);
        $this->assertArrayHasKey('template', $result);
        
        $this->assertInstanceOf(PaginationInterface::class, $result['pagination']);
        $this->assertInternalType('string', $result['template']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
