<?php

namespace app\tests\handlers;

use PHPUnit\Framework\TestCase;
use app\handlers\ProductsListHandlerTrait;
use app\tests\DbManager;
use app\tests\sources\fixtures\{CurrencyFixture,
    ProductsFixture};
use app\models\{CurrencyInterface,
    CurrencyModel};
use app\collections\{CollectionInterface,
    LightPagination,
    PaginationInterface,
    ProductsCollection};
use app\controllers\ProductsListController;
use app\filters\{ProductsFilters,
    ProductsFiltersInterface};
use app\exceptions\ExceptionsTrait;

/**
 * Тестирует класс ProductsListHandlerTrait
 */
class ProductsListHandlerTraitTests extends TestCase
{
    private static $dbClass;
    private $handler;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'products'=>ProductsFixture::class,
                'currency'=>CurrencyFixture::class,
            ],
        ]);
        self::$dbClass->loadFixtures();
        
        \Yii::$app->controller = new ProductsListController('list', \Yii::$app);
    }
    
    public function setUp()
    {
        \Yii::$app->registry->clean();
        
        $this->handler = new class() {
            use ProductsListHandlerTrait, ExceptionsTrait;
        };
    }
    
    /**
     * Тестирует метод ProductsListHandlerTrait::getProductsFilters
     */
    public function testGetProductsFilters()
    {
        $reflection = new \ReflectionMethod($this->handler, 'getProductsFilters');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler);
        
        $this->assertInstanceOf(ProductsFiltersInterface::class, $result);
    }
    
    /**
     * Тестирует метод ProductsListHandlerTrait::emptyProductsWidgetConfig
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
     * Тестирует метод ProductsListHandlerTrait::productsWidgetConfig
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
     * Тестирует метод ProductsListHandlerTrait::paginationWidgetConfig
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
