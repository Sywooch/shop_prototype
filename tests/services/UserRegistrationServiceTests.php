<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\UserRegistrationService;
use app\tests\DbManager;
use app\tests\sources\fixtures\{CategoriesFixture,
    CurrencyFixture};
use app\controllers\ProductsListController;

/**
 * Тестирует класс UserRegistrationService
 */
class UserRegistrationServiceTests extends TestCase
{
    private static $dbClass;
    
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
    
    /**
     * Тестирует свойства UserRegistrationService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(UserRegistrationService::class);
        
        $this->assertTrue($reflection->hasProperty('dataArray'));
    }
    
    /**
     * Тестирует метод UserRegistrationService::handle
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
        
        $service = new UserRegistrationService();
        $result = $service->handle($request);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        $this->assertArrayHasKey('userRegistrationWidgetConfig', $result);
        $this->assertArrayHasKey('userInfoWidgetConfig', $result);
        $this->assertArrayHasKey('shortCartWidgetConfig', $result);
        $this->assertArrayHasKey('currencyWidgetConfig', $result);
        $this->assertArrayHasKey('searchWidgetConfig', $result);
        $this->assertArrayHasKey('categoriesMenuWidgetConfig', $result);
        
        $this->assertInternalType('array', $result['userRegistrationWidgetConfig']);
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
