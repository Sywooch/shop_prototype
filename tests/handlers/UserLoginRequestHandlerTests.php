<?php

namespace app\tests\handlers;

use PHPUnit\Framework\TestCase;
use app\handlers\UserLoginRequestHandler;
use app\tests\DbManager;
use app\tests\sources\fixtures\{CategoriesFixture,
    CurrencyFixture};
use app\controllers\ProductsListController;
use app\forms\AbstractBaseForm;

/**
 * Тестирует класс UserLoginRequestHandler
 */
class UserLoginRequestHandlerTests extends TestCase
{
    private static $dbClass;
    private $handler;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'currency'=>CurrencyFixture::class,
                'categories'=>CategoriesFixture::class
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        $this->handler = new UserLoginRequestHandler();
    }
    
    /**
     * Тестирует свойства UserLoginRequestHandler
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(UserLoginRequestHandler::class);
        
        $this->assertTrue($reflection->hasProperty('dataArray'));
    }
    
    /**
     * Тестирует метод UserLoginRequestHandler::userLoginWidgetConfig
     */
    public function testUserLoginWidgetConfig()
    {
        $userLoginForm = new class() extends AbstractBaseForm {};
        
        $reflection = new \ReflectionMethod($this->handler, 'userLoginWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler, $userLoginForm);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('form', $result);
        $this->assertArrayHasKey('template', $result);
        
        $this->assertInstanceOf(AbstractBaseForm::class, $result['form']);
        $this->assertInternalType('string', $result['template']);
    }
    
    /**
     * Тестирует метод UserLoginRequestHandler::handle
     */
    public function testHandle()
    {
        \Yii::$app->controller = new ProductsListController('products-list', \Yii::$app);
        
        $request = new class() {
            public function get($name)
            {
                return null;
            }
        };
        
        $result = $this->handler->handle($request);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        $this->assertArrayHasKey('userLoginWidgetConfig', $result);
        $this->assertArrayHasKey('userInfoWidgetConfig', $result);
        $this->assertArrayHasKey('shortCartWidgetConfig', $result);
        $this->assertArrayHasKey('currencyWidgetConfig', $result);
        $this->assertArrayHasKey('searchWidgetConfig', $result);
        $this->assertArrayHasKey('categoriesMenuWidgetConfig', $result);
        
        $this->assertInternalType('array', $result['userLoginWidgetConfig']);
        $this->assertInternalType('array', $result['userInfoWidgetConfig']);
        $this->assertInternalType('array', $result['shortCartWidgetConfig']);
        $this->assertInternalType('array', $result['currencyWidgetConfig']);
        $this->assertInternalType('array', $result['searchWidgetConfig']);
        $this->assertInternalType('array', $result['categoriesMenuWidgetConfig']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
