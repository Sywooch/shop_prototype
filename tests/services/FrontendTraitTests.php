<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\{CurrentCurrencyService,
    FrontendTrait};
use app\tests\DbManager;
use app\helpers\HashHelper;
use app\models\{CategoriesModel,
    CurrencyModel,
    ProductsModel};
use app\tests\sources\fixtures\{CategoriesFixture,
    CurrencyFixture,
    ProductsFixture,
    SubcategoryFixture};
use yii\web\User;
use app\collections\{ProductsCollection,
    PurchasesCollectionInterface};
use app\forms\ChangeCurrencyForm;
use app\exceptions\ExceptionsTrait;
use app\controllers\ProductsListController;
use app\filters\ProductsFilters;
use yii\web\Request;

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
                'products'=>ProductsFixture::class,
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
        $this->assertTrue($reflection->hasProperty('productsModel'));
        $this->assertTrue($reflection->hasProperty('userArray'));
        $this->assertTrue($reflection->hasProperty('cartArray'));
        $this->assertTrue($reflection->hasProperty('currencyArray'));
        $this->assertTrue($reflection->hasProperty('searchArray'));
        $this->assertTrue($reflection->hasProperty('categoriesArray'));
    }
    
    /**
     * Тестирует метод FrontendTrait::getCurrencyModel
     * если данные достаются из СУБД
     */
    public function testGetCurrencyModel()
    {
        $reflection = new \ReflectionMethod($this->trait, 'getCurrencyModel');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->trait);
        
        $this->assertInstanceOf(CurrencyModel::class, $result);
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
     * Тестирует метод FrontendTrait::getProductsModel
     * если отсутствует параметр $request
     * @expectedException TypeError
     */
    public function testGetProductsModelEmptyRequest()
    {
        $reflection = new \ReflectionMethod($this->trait, 'getProductsModel');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->trait);
    }
    
    /**
     * Тестирует метод FrontendTrait::getProductsModel
     * если данные отсутствуют
     * @expectedException \yii\web\NotFoundHttpException
     */
    public function testGetProductsModel404()
    {
        $request = new class() extends Request {
            public function get($name = null, $defaultValue = null)
            {
                return 'nothing';
            }
        };
        
        $reflection = new \ReflectionMethod($this->trait, 'getProductsModel');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->trait, $request);
    }
    
    /**
     * Тестирует метод FrontendTrait::getProductsModel
     */
    public function testGetProductsModel()
    {
        $request = new class() extends Request {
            public $seocode;
            public function get($name = null, $defaultValue = null)
            {
                return $this->seocode;
            }
        };
        $reflection = new \ReflectionProperty($request, 'seocode');
        $reflection->setValue($request, self::$dbClass->products['product_1']['seocode']);
        
        $reflection = new \ReflectionMethod($this->trait, 'getProductsModel');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->trait, $request);
        
        $this->assertInstanceOf(ProductsModel::class, $result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
