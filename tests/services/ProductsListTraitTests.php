<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\ProductsListTrait;
use app\tests\DbManager;
use app\models\CurrencyModel;
use app\tests\sources\fixtures\{CurrencyFixture,
    ProductsFixture};
use app\collections\{LightPagination,
    PaginationInterface,
    ProductsCollection,
    ProductsCollectionInterface};
use app\exceptions\ExceptionsTrait;
use app\controllers\ProductsListController;
use app\filters\ProductsFilters;

/**
 * Тестирует класс ProductsListTrait
 */
class ProductsListTraitTests extends TestCase
{
    private static $dbClass;
    private $trait;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'products'=>ProductsFixture::class,
                'currency'=>CurrencyFixture::class,
            ],
        ]);
        self::$dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        $this->trait = new class() {
            use ProductsListTrait, ExceptionsTrait;
            public function getProductsCollection($request) {
                return new class() extends ProductsCollection {
                    public $pagination;
                    public function __construct()
                    {
                        $this->pagination = new class() extends LightPagination {};
                    }
                };
            }
            public function getCurrencyModel() {
                return new CurrencyModel();
            }
        };
    }
    
    /**
     * Тестирует свойства ProductsListTrait
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(ProductsListTrait::class);
        
        $this->assertTrue($reflection->hasProperty('filtersModel'));
        $this->assertTrue($reflection->hasProperty('emptyProductsArray'));
        $this->assertTrue($reflection->hasProperty('productsArray'));
        $this->assertTrue($reflection->hasProperty('paginationArray'));
    }
    
    /**
     * Тестирует метод ProductsListTrait::getFiltersModel
     */
    public function testGetFiltersModel()
    {
        \Yii::$app->controller = new ProductsListController('products-list', \Yii::$app);
        
        $reflection = new \ReflectionMethod($this->trait, 'getFiltersModel');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->trait);

        $this->assertInstanceOf(ProductsFilters::class, $result);
    }
    
    /**
     * Тестирует метод ProductsListTrait::getEmptyProductsArray
     */
    public function testGetEmptyProductsArray()
    {
        $request = [];
        
        $reflection = new \ReflectionMethod($this->trait, 'getEmptyProductsArray');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->trait, $request);
        
        $this->assertInternalType('array', $result);
        $this->assertArrayHasKey('view', $result);
        $this->assertInternalType('string', $result['view']);
    }
    
    /**
     * Тестирует метод ProductsListTrait::getProductsArray
     * если отсутствует параметр $request
     * @expectedException TypeError
     */
    public function testGetProductsArrayEmptyRequest()
    {
        $reflection = new \ReflectionMethod($this->trait, 'getProductsArray');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->trait);
    }
    
    /**
     * Тестирует метод ProductsListTrait::getProductsArray
     */
    public function testGetProductsArray()
    {
        $request = [];
        
        $reflection = new \ReflectionMethod($this->trait, 'getProductsArray');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->trait, $request);
        
        $this->assertInternalType('array', $result);
        $this->assertArrayHasKey('products', $result);
        $this->assertArrayHasKey('currency', $result);
        $this->assertArrayHasKey('view', $result);
        $this->assertInstanceOf(ProductsCollectionInterface::class, $result['products']);
        $this->assertInstanceOf(CurrencyModel::class, $result['currency']);
        $this->assertInternalType('string', $result['view']);
    }
    
    /**
     * Тестирует метод ProductsListIndexService::getPaginationArray
     * если отсутствует параметр $request
     * @expectedException TypeError
     */
    public function testGetPaginationArrayEmptyRequest()
    {
        $reflection = new \ReflectionMethod($this->trait, 'getPaginationArray');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->trait);
    }
    
    /**
     * Тестирует метод ProductsListIndexService::getPaginationArray
     */
    public function testGetPaginationArray()
    {
        $request = [
            \Yii::$app->params['pagePointer']=>2,
        ];
        
        $reflection = new \ReflectionMethod($this->trait, 'getPaginationArray');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->trait, $request);
        
        $this->assertInternalType('array', $result);
        $this->assertArrayHasKey('pagination', $result);
        $this->assertArrayHasKey('view', $result);
        $this->assertInstanceOf(PaginationInterface::class, $result['pagination']);
        $this->assertInternalType('string', $result['view']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
