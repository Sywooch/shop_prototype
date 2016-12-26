<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\FrontendTrait;
use app\tests\DbManager;
use app\helpers\HashHelper;
use app\models\{CategoriesModel,
    CurrencyModel};
use app\tests\sources\fixtures\{CategoriesFixture,
    CurrencyFixture,
    SubcategoryFixture};
use yii\web\User;
use app\collections\{ProductsCollection,
    PurchasesCollectionInterface};
use app\forms\ChangeCurrencyForm;
use app\exceptions\ExceptionsTrait;
use app\controllers\ProductsListController;
use app\filters\ProductsFilters;

/**
 * Тестирует класс FrontendTrait
 */
class FrontendTraitTests extends TestCase
{
    private static $dbClass;
    private $trait;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'categories'=>CategoriesFixture::class,
                'subcategory'=>SubcategoryFixture::class,
                'currency'=>CurrencyFixture::class,
            ],
        ]);
        self::$dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        $this->trait = new class() {
            use FrontendTrait, ExceptionsTrait;
            public function getProductsCollection($request) {
                return new ProductsCollection();
            }
        };
    }
    
    /**
     * Тестирует свойства FrontendTrait
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(FrontendTrait::class);
        
        $this->assertTrue($reflection->hasProperty('currencyModel'));
        $this->assertTrue($reflection->hasProperty('filtersModel'));
        $this->assertTrue($reflection->hasProperty('userArray'));
        $this->assertTrue($reflection->hasProperty('cartArray'));
        $this->assertTrue($reflection->hasProperty('currencyArray'));
        $this->assertTrue($reflection->hasProperty('searchArray'));
        $this->assertTrue($reflection->hasProperty('categoriesArray'));
        $this->assertTrue($reflection->hasProperty('emptyProductsArray'));
        $this->assertTrue($reflection->hasProperty('productsArray'));
    }
    
    /**
     * Тестирует метод FrontendTrait::getCurrencyModel
     * если данные валюты сохранены в сессии
     */
    public function testGetCurrencyModelSession()
    {
        $key = HashHelper::createCurrencyKey();
        
        $session = \Yii::$app->session;
        $session->open();
        $session->set($key, ['id'=>1, 'code'=>'MONEY', 'exchange_rate'=>12.0987, 'main'=>true]);
        
        $this->assertTrue($session->has($key));
        
        $reflection = new \ReflectionMethod($this->trait, 'getCurrencyModel');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->trait);
        
        $this->assertInstanceOf(CurrencyModel::class, $result);
        
        $session->remove($key);
        $session->close();
    }
    
    /**
     * Тестирует метод FrontendTrait::getCurrencyModel
     * если данные достаются из СУБД
     */
    public function testGetCurrencyModel()
    {
        $key = HashHelper::createCurrencyKey();
        
        $session = \Yii::$app->session;
        $session->open();
        $this->assertFalse($session->has($key));
        $session->close();
        
        $reflection = new \ReflectionMethod($this->trait, 'getCurrencyModel');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->trait);
        
        $this->assertInstanceOf(CurrencyModel::class, $result);
        
        $this->assertTrue($session->has($key));
        
        $session->remove($key);
        $session->close();
    }
    
    /**
     * Тестирует метод FrontendTrait::getFiltersModel
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
     * Тестирует метод FrontendTrait::getUserArray
     */
    public function testGetUserArray()
    {
        $reflection = new \ReflectionMethod($this->trait, 'getUserArray');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->trait);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty('array', $result);
        $this->assertArrayHasKey('user', $result);
        $this->assertArrayHasKey('view', $result);
        $this->assertInstanceOf(User::class, $result['user']);
        $this->assertInternalType('string', $result['view']);
    }
    
    /**
     * Тестирует метод FrontendTrait::getCartArray
     */
    public function testGetCartArray()
    {
        $reflection = new \ReflectionMethod($this->trait, 'getCartArray');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->trait);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty('array', $result);
        $this->assertArrayHasKey('purchases', $result);
        $this->assertArrayHasKey('currency', $result);
        $this->assertArrayHasKey('view', $result);
        $this->assertInstanceOf(PurchasesCollectionInterface::class, $result['purchases']);
        $this->assertInstanceOf(CurrencyModel::class, $result['currency']);
        $this->assertInternalType('string', $result['view']);
    }
    
    /**
     * Тестирует метод FrontendTrait::getCurrencyArray
     */
    public function testGetCurrencyArray()
    {
        \Yii::$app->controller = new ProductsListController('products-list', \Yii::$app);
        
        $reflection = new \ReflectionMethod($this->trait, 'getCurrencyArray');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->trait);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty('array', $result);
        $this->assertArrayHasKey('currency', $result);
        $this->assertArrayHasKey('form', $result);
        $this->assertArrayHasKey('view', $result);
        $this->assertInternalType('array', $result['currency']);
        $this->assertInstanceOf(ChangeCurrencyForm::class, $result['form']);
        $this->assertInternalType('string', $result['view']);
    }
    
    /**
     * Тестирует метод FrontendTrait::getSearchArray
     */
    public function testGetSearchArray()
    {
        $reflection = new \ReflectionMethod($this->trait, 'getSearchArray');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->trait);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty('array', $result);
        $this->assertArrayHasKey('text', $result);
        $this->assertArrayHasKey('view', $result);
        $this->assertInternalType('string', $result['text']);
        $this->assertInternalType('string', $result['view']);
    }
    
    /**
     * Тестирует метод FrontendTrait::getCategoriesArray
     */
    public function testGetCategoriesArray()
    {
        $reflection = new \ReflectionMethod($this->trait, 'getCategoriesArray');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->trait);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty('array', $result);
        $this->assertArrayHasKey('categories', $result);
        foreach ($result['categories'] as $item) {
            $this->assertInstanceOf(CategoriesModel::class, $item);
        }
    }
    
    /**
     * Тестирует метод FrontendTrait::getEmptyProductsArray
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
     * Тестирует метод FrontendTrait::getProductsArray
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
     * Тестирует метод FrontendTrait::getProductsArray
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
        $this->assertInstanceOf(ProductsCollection::class, $result['products']);
        $this->assertInstanceOf(CurrencyModel::class, $result['currency']);
        $this->assertInternalType('string', $result['view']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
