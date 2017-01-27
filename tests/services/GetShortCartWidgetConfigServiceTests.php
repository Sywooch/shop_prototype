<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\GetShortCartWidgetConfigService;
use app\tests\DbManager;
use app\tests\sources\fixtures\CurrencyFixture;
use app\collections\PurchasesCollectionInterface;
use app\models\CurrencyModel;

/**
 * Тестирует класс GetShortCartWidgetConfigService
 */
class GetShortCartWidgetConfigServiceTests extends TestCase
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
     * Тестирует свойства GetShortCartWidgetConfigService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(GetShortCartWidgetConfigService::class);
        
        $this->assertTrue($reflection->hasProperty('shortCartWidgetArray'));
    }
    
    /**
     * Тестирует метод GetShortCartWidgetConfigService::handle
     */
    public function testHandle()
    {
        $service = new GetShortCartWidgetConfigService();
        $result = $service->handle();
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        
        $this->assertArrayHasKey('purchases', $result);
        $this->assertArrayHasKey('currency', $result);
        $this->assertArrayHasKey('template', $result);
        
        $this->assertInstanceOf(PurchasesCollectionInterface::class, $result['purchases']);
        $this->assertInstanceOf(CurrencyModel::class, $result['currency']);
        $this->assertInternalType('string', $result['template']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
