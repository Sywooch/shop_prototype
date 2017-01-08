<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\GetCurrencyWidgetConfigService;
use app\tests\DbManager;
use app\tests\sources\fixtures\CurrencyFixture;
use app\controllers\ProductsListController;
use app\forms\ChangeCurrencyForm;

/**
 * Тестирует класс GetCurrencyWidgetConfigService
 */
class GetCurrencyWidgetConfigServiceTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'currency'=>CurrencyFixture::class
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства GetCurrencyWidgetConfigService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(GetCurrencyWidgetConfigService::class);
        
        $this->assertTrue($reflection->hasProperty('currencyWidgetArray'));
    }
    
    /**
     * Тестирует метод GetCurrencyWidgetConfigService::handle
     */
    public function testHandle()
    {
        \Yii::$app->controller = new ProductsListController('products-list', \Yii::$app);
        
        $service = new GetCurrencyWidgetConfigService();
        
        $result = $service->handle();
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        $this->assertArrayHasKey('currency', $result);
        $this->assertArrayHasKey('form', $result);
        $this->assertArrayHasKey('view', $result);
        $this->assertInternalType('array', $result['currency']);
        $this->assertInstanceOf(ChangeCurrencyForm::class, $result['form']);
        $this->assertInternalType('string', $result['view']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
