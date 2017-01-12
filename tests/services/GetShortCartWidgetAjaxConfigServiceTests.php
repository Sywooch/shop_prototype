<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\GetShortCartWidgetAjaxConfigService;
use app\tests\DbManager;
use app\tests\sources\fixtures\CurrencyFixture;
use app\collections\PurchasesCollection;
use app\models\CurrencyModel;

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
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        $this->assertArrayHasKey('purchases', $result);
        $this->assertArrayHasKey('currency', $result);
        $this->assertArrayHasKey('view', $result);
        $this->assertInstanceOf(PurchasesCollection::class, $result['purchases']);
        $this->assertInstanceOf(CurrencyModel::class, $result['currency']);
        $this->assertInternalType('string', $result['view']);
    }
    
    public static function tearDownAfterClass()
    {
         self::$dbClass->unloadFixtures();
    }
}
