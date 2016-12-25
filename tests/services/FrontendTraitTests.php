<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\FrontendTrait;
use app\tests\DbManager;
use app\helpers\HashHelper;
use app\models\{CategoriesModel,
    CurrencyModel};
use app\tests\sources\fixtures\{CategoriesFixture,
    CurrencyFixture};
use yii\web\User;
use app\collections\PurchasesCollectionInterface;
use app\forms\ChangeCurrencyForm;
use app\exceptions\ExceptionsTrait;
use app\controllers\ProductsListController;

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
                'currency'=>CurrencyFixture::class,
            ],
        ]);
        self::$dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        $this->trait = new class() {
            use FrontendTrait, ExceptionsTrait;
        };
    }
    
    /**
     * Тестирует свойства FrontendTrait
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(FrontendTrait::class);
        
        $this->assertTrue($reflection->hasProperty('currencyModel'));
        $this->assertTrue($reflection->hasProperty('userArray'));
        $this->assertTrue($reflection->hasProperty('cartArray'));
        $this->assertTrue($reflection->hasProperty('currencyArray'));
        $this->assertTrue($reflection->hasProperty('searchArray'));
        $this->assertTrue($reflection->hasProperty('categoriesArray'));
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
     * Тестирует метод FrontendTrait::getUserArray
     */
    public function testGetUserArray()
    {
        $reflection = new \ReflectionMethod($this->trait, 'getUserArray');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->trait);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty('array', $result);
        $this->assertArrayHasKey('userConfig', $result);
        $this->assertArrayHasKey('user', $result['userConfig']);
        $this->assertArrayHasKey('view', $result['userConfig']);
        $this->assertInstanceOf(User::class, $result['userConfig']['user']);
        $this->assertInternalType('string', $result['userConfig']['view']);
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
        $this->assertArrayHasKey('cartConfig', $result);
        $this->assertArrayHasKey('purchases', $result['cartConfig']);
        $this->assertArrayHasKey('currency', $result['cartConfig']);
        $this->assertArrayHasKey('view', $result['cartConfig']);
        $this->assertInstanceOf(PurchasesCollectionInterface::class, $result['cartConfig']['purchases']);
        $this->assertInstanceOf(CurrencyModel::class, $result['cartConfig']['currency']);
        $this->assertInternalType('string', $result['cartConfig']['view']);
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
        $this->assertArrayHasKey('currencyConfig', $result);
        $this->assertArrayHasKey('currency', $result['currencyConfig']);
        $this->assertArrayHasKey('form', $result['currencyConfig']);
        $this->assertArrayHasKey('view', $result['currencyConfig']);
        $this->assertInternalType('array', $result['currencyConfig']['currency']);
        $this->assertInstanceOf(ChangeCurrencyForm::class, $result['currencyConfig']['form']);
        $this->assertInternalType('string', $result['currencyConfig']['view']);
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
        $this->assertArrayHasKey('searchConfig', $result);
        $this->assertArrayHasKey('text', $result['searchConfig']);
        $this->assertArrayHasKey('view', $result['searchConfig']);
        $this->assertInternalType('string', $result['searchConfig']['text']);
        $this->assertInternalType('string', $result['searchConfig']['view']);
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
        $this->assertArrayHasKey('menuConfig', $result);
        $this->assertArrayHasKey('categories', $result['menuConfig']);
        foreach ($result['menuConfig']['categories'] as $item) {
            $this->assertInstanceOf(CategoriesModel::class, $item);
        }
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
