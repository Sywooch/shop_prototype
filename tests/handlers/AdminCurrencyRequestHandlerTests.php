<?php

namespace app\tests\handlers;

use PHPUnit\Framework\TestCase;
use app\handlers\AdminCurrencyRequestHandler;
use app\tests\DbManager;
use app\tests\sources\fixtures\CurrencyFixture;
use app\forms\AbstractBaseForm;
use app\controllers\AdminController;

/**
 * Тестирует класс AdminCurrencyRequestHandler
 */
class AdminCurrencyRequestHandlerTests extends TestCase
{
    private static $dbClass;
    private $handler;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'currency'=>CurrencyFixture::class
            ],
        ]);
        self::$dbClass->loadFixtures();
        
        \Yii::$app->controller = new AdminController('admin', \Yii::$app);
    }
    
    public function setUp()
    {
        \Yii::$app->registry->clean();
        
        $this->handler = new AdminCurrencyRequestHandler();
    }
    
    /**
     * Тестирует свойства AdminCurrencyRequestHandler
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(AdminCurrencyRequestHandler::class);
        
        $this->assertTrue($reflection->hasProperty('dataArray'));
    }
    
    /**
     * Тестирует метод AdminCurrencyRequestHandler::adminCurrencyWidgetConfig
     */
    public function testAdminCurrencyWidgetConfig()
    {
        $currencyModelArray = [new class() {}];
        $currencyForm = new class() extends AbstractBaseForm {};
        
        $reflection = new \ReflectionMethod($this->handler, 'adminCurrencyWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler, $currencyModelArray, $currencyForm);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('currency', $result);
        $this->assertArrayHasKey('form', $result);
        $this->assertArrayHasKey('header', $result);
        $this->assertArrayHasKey('template', $result);
        
        $this->assertInternalType('array', $result['currency']);
        $this->assertInstanceOf(AbstractBaseForm::class, $result['form']);
        $this->assertInternalType('string', $result['header']);
        $this->assertInternalType('string', $result['template']);
    }
    
    /**
     * Тестирует метод AdminCurrencyRequestHandler::adminCreateCurrencyWidgetConfig
     */
    public function testAdminCreateCurrencyWidgetConfig()
    {
        $currencyForm = new class() extends AbstractBaseForm {};
        
        $reflection = new \ReflectionMethod($this->handler, 'adminCreateCurrencyWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler, $currencyForm);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('form', $result);
        $this->assertArrayHasKey('header', $result);
        $this->assertArrayHasKey('template', $result);
        
        $this->assertInstanceOf(AbstractBaseForm::class, $result['form']);
        $this->assertInternalType('string', $result['header']);
        $this->assertInternalType('string', $result['template']);
    }
    
    /**
     * Тестирует метод AdminCurrencyRequestHandler::handle
     */
    public function testHandle()
    {
        $request = new class() {};
        
        $result = $this->handler->handle($request);

        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('adminCurrencyWidgetConfig', $result);
        $this->assertArrayHasKey('adminCreateCurrencyWidgetConfig', $result);
        
        $this->assertInternalType('array', $result['adminCurrencyWidgetConfig']);
        $this->assertInternalType('array', $result['adminCreateCurrencyWidgetConfig']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
