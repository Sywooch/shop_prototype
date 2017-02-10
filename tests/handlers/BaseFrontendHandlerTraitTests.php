<?php

namespace app\tests\handlers;

use PHPUnit\Framework\TestCase;
use yii\web\User;
use app\handlers\BaseFrontendHandlerTrait;
use app\tests\DbManager;
use app\tests\sources\fixtures\{CategoriesFixture,
    CurrencyFixture};
use app\models\{CurrencyInterface,
    CurrencyModel};
use app\collections\{CollectionInterface,
    PurchasesCollectionInterface};
use app\forms\ChangeCurrencyForm;
use app\controllers\ProductsListController;
use app\exceptions\ExceptionsTrait;

/**
 * Тестирует класс BaseFrontendHandlerTrait
 */
class BaseFrontendHandlerTraitTests extends TestCase
{
    private static $dbClass;
    private $handler;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'currency'=>CurrencyFixture::class,
                'categories'=>CategoriesFixture::class,
            ],
        ]);
        self::$dbClass->loadFixtures();
        
        \Yii::$app->controller = new ProductsListController('list', \Yii::$app);
    }
    
    public function setUp()
    {
        \Yii::$app->registry->clean();
        
        $this->handler = new class() {
            use BaseFrontendHandlerTrait, ExceptionsTrait;
        };
    }
    
    /**
     * Тестирует метод BaseFrontendHandlerTrait::userInfoWidgetConfig
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
     * Тестирует метод BaseFrontendHandlerTrait::shortCartWidgetConfig
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
     * Тестирует метод BaseFrontendHandlerTrait::currencyWidgetConfig
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
     * Тестирует метод BaseFrontendHandlerTrait::searchWidgetConfig
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
     * Тестирует метод BaseFrontendHandlerTrait::categoriesMenuWidgetConfig
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
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
