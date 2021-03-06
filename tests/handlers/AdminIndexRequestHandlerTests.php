<?php

namespace app\tests\handlers;

use PHPUnit\Framework\TestCase;
use app\handlers\AdminIndexRequestHandler;
use app\tests\DbManager;
use app\tests\sources\fixtures\{CurrencyFixture,
    PurchasesFixture,
    VisitorsCounterFixture};
use app\collections\{PurchasesCollection,
    PurchasesCollectionInterface};
use app\models\CurrencyModel;

/**
 * Тестирует класс AdminIndexRequestHandler
 */
class AdminIndexRequestHandlerTests extends TestCase
{
    private static $dbClass;
    private $handler;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'purchases'=>PurchasesFixture::class,
                'currency'=>CurrencyFixture::class,
                'visitors_counter'=>VisitorsCounterFixture::class
            ],
        ]);
        self::$dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        \Yii::$app->registry->clean();
        
        $this->handler = new AdminIndexRequestHandler();
    }
    
    /**
     * Тестирует свойства AdminIndexRequestHandler
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(AdminIndexRequestHandler::class);
        
        $this->assertTrue($reflection->hasProperty('dataArray'));
    }
    
    /**
     * Тестирует метод AdminIndexRequestHandler::adminTodayOrdersMinimalWidgetConfig
     */
    public function testAdminTodayOrdersMinimalWidgetConfig()
    {
        $reflection = new \ReflectionMethod($this->handler, 'adminTodayOrdersMinimalWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler, 106);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayhasKey('header', $result);
        $this->assertArrayhasKey('template', $result);
        
        $this->assertInternalType('string', $result['header']);
        $this->assertInternalType('string', $result['template']);
    }
    
    /**
     * Тестирует метод AdminIndexRequestHandler::visitsMinimalWidgetConfig
     */
    public function testVisitsMinimalWidgetConfig()
    {
        $reflection = new \ReflectionMethod($this->handler, 'visitsMinimalWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler, 2);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayhasKey('visits', $result);
        $this->assertArrayhasKey('template', $result);
        
        $this->assertInternalType('integer', $result['visits']);
        $this->assertInternalType('string', $result['template']);
    }
    
    /**
     * Тестирует метод AdminIndexRequestHandler::conversionWidgetConfig
     */
    public function testConversionWidgetConfig()
    {
        $reflection = new \ReflectionMethod($this->handler, 'conversionWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler, 2, 105);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayhasKey('purchases', $result);
        $this->assertArrayhasKey('visits', $result);
        $this->assertArrayhasKey('template', $result);
        
        $this->assertInternalType('integer', $result['purchases']);
        $this->assertInternalType('integer', $result['visits']);
        $this->assertInternalType('string', $result['template']);
    }
    
    /**
     * Тестирует метод AdminIndexRequestHandler::averageBillWidgetConfig
     */
    public function testAverageBillWidgetConfig()
    {
        $collection = new class() extends PurchasesCollection {};
        $currentCurrencyModel = new class() extends CurrencyModel {};
        
        $reflection = new \ReflectionMethod($this->handler, 'averageBillWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler, $collection, $currentCurrencyModel);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayhasKey('purchases', $result);
        $this->assertArrayhasKey('currency', $result);
        $this->assertArrayhasKey('header', $result);
        $this->assertArrayhasKey('template', $result);
        
        $this->assertInstanceOf(PurchasesCollectionInterface::class, $result['purchases']);
        $this->assertInstanceOf(CurrencyModel::class, $result['currency']);
        $this->assertInternalType('string', $result['header']);
        $this->assertInternalType('string', $result['template']);
    }
    
    /**
     * Тестирует метод AdminIndexRequestHandler::popularProductsWidgetConfig
     */
    public function testPopularProductsWidgetConfig()
    {
        $popularProductsArray = [new class() {}];
        
        $reflection = new \ReflectionMethod($this->handler, 'popularProductsWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler, $popularProductsArray);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayhasKey('header', $result);
        $this->assertArrayhasKey('goods', $result);
        $this->assertArrayhasKey('template', $result);
        
        $this->assertInternalType('string', $result['header']);
        $this->assertInternalType('array', $result['goods']);
        $this->assertInternalType('string', $result['template']);
    }
    
    /**
     * Тестирует метод AdminIndexRequestHandler::handle
     */
    public function testHandle()
    {
        $request = new class() {};
        
        $result = $this->handler->handle($request);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayhasKey('adminTodayOrdersMinimalWidgetConfig', $result);
        $this->assertArrayhasKey('visitsMinimalWidgetConfig', $result);
        $this->assertArrayhasKey('conversionWidgetConfig', $result);
        $this->assertArrayhasKey('averageBillWidgetConfig', $result);
        $this->assertArrayhasKey('popularGoodsWidgetConfig', $result);
        
        $this->assertInternalType('array', $result['adminTodayOrdersMinimalWidgetConfig']);
        $this->assertInternalType('array', $result['visitsMinimalWidgetConfig']);
        $this->assertInternalType('array', $result['conversionWidgetConfig']);
        $this->assertInternalType('array', $result['averageBillWidgetConfig']);
        $this->assertInternalType('array', $result['popularGoodsWidgetConfig']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
