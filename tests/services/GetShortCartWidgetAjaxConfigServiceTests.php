<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\GetShortCartWidgetAjaxConfigService;
use app\tests\DbManager;
use app\tests\sources\fixtures\CurrencyFixture;

/**
 * Тестирует класс GetShortCartWidgetAjaxConfigService
 */
class GetShortCartWidgetAjaxConfigServiceTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'currency'=>CurrencyFixture::class,
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства GetShortCartWidgetAjaxConfigService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(GetShortCartWidgetAjaxConfigService::class);
        
        $this->assertTrue($reflection->hasProperty('cartWidgetAjaxArray'));
    }
    
    /**
     * Тестирует метод  GetShortCartWidgetAjaxConfigService::handle
     */
    public function testHandle()
    {
        $service = new GetShortCartWidgetAjaxConfigService();
        $result = $service->handle();
        
        $this->assertInternalType('string', $result);
        $this->assertNotEmpty($result);
        $this->assertRegExp('#<p>Товаров в корзине: 0, Общая стоимость: 0,00 UAH</p>#', $result);
    }
    
    public static function tearDownAfterClass()
    {
         self::$dbClass->unloadFixtures();
    }
}
