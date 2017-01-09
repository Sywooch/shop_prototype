<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\UserRecoveryFormService;
use app\tests\DbManager;
use app\tests\sources\fixtures\{CategoriesFixture,
    CurrencyFixture};
use app\controllers\ProductsListController;

/**
 * Тестирует класс UserRecoveryFormService
 */
class UserRecoveryFormServiceTests extends TestCase
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
     * Тестирует свойства UserRecoveryFormService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(UserRecoveryFormService::class);
        
        $this->assertTrue($reflection->hasProperty('dataArray'));
    }
    
    /**
     * Тестирует метод UserRecoveryFormService::handle
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
        
        $service = new UserRecoveryFormService();
        $result = $service->handle($request);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        $this->assertArrayHasKey('userRecoveryWidgetConfig', $result);
        $this->assertArrayHasKey('userInfoWidgetConfig', $result);
        $this->assertArrayHasKey('cartWidgetConfig', $result);
        $this->assertArrayHasKey('currencyWidgetConfig', $result);
        $this->assertArrayHasKey('searchWidgetConfig', $result);
        $this->assertArrayHasKey('categoriesMenuWidgetConfig', $result);
        
        $this->assertInternalType('array', $result['userRecoveryWidgetConfig']);
        $this->assertInternalType('array', $result['userInfoWidgetConfig']);
        $this->assertInternalType('array', $result['cartWidgetConfig']);
        $this->assertInternalType('array', $result['currencyWidgetConfig']);
        $this->assertInternalType('array', $result['searchWidgetConfig']);
        $this->assertInternalType('array', $result['categoriesMenuWidgetConfig']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
